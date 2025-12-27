<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Slider;
use App\Models\User;
use App\Models\Location;
use App\Models\Attribute;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductImage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seeders
        $this->call([UserSeeder::class]);
        $this->call([LocationSeeder::class]);
        $this->call([AttributeSeeder::class]);

        // 2. Categories & Products
        Category::factory(10)
            ->has(
                Product::factory()
                    ->count(5)
                    ->afterCreating(function (Product $product) {
                        for ($i = 0; $i < 3; $i++) {
                            ProductImage::create([
                                'product_id' => $product->id,
                                'image' => 'products/' . rand(1, 30) . '.png'
                            ]);
                        }
                    })
            )
            ->create();

        // 3. Sliders (Make sure you put dummy images in 'storage/app/public/sliders' too!)
        Slider::factory()->count(3)->create(['type' => 'home_main']);
        Slider::factory()->count(2)->create(['type' => 'product_detail_banner']);

        // 4. Orders
        Order::factory(20)->create()->each(function ($order) {
            $items = OrderItem::factory(rand(2, 5))->make();
            $total = 0;
            foreach ($items as $item) {
                $item->order_id = $order->id;
                $item->save();
                $total += ($item->price * $item->quantity);
            }
            $order->update(['total_price' => $total]);
        });

        \App\Models\News::factory(20)->create();
    }
}
