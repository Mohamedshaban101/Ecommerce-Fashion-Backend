<?php

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Cart\CheckoutController;
use App\Http\Controllers\User\UserInfoController;
use App\Http\Controllers\Cart\AddToCartController;
use App\Http\Controllers\User\GetBrandsController;
use App\Http\Controllers\Admin\TempImageController;
use App\Http\Controllers\Admin\Sizes\SizeController;
use App\Http\Controllers\User\GetProductsController;
use App\Http\Controllers\Admin\Users\UsersController;
use App\Http\Controllers\Cart\OrderDetailsController;
use App\Http\Controllers\User\ShowUserInfoController;
use App\Http\Controllers\Admin\Brands\BrandController;
use App\Http\Controllers\User\GetCategoriesController;
use App\Http\Controllers\Admin\Orders\OrdersController;
use App\Http\Controllers\User\ChangePasswordController;
use App\Http\Controllers\User\LatestProductsController;
use App\Http\Controllers\Auth\GoogleSocialiteController;
use App\Http\Controllers\User\FeaturedProductsController;
use App\Http\Controllers\Admin\Brands\ShowBrandController;
use App\Http\Controllers\Admin\Orders\ShowOrderController;
use App\Http\Controllers\Admin\Products\ProductController;
use App\Http\Controllers\Cart\IncrementQuantityController;
use App\Http\Controllers\User\ShowLatestProductController;
use App\Http\Controllers\Admin\Brands\StoreBrandController;
use App\Http\Controllers\Admin\Products\UpdateDefaultImage;
use App\Http\Controllers\Cart\RemoveItemFromCartController;
use App\Http\Controllers\Admin\Brands\DeleteBrandController;
use App\Http\Controllers\Admin\Brands\UpdateBrandController;
use App\Http\Controllers\Admin\Orders\UpdateOrderController;
use App\Http\Controllers\Admin\Categories\CategoryController;
use App\Http\Controllers\User\OrdersController as OrdersUser;
use App\Http\Controllers\Admin\Products\ShowProductController;
use App\Http\Controllers\Admin\Products\StoreProductController;
use App\Http\Controllers\Admin\Shipping\ShowShippingController;
use App\Http\Controllers\Admin\Products\DeleteProductController;
use App\Http\Controllers\Admin\Products\UpdateProductController;
use App\Http\Controllers\Admin\Shipping\StoreShippingController;
use App\Http\Controllers\Admin\Categories\ShowCategoryController;
use App\Http\Controllers\Admin\Shipping\DeleteShippingController;
use App\Http\Controllers\Admin\Categories\StoreCategoryController;
use App\Http\Controllers\Admin\Categories\DeleteCategoryController;
use App\Http\Controllers\Admin\Categories\UpdateCategoryController;
use App\Http\Controllers\Cart\PaymentIntentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('register' , RegisterController::class);
Route::post('login' , LoginController::class);
Route::post('logout' , LogoutController::class);
Route::get('auth/google' , [GoogleSocialiteController::class , 'redirectToGoogle']);
Route::get('auth/google/callback' , [GoogleSocialiteController::class , 'handleGoogleCallback']);
Route::get('email/verify/{id}/{hash}' , function(Request $request , $id , $hash){
    $user = User::findOrFail($id);

    // if(!$request->hasValidSignature()){
    //     return response()->json([
    //         'status' => 403,
    //         'message'=> 'invalid or expired email verification link'
    //     ],403);
    // }

    // if($user->hasVerifiedEmail()){
    //     return response()->json([
    //         'status' => 403,
    //         'message'=> 'email already verify',
    //     ]); 
    // }

    // if(! hash_equals((string) $hash , $user->getEmailForVerification())){
    //     return response()->json([
    //         'status' => 403,
    //         'message'=> 'invalid hash'
    //     ]);
    // }

    $user->markEmailAsVerified();
    event(new Verified($user));
    return redirect(env('FRONTEND_URL') . '/login');
})->middleware('signed')->name('verification.verify');

Route::post('email/resend' , function(Request $request){
    $request->validate([
        'email' => 'required|email',
    ]);

    $user = User::where('email' , $request->email)->first();

    if($user->hasVerifiedEmail()){
        return response()->json('email already verify');
    }

    $user->sendEmailVerificationNotification();

    return response()->json('verification email resend');
});
Route::middleware(['jwtAdmin'])->group(function () {
    Route::get('me', function () {
        return response()->json([
            'status'    => 200,
            'user' => auth()->user(),
        ]);
    });
    Route::post('add-to-cart' , AddToCartController::class);
    Route::get('cart' , CartController::class);
    Route::delete('remove-item-from-cart/{id}' , RemoveItemFromCartController::class);
    Route::post('increment-quantity' , IncrementQuantityController::class);


    Route::post('account' , UserInfoController::class);
    Route::get('account' , ShowUserInfoController::class);

    Route::post('account/change-password' , ChangePasswordController::class);
    Route::post('checkout' , CheckoutController::class);
    Route::post('create-payment-intent' , PaymentIntentController::class);
    Route::get('order/confirmation/{id}' , OrderDetailsController::class);

    Route::get('account/orders' , OrdersUser::class);

});
Route::get('get-latest-products' , LatestProductsController::class);
Route::get('get-featured-products' , FeaturedProductsController::class);
Route::get('get-latest-product/{id}' , ShowLatestProductController::class);
Route::get('get-brands' , GetBrandsController::class);
Route::get('get-categories' , GetCategoriesController::class);
Route::get('get-products' , GetProductsController::class);
Route::middleware(['checkAdmin' , 'jwtAdmin'])->group(function(){
    // Routes For Categories
    Route::get('admin/categories' , CategoryController::class);
    Route::post('admin/categories/create' , StoreCategoryController::class);
    Route::get('admin/categories/show/{id}' , ShowCategoryController::class);
    Route::post('admin/categories/edit/{id}' , UpdateCategoryController::class);
    Route::delete('admin/categories/{id}' , DeleteCategoryController::class);

    //Routes For Brand

    Route::get('admin/brands' , BrandController::class);
    Route::post('admin/brands/create' , StoreBrandController::class);
    Route::get('admin/brands/show/{id}' , ShowBrandController::class);
    Route::post('admin/brands/edit/{id}' , UpdateBrandController::class);
    Route::delete('admin/brands/{id}' , DeleteBrandController::class);

    //routes For Product

    Route::get('admin/products' , ProductController::class);
    Route::post('admin/products/create' , StoreProductController::class);
    Route::get('admin/products/show/{id}' , ShowProductController::class);
    Route::post('admin/products/edit/{id}' , UpdateProductController::class);
    Route::delete('admin/products/delete/{id}' , DeleteProductController::class);
    Route::post('admin/temp-images' , TempImageController::class);
    Route::post('admin/change-product-default-image' , UpdateDefaultImage::class);

    // Routes For Sizes;

    Route::get('admin/sizes' , SizeController::class);

    //Routes For Users;

    Route::get('admin/users' , UsersController::class);

    // Routes For Shipping

    Route::get('admin/shipping' , ShowShippingController::class);
    Route::post('admin/shipping/create' , StoreShippingController::class);
    Route::delete('admin/shipping/delete' , DeleteShippingController::class);

    //Routes For Orders
    Route::get('admin/orders' , OrdersController::class);
    Route::post('admin/order/{id}' , UpdateOrderController::class);
    Route::get('admin/order/{id}' , ShowOrderController::class);
});