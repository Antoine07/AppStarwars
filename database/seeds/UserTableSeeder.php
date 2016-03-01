<?php

use App\Avatar;
use App\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
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
        DB::table('users')->insert(
            [
                [
                    'name' => 'Tony',
                    'email' => 'tony@tony.fr',
                    'password' => Hash::make('Tony'),
                    'role' => 'administrator'
                ],
                [
                    'name' => 'Antoine',
                    'email' => 'antoine@antoine.fr',
                    'password' => Hash::make('Antoine'),
                    'role' => 'visitor'
                ],
                [
                    'name' => 'Romain',
                    'email' => 'romain@romain.fr',
                    'password' => Hash::make('Tony'),
                    'role' => 'visitor'
                ],
                [
                    'name' => 'yini',
                    'email' => 'ynin@ynin.fr',
                    'password' => Hash::make('Yini'),
                    'role' => 'visitor'
                ],
            ]

        );

        factory(App\Customer::class, 4)->create();

        // avatars

        DB::table('avatars')->delete();
        DB::statement("ALTER TABLE avatars AUTO_INCREMENT=1");

        $files = Storage::allFiles(env('UPLOADS_AVATARS', 'uploads'));

        foreach ($files as $file) Storage::delete($file);

        $users = User::all();

        foreach ($users as $user) {

            $uri = str_random(12) . '_216x256.jpg';

            $fileName = file_get_contents('http://lorempixel.com/216/256/people');

            $pathDirectory = env('UPLOADS_AVATARS', 'avatars') . DIRECTORY_SEPARATOR . $uri;

            Storage::put(
                $pathDirectory, $fileName
            );

            $mime = mime_content_type(storage_path('app') . DIRECTORY_SEPARATOR . $pathDirectory);

            Avatar::create([
                'user_id' => $user->id,
                'uri' => $uri,
                'title' => $this->facker->name,
                'mime' => $mime,
            ]);
        }
    }

}
