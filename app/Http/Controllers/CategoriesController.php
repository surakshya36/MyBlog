<?php

namespace App\Http\Controllers;


use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // You're filtering by 'id' instead of 'created_by'
        $data['rows'] = Category::with('creator')
            ->where('created_by', Auth::id())  // Changed from where('id', ...)
            ->get();
    
        return view('categories.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create');
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'status' => 'required|in:1,0',
    ]);
    
    // Fix: Use correct model name and handle errors
    try {
        Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'created_by' => Auth::id(),
        ]);
        
        return redirect()->route('categories.index')
               ->with('success', 'Category created successfully.');
    } catch (\Exception $e) {
        return back()->withInput()
               ->with('error', 'Error creating category: '.$e->getMessage());
    }
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Find the post by ID, but only if it belongs to the logged-in user
        $category = Category::where('id', $id)->
        with('creator')
            ->where('id', Auth::id()) // Ensures the post belongs to the logged-in user
            ->first();

        // If the category is not found, redirect with an error message
        if (!$category) {
            return redirect()->route('categories.index')->with('error', 'category not found or unauthorized.');
        }

        // Return the view for showing the post details
        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
