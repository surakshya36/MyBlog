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
        // Get all categories with their creator information
        $data['rows'] = Category::with('creator')->latest()->get();
        
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
    $category = Category::with('creator')
                ->where('id', $id)
                ->where('created_by', Auth::id()) // Correct ownership check
                ->first();

    if (!$category) {
        return redirect()->route('categories.index')
               ->with('error', 'Category not found or unauthorized.');
    }

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
        $category = Category::find($id);

        if (!$category) {
            return redirect()->route('categories.index')->with('error', 'category not found.');
        }

        return view('categories.edit', compact('category'));
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
        
        $post = Category::find($id);

        if (!$post) {
            return redirect()->route('categories.index')->with('error', 'category not found.');
        }

        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:0,1',
        ]);

        // Update the post
        $post->update([
            'title' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->route('categories.index')->with('success', 'category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Category::find($id);

        if (!$post) {
            return redirect()->route('categories.index')->with('error', 'category not found.');
        }
        $post->delete();
        return redirect()->route('categories.index')->with('success', 'category deleted successfully.');
    }
}
