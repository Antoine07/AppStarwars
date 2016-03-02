<?php

namespace App\Cart;

class Cart implements \Countable
{

    protected $storage;

    public function __construct(IStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param $product
     * @param $quantity
     */
    public function buy($product, $quantity)
    {
        $quantity = abs((int)$quantity);

        $total = $quantity * $product->price;

        $this->storage->setValue($product->id, $total, $product->price, $quantity, $product->name, $product->quantity);
    }

    /**
     * @param $id
     * @param $quantity
     */
    public function restore($id, $quantity = 1)
    {
        $this->storage->restore($id, $quantity);
    }

    /**
     * @param $product
     * @return $this
     */
    public function delete($product)
    {
        $this->storage->delete($product->id);

        return $this;
    }

    /**
     * @return float
     */
    public function total()
    {
        return $this->storage->total();
    }

    /**
     * @return products into the cart
     */
    public function getCart()
    {
        return $this->storage->get();
    }

    /**
     *
     * @return NULL
     *
     * <pre>reset storage</pre>
     */
    public function reset()
    {
        return $this->storage->reset();
    }

    public function count()
    {
        return $this->storage->count();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getProduct($id)
    {
        return $this->storage->getValue($id);
    }

    /**
     * get quantity product into storage
     * @param $id
     * @return null
     */
    public function getQuantity($id)
    {
        $storage =  $this->storage->getValue($id);

        if(isset($storage['quantity'])) return $storage['quantity'];

        return null;
    }

}
