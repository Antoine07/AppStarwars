<?php

namespace App\Listeners;

use App\Score;
use App\Product;
use App\Events\ScoreEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ScoreListener
{

    public function handle(ScoreEvent $event)
    {
        $score = Product::find($event->productId)->score;

        if(!is_null($score)) {
            $score->number_command += $event->quantity;
            $score->score++;
            $score->save();

        }else{
            Score::create(['product_id'=>$event->id, 'score'=> 1, 'number_command'=> $event->quantity]);
        }

    }
}
