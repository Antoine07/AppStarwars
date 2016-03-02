<?php

use App\Product;
use App\Cart\Cart;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CartControllerTest extends TestCase
{

    protected $cart;
    protected $product;

    public function setUp()
    {
        parent::setUp();

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

        $this->product = Product::create([
            'id' => 1,
            'name' => 'test product',
            'price' => 10.5,
            'quantity' => 10,
            'quantity_lock' => null,
            'published_at' => \Carbon\Carbon::now()
        ]);

        $this->beforeApplicationDestroyed(function () {
            $this->artisan('migrate:rollback');
        });
    }

    /**
     * service provider cart
     *
     * @test
     */
    public function testServiceProviderCart()
    {
        $this->assertInstanceOf('App\Cart\Cart', $this->cart);

    }

    /**
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
     * @test quantity command into storage and update model product
     */
    public function testStoreProduct()
    {

        $credentials =['id' => 1, 'quantity' => 7, 'price' => 10.5];

        $response = $this->action('POST', 'FrontController@storeProduct', $credentials );

        $product = $this->product->find(1);

        $this->assertEquals(3, $product->quantity);
        $this->assertEquals(10, $product->quantity_lock);

        $qStorage = $this->cart->getQuantity(1);

        $this->assertEquals(7, $qStorage);

        $this->assertTrue($response->isRedirection());

    }

    /**
     * @test restore product
     */
    public function testRestoreProduct()
    {
        $product = $this->product->find(1);
        $product->quantity = 3;
        $product->quantity_lock = 10;
        $product->save();

        $response = $this->action('GET', 'FrontController@deleteOne', ['id' => 1] );

        $p = $this->product->find(1);

        $this->assertEquals(10, $p->quantity);
        $this->assertEquals(null, $p->quantity_lock);

        $qStorage = $this->cart->getQuantity(1);

        $this->assertEquals(0, $qStorage);
    }
}
