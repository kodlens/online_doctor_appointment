<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use App\Models\Appointment;

class PDFController extends Controller
{
    //

    public function appointmentPDF()
    {

        $todayDate = date('Y-m-d');
        $data = Appointment::with(['user', 'schedule', 'patients'])
            ->where('appointment_date', $todayDate)
            ->where('is_archived', 0)->get();


        $viewdata = [
            'appointment' => $data
        ];
   
        $pdf = PDF::loadView('administrator.appointment-pdf', $viewdata);
        return $pdf->download('appointmentPDF.pdf');
        //return view('administrator.appointment-pdf', $viewdata);
    }


    


}
