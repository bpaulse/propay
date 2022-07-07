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
	public function build()
	{
		return $this->from($this->details['email'], $this->details['name'])
					->subject($this->details['subject'])
					->view('emails.send_mail')
					->attach(public_path('pdf/invoice.pdf'), [
						'as' => 'invoice.pdf',
						'mime' => 'application/pdf',
				   ]);
	}
}
