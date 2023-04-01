<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use PDF;
use App\Http\Controllers\InvoiceLineController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PersonController;
use Illuminate\Support\Facades\Auth;

class PDFController extends Controller {

	// protected $details;
	// public $timeout = 7200;
	public $ds = DIRECTORY_SEPARATOR;
	private $data = '';
	private $invoiceid;

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */


	public function __construct ($invid) {
		$this->invoiceid = $invid;
	}

	public function getInvoiceId () {
		return $this->invoiceid;
	}

	public function setInvoiceId($invid) {
		$this->invoiceid = $invid;
	}

	public function generatePDF() {
		// var_dump($this->getInvoiceId());
		return $this->createPDF($this->createFileName());
	}

	public function createFileName() {
		$currentDate = date('dmYHms');
		$filenameStr = 'invoice' . '_' . $this->invoiceid . '_' . $currentDate;
		// var_dump($filenameStr);
		return $filenameStr;
	}

	public function createPDF ($fileName) {

		$public = 'public';

		$invoicelinesObj = new InvoiceLineController();
		$invLines = $invoicelinesObj->getInvoiceLines($this->invoiceid);

		// var_dump($invLines);

		$invoiceTotal = floatval(0.00);

		foreach ( $invLines as $line ) {
			$invoiceTotal = $invoiceTotal + floatval($line['linetotal']);
		}

		$client = new ClientController();
		$clientDetails = $client->getClientInfo($this->invoiceid);

		$user = new PersonController();
		$userDetails = $user->getPersonInfo(Auth::user()->id);

		$data = [
			'title' => 'Welcome to datanav.com',
			'date' => date('m/d/Y'),
			'invoicelines' => $invLines,
			'invoiceTotal' => number_format($invoiceTotal, 2),
			'currency' => 'R',
			'invoiceNumber' => 'INV_2393'
		];

		$pdf = PDF::loadView('pdf.pdf', $data);

		$fullpathName = 'pdf' . $this->ds . $fileName . '.pdf';
		$pdf->save($fullpathName);

		return $fullpathName;

	}

}

?>