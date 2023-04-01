<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BuildEmailToSend extends Mailable
{
	use Queueable, SerializesModels;

	protected $details;

	public $ds = DIRECTORY_SEPARATOR;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct($details) {
		$this->details = $details;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build() {

		$pieces = explode($this->ds, $this->details['filename']);

		// $emailText = 'This is the passthrough body text of the email...';

		return $this->from($this->details['email'], $this->details['name'])
			->subject($this->details['subject'])
			->view('emails.send_mail')
			->with(['myownvar'=> $this->details['emailText']])
			->attach(
				public_path($this->details['filename']),
				['as' => $pieces[1],'mime' => 'application/pdf']
			);
	}
}
