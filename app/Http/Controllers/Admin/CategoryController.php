<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function getCategory()
    {
        return Category::all();
    }

    public function store(Request $request)
    {
        if (isset($request->category)) {    
            Category::create([
                'title' => $request->category['title'] == null ? null :$request->category['title'] 
            ]);
        }
        $categories = Category::get();
        
        return response()->json(['categories' => $categories]);
    }

    public function edit(Request $request)
    {
        if (isset($request->category)) {
            $category = Category::find($request->category['id'])->first();
            $category->title = $request->category['title'] == null ? null :$request->category['title'];
            $category->save();
        }
        
        $categories = Category::get();
        return response()->json(['categories' => $categories]);
    }

    public function remove(Request $request)
    {
        $category = Category::find($request->id)->first();
        $category->delete();
        $categories = Category::get();
        return response()->json(['categories' => $categories]);
    }
}
