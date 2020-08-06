<?php

use Illuminate\Database\Seeder;

class ColorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            '#000',
            '#fff'
        ];

        foreach ($data as $item) {
            \App\Color::query()->create([
                'value' => $item
            ]);
        }
    }
}
