<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartProductController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StripeCheckoutController;


Route::post('api/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('api/register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('api/logout', [App\Http\Controllers\AuthController::class, 'logout']);
Route::get('api/products/{product}/reviews', [ProductReviewController::class, 'index']);
Route::prefix('api/products/{product}')->middleware('auth:sanctum')->group(function () {
    Route::post('/reviews', [ProductReviewController::class, 'store']);
});

Route::prefix('api')->middleware('auth:sanctum')->group(function () {

    Route::get('/user/reviews', [ProductReviewController::class, 'userReviews']);
    Route::delete('/reviews/{id}', [ProductReviewController::class, 'destroy']);
});
Route::get('/api/products/ratings', [ProductReviewController::class, 'productRatings']);
Route::prefix('api')->middleware('auth:sanctum')->group(function () {
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites/add', [FavoriteController::class, 'addToFavorites']);
    Route::delete('/favorites/remove/{product}', [FavoriteController::class, 'deleteFavorite']);
    Route::get('/favorites/check/{product}', [FavoriteController::class, 'checkFavorite']);
    Route::delete('/favorites/clear', [FavoriteController::class, 'clearAll']);
});

Route::prefix('api')->middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartProductController::class, 'index']);
    Route::post('/AddCart', [CartProductController::class, 'addToCart']);
    Route::put('/cart/increment/{id}', [CartProductController::class, 'increment']);
    Route::put('/cart/decrement/{id}', [CartProductController::class, 'decrement']);
    Route::delete('/cart/remove/{id}', [CartProductController::class, 'removeFromCart']);

});
Route::prefix('api')->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);

    Route::get('/categories/{categoryId}/products', [ProductController::class, 'byCategory']);
});


Route::prefix('api')->group(function () {
    Route::get('/profile/{id}', [ProfileController::class, 'show']);
    Route::put('/profile/{id}', [ProfileController::class, 'update']);
    Route::delete('/profile/{id}', [ProfileController::class, 'destroy']);
    Route::put('/profile/{id}', [ProfileController::class, 'update']);
    Route::post('/profile/{id}/avatar', [ProfileController::class, 'updateAvatar']);
});
Route::prefix('api')->group(function () {

    Route::get('/products', [ProductController::class, 'index']);
});

Route::get('/api/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::get('/categories/{categoryId}/products', [CategoryController::class, 'products']);


Route::middleware(['auth:sanctum',\App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->group(function () {
    // Продукты
    Route::get('/products', [AdminController::class, 'productIndex']);
    Route::post('/products', [AdminController::class, 'productStore']);
    Route::get('/products/{id}', [AdminController::class, 'productShow']);
    Route::put('/products/{id}', [AdminController::class, 'productUpdate']);
    Route::delete('/products/{id}', [AdminController::class, 'productDestroy']);


    Route::get('/categories', [AdminController::class, 'categoryIndex']);
    Route::post('/categories', [AdminController::class, 'categoryStore']);
    Route::get('/categories/{id}', [AdminController::class, 'categoryShow']);
    Route::put('/categories/{id}', [AdminController::class, 'categoryUpdate']);
    Route::delete('/categories/{id}', [AdminController::class, 'categoryDestroy']);
});



Route:: prefix('api')-> middleware('auth:sanctum')->group(function () {
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
});





Route::prefix('api')->group(function () {


    Route::get('/payment/success', [StripeCheckoutController::class, 'success']);
    Route::get('/payment/cancel', [StripeCheckoutController::class, 'cancel']);
});



Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');