<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{

	protected $table = 'clients';
	use HasFactory;

	public function user() {
		return $this->belongsTo(Client::class);
	}
}
