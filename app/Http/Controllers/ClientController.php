<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceClient;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use File;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{

	public function index () {
		return view('client-list');
	}

	public function getClientLineInfo(Request $request) {
		$clients = Client::where('user_id', Auth::user()->id)->get();
		return response()->json($clients);
	}

	public function addClient (Request $request) {

		$fileName = time().'.'.$request->file->getClientOriginalExtension();
		$request->file->move(public_path('/clientLogos'), $fileName);

		$client = new Client();

		$client->name			= $request->name;
		$client->surname 		= $request->surname;
		$client->mobile 		= $request->mobile;
		$client->email 			= $request->email;
		$client->website 		= $request->website;
		$client->landline 		= $request->landline;
		$client->vat 			= $request->vat;
		$client->companyreg 	= $request->companyreg;
		$client->companyname 	= $request->companyname;
		$client->address 		= $request->address;
		$client->user_id 		= Auth::user()->id;
		$client->logo 			= $fileName;

		$save = $client->save();

		if ( $save ) {
			$return = ['code' => 1, 'msg' => 'Client added successfully!', 'data' => $client];
		} else {
			$return = ['code' => 0, 'msg' => 'Error add Client', 'data' => []];
		}

		return response()->json($return);


	}

	public function editClient (Request $request) {

		$client = Client::find($request->id);

		$client->name			= $request->name;
		$client->surname 		= $request->surname;
		$client->mobile 		= $request->mobile;
		$client->email 			= $request->email;
		$client->website 		= $request->website;
		$client->landline 		= $request->landline;
		$client->vat 			= $request->vat;
		$client->companyreg 	= $request->companyreg;
		$client->companyname 	= $request->companyname;
		$client->address 		= $request->address;

		$update = Client::whereId($request->id)->update($request->all());

		if ( $update ) {
			$return = ['code' => 1, 'msg' => 'Client updated successfully!', 'data' => $client];
		} else {
			$return = ['code' => 0, 'msg' => 'Error updating Client'];
		}
		return response()->json($return);

	}

	public function getClientInvoiceInfo($invoiceId) {

		return InvoiceClient::where('invoice_id', $invoiceId)->firstOrFail();

	}

	public function getClientInfo($invoiceId) {

		$check = $this->getClientInvoiceInfo($invoiceId);

		if ( $check ){
			return $clients = Client::where('id', $check->client_id)->firstOrFail();
		} else {
			return false;
		}

	}

	public function getClient(Request $request) {
		$clients = Client::where('id', $request->clientid)->get();
		return response()->json($clients[0]);
	}

	public function deleteClientLine (Request $request) {

		$delete = Client::find($request->clientid)->delete();

		if ( $delete ) {
			$return = ['code' => 1, 'msg' => 'Client deleted successfully!'];
		} else {
			$return = ['code' => 0, 'msg' => 'Error deleting Client'];
		}
		return response()->json($return);
	}

	public function saveClientToInvoice(Request $request) {
		
		$check = InvoiceClient::where('invoice_id', $request->invoiceid)->get();
		$clientDetails = Client::find($request->clientid);

		$type = '';
		$save = null;

		if ( empty($check[0]) ) {

			$invClint = new InvoiceClient();
			$invClint->client_id = $request->clientid;
			$invClint->invoice_id = $request->invoiceid;
			$save = $invClint->save();
			$type = 'create';

		} else {

			$invClint = InvoiceClient::find($check[0]->id);
			$invClint->client_id = $request->clientid;
			$save = $invClint->save();
			$type = 'update';
		}

		if ( $save ) {

			$invoiceClientData = [
				'type' => $type,
				'id' => $invClint->id,
				'clientid' => $invClint->client_id,
				'invoiceid' => $invClint->invoice_id
			];

			return response()->json([
				'code' => 1,
				'msg' => 'New InvoiceClient has been successfully created!',
				'data' => ['invoiceClientData' => $invoiceClientData, 'clientDetails' => $clientDetails]
			]);

		} else {

			return response()->json([
				'code' => 0,
				'msg' => 'Something went wrong.',
				'data' => null
			]);

		}

	}
}
