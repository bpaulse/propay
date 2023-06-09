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

		$invoiceTotal = floatval(0.00);

		foreach ( $invLines as $line ) {
			$invoiceTotal = $invoiceTotal + floatval($line['linetotal']);
		}

		$client = new ClientController();
		$clientDetails = $client->getClientInfo($this->invoiceid);

		$user = new PersonController();
		$userDetails = $user->getPersonInfo(Auth::user()->id);

		$paddedInvoiceNumber = $this->padInvoiceNumber($this->invoiceid);

		$data = [
			'title' => 'Welcome to datanav.com',
			'date' => date('m/d/Y'),
			'invoicelines' => $invLines,
			'invoiceTotal' => number_format($invoiceTotal, 2),
			'currency' => 'R',
			'invoiceNumber' => $paddedInvoiceNumber,
			'client' => $clientDetails,
			'user' => $userDetails,
			'image' => 'clientLogos/' . $userDetails->logo
		];

		$pdf = PDF::loadView('pdf.pdf', $data);

		$fullpathName = 'pdf' . $this->ds . $fileName . '.pdf';
		$pdf->save($fullpathName);

		return $fullpathName;

	}

	public function padInvoiceNumber($id) {
		$prefix = 'INV_';
		$zeros = '';
		if ($id >= 0 && $id < 10) {
			$zeros = '0000';
		} else if ($id >= 10 && $id < 100) {
			$zeros = '000';
		} else if ($id >= 100 && $id < 1000) {
			$zeros = '00';
		} else if ($id >= 1000 && $id < 10000) {
			$zeros = '0';
		} else {
			$zeros = '';
		}
		return $prefix . $zeros . $id;
	}

}

?>