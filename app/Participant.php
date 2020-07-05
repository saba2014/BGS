<?php
/**
 * Created by PhpStorm.
 * User: web
 * Date: 04.07.20
 * Time: 19:42
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Participant
 * @package App
 */
class Participant extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'email', 'event_id'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function event()
    {
        return $this->belongsToMany(Event::class, 'events', 'event_id');
    }

}