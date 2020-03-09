<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Image;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        return view('product.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = new Product();
        $product->title = $request->title;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->save();
        $product->categories()->attach($request->category);
        foreach($request->images as $image) {
            $iamgePath = Storage::disk('public')->put(auth()->user()->email, $image);
            Image::create([
                'image_path' => '/public/' . $iamgePath,
                'product_id' => $product->id 
            ]);
        }
       
        return ['flash' => 'Success saved', 'product' => $product];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }

    public function getProducts()
    {
        return [
            'products' =>Product::with('categories')->orderBy('created_at', 'DESC')->paginate(10),
            'category' => Category::all()
        ];
    }

    public function changePublishStatus(Product $product)
    {
        $product->is_publish = $product->is_publish ? 0 : 1;
        $product->save();
        return [
            'flash' => 'Success publish saved',
            'product' => $product
        ];
    }

    public function storeImages(Product $product) 
    {
        dd($product->toArray());
    }
}
