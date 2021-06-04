<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $products = Product::with(['variants.variantPrice', 'price'])
                           ->paginate(2);
        $count    = Product::count();
        // dd($products[0]->price->stock);
        $variants = ProductVariant::get();
        // dd($variants);
        $min_price = ProductVariantPrice::min('price');
        $max_price = ProductVariantPrice::max('price');

        return view('products.index', compact('products', 'count', 'variants', 'min_price', 'max_price'));
    }

    public function searchProduct(Request $request)
    {
        if($request->title)
        {
            $products = Product::with(['variants.variantPrice', 'price'])
                               ->where('title', 'LIKE', '%'. $request->title .'%')
                               ->paginate(2);
            $count    = Product::count();

            $variants = ProductVariant::get();
            $min_price = ProductVariantPrice::min('price');
            $max_price = ProductVariantPrice::max('price');
            return view('products.index', compact('products', 'count', 'variants', 'min_price', 'max_price'));  
        }

        if($request->variant)
        {
            $variant = $request->variant;
            $products = Product::with(['variants.variantPrice', 'price'])
                                ->whereHas('variants', function ($query) use($variant){
                                    $query->where('variant_id', '=', $variant);
                                })
                               ->paginate(2);
            $count    = Product::count();

            $variants = ProductVariant::get();
            $min_price = ProductVariantPrice::min('price');
            $max_price = ProductVariantPrice::max('price');
            return view('products.index', compact('products', 'count', 'variants', 'min_price', 'max_price'));
        }
        if($request->price_from && $request->price_to)
        {
            $variant = $request->variant;
            
            $count    = Product::count();

            $variants = ProductVariant::get();
            $min_price = $request->price_from;
            $max_price = $request->price_to;
            $products  = Product::with(['price'])           
                                ->whereHas('price', function ($query) use($min_price, $max_price){
                                    $query->whereBetween('price', [$min_price, $max_price]);
                                })
                                ->paginate(2);
            // dd($products);
            return view('products.index', compact('products', 'count', 'variants', 'min_price', 'max_price'));
        }

        if($request->date)
        {   
            $variant = $request->variant;
            
            $count    = Product::count();

            $variants = ProductVariant::get();

            $products  = Product::whereDate('created_at','=', $request->date)
                                ->paginate(2);
            // dd($products);
            return view('products.index', compact('products', 'count', 'variants', 'variant'));
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $product = Product::find($id);
        $variants = Variant::all();
        return view('products.edit', compact('variants', 'product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
