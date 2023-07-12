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

use App\Mail\SystemEmail;
use Illuminate\Support\Facades\Mail;


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
	
	public function setInvoiceId($id){
		$this->invoiceId = $id;
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

			$invoice = Invoice::where([['deleted', '=', 0],['user_id', '=', Auth::user()->id]])->get();

			foreach ( $invoice as $inv ) {
				// var_dump($inv->invoiceline->count());
				$element = [
					'id' => $inv->id,
					'count' => $inv->invoiceline->count()
				];

				array_push($invoicelines, $element);
			}

		}

		return response()->json(['details' => $invoice, 'invoicelines' => $invoicelines]);

	}

	public function filterInvoices(Request $request) {

		$status = $request->status;
		$fromDate = $request->fromDate;
		$toDate = $request->toDate;

		// var_dump($status);
		// var_dump($fromDate);
		// var_dump($toDate);

		$invoice = [];
		$invoicelines = [];

		if (Auth::check()) {

			if ( $toDate == 0 || $fromDate == 0 ) {

				$invoice = Invoice::where(
					[
						['deleted', '=', 0],
						['user_id', '=', Auth::user()->id],
						['invoiceStatus', '=', $status]
					]
				)->get();
	
				// SELECT * FROM `invoices` 
				// WHERE user_id = 1 and deleted = 0 
				// and invoiceStatus = 1 
				// and sent_at > '2023-05-25' 
				// and sent_at < '2023-06-05'
	
			} else {

				$queryDates = [$fromDate, $toDate];

				if ( $status == 0 ) {

					$invoice = Invoice::where(
						[
							['deleted', '=', 0],
							['user_id', '=', Auth::user()->id],
							['invoiceStatus', '=', $status]
						]
					)->whereBetween('created_at', $queryDates
					)->get();

				} elseif ( $status == 1 ) {

					$invoice = Invoice::where(
						[
							['deleted', '=', 0],
							['user_id', '=', Auth::user()->id],
							['invoiceStatus', '=', $status]
						]
					)->whereBetween('sent_at', $queryDates
					)->get();

				} elseif ( $status == 2 ) {

					$invoice = Invoice::where(
						[
							['deleted', '=', 0],
							['user_id', '=', Auth::user()->id],
							['invoiceStatus', '=', $status]
						]
					)->whereBetween('paid_at', $queryDates
					)->get();

				}

			}

			foreach ( $invoice as $inv ) {
				// var_dump($inv->invoiceline->count());
				$element = [
					'id' => $inv->id,
					'count' => $inv->invoiceline->count()
				];

				array_push($invoicelines, $element);
			}


		}

		return response()->json(['details' => $invoice, 'invoicelines' => $invoicelines]);

	}

	public function printInvoice () {
		return view('print.invoice');
	}

	public function buildAndSendInvoice (Request $request) {

		// var_dump($request->input('invoiceid'));

		$this->setInvoiceId($request->input('invoiceid'));
		$output = $this->sendSystemEmail();

		return response()->json(['code' => $output['code'], 'msg' => $output['msg']]);

	}

	public function updateInvoiceForSending () {

		$invoice = Invoice::find($this->getInvoiceId());

		$invoice->invoiceStatus = 1;
		$invoice->sent_at = date('Y-m-d H:i:s');

		$save = $invoice->save();

		if ( $save ) {
			return ['status' => 'success', 'msg' => 'Invoice status updated successfully!'];
		} else {
			return ['status' => 'error', 'msg' => 'Something went wrong!'];
		}

	}

	public function sendSystemEmail() {

		if (Auth::check()) {

			if ( $this->updateInvoiceForSending()['status'] == 'success' ) {


				// return response()->json(['code' => 1, 'msg' => 'Email sent successfully...']);
				// return ['code' => 1, 'msg' => 'Email sent successfully...'];
				// return response()->json(['msg' => 'Email sent successfully...']);
				// $data = ['code' => 1, 'msg' => 'Email sent successfully...'];

				// return response()->json($data)->header('Content-Type', 'application/json');

				// generate pdf
				$pdf = new PDFController($this->getInvoiceId());
				$pdfFileName = $pdf->generatePDF();
				$this->setPdfFileName($pdfFileName);

				$user = Auth::user();

				// $attachment = public_path('pdf/invoice_1_06062023130611.pdf');
				$attachment = public_path($pdfFileName);
				$subject = 'navBill: Invoice';
				$emailContentText = 'Dear {recipientName}, <br />\n\n Please find attached your invoice for the services rendered/Products purchases.<br />Thank you for your business.<br />Kind regards,<br />navBill Team';

				Mail::to($user->email)->send(new SystemEmail(
					[
						'attachmentPath' => $attachment, 
						'subject' => $subject,
						'emailContentText' => $emailContentText,
					]
				));

				if (Mail::failures()) {
					return ['code' => 0, 'msg' => 'Something went wrong sending the email...'];
				} else {
					return ['code' => 1, 'msg' => 'Email sent successfully...'];
				}

			} else {
				return ['code' => 0, 'msg' => 'Something went wrong updating Invoice Status...'];
			}

		}
		// else {
		// 	return redirect('/login');
		// }

	}

}
