<?php

namespace App\Http\Controllers;

use App\Models\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
	public function getProductServicesList() {
		// Auth::user();
		$user_id = 1;
		$products = Product::where('user_id', Auth::user()->id)->get();

		return response()->json(['products' => $products]);

	}

	public function deleteProduct(Request $request) {
		// Auth::user();
		// $user_id = 1;
		// $request->product_id;
		
		$delete = Product::find($request->product_id)->delete();

		return response()->json([
			'code' => $delete,
			'msg' => ( $delete ? 'Product (ID: ' . $request->product_id . ') deleted successfully...' : 'Something went wrong deleting the Product...' ),
			'product' => ( $delete ? $request->product_id : null ),
		]);

	}

	public function updateProductLine(Request $request) {

		if ( $request->product_id == 0 ) {

			$product = Product::create(
				[
					'product_name' => $request->product_name,
					'unitprice' => $request->unitprice,
					'user_id' => Auth::user()->id,
				]
			);

			if ( $product ) {

				return response()->json([
					'code' => 1,
					'msg' => 'Product added successfully!',
					'data' => ['product' => $product ]
				]);
			} else {

			}

		} else {

			$product = Product::find($request->product_id);

			$product->product_name = $request->product_name;
			$product->unitprice = $request->unitprice;
			$product->user_id = Auth::user()->id;

			$update = $product->save();

			if ( $update ) {
				return response()->json([
					'code' => 3,
					'msg' => 'Product update Successfull...',
					'data' => ['product' => $product ]
				]);
			} else {
				return response()->json([
					'code' => 0,
					'msg' => 'Product update Failure...',
					'data' => []
				]);

			}


		}

	}
}
