<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Music',               'icon' => 'musical-note',       'color' => 'purple'],
            ['name' => 'Business',            'icon' => 'briefcase',           'color' => 'blue'],
            ['name' => 'Technology',          'icon' => 'cpu-chip',            'color' => 'indigo'],
            ['name' => 'Sports',              'icon' => 'trophy',              'color' => 'orange'],
            ['name' => 'Education',           'icon' => 'academic-cap',        'color' => 'green'],
            ['name' => 'Nightlife',           'icon' => 'moon',                'color' => 'violet'],
            ['name' => 'Food & Drink',        'icon' => 'cake',                'color' => 'amber'],
            ['name' => 'Health & Wellness',   'icon' => 'heart',               'color' => 'red'],
            ['name' => 'Arts & Culture',      'icon' => 'paint-brush',         'color' => 'pink'],
            ['name' => 'Community',           'icon' => 'user-group',          'color' => 'teal'],
            ['name' => 'Networking',          'icon' => 'share',               'color' => 'cyan'],
            ['name' => 'Workshops',           'icon' => 'wrench-screwdriver',  'color' => 'yellow'],
            ['name' => 'Festivals',           'icon' => 'star',                'color' => 'orange'],
            ['name' => 'Family',              'icon' => 'home',                'color' => 'green'],
            ['name' => 'Travel',              'icon' => 'globe-alt',           'color' => 'blue'],
            ['name' => 'Outdoor & Adventure', 'icon' => 'map',                 'color' => 'emerald'],
            ['name' => 'Religious',           'icon' => 'sun',                 'color' => 'amber'],
            ['name' => 'Charity',             'icon' => 'hand-raised',         'color' => 'rose'],
            ['name' => 'Fashion',             'icon' => 'sparkles',            'color' => 'pink'],
            ['name' => 'Conferences',         'icon' => 'building-office-2',   'color' => 'slate'],
        ];

        foreach ($categories as $order => $data) {
            Category::firstOrCreate(
                ['slug' => Str::slug($data['name'])],
                [
                    'name'       => $data['name'],
                    'icon'       => $data['icon'],
                    'color'      => $data['color'],
                    'is_active'  => true,
                    'sort_order' => $order + 1,
                ]
            );
        }
    }
}
