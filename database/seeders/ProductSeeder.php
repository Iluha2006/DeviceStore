<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Категория 1: Смарт-часы
            [
                'name' => 'Apple Watch Series 10',
                'category_id' => 1,
                'price' => 49990,
                'discount' => '10%',
                'quantity' => 15,
                'description' => 'Умные часы с ярким OLED-дисплеем, мониторингом сна, ЭКГ и измерением кислорода в крови. Алюминиевый корпус, защита от воды 50 м.',
            ],
            // Категория 2: Планшеты
            [
                'name' => 'Apple iPad Pro 11 M4',
                'category_id' => 2,
                'price' => 99990,
                'discount' => '5%',
                'quantity' => 10,
                'description' => 'Профессиональный планшет с чипом M4, дисплеем Ultra Retina XDR и поддержкой Apple Pencil Pro. Идеален для дизайна и видео.',
            ],
            // Категория 3: Телевизоры
            [
                'name' => 'Samsung UE55DU8000 55"',
                'category_id' => 3,
                'price' => 54990,
                'discount' => '0%',
                'quantity' => 8,
                'description' => '4K UHD телевизор с диагональю 55 дюймов, HDR10+, умной платформой Tizen и тонким безрамочным дизайном.',
            ],
            // Категория 4: Аудио
            [
                'name' => 'Sony WH-1000XM5',
                'category_id' => 4,
                'price' => 34990,
                'discount' => '15%',
                'quantity' => 25,
                'description' => 'Беспроводные наушники с активным шумоподавлением мирового класса, до 30 часов автономной работы и поддержкой LDAC.',
            ],
            [
                'name' => 'JBL Charge 5',
                'category_id' => 4,
                'price' => 15990,
                'discount' => '20%',
                'quantity' => 30,
                'description' => 'Портативная Bluetooth-колонка с мощным звуком, защитой IP67 и функцией power bank для зарядки телефона.',
            ],
            // Категория 5: Фототехника
            [
                'name' => 'Canon EOS R8',
                'category_id' => 5,
                'price' => 119990,
                'discount' => '0%',
                'quantity' => 5,
                'description' => 'Полнокадровая беззеркальная камера 24.2 Мп, 4K 60p видео, быстрый автофокус с распознаванием людей и животных.',
            ],
            // Категория 6: Умный дом
            [
                'name' => 'Яндекс Станция Мини 2',
                'category_id' => 6,
                'price' => 12990,
                'discount' => '10%',
                'quantity' => 40,
                'description' => 'Умная колонка с голосовым помощником Алиса, управлением техникой, музыкой и сценариями умного дома.',
            ],
            // Категория 7: Мониторы
            [
                'name' => 'LG UltraGear 27GS95QE 27"',
                'category_id' => 7,
                'price' => 89990,
                'discount' => '0%',
                'quantity' => 12,
                'description' => 'Игровой OLED-монитор 27 дюймов, 2K 240 Гц, время отклика 0.03 мс, поддержка G-Sync и FreeSync Premium.',
            ],
            [
                'name' => 'Dell UltraSharp U2723QE 27"',
                'category_id' => 7,
                'price' => 57990,
                'discount' => '5%',
                'quantity' => 14,
                'description' => '4K IPS монитор с покрытием 100% sRGB и Rec.709, USB-C хаб 90 Вт для дизайнеров и офисной работы.',
            ],
            // Категория 8: Телефоны
            [
                'name' => 'Apple iPhone 15 Pro 256GB',
                'category_id' => 8,
                'price' => 119990,
                'discount' => '10%',
                'quantity' => 20,
                'description' => 'Флагманский смартфон с титановым корпусом, чипом A17 Pro, тройной камерой 48 Мп и Dynamic Island.',
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'category_id' => 8,
                'price' => 109990,
                'discount' => '15%',
                'quantity' => 18,
                'description' => 'Смартфон с встроенным S Pen, Galaxy AI, 200 Мп камерой, титановой рамкой и аккумулятором 5000 мАч.',
            ],
            [
                'name' => 'Google Pixel 9 Pro',
                'category_id' => 8,
                'price' => 99990,
                'discount' => '0%',
                'quantity' => 16,
                'description' => 'Смартфон с камерой профессионального уровня, чипом Tensor G4, функциями ИИ и 7 годами обновлений.',
            ],
            // Категория 9: Ноутбуки
            [
                'name' => 'Apple MacBook Pro 14 M4',
                'category_id' => 9,
                'price' => 199990,
                'discount' => '5%',
                'quantity' => 9,
                'description' => 'Профессиональный ноутбук с чипом M4 Pro, дисплеем Liquid Retina XDR и автономностью до 22 часов.',
            ],
            [
                'name' => 'ASUS ROG Zephyrus G16',
                'category_id' => 9,
                'price' => 189990,
                'discount' => '0%',
                'quantity' => 7,
                'description' => 'Игровой ноутбук с RTX 4070, Ryzen 9 и OLED-экраном 240 Гц. Мощность и лёгкость в одном корпусе.',
            ],
            [
                'name' => 'Lenovo ThinkPad X1 Carbon',
                'category_id' => 9,
                'price' => 159990,
                'discount' => '10%',
                'quantity' => 11,
                'description' => 'Бизнес-ультрабук из углеродного волокна весом 1.08 кг, клавиатура с подсветкой, до 18 часов работы.',
            ],
            // Категория 10: Консоли
            [
                'name' => 'Sony PlayStation 5 Pro',
                'category_id' => 10,
                'price' => 74990,
                'discount' => '0%',
                'quantity' => 13,
                'description' => 'Игровая консоль нового поколения с 4K 60 FPS, трассировкой лучей и сверхбыстрым SSD 2 ТБ.',
            ],
            [
                'name' => 'Xbox Series X',
                'category_id' => 10,
                'price' => 49990,
                'discount' => '10%',
                'quantity' => 15,
                'description' => 'Самая мощная консоль Microsoft с 12 TFLOPS графики, 4K 120 Гц и подпиской Game Pass.',
            ],
            [
                'name' => 'Nintendo Switch 2',
                'category_id' => 10,
                'price' => 45990,
                'discount' => '0%',
                'quantity' => 20,
                'description' => 'Гибридная консоль с большим экраном, улучшенными контроллерами Joy-Con 2 и играми эксклюзивами Nintendo.',
            ],
            // Категория 11: Клавиатуры
            [
                'name' => 'Keychron Q3 Pro',
                'category_id' => 11,
                'price' => 18990,
                'discount' => '5%',
                'quantity' => 22,
                'description' => 'Механическая клавиатура 75% с алюминиевым корпусом, hot-swap свитчами и беспроводным соединением.',
            ],
            // Категория 12: Мыши
            [
                'name' => 'Logitech MX Master 3S',
                'category_id' => 12,
                'price' => 9990,
                'discount' => '10%',
                'quantity' => 35,
                'description' => 'Эргономичная беспроводная мышь с бесшумными кликами, 8000 DPI и бесконечным колесом прокрутки.',
            ],
            // Категория 13: Накопители
            [
                'name' => 'Samsung T9 2TB',
                'category_id' => 13,
                'price' => 25990,
                'discount' => '0%',
                'quantity' => 17,
                'description' => 'Портативный SSD 2 ТБ со скоростью чтения/записи до 2000 МБ/с и защитой от падений с высоты 3 м.',
            ],
            // Категория 14: Роутеры
            [
                'name' => 'TP-Link Archer AX55',
                'category_id' => 14,
                'price' => 9990,
                'discount' => '20%',
                'quantity' => 28,
                'description' => 'Wi-Fi 6 роутер со скоростью до 3000 Мбит/с, покрытием большой квартиры и гигабитными портами.',
            ],
            // Категория 15: Принтеры
            [
                'name' => 'HP LaserJet M111w',
                'category_id' => 15,
                'price' => 14990,
                'discount' => '0%',
                'quantity' => 19,
                'description' => 'Компактный лазерный принтер с Wi-Fi и мобильной печатью, 20 страниц в минуту, ресурс картриджа до 1000 стр.',
            ],
            // Категория 16: Игровые аксессуары
            [
                'name' => 'Sony DualSense Edge',
                'category_id' => 16,
                'price' => 22990,
                'discount' => '5%',
                'quantity' => 21,
                'description' => 'Профессиональный геймпад для PS5 со сменными стиками, программируемыми кнопками и адаптивными триггерами.',
            ],
            // Категория 17: Дроны
            [
                'name' => 'DJI Mini 4 Pro',
                'category_id' => 17,
                'price' => 84990,
                'discount' => '0%',
                'quantity' => 6,
                'description' => 'Компактный дрон весом 249 г с камерой 4K HDR, всенаправленным обходом препятствий и автономностью 34 мин.',
            ],
            // Категория 18: Электронные книги
            [
                'name' => 'PocketBook InkPad 4',
                'category_id' => 18,
                'price' => 29990,
                'discount' => '10%',
                'quantity' => 24,
                'description' => 'Ридер с E-Ink Carta 7.8", влагозащитой IPX8, регулируемой подсветкой и поддержкой всех форматов книг.',
            ],
            // Категория 19: Веб-камеры
            [
                'name' => 'Logitech Brio 4K',
                'category_id' => 19,
                'price' => 21990,
                'discount' => '0%',
                'quantity' => 26,
                'description' => 'Веб-камера 4K Ultra HD с HDR, фокусировкой и регулировкой поля зрения до 90° для стримов и конференций.',
            ],
            // Категория 20: Зарядные устройства
            [
                'name' => 'Anker GaN 65W',
                'category_id' => 20,
                'price' => 3990,
                'discount' => '15%',
                'quantity' => 50,
                'description' => 'Компактная GaN-зарядка на 65 Вт с двумя USB-C и одним USB-A, заряжает ноутбук, телефон и планшет одновременно.',
            ],
        ];

        foreach ($products as $product) {
            $seed = Str::slug($product['name']);

            Product::updateOrCreate(
                ['name' => $product['name']],
                array_merge($product, [
                    'imageUrl' => "/images/products/{$seed}.jpg",
                ])
            );
        }
    }
}
