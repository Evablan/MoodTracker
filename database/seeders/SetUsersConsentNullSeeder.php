<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SetUsersConsentNullSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->update(['consent_at' => null]);
        $this->command?->info('Users.consent_at establecido a null para todos los usuarios.');
    }
}
