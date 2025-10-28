<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Middleware ya aplicado en las rutas
        // $this->middleware(['auth', 'can:hr-admin']);
    }

    public function overview(Request $request)
    {
        try {
            // --- 1) Filtros (por defecto últimos 30 días)
            $from = $request->query('from', now()->subDays(30)->toDateString());
            $to   = $request->query('to',   now()->toDateString());

            // Validación robusta de fechas
            try {
                $from = Carbon::parse($from)->startOfDay();
                $to   = Carbon::parse($to)->endOfDay();

                // Validar que from no sea mayor que to
                if ($from->gt($to)) {
                    throw new \InvalidArgumentException('La fecha desde no puede ser mayor que la fecha hasta');
                }
            } catch (\Throwable $e) {
                abort(422, 'Rango de fechas inválido: ' . $e->getMessage());
            }

            // --- 2) KPIs básicos usando Query Builder (más eficiente)
            $basicKpis = $this->getBasicKpis($from, $to);

            // --- 3) KPIs de emociones usando Query Builder
            $emotionKpis = $this->getEmotionKpis($from, $to);

            // --- 4) CQP (work_quality) usando Query Builder
            $cqp = $this->getCqp($from, $to);

            // --- 5) Calcular IGB
            $igb = $this->calculateIgb($emotionKpis, $cqp);

            // --- 6) Tendencia diaria (eliminada - causaba problemas de renderizado en Chart.js)
            $trend = [];

            // --- 7) Alertas de usuarios con baja energía
            $alerts = $this->getAlerts($from, $to);

            // Preparar filtros para la vista
            $filters = [
                'from' => $from->toDateString(),
                'to' => $to->toDateString()
            ];

            return view('admin.dashboard', compact(
                'basicKpis',
                'emotionKpis',
                'cqp',
                'igb',
                'trend',
                'alerts',
                'from',
                'to',
                'filters'
            ));
        } catch (\Exception $e) {
            // Log del error para debugging
            \Log::error('Dashboard error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            // Retornar vista con datos por defecto en caso de error
            return view('admin.dashboard', [
                'basicKpis' => (object) ['total_entries' => 0, 'total_users' => 0, 'total_departments' => 0, 'avg_work_quality' => 0],
                'emotionKpis' => (object) ['ieo' => 0, 'ifc' => 0, 'ias' => 0, 'ipf' => 0],
                'cqp' => 0,
                'igb' => 0,
                'trend' => [],
                'alerts' => collect([]),
                'from' => now()->subDays(30),
                'to' => now(),
                'filters' => ['from' => now()->subDays(30)->toDateString(), 'to' => now()->toDateString()]
            ])->with('error', 'Error al cargar los datos del dashboard. Por favor, inténtalo de nuevo.');
        }
    }

    /**
     * KPIs básicos: totales y promedios
     */
    private function getBasicKpis($from, $to)
    {
        return DB::table('mood_entries')
            ->selectRaw('
                COUNT(DISTINCT id) as total_entries,
                COUNT(DISTINCT user_id) as total_users,
                COUNT(DISTINCT department_id) as total_departments,
                ROUND(AVG(work_quality), 2) as avg_work_quality
            ')
            ->whereBetween('created_at', [$from, $to])
            ->first();
    }

    /**
     * KPIs de emociones (IEO, IFC, IAS, IPF)
     */
    private function getEmotionKpis($from, $to)
    {
        $validKeys = ['q_energy_motivation', 'q_flow_focus', 'q_social_support', 'q_future_outlook'];

        return DB::table('mood_entry_answers as mea')
            ->join('mood_entries as me', 'me.id', '=', 'mea.mood_entry_id')
            ->join('questions as q', function ($join) use ($validKeys) {
                $join->on('q.id', '=', 'mea.question_id')
                    ->whereIn('q.key', $validKeys);
            })
            ->selectRaw('
                ROUND(AVG(CASE WHEN q.key = ? THEN mea.answer_numeric END) * 20, 2) as ieo,
                ROUND(AVG(CASE WHEN q.key = ? THEN mea.answer_numeric END) * 20, 2) as ifc,
                ROUND(AVG(CASE WHEN q.key = ? THEN mea.answer_numeric END) * 20, 2) as ias,
                ROUND(AVG(CASE WHEN q.key = ? THEN mea.answer_numeric END) * 20, 2) as ipf
            ', $validKeys)
            ->whereBetween('me.created_at', [$from, $to])
            ->first();
    }

    /**
     * CQP (work_quality) promedio
     */
    private function getCqp($from, $to)
    {
        return round(
            DB::table('mood_entries')
                ->whereBetween('created_at', [$from, $to])
                ->avg('work_quality') * 10,
            2
        );
    }

    /**
     * Calcular IGB (Índice General de Bienestar)
     */
    private function calculateIgb($emotionKpis, $cqp)
    {
        $ieo = $emotionKpis->ieo ?? 0;
        $ifc = $emotionKpis->ifc ?? 0;
        $ias = $emotionKpis->ias ?? 0;
        $ipf = $emotionKpis->ipf ?? 0;
        $cqp = $cqp ?? 0;

        return round(($ieo + $ifc + $ias + $ipf + $cqp) / 5, 2);
    }


    /**
     * Método alternativo usando Eloquent (más legible)
     */
    public function overviewEloquent(Request $request)
    {
        $from = $request->query('from', now()->subDays(30)->toDateString());
        $to   = $request->query('to',   now()->toDateString());

        try {
            $from = Carbon::parse($from)->startOfDay();
            $to   = Carbon::parse($to)->endOfDay();
        } catch (\Throwable $e) {
            abort(422, 'Rango de fechas inválido');
        }

        // Usando Eloquent con relaciones
        $moodEntries = \App\Models\MoodEntry::with(['answers.question'])
            ->whereBetween('created_at', [$from, $to])
            ->get();

        // Procesar datos con Eloquent
        $basicKpis = [
            'total_entries' => $moodEntries->count(),
            'total_users' => $moodEntries->pluck('user_id')->unique()->count(),
            'total_departments' => $moodEntries->pluck('department_id')->unique()->count(),
            'avg_work_quality' => round($moodEntries->avg('work_quality'), 2)
        ];

        // Calcular KPIs de emociones
        $emotionKpis = $this->calculateEmotionKpisFromCollection($moodEntries);

        // Resto de la lógica...

        return view('admin.dashboard', compact('basicKpis', 'emotionKpis'));
    }

    /**
     * Calcular KPIs de emociones desde una colección Eloquent
     */
    private function calculateEmotionKpisFromCollection($moodEntries)
    {
        $emotions = [
            'q_energy_motivation' => 'ieo',
            'q_flow_focus' => 'ifc',
            'q_social_support' => 'ias',
            'q_future_outlook' => 'ipf'
        ];

        $results = [];

        foreach ($emotions as $questionKey => $kpiKey) {
            $answers = $moodEntries->flatMap(function ($entry) use ($questionKey) {
                return $entry->answers->filter(function ($answer) use ($questionKey) {
                    return $answer->question && $answer->question->key === $questionKey;
                });
            });

            $results[$kpiKey] = round($answers->avg('answer_numeric') * 20, 2);
        }

        return (object) $results;
    }

    /**
     * Alertas de usuarios con baja energía
     */
    private function getAlerts($from, $to)
    {
        return DB::table('mood_entries as me')
            ->join('mood_entry_answers as mea', 'me.id', '=', 'mea.mood_entry_id')
            ->join('questions as q', function ($join) {
                $join->on('q.id', '=', 'mea.question_id')
                    ->where('q.key', 'q_energy_motivation');
            })
            ->join('users as u', 'u.id', '=', 'me.user_id')
            ->selectRaw('
                me.user_id,
                u.name as user_name,
                COUNT(DISTINCT me.id) as entries,
                ROUND(AVG(CASE WHEN q.key = ? THEN mea.answer_numeric END) * 20, 2) as avg_energy
            ', ['q_energy_motivation'])
            ->whereBetween('me.created_at', [$from, $to])
            ->groupBy('me.user_id', 'u.name')
            ->havingRaw('AVG(CASE WHEN q.key = ? THEN mea.answer_numeric END) * 20 < ?', ['q_energy_motivation', 60])
            ->orderBy('avg_energy', 'asc')
            ->get();
    }
}
