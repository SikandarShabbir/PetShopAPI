<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $page_num = $request->page && $request->page > 0 ? $request->page : 1;
        $no_of_records_per_page = $request->size && $request->size > 0
            ? $request->size : 10;
        $offset = ($page_num - 1) * $no_of_records_per_page;

        $query = Product::query();
        $query->when(
            $request->search,
            function ($q) {
                return $q->where('name', 'like', '%'.request('search').'%')
                    ->orWhere('description', 'like', '%'.request('search').'%');
            }
        );
        $products = $query->skip($offset)
            ->take($no_of_records_per_page)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json(
            [
                'success' => true,
                'errors'  => null,
                'data'    => $products,
            ],
            200
        );
    }

    /**
     * StoreRequest a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $product = new Product();
        $product->name = $request->name ? $request->name : null;
        $product->price = $request->price ? $request->price : null;
        $product->cost = $request->cost ? $request->cost : null;
        $product->description = $request->description ? $request->description
            : null;
        $product->unitsAndInfo = $request->has('unitsAndInfo');
        $product->unit = $request->unit ? $request->unit : null;
        $product->weightPerUnit = $request->weightPerUnit
            ? $request->weightPerUnit : null;
        $product->imageUrls = $request->imageUrls ? $request->imageUrls
            : null;
        $product->save();

        if ($product) {
            return response()->json(
                [
                    'success' => true,
                    'errors'  => null,
                    'data'    => $product,
                ],
                200
            );
        }

        return response()->json(
            [
                'success' => false,
                'errors'  => ['Product is not created!'],
                'data'    => null,
            ],
            422
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
