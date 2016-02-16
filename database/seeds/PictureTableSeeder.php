<?php

use App\Picture;
use App\Product;

use Illuminate\Database\Seeder;

class PictureTableSeeder extends Seeder
{
    protected $faker;

    public function __construct(Faker\Generator $faker)
    {
        $this->facker = $faker;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        Eloquent::unguard();

        DB::table('pictures')->delete();
        DB::statement("ALTER TABLE pictures AUTO_INCREMENT=1");

        $files = Storage::allFiles();

        foreach ($files as $file) Storage::delete($file);

        $products = Product::all();

        foreach ($products as $product) {

            $uri = str_random(12) . '_370x235.jpg';

            Storage::put(
                $uri,
                file_get_contents('http://lorempixel.com/futurama/370/235')
            );

            Picture::create([
                'product_id' => $product->id,
                'uri'        => $uri,
                'title'      => $this->facker->name,
                'type'       => 'jpg',
                'size'       => 200,
            ]);
        }
    }
}
