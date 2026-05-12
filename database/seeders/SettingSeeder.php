<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::firstOrCreate(['id' => 1], [
            'app_name' => config('app.name', 'Pitisha'),
            'logo'     => null,
            'favicon'  => null,
        ]);
    }
}
