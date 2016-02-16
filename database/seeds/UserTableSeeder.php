<?php

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
                    'name'     => 'Tony',
                    'email'    => 'tony@tony.fr',
                    'password' => Hash::make('Tony'),
                    'role'     => 'administrator'
                ],
                [
                    'name'     => 'Antoine',
                    'email'    => 'antoine@antoine.fr',
                    'password' => Hash::make('Antoine'),
                    'role'=>'visitor'
                ],
                [
                    'name'     => 'Romain',
                    'email'    => 'romain@romain.fr',
                    'password' => Hash::make('Tony'),
                    'role'=>'visitor'
                ],
                [
                    'name'     => 'yini',
                    'email'    => 'ynin@ynin.fr',
                    'password' => Hash::make('Yini'),
                    'role'=>'visitor'
                ],
            ]

        );

        factory(App\Customer::class, 4)->create();
    }
}
