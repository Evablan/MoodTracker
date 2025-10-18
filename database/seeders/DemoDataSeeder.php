<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $companyId = DB::table('companies')->where('slug', 'democorp')->value('id');
        $users     = DB::table('users')->when(
            \Schema::hasColumn('users', 'company_id'),
            fn($q) => $q->where('company_id', $companyId)
        )->limit(3)->get();
        if ($users->isEmpty()) return;

        $deptId    = DB::table('departments')->where('company_id', $companyId)->where('name', 'IT')->value('id');
        $tenseId   = DB::table('emotions')->whereNull('company_id')->where('key', 'tense')->value('id');
        $happyId   = DB::table('emotions')->whereNull('company_id')->where('key', 'happy')->value('id');
        $causeWork = DB::table('causes')->whereNull('company_id')->where('key', 'work')->value('id');
        $causePers = DB::table('causes')->whereNull('company_id')->where('key', 'personal')->value('id');

        $qIntensity = DB::table('questions')->whereNull('company_id')->where('key', 'q_intensity')->value('id');
        $qSupport   = DB::table('questions')->whereNull('company_id')->where('key', 'q_need_support')->value('id');
        $qTrigger   = DB::table('questions')->whereNull('company_id')->where('key', 'q_trigger')->value('id');

        $now = Carbon::now();
        $samples = [
            ['user' => $users[0], 'emotion_id' => $tenseId, 'cause_id' => $causeWork, 'quality' => 6, 'when' => $now->copy()->subHours(4)],
            ['user' => $users[1], 'emotion_id' => $tenseId, 'cause_id' => $causePers, 'quality' => 5, 'when' => $now->copy()->subDay()->setTime(10, 15)],
            ['user' => $users[2], 'emotion_id' => $happyId, 'cause_id' => $causeWork, 'quality' => 8, 'when' => $now->copy()->subDay()->setTime(16, 45)],
        ];

        foreach ($samples as $s) {
            $entryId = DB::table('mood_entries')->insertGetId([
                'company_id'    => $companyId,
                'department_id' => $deptId,
                'user_id'       => $s['user']->id,
                'emotion_id'    => $s['emotion_id'],
                'cause_id'      => $s['cause_id'],
                'work_quality'  => $s['quality'],
                'entry_at'      => $s['when'],
                'entry_date'    => $s['when']->toDateString(),
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            if ($s['emotion_id'] === $tenseId) {
                DB::table('mood_entry_answers')->insert([
                    [
                        'mood_entry_id' => $entryId,
                        'question_id'   => $qIntensity,
                        'answer_numeric' => 4,
                        'answer_bool'   => null,
                        'answer_option_key' => null,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ],
                    [
                        'mood_entry_id' => $entryId,
                        'question_id'   => $qSupport,
                        'answer_numeric' => null,
                        'answer_bool'   => true,
                        'answer_option_key' => null,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ],
                    [
                        'mood_entry_id' => $entryId,
                        'question_id'   => $qTrigger,
                        'answer_numeric' => null,
                        'answer_bool'   => null,
                        'answer_option_key' => 'workload',
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ],
                ]);
            }
        }
    }
}
