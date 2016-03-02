<?php

namespace App\Http\Controllers;

use Mail;
use Auth;
use View;
use Storage;
use App\Score;
use App\Avatar;
use App\History;
use App\Picture;
use App\Product;
use App\Category;
use App\Customer;
use App\Cart\Cart;
use Carbon\Carbon;
use App\Score\IScore;
use App\Http\Requests;
use App\Events\ScoreEvent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Menu\TraitMainMenu;


class FrontController extends Controller
{

    use TraitMainMenu;

    protected $paginate = 5;
    protected $score = null;
    protected $cacheTime = 120;
    private $cart;
    protected $product;
    protected $history;
    protected $avatar;
    protected $category;
    protected $picture;
    protected $customer;

    public function __construct(Cart $cart, Product $product, Category $category, History $history)
    {
        $this->getMenu();

        $this->cart = $cart;
        $this->category = $category;
        $this->product = $product;
        $this->history = $history;
    }

    /**
     * @param IScore $score
     * @return View
     */
    public function index(IScore $score)
    {
        $title = " Welcome Home page";

        $products = $this->product->with('tags', 'category', 'picture')
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

            $products = $this->product->with('tags', 'category', 'picture')->skip($offset)->take(5)->get();
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

        $id = (int)$id;

        if (!$product) {
            $product = $this->product->with('category', 'tags', 'picture')->findOrFail($id);
            $this->putCache($request, $product);
        }

        $title = " Page product:{$product->name}";

        $qStorage = 0;
        if ($this->cart->getQuantity($id)) $qStorage = $this->cart->getQuantity($id);

        $quantities = ($qStorage >= $product->quantity) ? null : range(1, $product->quantity - $qStorage);

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

        $category = $this->category->findOrFail($id);
        $title = " Welcome category page {$category->title}";

        $products = $this->getCache($request);

        if (!$products) {
            $products = $category->products()->with('tags', 'category', 'picture')->online()->orderBy('published_at')->paginate($this->paginate);
            $this->putCache($request, $products);
        }

        return view('front.category', compact('products', 'title'));

    }

    /**
     * @return View
     */
    public function showCart()
    {
        $products = $this->cart->getCart();

        $total = $this->cart->total();
        $number = $this->cart->count();

        return view('front.cart', compact('products', 'total', 'number'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeProduct(Request $request)
    {

        $this->validate($request, [
            'id' => 'required|integer',
            'quantity' => 'required|integer',
            'price' => 'required|numeric'
        ]);

        $product = $this->product->findOrFail($request->input('id'));

        if (is_null($product->quantity_lock))
            $product->quantity_lock = $product->quantity;

        $product->quantity = ($product->quantity - (int)$request->input('quantity'));
        $product->save();

        $this->cart->buy($product, $request->input('quantity'));

        return back()->with(['message' => trans('app.thank')]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatedCommand(Request $request)
    {

        $this->validate($request, [
            'reset.*' => 'integer',
            'product_id.*' => 'integer',
            'delete' => 'in:true',
            'quantity.*' => 'integer',
            'id.*' => 'integer',
        ]);

        if (!empty($request->input('reset'))) {
            foreach ($request->input('reset') as $id) {
                $this->cart->delete($id);
            }

            return back()->with(['message' => trans('app.deleteProduct')]);
        }

        if (!empty($request->input('delete'))) {
            $cookie = $this->cart->reset();

            return back()->withCookie($cookie)->with(['message' => trans('app.cartEmpty')]);
        }

        if (!Auth::check()) {
            return back()->with(['message' => trans('app.noAuthentificate')]);
        }

        if (!empty($request->input('product_id'))) {

            foreach ($request->input('product_id') as $id) {

                $quantity = (int)$request->input('quantity' . $id);
                $this->history->create([
                    'product_id' => $id,
                    'user_id' => $request->user()->id,
                    'quantity' => $quantity
                ]);

                \Event::fire(new ScoreEvent($id, $quantity));

            }

            $cookie = $this->cart->reset();

            return redirect()->home()->withCookie($cookie)->with(['message' => trans('app.thankForYourCommand')]);
        }

    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteOne($id)
    {
        $id = (int)$id;

        $this->cart->restore($id);

        // todo restore quantity dispo

        $product = $this->product->findOrFail($id);

        $product->quantity = $product->quantity_lock;
        $product->quantity_lock = null;
        $product->save();

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
            'email' => 'required|email',
            'content' => 'required|max:255'
        ]);

        $content = $request->input('content');

        Mail::send('emails.contact', compact('content'), function ($m) use ($request) {
            $m->from($request->input('email'), 'Client');
            $m->to(env('EMAIL_TECH'), 'admin')->subject('Contact e-boutique');
        });

        return redirect('contact')->with([
            'message' => trans('app.contactSuccess'),
            'alert' => 'success'
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

    /**
     * @param $userId
     * @return mixed
     */
    public function getAvatar($userId)
    {
        $avatar = Avatar::where('user_id', '=', (int)$userId)->firstOrFail();
        $file = Storage::disk('local')->get(env('UPLOAD_AVATARS') . DIRECTORY_SEPARATOR . $avatar->uri);

        return response($file, 200)->header('Content-Type', $avatar->mime);

    }

}