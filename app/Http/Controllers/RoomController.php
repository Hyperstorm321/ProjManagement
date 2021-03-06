<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Room;
use App\Models\RoomExaminee;

class RoomController extends Controller
{
    public function create(Request $request){
        $room = new Room();

        $room->examiner_id = $request->input('examiner_id');
        $room->room_code =  $request->input('room_code');
        $room->room_title = $request->input('room_title');
        $room->room_desc = $request->input('room_description');

        $room->save();
    }

    public function update(Request $request, $room_id){
        $update_room = Room::find($room_id);
        $update_room->room_title = $request->input('room_title');
        $update_room->room_desc = $request->input('room_description');
        $update_room->save();

    }

    public function delete(Request $request, $room_id){
        RoomExaminee::destroy($room_id);
        Room::destroy($room_id);
    }

    public function get(Request $request, $instructor_id){
        $rooms = Room::where('examiner_id',$instructor_id)->get();
        return response()->json($rooms);
    }

    public function joinRoom(Request $request){
        $room_code = $request->input('room_code');

        $room_id = Room::where('room_code',$room_code)->value('room_id');
        
        $new_examinee = new RoomExaminee();

        $new_examinee->room_id = $room_id;
        $new_examinee->examinee_id = $request->input('examinee_id');

        $new_examinee->save();

        DB::table('rooms')->increment('total_examinees');
    }

    public function getExamineeRoom(Request $request, $examinee_id){
        $rooms = DB::table('room_examinees AS re')
                ->join('rooms as r', 're.room_id','r.room_id')
                ->where('re.examinee_id',$examinee_id)
                ->select('r.room_id','r.room_code','r.room_title','r.room_desc','r.total_examinees','r.total_exams')
                ->get();

        return $rooms;
        // marka ng katangahan ni andre hindi nag join nag loop hahaha
        // $examinee_rooms = RoomExaminee::where('examinee_id', $examinee_id)->get('room_id');
        
        // $rooms = []; // dito natin lalagay mga info

        // foreach($examinee_rooms as $examinee_room){
        //     array_push($rooms,  Room::where('room_id', $examinee_room->room_id)->get());
        // }
        //  Log::info(json_encode($rooms));
        //  return $rooms;
    }

    public function getRoomDetail(Request $request, $room_id){
        // $room_details = Room::where('room_id',$room_id)->get();
        $room_details = DB::table('rooms as r')
                        ->join('users as u', 'u.user_id','r.examiner_id')
                        ->where('r.room_id', $room_id)
                        ->get();

        return $room_details;
    }
}
