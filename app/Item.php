<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;

class Item extends Model {

    use SpatialTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'notable_damage', 'environment_found', 'position_found', 'position_radius', 'position_comment',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'finder_email', 'admin_email',
    ];

    protected $spatialFields = [
      'position_found',
    ];

    public function finder() {
        return $this->belongsTo('App\User', 'finder_id');
    }

    public function claims()
    {
        return $this->hasMany('App/ItemClaim');
    }

    public function has_claims() {
        return $this->claims()->count() > 0;
    }

}
