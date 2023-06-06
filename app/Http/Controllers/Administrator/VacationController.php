<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Vacation;

class VacationController extends Controller
{
    //


    public function index(){
        return view('administrator.vacation');
    }


    public function getVacations(Request $req){
        $sort = explode('.', $req->sort_by);

        $data = Vacation::orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }


    public function create(){
        return view('administrator.vacation-create-edit')
            ->with('id', 0)
            ->with('data', '');

    }


    public function store(Request $req){
        

        $req->validate([
            'vacations' => ['required', 'array'],
            'vacations.*' => ['required']
        ], [
            'vacations.required' => 'Please input at least one vacation.',
            'vacations.*.required' => 'Please input a value for each vacation.'
        ]);


        //return $req;

        foreach($req->vacations as $v){
            $vDate = date('Y-m-d', strtotime($v['vacation_date']));
            Vacation::create([
                'vacation_date' => $vDate
            ]);
        }

        return response()->json([
            'status' => 'saved'
        ], 200);
    }


    public function edit($id){
        $data = Vacation::find($id);
        return view('administrator.vacation-create-edit')
            ->with('id', $id)
            ->with('data', $data);
    }

    public function update(Request $req, $id){
        //return $req;

        $req->validate([
            'vacation_date' => ['required', 'unique:vacations,' .$id. 'vacation_id'],
        ]);

        $vDate = date('Y-m-d', strtotime($req->vacation_date));

        $data = Vacation::find($id);
        $data->vacation_date = $vDate;
        $data->saved();

        return response()->json([
            'status' => 'updated'
        ], 200);
    }


    public function destroy($id){
        Vacation::destroy($id);

        return response()->json([
            'status' => 'deleted'
        ], 200);
    }
}
