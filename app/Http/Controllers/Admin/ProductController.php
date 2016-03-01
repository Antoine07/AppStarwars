<?php

namespace App\Http\Controllers\Admin;

use View;
use File;
use App\Tag;
use App\Product;
use App\Picture;
use App\Category;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;

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
    public function store(ProductRequest $request)
    {
        $product = Product::create($request->all());

        if (!empty($request->input('tag_id')))
            $product->tags()->attach($request->input('tag_id'));

        if (!empty($request->file('picture'))) {
            $this->upload($request->file('picture'), $product->id);
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
    public function update(ProductRequest $request, $id)
    {
        $product = Product::find($id);

        $tags = !empty($request->input('tag_id')) ? $request->input('tag_id') : [];

        $product->tags()->sync($tags);

        if ($request->input('deletePicture') == 'true') {
            $deletePicture = $this->deletePicture($product);
        }

        $im = $request->file('picture');

        if (!is_null($im)) {

            if (empty($deletePicture))
                $this->deletePicture($product);

            $this->upload($im, $product->id);
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

        $this->deletePicture($product);

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
     * @param $im
     * @param $productId
     */
    private function upload($im, $productId)
    {
        $ext = $im->getClientOriginalExtension();
        $uri = str_random(12) . '.' . $ext;
        Picture::create([
            'uri' => $uri,
            'size' => $im->getSize(),
            'type' => $ext,
            'mime' => $im->getClientMimeType(),
            'product_id' => $productId
        ]);

        $im->move(env('UPLOAD_PATH', './uploads'), $uri);
    }

    /**
     * @param Product $p
     * @return bool
     */
    private function deletePicture(Product $p)
    {
        if (!is_null($p->picture)) {
            $fileName = public_path('uploads'). DIRECTORY_SEPARATOR. $p->picture->uri;

            if (File::exists($fileName))
                File::delete($fileName);

            $p->picture->delete();

            return true;
        }

        return false;
    }

}