<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $companyId = DB::table('companies')->where('slug', 'democorp')->value('id');
        $deptIT    = DB::table('departments')->where('company_id', $companyId)->where('name', 'IT')->value('id');
        $deptSup   = DB::table('departments')->where('company_id', $companyId)->where('name', 'Support')->value('id');

        $cols = [
            'password'      => Schema::hasColumn('users', 'password'),
            'provider'      => Schema::hasColumn('users', 'provider'),
            'external_id'   => Schema::hasColumn('users', 'external_id'),
            'status'        => Schema::hasColumn('users', 'status'),
            'remember'      => Schema::hasColumn('users', 'remember_token'),
            'verified_at'   => Schema::hasColumn('users', 'email_verified_at'),
            'dept'          => Schema::hasColumn('users', 'department_id'),
            'company'       => Schema::hasColumn('users', 'company_id'),
        ];

        $people = [
            ['name' => 'Eva Blanco', 'email' => 'eva@democorp.test',  'department_id' => $deptIT],
            ['name' => 'Luis Pérez', 'email' => 'luis@democorp.test', 'department_id' => $deptSup],
            ['name' => 'Marta Ruiz', 'email' => 'marta@democorp.test', 'department_id' => $deptIT],
        ];

        foreach ($people as $p) {
            // where keys para "updateOrInsert"
            $where = ['email' => $p['email']];
            if ($cols['company']) $where['company_id'] = $companyId;

            $row = [
                'name'       => $p['name'],
                'updated_at' => now(),
            ];

            if ($cols['dept'])    $row['department_id'] = $p['department_id'];
            if ($cols['company']) $row['company_id']    = $companyId;
            if ($cols['provider'])    $row['provider']  = 'local';
            if ($cols['external_id']) $row['external_id'] = null;
            if ($cols['status'])      $row['status']     = 'active';
            if ($cols['password']) {
                $row['password'] = Hash::make('secret123');
                if ($cols['remember']) $row['remember_token'] = Str::random(10);
            }
            if ($cols['verified_at']) $row['email_verified_at'] = now();
            // created_at solo si inserta:
            $row['created_at'] = now();

            DB::table('users')->updateOrInsert($where, $row);
        }
    }
}
