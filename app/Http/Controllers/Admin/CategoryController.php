<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function store(Request $request) 
    {
        Category::create([
            'title' => $request->category['title']
        ]);
        $categories = Category::get();
        return response()->json(['categories' => $categories]);
    }
}
