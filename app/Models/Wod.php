<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wod extends Model
{
	use HasFactory;

	public function event(){
		return $this->belongsTo(Event::class);
	}

	public function score(){
		return $this->belongsTo(Score::class);
	}

}
