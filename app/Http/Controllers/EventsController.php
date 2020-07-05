<?php
/**
 * Created by PhpStorm.
 * User: web
 * Date: 04.07.20
 * Time: 20:37
 */

namespace App\Http\Controllers;

use App\Event;
use App\Jobs\SendMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;


/**
 * Class EventsController
 * @package App\Http\Controllers
 */
class EventsController extends Controller
{

    /**
     * Gets all Events with participants if is null event_id
     * else return this event and its participants
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $events = Event::forIndex();
        $event_name = $request->input('event_name');
        $event = $event_name ? $events->where('name', 'like', '%' . $event_name . '%')->get() : $events->get();

        return response()->json($event);
    }


    /**
     * Deleted participant with participant.id ('pt_id') from Event
     * with id (event_id)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $events = Event::forIndex();
        $event_id = $request->input('event_id');
        $pt_id = $request->input('pt_id');
        $participant = $events->find($event_id)->participant->find($pt_id)->delete();

        return response()->json($participant);
    }


    /**
     * Edit participant with participant.id ('pt_id') in Event
     * with id (event_id)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function edit(Request $request)
    {
        $event_id = $request->input('event_id');
        $pt_id = $request->input('pt_id');
        $f_name = $request->input('f_name');
        $l_name = $request->input('l_name');
        $email = $request->input('email');

        $edited = [
            'first_name' => $f_name,
            'last_name' => $l_name,
            'email' => $email,
        ];

        $events = Event::forIndex();
        $participant = $events->find($event_id)->participant->find($pt_id)->update($edited);

        return response()->json($participant);

    }

    /**
     * Ads new participant  into Even with id (event_id)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {

        $event_id = $request->input('event_id');
        $f_name = $request->input('f_name');
        $l_name = $request->input('l_name');
        $email = $request->input('email');
        $events = Event::forIndex();
        $event = $events->find($event_id)->participant();

        $new_participant = [
            'email' => $email,
            'event_id' => $event_id,
            'first_name' => $f_name,
            'last_name' => $l_name,
        ];
        $new_participant = $event->create($new_participant);

        if($new_participant){
            SendMail::dispatch()
                ->delay(Carbon::now()->addSeconds(10));
        }
        return response()->json($new_participant);
    }

}