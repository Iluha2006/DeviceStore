<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            1  => 'Смарт-часы',
            2  => 'Планшеты',
            3  => 'Телевизоры',
            4  => 'Аудио',
            5  => 'Фототехника',
            6  => 'Умный дом',
            7  => 'Мониторы',
            8  => 'Телефоны',
            9  => 'Ноутбуки',
            10 => 'Консоли',
            11 => 'Клавиатуры',
            12 => 'Мыши',
            13 => 'Накопители',
            14 => 'Роутеры',
            15 => 'Принтеры',
            16 => 'Игровые аксессуары',
            17 => 'Дроны',
            18 => 'Электронные книги',
            19 => 'Веб-камеры',
            20 => 'Зарядные устройства',
        ];

        foreach ($categories as $id => $name) {
            Category::updateOrCreate(
                ['id' => $id],
                ['categoryname' => $name]
            );
        }
    }
}
