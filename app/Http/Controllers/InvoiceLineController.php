<?php

namespace App\Http\Controllers;

use App\Models\InvoiceLine;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Traits\CalcTrait;

class InvoiceLineController extends Controller
{
	use CalcTrait;
	private $currency = 'R';

	public function deleteInvoiceLineData (Request $request) {

		$deletedRows = InvoiceLine::where('id', $request->ivlId)->delete();

		if ( $deletedRows ) {
			$msg = 'You have successfully deleted Invoice Line ID = ';
			$code = 1;
		} else {
			$msg = 'something went wrong deleting the row with ID = ';
			$code = 0;
		}

		return response()->json([
			'code' => $code,
			'msg' => $msg
		]);

	}

	public function UpdateInvoiceLine(Request $request) {

		// 0 - save failure
		// 1 - save success
		// 2 - update failure
		// 3 - update success

		// var_dump('invoice_line_id: ');
		// var_dump($request->invoice_line_id);
		

		if ( $request->invoice_line_id == 0 ) {

			// var_dump('new');

			$invoiceline = new InvoiceLine();
			$invoiceline->quantity = $request->quantity;
			$invoiceline->invoice_id = $request->invoice_id;
			$invoiceline->product_id = $request->product_id;

			$invoiceline->linetotal = $request->quantity * $this->getUnitprice($request->product_id);

			$save = $invoiceline->save();


			if ( $save ) {

				$newTotal = $this->calculateNewTotal($invoiceline->invoice_id);
				// var_dump($newTotal);

				return response()->json([
					'code' => 1,
					'msg' => 'Invoice Line saved successfully!',
					'data' => ['invoiceLine' => $invoiceline, 'products' => Product::all(), 'newTotal' => $newTotal ]
				]);

			} else {
				return response()->json([
					'code' => 0,
					'msg' => 'Something went wrong.',
					'data' => []
				]);
			}

		} else {

			// var_dump('update');

			$update = InvoiceLine::find($request->invoice_line_id)->update(
				[
					'quantity' => $request->quantity,
					'product_id' => $request->product_id,
					'linetotal' => $request->quantity * $this->getUnitprice($request->product_id),
				]
			);

			$invoiceline = InvoiceLine::find($request->invoice_line_id);

			if ( $update ) {

				$newTotal = $this->calculateNewTotal($request->invoice_id);

				return response()->json([
					'code' => 3,
					'msg' => 'Invoice Line updated successfully!',
					'data' => ['invoiceLine' => $invoiceline, 'products' => Product::all(), 'newTotal' => $newTotal ]
				]);

			} else {
				return response()->json([
					'code' => 2,
					'msg' => 'Something went wrong.',
					'data' => []
				]);
			}


		}

		// var_dump($save);

		// if ( $save ){ 

		// 	$newTotal = $this->calculateNewTotal($invoiceLine->Invoice->id);

		// 	return response()->json([
		// 		'code' => 1,
		// 		'msg' => 'Invoice Line updated successfully!',
		// 		'data' => ['invoiceLine' => $invoiceLine, 'products' => Product::all(), 'newTotal' => $newTotal ]
		// 	]);
		// } else {
		// 	return response()->json([
		// 		'code' => 0,
		// 		'msg' => 'Something went wrong.',
		// 		'data' => null
		// 	]);
		// }

	}

	private function getUnitprice ( $productId ) {
		$product = Product::find( $productId );
		return $product->unitprice;
	}

	private function calculateNewTotal ($invoice_id) {
		$invoicelines = InvoiceLine::where('invoice_id', $invoice_id)->get();
		$total = 0;
		foreach ($invoicelines as $invoiceline) {
			$product  = Product::find($invoiceline->product_id);
			$total = $total + ( $invoiceline->quantity * $product->unitprice );
		}
		return $this->currency . ' ' . number_format((float) $total, 2, '.', ',');
	}

	public function getInvoiceLineById(Request $request) {
		$invoice_line_id = $request->inv_line_id;
		$invoicelineInfo = InvoiceLine::find($invoice_line_id);

		// print_r($invoicelineInfo->product_id);

		$products = Product::all();

		$productUnitPrice = 0;

		foreach ( $products as $product ) {
			if ( $product->id == $invoicelineInfo->product_id ) {
				$productUnitPrice = $product->unitprice;
			}
			// var_dump($product->unitprice);
		}

		return response()->json([ 'invoicelineInfo' => $invoicelineInfo, 'unitprice' => $productUnitPrice, 'products' => $products ]);
	}

	public function getProductInfo() {
		$products = Product::all();
		return response()->json([ 'products' => $products ]);
	}

	public function retrieveProduct (Request $request) {

		$productItem = Product::where('id', $request->id)->get();
		return response()->json($productItem);

	}

	public function getInvoiceLineDetails(Request $request) {

		$invoicelines = InvoiceLine::where('invoice_id', $request->inv_id)->get();
		$data = $this->buildInvoiceLines($invoicelines);
		return response()->json($data);

	}

	private function buildInvoiceLines($invoicelines) {

		$invoiceTotal = 0.00;
		$invoicelinesData = [];

		foreach ($invoicelines as $invoiceline) {

			$product = Product::where('id', $invoiceline->product_id)->get();
			$lineTotal = floatval($invoiceline->quantity) * floatval($product[0]->unitprice);
			$lineData = [
				'invoice_line_id' => $invoiceline->id,
				'quantity' => number_format((float) $invoiceline->quantity, 2, '.', ','),
				'product_id' => $invoiceline->product_id,
				'unitprice' => $this->currency . ' ' . number_format((float) $product[0]->unitprice, 2, '.', ','),
				'product_name' => $product[0]->product_name,
				'linetotal' => $this->currency . ' ' . number_format((float) $lineTotal, 2, '.', ','),
			];
			array_push($invoicelinesData, $lineData);
			$invoiceTotal = floatval($invoiceTotal) + floatval($lineTotal); 
		}

		return [
			'invoiceTotal' => $this->currency . ' ' . number_format((float) $invoiceTotal, 2, '.', ','),
			'invoicelinesData' => $invoicelinesData
		];

	}
}
