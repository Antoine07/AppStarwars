<?php

use Illuminate\Database\Seeder;

class TagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tags')->insert([
            [
                'name' => 'laser',
            ],
            [
                'name' => 'star',
            ],
            [
                'name' => 'war',
            ],
            [
                'name' => 'peace',
            ],
            [
                'name' => 'galaxy',
            ],
            [
                'name' => 'princess',
            ],
            [
                'name' => 'dark',
            ],
            [
                'name' => 'force',
            ],
            [
                'name' => 'tatooine',
            ],
            [
                'name' => 'luc',
            ],
            [
                'name' => 'chewbacca',
            ],
        ]);
    }
}
