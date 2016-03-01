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
        DB::statement("ALTER TABLE avatars AUTO_INCREMENT=1");

        $dirUploads = public_path(env('UPLOAD_PICTURES', 'uploads'));

        $files = File::allFiles($dirUploads);

        foreach ($files as $file) File::delete($file);

        $products = Product::all();

        foreach ($products as $product) {

            $uri = str_random(12) . '_370x235.jpg';

            $fileName = file_get_contents('http://lorempixel.com/futurama/370/235');

            $pathDirectory = $dirUploads. DIRECTORY_SEPARATOR.$uri;

            FILE::put(
                $pathDirectory, $fileName
            );

            $mime = mime_content_type($dirUploads. DIRECTORY_SEPARATOR . $uri);

            Picture::create([
                'product_id' => $product->id,
                'uri' => $uri,
                'title' => $this->facker->name,
                'mime' => $mime,
                'size' => 200,
            ]);
        }
    }
}
