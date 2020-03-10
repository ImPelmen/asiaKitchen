<?php

namespace App\Http\Controllers\Admin;
////controller
use App\Http\Controllers\Controller;
///Models
use App\Models\Product;
use App\Models\Category;
///Requests
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;

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
    public function store(ProductRequest $request)
    {
        $product = Product::create($request->all());
        $product->categories()->attach($request->category);
        return ['flash' => 'Success saved'];
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
        dd($request);
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
    public function remove(Product $product)
    {
        dd($request);
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
}
