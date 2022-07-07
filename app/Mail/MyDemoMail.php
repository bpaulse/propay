<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MyDemoMail extends Mailable
{
	use Queueable, SerializesModels;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{

		return $this->view('emails.send_mail')
					->attach(public_path('pdf/invoice.pdf'), [
						 'as' => 'invoice.pdf',
						 'mime' => 'application/pdf',
					]);
	}
}
