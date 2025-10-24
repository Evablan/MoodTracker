<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttachAdminRoleSeeder extends Seeder
{
    public function run(): void
    {
        $adminEmail = env('ADMIN_EMAIL', 'admin@empresa.com'); // cambia o ponlo en .env
        $user = DB::table('users')->where('email', $adminEmail)->first();
        $role = DB::table('roles')->where('name', 'hr_admin')->first();
        $company = DB::table('companies')->where('slug', 'democorp')->first();

        if ($user && $role && $company) {
            DB::table('role_user')->updateOrInsert([
                'user_id' => $user->id,
                'role_id' => $role->id,
                'company_id' => $company->id, // Usar ID de la empresa
            ], []);
        }
    }
}
