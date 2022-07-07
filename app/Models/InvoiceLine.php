<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceLine extends Model
{
	use HasFactory;

	protected $fillable = ['quantity', 'linetotal'];

	public function invoice(){
		return $this->belongsTo(Invoice::class);
	}

	public function product () {
		return $this->hasOne(InvoiceLine::class);
	}
}