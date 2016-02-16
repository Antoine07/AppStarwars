<?php

namespace App\Http\Controllers\Admin;

use View;
use Storage;
use App\Tag;
use App\Product;
use App\Picture;
use App\Category;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with('category', 'tags')->paginate(10);

        return view('admin.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = Tag::lists('name', 'id');
        $categories = Category::lists('title', 'id');

        return view('admin.product.create', compact('tags', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Requests\ProductRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\ProductRequest $request)
    {

        $product = Product::create($request->all());

        if (!empty($request->input('tags')))
            $product->tags()->attach($request->input('tags'));

        if (!is_null($request->file('thumbnail'))) {
            $this->upload($request, $product);
        }

        return redirect('product')->with(['message' => 'success add']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);

        $tags = Tag::lists('name', 'id');
        $categories = Category::lists('title', 'id');

        return view('admin.product.edit', compact('product', 'tags', 'categories'));
    }

    /**
     * @param Requests\ProductRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Requests\ProductRequest $request, $id)
    {
        $product = Product::find($id);

        if (!empty($request->input('tags')))
            $product->tags()->sync($request->input('tags'));
        else
            $product->tags()->detach();

        if ($request->input('delete') == 'true') $this->deleteImage($product);

        if (!is_null($request->file('thumbnail'))) {
            $this->deleteImage($product);
            $this->upload($request, $product);
        }

        $product->update($request->all());

        return redirect('product')->with(['message' => 'success update']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // todo comfirm client deleted
        $product = Product::find($id);

        $this->deleteImage($product);

        $product->delete();

        return redirect('product')->with(['message' => 'delete product success']);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeStatus($id)
    {
        $product = Product::find($id);

        $product->status = ($product->status == 'opened') ? 'closed' : 'opened';

        $product->save();

        return back()->with(['message' => trans('app.changeStatus')]);
    }

    /**
     * @param Request $request
     * @param $product
     */
    private function upload(Request $request, $product)
    {
        $im = $request->file('thumbnail');

        $ext = $im->getClientOriginalExtension();
        $picture = Picture::create([
            'uri'        => $uri = str_random(12) . '.' . $ext,
            'size'       => $im->getSize(),
            'type'       => $ext,
            'product_id' => $product->id
        ]);

        $request->file('thumbnail')->move(env('UPLOAD_PATH', './uploads'), $picture->uri);
    }

    /**
     * @param $p
     */
    private function deleteImage($p)
    {
        if (!is_null($p->picture)) {
            Storage::delete($p->picture->uri);
            $p->picture->delete();
        }
    }

}