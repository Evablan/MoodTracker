<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $companyId = DB::table('companies')->where('slug', 'democorp')->value('id');

        foreach (['IT', 'Support'] as $name) {
            DB::table('departments')->updateOrInsert(
                ['company_id' => $companyId, 'name' => $name],
                ['updated_at' => now(), 'created_at' => now()]
            );
        }
    }
}
