<?php namespace App\Score;

interface IScore
{
    function best();
    function score($productId);
    function set($productId, $numberCommand);
}
