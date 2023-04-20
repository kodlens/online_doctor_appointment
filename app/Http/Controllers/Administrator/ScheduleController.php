<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    //

    public function index(){
        return view('administrator.schedules');
    }

    public function getSchedules(Request $req){
        $sort = explode('.', $req->sort_by);

        $data = Schedule::orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }

    public function show($id){
        return Schedule::find($id);
    }

    public function store(Request $req){

        $timeFrom = date('H:i:s', strtotime($req->time_from));
        $timeEnd = date('H:i:s', strtotime($req->time_end));
        

        $req->validate([
            'time_from' => ['required'],
            'time_end' => ['required'],
            'max_no' => ['required']
        ]);

        Schedule::create([
            'time_from' => $timeFrom,
            'time_end' => $timeEnd,
            'max_no' => $req->max_no,
            'mon' => $req->mon,
            'tue' => $req->tue,
            'wed' => $req->wed,
            'thu' => $req->thu,
            'fri' => $req->fri,
            'sat' => $req->sat,
            'sun' => $req->sun,
        ]);

        return response()->json([
            'status' => 'saved'
        ], 200);
    }

    public function update(Request $req, $id){

        $timeFrom = date('H:i:s', strtotime($req->time_from));
        $timeEnd = date('H:i:s', strtotime($req->time_end));

        $req->validate([
            'time_from' => ['required'],
            'time_end' => ['required'],
            'max_no' => ['required']
        ]);

        Schedule::where('schedule_id', $id)
        ->update([
            'time_from' => $timeFrom,
            'time_end' => $timeEnd,
            'max_no' => $req->max_no,
            'mon' => $req->mon,
            'tue' => $req->tue,
            'wed' => $req->wed,
            'thu' => $req->thu,
            'fri' => $req->fri,
            'sat' => $req->sat,
            'sun' => $req->sun,
        ]);
        return response()->json([
            'status' => 'updated'
        ], 200);
    }


    public function destroy($id){
        Schedule::destroy($id);
        return response()->json([
            'status' => 'deleted'
        ], 200);
    }

    
}
