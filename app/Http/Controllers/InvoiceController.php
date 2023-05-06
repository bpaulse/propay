<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceClient;
use App\Models\Client;
use App\Http\Controllers\PDFController;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ClientController;

use Illuminate\Support\Facades\Auth;


class InvoiceController extends Controller
{

	private $pdfFileName;
	private $invoiceId;
	private $details = [];
	private $message;

	public function setMessage ($clientName, $user) {

		$this->message = 'Good day [clientName],<br /><br />Please find attached the invoice for the service rendered/product(s) supplied.<br /><br />Should you have any questions or comments, please contact me immediately via email/messaging or telephonically.<br /><br />Many Regards.<br /><br />[user]';
		$this->message = str_replace('[clientName]', $clientName, $this->message);
		$this->message = str_replace('[user]', $user, $this->message);

	}

	public function getMessage () {
		return $this->message;
	}

	public function setPdfFileName($filename){
		$this->pdfFileName = $filename;
	}

	public function getPdfFileName(){
		return $this->pdfFileName;
	}
	
	public function setInvoiceId($filename){
		$this->invoiceId = $filename;
	}

	public function getInvoiceId(){
		return $this->invoiceId;
	}

	public function index () {
		return view('invoice-list');
	}

	public function SendTestEmail (){

		// getClientDetails
		$clientController = new ClientController();
		$clientDetails = $clientController->getClientInfo($this->getInvoiceId());

		if (Auth()->check()) {

			$authUserName = Auth()->user()->name;
			$authUserEmail = Auth()->user()->email;

			$this->setMessage($clientDetails->name, $authUserName);

			$this->details['email'] = $authUserEmail;
			$this->details['to'] = $clientDetails->email;

			$this->details['name'] = $authUserName;
			$this->details['subject'] = 'Invoice: #';
			$this->details['emailText'] = $this->getMessage();
			$this->details['filename'] = $this->getPdfFileName();

			// var_dump($this->details);

			$dispatch = dispatch(new \App\Jobs\SendEmailJob($this->details));

			if ( $dispatch ) {
				return response()->json(['message'=>'Mail Send Successfully!!']);
			} else {
				return response()->json(['message'=>'Error Sending Email!!']);
			}
		} else {
			return redirect('/login');
		}
	}

	public function buildAndSendInvoice (Request $request) {

		$this->setInvoiceId($request->input('invoiceid'));

		$updateArr = [ 'status' => 1 ];
		$pdf = new PDFController($this->getInvoiceId());
		$pdfFileName = $pdf->generatePDF();
		$this->setPdfFileName($pdfFileName);

		$this->SendTestEmail();
		
		// return response()->json(['code' => 1, 'msg' => 'Email has successfully been sent to your client mailbox!' ]);

	}

	public function deleteInvoice(Request $request) {

		$invoice_id = $request->inv_id;
		// var_dump($invoice_id);

		$deletedRow = Invoice::where('id', $invoice_id)->delete();

		if ( $deletedRow ) {
			$msg = 'You have successfully deleted Invoice ID = ' . $invoice_id;
			$code = 1;
		} else {
			$msg = 'something went wrong deleting the Invoice with ID = ' . $invoice_id;
			$code = 0;
		}

		return response()->json([
			'code' => $code,
			'msg' => $msg,
			'invoice_id' => $invoice_id
		]);

	}

	public function updateSingleInvoiceField (Request $request) {

		$updateArr = [
			'invoice_' . $request->type => $request->fieldValue
		];

		$update = Invoice::find($request->invoice_id)->update($updateArr);

		if ( $update ) {
			return response()->json(['code' => 1, 'msg' => 'Invoice ' . $request->type . ' updated successfully...']);
		} else {
			return response()->json(['code' => 0, 'msg' => 'Something went wrong with the updated...']);
		}

	}

	public function addInvoice(Request $request) {

		$validator = Validator::make($request->all(), [
			'invoice_name' => 'required|unique:invoices|max:255',
			'invoice_desc' => 'required',
		]);

		if ( !$validator->fails() ) {

			$name = $request->input('invoice_name');
			$desc = $request->input('invoice_desc');

			$invoice = new Invoice();

			$invoice->invoice_name = $name;
			$invoice->invoice_desc = $desc;
			$invoice->user_id = Auth::user()->id;

			$save = $invoice->save();

			if ( $save ) {

				$invoiceData = [
					'id' => $invoice->id,
					'name' => $name, 
					'desc' => $desc,
					'status' => 0
				];

				return response()->json([
					'code' => 1,
					'msg' => 'New Invoice has been successfully created!',
					'data' => $invoiceData
				]);

			} else {

				return response()->json([
					'code' => 0,
					'msg' => 'Something went wrong.',
					'data' => null
				]);

			}

		} else {
			return response()->json([
				'code' => 0,
				'error' => $validator->errors()->toArray(),
				'data' => null
			]);
		}

	}

	public function getInvoiceDetails(Request $request) {
		$invoice_id = $request->invoice_id;
		$invoiceDetails = Invoice::find($invoice_id);
		$invoiceClient = InvoiceClient::where('invoice_id', $invoice_id)->get();

		if ( empty($invoiceClient[0]) ) {
			return response()->json(['details' => $invoiceDetails, 'invoiceClient' => NULL]);
		}

		return response()->json(['details' => $invoiceDetails, 'invoiceClient' => $invoiceClient]);

	}

	public function getInvoiceLinesCount(Request $request) {

		// var_dump($request->inv_id);
		$count = Invoice::find($request->inv_id)->invoiceline->count();

		return response()->json(['details' => ['count' => $count, 'id' => $request->inv_id]]);
	}

	public function getInvoicesList() {

		$invoice = [];
		$invoicelines = [];

		if (Auth::check()) {
			// var_dump("User is logged in...");


			$invoice = Invoice::where([['deleted', '=', 0],['user_id', '=', Auth::user()->id]])->get();

			foreach ( $invoice as $inv ) {
				// var_dump($inv->invoiceline->count());
				$element = [
					'id' => $inv->id,
					'count' => $inv->invoiceline->count()
				];

				// foreach( $inv->invoiceline as $invLine ) {
					// dd($invLine);
				// }

				// dd($inv->invoiceline->id());
	
				// echo "<br /><br />";
				array_push($invoicelines, $element);
			}

		}

		return response()->json(['details' => $invoice, 'invoicelines' => $invoicelines]);

	}

	public function printInvoice () {
		return view('print.invoice');
	}

}
