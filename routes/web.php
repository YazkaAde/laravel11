<?php

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Mail\WelcomeMail;
use App\Jobs\ProcessWelcomeMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;



Route::middleware('guest')->group(function(){
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticating']);
    Route::get('/register', [AuthController::class, 'register']);
    Route::post('/register', [AuthController::class, 'createUser']);
});

// Route::get('/', function() {
//     return auth()->user()()->name;
// })->middleware('verified');

Route::middleware(['auth'/*,'verified'*/])->group(function () {
    Route::get('/', function () {
        return view('home', ['title' => 'Home Page']);
    });

    Route::get('/about', function () {
        return view('about', ['nama' => 'Yazka Ade', 'title' => 'About']);
    });

    Route::get('/posts', function () {
        return view('posts', ['title' => 'Blog', 'posts' => Post::filter(request(['search', 'category', 'author']))->latest()->paginate(9)->withQueryString()]);
    });

    Route::get('/posts/{post:slug}', function(Post $post) {
        return view('post', ['title' => 'Single post', 'post' => $post]);
    });

    Route::get('/authors/{user:username}', function(User $user) {
        return view('posts', [
            'title' => count($user->posts) . ' Article by ' . $user->name, 
            'posts' => $user->posts
        ]);
    });

    Route::get('/category', function () {
        return view('category', ['nama' => 'Yazka Ade', 'title' => 'Category']);
    });

    Route::get('/categories/{category:slug}', function(Category $category) {
        return view('posts', [
            'title' => ' Articles in: ' . $category->name, 
            'posts' => $category->posts
        ]);
    });

    // CRUD START
    // Post start
    // Create
    Route::get('/blog/add', [BlogController::class, 'add']);
    Route::post('/blog/create', [BlogController::class, 'create']);
    
    // Edit
    Route::get('/blog/{id}/edit', [BlogController::class, 'edit']);
    Route::put('/blog/{id}', [BlogController::class, 'update'])->name('blog.update');
    // Route::post('/blog/{id}', [BlogController::class, 'update']);
    
    // Delete
    Route::delete('/blog/{id}', [BlogController::class, 'destroy']);
    // Post end

    // Category start
    Route::get('/category', [CategoryController::class, 'index']);
    Route::post('/category', [CategoryController::class, 'store']);
    Route::put('/category/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/category/{id}', [CategoryController::class, 'destroy']);
    // Route::put('/category/{id}', [CategoryController::class, 'update'])->name('category.update');
    // Category end

    // CRUD END

    Route::get('/contact', function () {
        return view('contact', ['title' => 'Contact']);
    });

    Route::get('/profile', function () {
        return view('profile', ['title' => 'Profile']);
    });

    // web.php
    Route::get('/blog/{id}/edit', [BlogController::class, 'edit'])->middleware('can:update,post');
    Route::put('/blog/{id}', [BlogController::class, 'update'])->name('blog.update')->middleware('can:update,post');

    // Logout
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware(['auth'])->group(function () {
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');


Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
$request->fulfill();

return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');
});

Route::get('/send-welcome-mail', function() {
    $users = [
        ['email' => 'keiza@gmail.com', 'password' => 123],
        ['email' => 'yazka@gmail.com', 'password' => 123],
        ['email' => 'kaka@gmail.com', 'password' => 123],
        ['email' => 'keke@gmail.com', 'password' => 123],
        ['email' => 'jetsko@gmail.com', 'password' => 123],
    ];

    foreach ($users as $userData) {
        ProcessWelcomeMail::dispatch($userData)->onQueue('send-email');
        
    }

    return "Email jobs telah dikirim ke queue!";
});