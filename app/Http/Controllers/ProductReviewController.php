<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function index(Product $product)
    {
    $reviews = $product->reviews()
        ->with('user')
        ->latest()
        ->get();

    return response()->json($reviews);
}

    public function productRatings()
    {

        return Product::withCount('reviews')
            ->withAvg('reviews as average_rating', 'rating')
            ->get();
    }

    public function show($id)
{
    $product = Product::withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->findOrFail($id);

    return response()->json($product);
}
public function store(Request $request, Product $product)
{
    $request->validate(
[
        'rating' => 'required|integer|min:1|max:5',
        'pros' => 'nullable|string|max:500',
        'cons' => 'nullable|string|max:500',
        'comment' => 'nullable|string|max:1000',
       ],

    [
        'rating.required' => 'Пожалуйста, укажите оценку',
        'rating.min' => 'Минимальная оценка - 1 звезда',
        'rating.max' => 'Максимальная оценка - 5 звезд',
        'pros.max' => 'Достоинства не должны превышать 500 символов',
        'cons.max' => 'Недостатки не должны превышать 500 символов',
        'comment.max' => 'Комментарий не должен превышать 1000 символов',
    ]);

    $review = new ProductReview($request->all());
    $review->user_id = auth()->id();
    $product->reviews()->save($review);
    return $review->load('user');
}

public function destroy($id)
{

    $review = auth()->user()->reviews()->findOrFail($id);
    $review->delete();
    return response()->json([
        'message' => 'Отзыв успешно удален'
    ]);
}

public function userReviews(Request $request)
{
    $user = $request->user();
    $reviews = ProductReview::with(['product' => function($query) {
            $query->select('id', 'name', 'imageUrl');
        }])
        ->where('user_id', $user->id)
        ->latest()
        ->get()
        ->map(function($review)
        {
            return [
                'id' => $review->id,
                'rating' => $review->rating,
                'pros' => $review->pros,
                'cons' => $review->cons,
                'comment' => $review->comment,
                'created_at' => $review->created_at,
                'product' => [
                    'id' => $review->product->id,
                    'name' => $review->product->name,
                    'imageUrl' => $review->product->imageUrl
                ]
            ];
        });

    return response()->json($reviews);
}
}