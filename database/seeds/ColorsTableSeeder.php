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
            'linear-gradient(225deg, #B8C1CC 0%, #99A2AD 100%)',
            'linear-gradient(225deg, #FA646E 0%, #E6404B 100%)',
            'linear-gradient(225deg, #FFA252 0%, #FA7F14 100%)',
            'linear-gradient(225deg, #FFCF4D 0%, #F0B30E 100%)',
            'linear-gradient(225deg, #85D69A 0%, #46B864 100%)',
            'linear-gradient(225deg, #85D69A 0%, #46B864 100%)',
            'linear-gradient(225deg, #80A1FF 0%, #5D84F5 100%)',
            'linear-gradient(225deg, #F04394 0%, #D10866 100%)',
            'linear-gradient(225deg, #EBA0A6 0%, #EB848D 100%)',
            'linear-gradient(225deg, #E6A965 0%, #D18E43 100%)',
            'linear-gradient(225deg, #805253 0%, #432324 100%)',
            'linear-gradient(225deg, #8F8F8F 0%, #666666 100%)',
        ];

        foreach ($data as $item) {
            \App\Color::query()->create([
                'value' => $item
            ]);
        }
    }
}
