<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemClaim extends Model {

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function claimed() {

    }

    public function returned() {

    }

}
