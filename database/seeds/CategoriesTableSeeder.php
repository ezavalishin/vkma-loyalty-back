<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'Поесть',
            'Автосервис',
            'Красота',
            'Автомир',
            'Развлечения',
            'Медицина',
            'Автотовары',
            'Товары',
            'Услуги',
            'Туризм',
            'Продукты',
            'Спорт',
            'Образование',
            'Ремонт, стройка',
        ];

        foreach ($data as $item) {
            \App\Category::query()->updateOrCreate([
                'title' => $item
            ]);
        }
    }
}
