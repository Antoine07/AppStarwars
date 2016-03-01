<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

\Debugbar::disable();

Route::pattern('id', '[1-9][0-9]*');
Route::pattern('slug', '[a-z0-9-\_]+');
Route::pattern('filename', '[a-zA-Z0-9\_\.]+');

Route::get('file/{filename}', [
    'as' => 'get', 'uses' => 'FrontController@getFile']);

Route::group(['middleware' => ['web']], function () {

    Route::get('/', ['as' => 'home', 'uses' => 'FrontController@index']);

    Route::get('/cat/{id}/{slug?}', 'FrontController@showProductByCategory');
    Route::get('/prod/{id}/{slug?}', 'FrontController@showProduct');

    Route::post('command', 'FrontController@storeProduct');
    Route::post('updatedCommand', 'FrontController@updatedCommand');

    Route::get('cart', ['as' => 'cart', 'uses' => 'FrontController@showCart']);

    Route::get('contact', 'FrontController@showContact');
    Route::post('send', 'FrontController@sendContact');

    Route::resource('customer', 'CustomerController');
    Route::resource('inscription', 'InscriptionController');

    // limit 60 requests per one minute from a single address IP, throttle
    Route::group(['middleware' => ['throttle:60,1']], function () {
        Route::any('login', 'LoginController@login');
    });

    Route::get('logout', 'LoginController@logout');
    Route::post('prod/ajax', 'FrontController@onePage');
    Route::get('deleteOne/{id}', 'FrontController@deleteOne');

    /*
     * Admin routes
     */
    Route::group([
        'middleware' => ['auth', 'admin']], function () {
        Route::resource('product', 'Admin\ProductController');
        Route::resource('history', 'Admin\HistoryController');
        Route::get('product/status/{id}', 'Admin\ProductController@changeStatus');
        Route::resource('dashboard', 'Admin\DashboardController');
    });

});

/* ------------------------------------------------- *\
    Container de service
\* ------------------------------------------------- */
//
//class Foo
//{
//}
//
//$foo = new Foo;
//
//class Bar
//{
//    private $foo;
//
//    public function __construct(Foo $foo)
//    {
//        $this->foo = $foo;
//    }
//}
//
//
////var_dump(App::make('Bar'));
//
//
//class Ip
//{
//
//
//    private $ip;
//    private $uniqid;
//
//    /**
//     * @param $ip
//     */
//    public function __construct($ip)
//    {
//
//        $this->ip = $ip;
//
//        $this->uniqid = uniqid();
//    }
//
//}
//
//App::bind('Ip', function ($app) {
//    return new Ip($app->make('request')->getClientIp());
//});
//
////App::singleton('Ip', function ($app) {
////    return new Ip($app->make('request')->getClientIp());
////});
//
//
////var_dump(App::make('Ip'));
////var_dump(App::make('Ip'));
////var_dump(App::make('Ip'));
//
//
//Route::get('test', function (Ip $ip) {
//    dd($ip);
//});
//
//
///* ------------------------------------------------- *\
//    Inversion of control
//\* ------------------------------------------------- */
//
//App::bind('App\Cache\ICache', 'App\Cache\CacheFile');
//
//
//Route::get('test2', function (App\Cache\ICache $cache) {
//    dd($cache);
//});
//
///* ------------------------------------------------- *\
//    Use Interface framework
//\* ------------------------------------------------- */
//
//Route::get('test3', function (Illuminate\Contracts\Cache\Repository $cache) {
//    dd($cache);
//});
//
///* ------------------------------------------------- *\
//    Service provider
//\* ------------------------------------------------- */
//
//// winner customer
//Route::get('test4', function (App\Score\IScore $score) {
//    dd($score->get(100));
//});
