<?php

namespace App\Providers;

use App\History;
use App\Score;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.customers
     *
     * @return void
     */
    public function boot()
    {
        History::creating(function ($history) {

            $score = Score::find($history->product_id);

            if (!is_null($score)) {
                $score->score++;
                $score->number_command += $history->quantity;
                $score->save();
            } else {
               Score::create([
                    'score'          => 1,
                    'number_command' => $history->quantity,
                ]);
            }

        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
