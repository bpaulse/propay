<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
	use HasFactory;

	public function language() {
		return $this->hasOne(Language::class);
	}

	public function interest() {
		return $this->hasMany(Interest::class);
	}

	public function person () {
		return $this->belongsTo(Person::class);
	}

}
