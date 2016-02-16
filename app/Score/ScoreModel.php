<?php namespace App\Score;

use App\Score;

class ScoreModel implements IScore
{

    protected $score;

    protected $render = 'layouts.master';

    public function __construct(Score $score)
    {
        $this->score = $score;
    }

    public function best()
    {

        $score = $this->score
            ->whereRaw('number_command = (SELECT MAX(number_command) FROM scores)')
            ->first();

        if (!empty($score)) {
            $best = $score->product;
            view()->composer($this->render, function ($view) use ($best) {
                $view->with(compact('best'));
            });
        }
    }

    public function score($productId)
    {
        return $this->score->where('product_id', '=', $productId)->first();
    }

    public function set($productId, $numberCommand)
    {
        $score = $this->score->where('product_id', '=', $productId)->get();

        if (count($score) > 0) {
            $score->number_command += $numberCommand;
        } else {
            Score::create(['number_command' => $numberCommand, 'product_id' => $productId]);
        }
    }

}