<?php
/**
 * Created by PhpStorm.
 * User: web
 * Date: 04.07.20
 * Time: 19:38
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Event
 * @package App
 */
class Event extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'date', 'city'];

    /**
     * @var bool
     */
    public $timestamps = false;


    /**
     * @param $query
     * @return mixed
     */
    public function scopeForIndex($query)
    {
        return $query->with(['participant']);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function participant()
    {
        return $this->hasMany(Participant::class, 'event_id', 'id');
    }

}