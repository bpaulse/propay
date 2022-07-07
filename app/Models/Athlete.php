<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Athlete extends Model
{
	use HasFactory;

	public function athleteevent() {
		return $this->belongsTo(AthleteEvent::class);
	}

}
