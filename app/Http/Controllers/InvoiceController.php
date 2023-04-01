<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceClient;
use App\Models\Client;
use App\Http\Controllers\PDFController;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ClientController;

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

		// print_r($request->input('invoiceid'));

		$updateArr = [ 'status' => 1 ];
		$pdf = new PDFController($this->getInvoiceId());
		$pdfFileName = $pdf->generatePDF();
		$this->setPdfFileName($pdfFileName);

		// print_r('test pdf');

		$this->SendTestEmail();
		return response()->json(['code' => 1, 'msg' => 'Email has successfully been sent to your client mailbox!' ]);

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
			$invoice->user_id = 1;

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

		$invoices = Invoice::where('deleted', 0)->get();
		$invoicelines = [];

		foreach ( $invoices as $inv ) {
			// var_dump($inv->invoiceline->count());
			$element = [
				'id' => $inv->id,
				'count' => $inv->invoiceline->count()
			];
			array_push($invoicelines, $element);
		}
	
		return response()->json(['details' => $invoices, 'invoicelines' => $invoicelines]);

	}

	public function printInvoice () {
		return view('print.invoice');
	}

}
