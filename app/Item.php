<?php

namespace App;

use App\Search\SearchableTrait;
use Illuminate\Database\Eloquent\Model;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;

class Item extends Model
{
    use SpatialTrait;
    use SearchableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'notable_damage',
        'environment_found',
        'position_found',
        'position_radius',
        'position_comment',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'finder_email',
        'admin_email',
    ];

    protected $spatialFields = [
        'position_found',
    ];

    protected $appends = array('claimed');

    public function finder()
    {
        return $this->belongsTo('App\User', 'finder_id');
    }

    public function claims()
    {
        return $this->hasMany('App\ItemClaim');
    }

    public function has_claims()
    {
        return $this;
    }

    public function getClaimedAttribute()
    {
        return $this->claims()->count() > 0 ? $this->claims()->where('claimed', '=', '1')->count() != 0 : 0;
    }
}
