<?php namespace App\Cart;

use DB;
use Session;

class SqliteStorage implements IStorage
{

    protected $storage = null;

    public function __construct()
    {
        $this->storage = DB::connection('sqlite');
        $this->hash = session()->get('hash');

    }


    function setValue($id, $total, $price, $quantity, $name)
    {

        $count = $this->storage->select('select count(*) as number from carts where hash=?', [$this->hash]);

        if ($count['number'] > 0)
            $this->storage->update('update carts set product_id = ?, total=?, price=?, quantity=?, name=? where hash = ?', [$id, $total, $price, $quantity, $name, $this->hash]);
    }

    function getValue($id)
    {
        // TODO: Implement getValue() method.
    }

    function get()
    {
        // TODO: Implement get() method.
    }

    function delete($id)
    {
        // TODO: Implement delete() method.
    }

    function reset()
    {
        // TODO: Implement reset() method.
    }

    function total()
    {
        // TODO: Implement total() method.
    }

    function count()
    {
        // TODO: Implement count() method.
    }

}