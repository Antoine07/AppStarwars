<?php

namespace App\Http\Controllers;

use App\History;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use View;
use Mail;
use Auth;
use App\Product;
use App\Category;
use App\Customer;
use App\Cart\Cart;
use App\Score\IScore;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Menu\TraitMainMenu;


class FrontController extends Controller
{

    use TraitMainMenu;

    protected $paginate = 5;
    protected $score = null;
    protected $cacheTime = 120;

    public function __construct()
    {
        $this->getMenu();
    }

    /**
     * @param IScore $score
     * @return View
     */
    public function index(IScore $score)
    {
        $title = " Welcome Home page";

        $products = Product::with('tags', 'category', 'picture')
            ->online()
            ->orderBy('published_at', 'desc')
            ->paginate($this->paginate);

        $score->best();

        $count = 1;

        return view('front.index', compact('products', 'title', 'count'));
    }

    /**
     * @param Request $request
     * @return string|View|void
     */
    public function onePage(Request $request)
    {
        if ($request->ajax()) {
            $offset = (int)$request->input('offset') + 1;

            $products = Product::with('tags', 'category', 'picture')->skip($offset)->take(5)->get();
            $count = $offset;

            if (count($products) > 0)
                return view('front.partials.products', compact('products', 'count'));

            return 'last';
        }

        return;
    }

    /**
     * @param IScore $score
     * @param $id
     * @param string $slug
     * @param Request $request
     * @return View
     */
    public function showProduct(IScore $score, $id, $slug = '', Request $request)
    {
        $product = $this->getCache($request);

        if (!$product) {
            $product = Product::with('category', 'tags', 'picture')->findOrFail($id);
            $this->putCache($request, $product);
        }

        $title = " Page product:{$product->name}";
        $quantities = range(1, $product->quantity);

        $pop = $score->score($product->id);

        return view('front.show', compact('product', 'title', 'quantities', 'pop'));
    }

    /**
     * @param $id
     * @param string $slug
     * @return View
     */
    public function showProductByCategory($id, $slug = '', Request $request)
    {

        $category = Category::findOrFail($id);
        $title = " Welcome category page {$category->title}";

        $products = $this->getCache($request);

        if (!$products) {
            $products = $category->products()->with('tags', 'category', 'picture')->online()->orderBy('published_at')->paginate($this->paginate);
            $this->putCache($request, $products);
        }

        return view('front.category', compact('products', 'title'));

    }

    /**
     * @param Cart $cart
     * @return View
     */
    public function showCart(Cart $cart)
    {
        $products = $cart->getCart();

        $total = $cart->total();
        $number = $cart->count();

        return view('front.cart', compact('products', 'total', 'number'));
    }

    /**
     * @param Request $request
     * @param Cart $cart
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeProduct(Request $request, Cart $cart)
    {

        $this->validate($request, [
            'id'       => 'required|integer',
            'quantity' => 'required|integer',
            'price'    => 'required|numeric'
        ]);

        $product = Product::findOrFail($request->input('id'));

        $cart->buy($product, $request->input('quantity'));

        return back()->with(['message' => trans('app.thank')]);
    }

    /**
     * @param Request $request
     * @param Cart $cart
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatedCommand(Request $request, Cart $cart)
    {

        $this->validate($request, [
            'reset.*'      => 'integer',
            'product_id.*' => 'integer',
            'delete'       => 'in:true',
            'quantity.*'   => 'integer',
            'id.*'         => 'integer',
        ]);

        if (!empty($request->input('reset'))) {
            foreach ($request->input('reset') as $id) {
                $cart->delete($id);
            }

            return back()->with(['message' => trans('app.deleteProduct')]);
        }

        if (!empty($request->input('delete'))) {
            $cookie = $cart->reset();

            return back()->withCookie($cookie)->with(['message' => trans('app.cartEmpty')]);
        }

        if (!Auth::check()) {
            return back()->with(['message' => trans('app.noAuthentificate')]);
        }

        if (!empty($request->input('product_id'))) {
            foreach ($request->input('product_id') as $id) {

                History::create([
                    'product_id' => $id,
                    'user_id'    => $request->user()->id,
                    'quantity'   => $request->input('quantity' . $id)
                ]);
            }

            $cookie = $cart->reset();

            return redirect()->home()->withCookie($cookie)->with(['message' => trans('app.thankForYourCommand')]);
        }

    }

    /**
     * @param $id
     * @param Cart $cart
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteOne($id, Cart $cart)
    {
        $id = (int)$id;

        $cart->restore($id);

        return back()->with(['message' => trans('app.deleteOneProductSuccess')]);

    }

    /**
     * @return View
     */
    public function showContact()
    {
        $title = " Page contact";

        return view('front.contact', compact('title'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendContact(Request $request)
    {

        $this->validate($request, [
            'email'   => 'required|email',
            'content' => 'required|max:255'
        ]);

        $content = $request->input('content');

        Mail::send('emails.contact', compact('content'), function ($m) use ($request) {
            $m->from($request->input('email'), 'Client');
            $m->to(env('EMAIL_TECH'), 'admin')->subject('Contact e-boutique');
        });

        return redirect('contact')->with([
            'message' => trans('app.contactSuccess'),
            'alert'   => 'success'
        ]);
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function getCache(Request $request)
    {
        $ext = $request->get('page') ? $request->get('page') : '';
        $url = $request->url() . $ext;

        if (Cache::has($url)) {
            return Cache::get($url);
        }

        return false;
    }

    /**
     * @param Request $request
     * @param $data
     * @param string $time
     */
    private function putCache(Request $request, $data, $time = '')
    {
        $time = (!empty($time)) ? $time : $this->cacheTime;

        $ext = $request->get('page') ? $request->get('page') : '';
        $url = $request->url() . $ext;

        $expiresAt = Carbon::now()->addMinutes($time);
        Cache::put($url, $data, $expiresAt);
    }

}