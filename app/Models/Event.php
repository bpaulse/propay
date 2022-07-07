<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
	use HasFactory;

	public function athleteevent() {
		return $this->hasMany(AthleteEvent::class);
	}

	public function wod() {
		return $this->hasMany(Wod::class);
	}

}
