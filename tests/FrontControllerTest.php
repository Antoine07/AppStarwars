<?php

use App\Product;
use App\Cart\Cart;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FrontControllerTest extends TestCase
{

    protected $cart;

    public function setUp()
    {
        parent::setUp();

        $this->app->bind('App\Cart\IStorage', 'Stub\ArrayStorage');
        $this->cart = $this->app['App\Cart\Cart'];

    }

    /**
     * @before
     */
    public function runDatabaseMigrations()
    {

        $this->artisan('migrate', [
            '--path' => './tests/migrations',
        ]);

        Product::create([
            'id' => 1,
            'name' => 'test product',
            'price' => 10.5,
            'quantity' => 10,
            'quantity_lock' => null,
            'published_at' => \Carbon\Carbon::now()
        ]);

        Product::create([
            'id' => 2,
            'name' => 'test product',
            'price' => 10.5,
            'quantity' => 7,
            'quantity_lock' => null,
            'published_at' => \Carbon\Carbon::now()
        ]);

        $this->beforeApplicationDestroyed(function () {
            $this->artisan('migrate:rollback');
        });
    }

    /**
     * service provider cart object exist and the storage is stubbed
     *
     * @test
     */
    public function testServiceProviderCart()
    {
        $this->assertInstanceOf('App\Cart\Cart', $this->cart);
        $this->assertEquals([], $this->cart->getCart());

    }

    /**
     * validate rules store product
     *
     * @test validation store product
     */
    public function testValidateStoreProduct()
    {
        $rules = [
            'id' => 'required|integer',
            'quantity' => 'required|integer',
            'price' => 'required|numeric'
        ];

        $data = ['id' => 1, 'quantity' => 10, 'price' => 10.5];

        $v = $this->app['validator']->make($data, $rules);

        $this->assertTrue($v->passes());
    }

    /**
     * store product with specific product values
     *
     * @test quantity command into storage and update model product
     */
    public function testStoreProduct()
    {

        $credentials = ['id' => 1, 'quantity' => 7, 'price' => 10.5];

        $response = $this->action('POST', 'FrontController@storeProduct', $credentials);

        $product = Product::find(1);

        $this->assertEquals(3, $product->quantity);
        $this->assertEquals(10, $product->quantity_lock);

        $qStorage = $this->cart->getQuantity(1);

        $this->assertEquals(7, $qStorage);

        $this->assertTrue($response->isRedirection());

    }

    /**
     * restore product with specific product values
     *
     * @test restore product
     */
    public function testRestoreProduct()
    {
        $product = Product::find(1);
        $product->quantity = 3;
        $product->quantity_lock = 10;
        $product->save();

        $this->cart->buy($product, 3);

        $response = $this->action('GET', 'FrontController@deleteOne', ['id' => 1]);

        $p = Product::find(1);

        $this->assertEquals(2, $p->quantity);
        $this->assertEquals(10, $p->quantity_lock);

        $qStorage = $this->cart->getQuantity(1);

        $this->assertEquals(2, $qStorage);
    }

    /**
     * test with two products into storage and reset all products, check if storage and product entity are rested
     *
     * @test
     */
    public function testResetAll()
    {

        $p1 = Product::find(1);
        $p1->quantity = 5;
        $p1->quantity_lock = 10;
        $p1->save();

        $p2 = Product::find(2);
        $p2->quantity = 2;
        $p2->quantity_lock = 7;
        $p2->save();

        $this->cart->buy($p1,5);
        $this->cart->buy($p2,2);

        $credentials = ['delete' => 'true'];

        $response = $this->action('POST', 'FrontController@updatedCommand', $credentials);

        $p1 = Product::find(1);
        $p2 = Product::find(2);

        $this->assertEquals(10, $p1->quantity);
        $this->assertEquals(null, $p1->quantity_lock);

        $this->assertEquals(7, $p2->quantity);
        $this->assertEquals(null, $p2->quantity_lock);

        $qStorage = $this->cart->getQuantity(1);

        $this->assertEquals(0, $qStorage);

    }
}
