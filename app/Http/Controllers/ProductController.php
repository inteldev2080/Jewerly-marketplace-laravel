<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchProductRequest;
use App\Models\Product;
use App\Models\Upload;
use App\Models\UserSearch;
use App\Models\ProductsVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function searchCategory(Request $req)
    {
        // if (Auth::check()) {
        //     $search = new UserSearch;
        //     $search->user_id = Auth::user()->id;
        //     $search->query = json_encode(['category' => $req->category, 'query' => $req->q]);
        //     $search->save();
        // }

        $products = Product::searchWithImages($req->q, $req->category);
        // return view('search', compact('products'));

        return view('components.products-display', compact('products'));
    }

    public function search(SearchProductRequest $req)
    {
        if (Auth::check()) {
            $search = new UserSearch;
            $search->user_id = Auth::user()->id;
            $search->query = json_encode(['category' => $req->category, 'query' => $req->q]);
            $search->save();
        }

        $products = Product::searchWithImages($req->q, $req->category);
        return view('search', compact('products'));
    }

    function index() {
        return redirect()->route('shop_index');
    }

    public function products_index()
    {
        $products = Product::orderBy('id', 'DESC')->get();
        $products->each(function($product){
            $product->setPriceToFloat();
        });
        return view('products.list', [
            'products' => $products
        ]);

    }

    public function show($slug)
    {
        $product = Product::with(['modelpreview'])->whereSlug($slug)->firstOrFail();

        abort_if(! $product, 404);

        $product->setPriceToFloat();
        $uploads = Upload::whereIn('id', explode(',',$product->product_images))->get(); 
        $variants = ProductsVariant::where('product_id', $product->id)->get();

        $maxPrice = ProductsVariant::where('product_id', $product->id)->max('variant_price') / 100;
        $minPrice = ProductsVariant::where('product_id', $product->id)->min('variant_price') / 100;

        return view('products.show', compact('product', 'uploads', 'variants', 'maxPrice', 'minPrice'));
    }

    public function download(Request $request)
    {
        if ($request->has('product_id')) {
            $product = Product::find($request->product_id);

            return response()->download(public_path('uploads/all/') . $product->digital->file_name, $product->getDigitalOriginalFileName());
        } else {
            $productVariant = ProductsVariant::find($request->variant_id);

            return response()->download(public_path('uploads/all/') . $productVariant->asset->file_name, $productVariant->getAssetOriginalFileName());
        }

    }
}
