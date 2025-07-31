<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    function add()
    {
        return view('posts-add', ['title' => 'Create New Blog']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|max:255|unique:posts,title',
        'category_id' => 'required|exists:categories,id',
        'body' => 'required',
    ], [
        'title.required' => 'Judul artikel wajib diisi',
        'title.max' => 'Judul tidak boleh lebih dari 255 karakter',
        'title.unique' => 'Judul artikel sudah ada, gunakan judul yang berbeda',
        'category_id.required' => 'Kategori wajib dipilih',
        'category_id.exists' => 'Kategori yang dipilih tidak valid',
        'body.required' => 'Konten artikel wajib diisi',
    ]);

    Post::create([
        'title' => $validated['title'],
        'category_id' => $validated['category_id'],
        'author_id' => auth()->id(),
        'slug' => Str::slug($validated['title']),
        'body' => $validated['body'],
    ]);


    return redirect('/posts')->with('success', 'Post created successfully!');}
    /**
     * Store a newly created resource in storage.
     */
    public function store()
{
    //
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
{
    $post = Post::findOrFail($id);
    
    if (auth()->id() !== $post->author_id) {
        abort(403, 'Unauthorized action.');
    }

    $categories = \App\Models\Category::all();
    return view('posts-edit', [
        'title' => 'Edit Blog',
        'post' => $post,
        'categories' => $categories
    ]);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $post = Post::findOrFail($id);

    if (auth()->id() !== $post->author_id) {
        abort(403, 'Unauthorized action.');
    }
    
    $validated = $request->validate([
        'title' => 'required|max:255|unique:posts,title,'.$post->id,
        'category_id' => 'required|exists:categories,id',
        'body' => 'required',
    ], [
        'title.required' => 'Judul artikel wajib diisi',
        'title.max' => 'Judul tidak boleh lebih dari 255 karakter',
        'title.unique' => 'Judul artikel sudah ada, gunakan judul yang berbeda',
        'category_id.required' => 'Kategori wajib dipilih',
        'category_id.exists' => 'Kategori yang dipilih tidak valid',
        'body.required' => 'Konten artikel wajib diisi',
    ]);

    $post->update([
        'title' => $validated['title'],
        'category_id' => $validated['category_id'],
        'slug' => Str::slug($validated['title']),
        'body' => $validated['body'],
    ]);

    return redirect('/posts/'.$post->slug)->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    $post = Post::findOrFail($id);
    $post->delete();
    
    return redirect('/posts')->with('success', 'Post deleted successfully!');
    }
}
