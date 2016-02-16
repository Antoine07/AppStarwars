<?php namespace App\Cart;

interface IStorage
{
    function setValue($id, $total, $price, $quantity, $name, $quantityMax);

    function getValue($id);

    function get();

    function delete($id);

    function reset();

    function total();

    function count();
}
