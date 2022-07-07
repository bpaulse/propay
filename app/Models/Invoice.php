<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
	use HasFactory;

	protected $fillable = ['invoice_name', 'invoice_desc'];

	public function invoiceline () {
		return $this->hasMany(InvoiceLine::class);
	}

}
