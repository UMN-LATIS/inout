<?php

namespace App;
use Illuminate\Database\Eloquent\Relations\Pivot;
 
class BoardUserPivot extends Pivot {

	protected $dates = ['winner'];
}