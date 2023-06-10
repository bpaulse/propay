<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SystemEmail extends Mailable
{
	use Queueable, SerializesModels;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */

	// protected $attachmentPath;
	protected $details;

	public function __construct($details)
	{
		$this->details = $details;
		// $this->subject = $subject;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		return $this->view('emails.welcome')
		->attach($this->details['attachmentPath'])
		->with(['emailContentText'=> $this->details['emailContentText']])
		->subject($this->details['subject']);
	}
}
