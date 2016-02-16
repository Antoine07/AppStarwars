<?php namespace App\Cart;


use Illuminate\Support\Facades\Cookie;

class SessionStorage implements IStorage
{

    private $storage = [];

    public function __construct()
    {
        if (Cookie::has('cart')) {
            $this->storage = Cookie::get('cart');

            return;
        }

        $this->storage = [];
    }

    public function get()
    {
        return $this->storage;
    }

    public function delete($id)
    {
        if (!empty($this->storage)) {
            if (!empty($this->storage[$id])) {
                unset($this->storage[$id]);
                $this->save();
            }
        }

        return false;
    }

    public function count()
    {
        $res = 0;
        foreach ($this->storage as $product) $res += $product['quantity'];

        return $res;
    }

    public function total()
    {
        $res = 0;
        foreach ($this->storage as $product) $res += $product['total'];

        return $res;
    }

    public function setValue($id, $total, $price, $quantity, $name, $quantityMax)
    {
        if (!empty($this->storage)) {

            if (!empty($this->storage[$id])) {
                $this->storage[$id]['total'] += $total;
                $this->storage[$id]['quantity'] += $quantity;

                if ($this->storage[$id]['quantity'] == 0) {
                    $this->delete($id);

                    return;
                }

                $this->save();

                return;
            }
        }

        $this->storage[$id] = ['total' => $total, 'price' => $price, 'quantity' => $quantity, 'name' => $name, 'max' => $quantityMax];
        $this->save();

    }

    public function getValue($id)
    {
        if (!empty($this->storage)) {
            if (!empty($this->storage[$id])) {
                return $this->storage[$id];
            }
        }

        return false;

    }

    public function reset()
    {
        return cookie()->forget('cart');
    }

    /**
     * @param $id
     * @param int $quantity
     */
    public function restore($id, $quantity = 1)
    {
        $storage = $this->getValue($id);
        $total = $storage['total'];
        $price = $storage['price'];
        $name = $storage['name'];
        $max = $storage['max'];

        if ($total > 0) {
            $t = -((int)$quantity) * $price;

            if (abs($t) > $total) {
                $quantity = -$quantity;
                $this->setValue($id, -$total, $price, $quantity, $name, $max);

                return;
            }

            $quantity = -$quantity;
            $this->setValue($id, $t, $price, $quantity, $name, $max);
        }
    }

    private function save()
    {
        cookie()->queue(cookie()->make('cart', $this->storage, 24 * 3600));
    }

}