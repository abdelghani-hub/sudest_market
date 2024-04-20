<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SellerRequestController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PayementController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Seller\SellerController;
use Illuminate\Support\Facades\Route;
use Mockery\Generator\StringManipulation\Pass\Pass;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post("/products/addToCart/{product}", [CartController::class, "addProducttoCart"])->name("add_to_cart");
Route::post("/products/removeFromCart", [CartController::class, "removeFromCart"])->name("remove_from_cart");
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/search', SearchController::class)->name('search');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

Route::group(['middleware'=>"auth"], function(){
    // Admin
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Seller
    Route::get('/MyStore', [SellerController::class, 'statistics'])->name('seller.mystore');
    Route::prefix('/MyStore')->resource('/MyStore/categories', SellerController::class)
            ->only([
                'index',
                'show',
            ])
            ->names("seller.mystore.categories");
    Route::put("/MyStore/categories", [SellerController::class, 'update'])->name("seller.mystore.categories.update");
    Route::resource('/MyStore/products', ProductController::class)->except(['create', 'show']);
    Route::get('/MyStore/products/create/{category}', [ProductController::class, 'create'])->name('products.create');

    // Customer
    Route::get('/sellerRequests', [SellerRequestController::class, 'index'])->name('sellerRequests.index');
    Route::post('/become-seller', [SellerRequestController::class, 'store'])->name('become-seller');
    Route::put('/sellerRequests/{id}', [SellerRequestController::class, 'update'])->name('sellerRequests.update');
    Route::post("/checkout", [OrderController::class, 'checkout'])->name("checkout");
    Route::get("/success", [PayementController::class, 'success'])->name("success");
    Route::get("/cancel", [PayementController::class, 'cancel'])->name("cancel");

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
});

require __DIR__ . '/auth.php';
