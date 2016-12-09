<?php
// Frontend
use App\Http\Controllers\AppController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\UriController;

// Backend
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\ProductsController;
use App\Http\Controllers\Backend\UsersController;
use App\Http\Controllers\Backend\CategorysController;
use App\Http\Controllers\Backend\VendorsController;
use App\Http\Controllers\Backend\BlogsController;
use App\Http\Controllers\Backend\BlogcategoriesController;
use App\Http\Controllers\Backend\BlogtagsController;
use App\Http\Controllers\Backend\ProducttagsController;
use App\Http\Controllers\Backend\AttributesController;
use App\Http\Controllers\Backend\AttributesvaluesController;
use App\Http\Controllers\Backend\FileManagerController;
use App\Http\Controllers\Backend\UploadController;

use App\Http\Controllers\Backend\OrderController as BackendOrderController;
use App\Http\Controllers\Backend\ShippingOptionController;
use App\Http\Controllers\Backend\SettingGeneralController;
use App\Http\Controllers\Backend\PageController;
use App\Http\Controllers\Backend\TaxOptionController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Backend
Route::group(['prefix' => 'backend', 'as' => 'backend.', 'middleware' => ['auth', 'admin']], function ()
{
	Route::group(['prefix' => 'setting'], function () {
		// tax
		Route::resource('/tax', TaxOptionController::class);
		// // shipping
		Route::resource('/shipping', ShippingOptionController::class);
		// // // general
		Route::resource('/general', SettingGeneralController::class);
	});
	// page
	Route::resource('/page', PageController::class);

	//uploads
	Route::group(['prefix' => 'filemanager', 'as' => 'filemanager.'], function ()
	{
		Route::get('/', [UploadController::class, 'index'])->name('list');
		Route::middleware('optimizeImages')->group(function () {
			// all images will be optimized automatically
			// Route::post('upload-images', 'UploadController@index');
			Route::post('/upload', [UploadController::class, 'upload'])->name('upload');
			Route::post('/ajaxupload', [UploadController::class, 'ajaxupload'])->name('ajaxupload');
			Route::post('/store', [UploadController::class, 'store'])->name('store');
		});
		// Route::post('/upload', [UploadController::class, 'upload'])->name('upload');
		Route::get('/get_filemanager', [UploadController::class, 'get_filemanager'])->name('get_filemanager');
		Route::get('/files', [UploadController::class, 'getUploadedFile'])->name('getUploadedFile');
		Route::put('/update/{product}', [UploadController::class, 'update'])->name('update');
		Route::get('/get', [UploadController::class, 'get'])->name('get');
		Route::get('/getUploadedAssetsId', [UploadController::class, 'getUploadedAssetsId'])->name('getUploadedAssetsId');
	});

	Route::group(['prefix' => 'file', 'as' => 'file.'], function() {
		Route::get('/', [FileManagerController::class, 'index'])->name('index');
		Route::get('/show', [FileManagerController::class, 'show'])->name('show');
		Route::post('/store', [FileManagerController::class, 'store'])->name('store');
		Route::post('/destroy/{id}', [FileManagerController::class, 'destroy'])->name('destroy');
	});

	//products routes
	Route::group(['prefix' => 'products', 'as' => 'products.'], function ()
	{
		Route::get('/', [ProductsController::class, 'index'])->name('list');
		Route::get('/trash', [ProductsController::class, 'trash'])->name('trash');
		Route::get('/trash/recover/{id}', [ProductsController::class, 'recover'])->name('recover');
		Route::get('/create', [ProductsController::class, 'create'])->name('create');
		Route::get('/edit/{id}', [ProductsController::class, 'edit'])->name('edit');
		Route::put('/update/{product}', [ProductsController::class, 'update'])->name('update');
		Route::post('/store', [ProductsController::class, 'store'])->name('store');
		Route::get('/delete/{id}', [ProductsController::class, 'destroy'])->name('delete');
		Route::get('/get', [ProductsController::class, 'get'])->name('get');
		Route::put('/update_digital_assets/{id}', [ProductsController::class, 'update_digital_assets'])->name('update_digital_assets');
	});

	//users routes
	Route::group(['prefix' => 'users', 'as' => 'users.'], function ()
	{
		Route::get('/', [UsersController::class, 'index'])->name('list');
		Route::get('/create', [UsersController::class, 'create'])->name('create');
		Route::get('/edit/{id}', [UsersController::class, 'edit'])->name('edit');
		Route::put('/update/{product}', [UsersController::class, 'update'])->name('update');
		Route::post('/store', [UsersController::class, 'store'])->name('store');
		Route::get('/get', [UsersController::class, 'get'])->name('get');
	});

	Route::group(['prefix' => 'customers', 'as' => 'customers.'], function ()
	{
		Route::get('/', [UsersController::class, 'customers'])->name('list');
	});

	//categories routes
	Route::group(['prefix' => 'products/categories', 'as' => 'products.categories.'], function ()
	{
		Route::get('/', [CategorysController::class, 'index'])->name('list');
		Route::get('/create', [CategorysController::class, 'create'])->name('create');
		Route::get('/edit/{id}', [CategorysController::class, 'edit'])->name('edit');
		Route::put('/update/{product}', [CategorysController::class, 'update'])->name('update');
		Route::get('/delete/{id}', [CategorysController::class, 'destroy'])->name('delete');
		Route::post('/store', [CategorysController::class, 'store'])->name('store');
		Route::get('/get', [CategorysController::class, 'get'])->name('get');
	});

	//attributes routes
	Route::group(['prefix' => 'products/attributes', 'as' => 'products.attributes.'], function ()
	{
		Route::get('/', [AttributesController::class, 'index'])->name('list');
		Route::get('/create', [AttributesController::class, 'create'])->name('create');
		Route::get('/edit/{id}', [AttributesController::class, 'edit'])->name('edit');
		Route::put('/update/{product}', [AttributesController::class, 'update'])->name('update');
		Route::post('/store', [AttributesController::class, 'store'])->name('store');
		Route::post('/get_for_variants', [AttributesController::class, 'ajaxcall'])->name('ajaxcall');
		Route::post('/get_combinations', [AttributesController::class, 'combinations'])->name('combinations');
		Route::get('/get', [AttributesController::class, 'get'])->name('get');
		Route::post('/get/values', [AttributesController::class, 'getvalues'])->name('getvalues');
		Route::get('/get_product_attribute', [AttributesController::class, 'getProductAttribute'])->name('getproductattribute');
	});

	Route::group(['prefix' => 'products/attributes/{id_attribute}/values', 'as' => 'products.attributes.values.'], function ()
	{
		Route::get('/', [AttributesvaluesController::class, 'index'])->name('list');
		Route::get('/create', [AttributesvaluesController::class, 'create'])->name('create');
		Route::get('/edit/{id}', [AttributesvaluesController::class, 'edit'])->name('edit');
		Route::put('/update/{id}', [AttributesvaluesController::class, 'update'])->name('update');
		Route::post('/store', [AttributesvaluesController::class, 'store'])->name('store');
		Route::get('/get', [AttributesvaluesController::class, 'get'])->name('get');
	});

	Route::group(['prefix' => 'sellers', 'as' => 'sellers.'], function ()
	{
		Route::get('/', [VendorsController::class, 'index'])->name('list');
		Route::get('/create', [VendorsController::class, 'create'])->name('create');
		Route::get('/edit/{id}', [VendorsController::class, 'edit'])->name('edit');
		Route::put('/update/{product}', [VendorsController::class, 'update'])->name('update');
		Route::post('/store', [VendorsController::class, 'store'])->name('store');
		Route::get('/get', [VendorsController::class, 'get'])->name('get');
	});

	//posts routes
	Route::group(['prefix' => 'blog/posts', 'as' => 'posts.'], function ()
	{
		Route::get('/', [BlogsController::class, 'index'])->name('list');
		Route::get('/trash', [BlogsController::class, 'trash'])->name('trash');
		Route::get('/trash/recover/{id}', [BlogsController::class, 'recover'])->name('recover');
		Route::get('/create', [BlogsController::class, 'create'])->name('create');
		Route::get('/edit/{id}', [BlogsController::class, 'edit'])->name('edit');
		Route::put('/update/{product}', [BlogsController::class, 'update'])->name('update');
		Route::post('/store', [BlogsController::class, 'store'])->name('store');
		Route::get('/delete/{id}', [BlogsController::class, 'destroy'])->name('delete');
		Route::get('/get', [BlogsController::class, 'get'])->name('get');
	});

	//posts routes
	Route::group(['prefix' => 'blog/categories', 'as' => 'blog.categories.'], function ()
	{
		Route::get('/', [BlogcategoriesController::class, 'index'])->name('list');
		Route::get('/create', [BlogcategoriesController::class, 'create'])->name('create');
		Route::get('/edit/{id}', [BlogcategoriesController::class, 'edit'])->name('edit');
		Route::get('/delete/{id}', [BlogcategoriesController::class, 'destroy'])->name('delete');
		Route::put('/update/{product}', [BlogcategoriesController::class, 'update'])->name('update');
		Route::post('/store', [BlogcategoriesController::class, 'store'])->name('store');
		Route::get('/get', [BlogcategoriesController::class, 'get'])->name('get');
	});

	Route::group(['prefix' => 'blog/tags', 'as' => 'blog.tags.'], function ()
	{
		Route::get('/', [BlogtagsController::class, 'index'])->name('list');
		Route::get('/create', [BlogtagsController::class, 'create'])->name('create');
		Route::get('/edit/{id}', [BlogtagsController::class, 'edit'])->name('edit');
		Route::get('/delete/{id}', [BlogtagsController::class, 'destroy'])->name('delete');
		Route::put('/update/{product}', [BlogtagsController::class, 'update'])->name('update');
		Route::post('/store', [BlogtagsController::class, 'store'])->name('store');
		Route::get('/get', [BlogtagsController::class, 'get'])->name('get');
	});

	Route::group(['prefix' => 'products/tags', 'as' => 'products.tags.'], function ()
	{
		Route::get('/', [ProducttagsController::class, 'index'])->name('list');
		Route::get('/create', [ProducttagsController::class, 'create'])->name('create');
		Route::get('/edit/{id}', [ProducttagsController::class, 'edit'])->name('edit');
		Route::get('/delete/{id}', [ProducttagsController::class, 'destroy'])->name('delete');
		Route::put('/update/{product}', [ProducttagsController::class, 'update'])->name('update');
		Route::post('/store', [ProducttagsController::class, 'store'])->name('store');
		Route::get('/get', [ProducttagsController::class, 'get'])->name('get');
	});

	Route::group(['prefix' => 'orders', 'as' => 'orders.'], function ()
	{
		Route::get('/', [BackendOrderController::class, 'index'])->name('list');
		Route::post('/', [BackendOrderController::class, 'pending_badge'])->name('pending_badge_get');
		Route::put('/status_tracking/{id}', [BackendOrderController::class, 'status_tracking_set'])->name('status_tracking');
		Route::get('/show/{id}', [BackendOrderController::class, 'show'])->name('show');
		Route::put('/item/{id}', [BackendOrderController::class, 'update'])->name('update');
		Route::get('/pending', [BackendOrderController::class, 'pending'])->name('pending');
	});

	Route::get('/', [DashboardController::class, 'index'])->name('login');

});
// End Backend

// Homepage
Route::get('/', [AppController::class, 'index'])->name('index');

Route::group(['middleware' => ['auth']], function () {
	Route::get('/dashboard', [AppController::class, 'dashboard']);
});
Route::get('/image/{filename}', [AppController::class, 'image']);

// Blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.post.url');
Route::get('/blog/archives/category/', [BlogController::class, 'categoryAll'])->name('categoryAll');
Route::get('/blog/category/{category}', [BlogController::class, 'categoryPost'])->name('categoryPost');
Route::get('/blog/archives/tag/', [BlogController::class, 'tagAll'])->name('tagAll');
Route::get('/blog/tag/{tag}', [BlogController::class, 'tagPost'])->name('tagPost');

// Search
Route::get('/search', [ProductController::class, 'search'])->name('search');
Route::get('/searchCategory', [ProductController::class, 'searchCategory'])->name('searchCategory');

// Products
Route::middleware(['auth', 'admin'])->resource('products', ProductController::class)->except(['index', 'show']);

Route::get('products/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('products', [ProductController::class, 'index'])->name('products.index');

// Products Shop Page
Route::get('/3d-models', [ProductController::class, 'products_index'])->name('shop_index');

// Cart
Route::group(['controller' => CartController::class, 'prefix' => 'cart', 'as' => 'cart.'], function ()
{
	Route::group(['middleware' => 'auth'], function ()
	{
		Route::middleware('verified')->post('/buy-now', 'buyNow')->name('buy.now');

	});
	Route::get('/count', 'getCount')->name('count');
	Route::post('/edit', 'editQty')->name('edit.qty');
	Route::get('/remove/{id}', 'removeProduct')->name('remove.product');
});


Route::group(['controller' => CartController::class], function ()
{
    Route::group(['middleware' => 'auth'], function ()
    {

        Route::group(['prefix' => 'wishlist', 'as' => 'wishlist'], function ()
        {
            Route::get('/', 'wishlist');
            Route::post('/', 'wishlistStore');
            Route::put('/', 'wishlistToCart');
            Route::delete('/', 'removeFromWishlist');
        });

    });
});



Route::resource('cart', CartController::class)->only(['index', 'store', 'destroy']);

// Auth
Route::group(['middleware' => 'auth'], function ()
{

	Route::get('/product/download', [ProductController::class, 'download'])->name('download');

	Route::group(['prefix' => 'email/verify', 'as' => 'verification.', 'controller' => VerifyEmailController::class ], function ()
	{
		Route::get('/', 'emailVerificationNotice')->name('notice');
		Route::get('/{id}/{hash}', 'verificationHandler')->middleware('signed')->name('verify');
	});
	Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'resend'])->middleware('throttle:6,1')->name('verification.send');

	Route::group(['prefix' => 'payment', 'as' => 'checkout.', 'controller' => CheckoutController::class ], function ()
	{
		Route::get('/finished', 'paymentFinished')->name('finished');
		Route::delete('/cancel', 'cancel')->name('cancel');
	});

	Route::group(['middleware' => ['checkout', 'verified']], function ()
	{
		Route::resource('checkout', CheckoutController::class)->only(['index', 'store']);
		Route::post('/payment/intent', [CheckoutController::class, 'createPaymentIntent'])->name('checkout.payment.intent');
		Route::get('checkout/shipping', [CheckoutController::class, 'getShipping'])->name('checkout.shipping.get');
		Route::post('checkout/shipping', [CheckoutController::class, 'postShipping'])->name('checkout.shipping.post');
		Route::get('checkout/billing', [CheckoutController::class, 'getBilling'])->name('checkout.billing.get');
		Route::post('checkout/billing', [CheckoutController::class, 'postBilling'])->name('checkout.billing.post');
		Route::get('checkout/payment', [CheckoutController::class, 'getPayment'])->name('checkout.payment.get');
		Route::post('checkout/payment', [CheckoutController::class, 'postPayment'])->name('checkout.payment.post');
	});

	Route::resource('orders', OrderController::class)->only(['index', 'show', 'update']);

	Route::group(['prefix' => 'user', 'as' => 'user.', 'controller' => UserController::class ], function ()
	{
		Route::get('/edit', 'edit')->name('edit');
		Route::get('/edit/password', 'editPassword')->name('edit.password');
		Route::patch('/edit/password', 'updatePassword')->name('update.password');
		Route::put('/edit', 'update')->name('update');
		Route::delete('/delete', 'delete')->name('delete');
		Route::get('/{id_user}', 'index')->name('index');
	});

});

require __DIR__ . '/auth.php';

Route::get('{slug?}', UriController::class)->name('page')->where('slug','.+');