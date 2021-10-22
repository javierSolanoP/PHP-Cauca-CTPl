<?php

namespace App\Http\Controllers\public_module;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Controllers\shift_module\ScheduleMontController;
use Illuminate\Support\Facades\DB;

class OrganizationChartController extends Controller
{
    
    public function stream($name)
    {
        $name_turn = strtolower($name);

        //realizamos la consulta en la bae de datos
        $model = DB::table('shifts')

            ->where('name_turn', $name_turn)
            ->join('schedules', 'schedules.id_schedule', '=', 'shifts.schedule_id')
            ->join('nurses', 'nurses.id_nurse', '=', 'schedules.nurse_id')
            ->join('assignment_times', 'assignment_times.shift_id', '=', 'shifts.id_shift')
            ->join('times', 'times.id_time', '=', 'assignment_times.time_id')
            ->select('shifts.abbreviation_name', 'schedules.*', 'times.*', 'nurses.identification');

        //validamos que exista el registro
        $validateShifts = $model->get();

        $week = [];

        foreach($validateShifts as $validateShift){

            $week[] = [
                'monday' => ['work' => $validateShift->monday, 'shift' =>  $validateShift->abbreviation_name, 'time' => ['start_time' => $validateShift->start_time, 'finish_time' => $validateShift->finish_time]], 
                'tuesday' => ['work' => $validateShift->tuesday, 'shift' => $validateShift->abbreviation_name, 'time' => ['start_time' => $validateShift->start_time, 'finish_time' => $validateShift->finish_time]], 
                'wednesday' => ['work' => $validateShift->wednesday, 'shift' => $validateShift->abbreviation_name, 'time' => ['start_time' => $validateShift->start_time, 'finish_time' => $validateShift->finish_time]], 
                'thursday' => ['work' => $validateShift->thursday, 'shift' => $validateShift->abbreviation_name, 'time' => ['start_time' => $validateShift->start_time, 'finish_time' => $validateShift->finish_time]], 
                'friday' => ['work' => $validateShift->friday, 'shift' => $validateShift->abbreviation_name, 'time' => ['start_time' => $validateShift->start_time, 'finish_time' => $validateShift->finish_time]], 
                'saturday' => ['work' => $validateShift->saturday, 'shift' => $validateShift->abbreviation_name, 'time' => ['start_time' => $validateShift->start_time, 'finish_time' => $validateShift->finish_time]], 
                'sunday' => ['work' => $validateShift->sunday, 'shift' => $validateShift->abbreviation_name, 'time' => ['start_time' => $validateShift->start_time, 'finish_time' => $validateShift->finish_time]]];

        }

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('organization_chart', ['week' => $week])->setPaper('a4', 'landscape')->save("organization_charts/".$validateShifts[0]->identification.".pdf");
        return $pdf->stream();
    }

    public function download($name_turn)
    {
        // $week  = ['monday' => true, 'tuesday' => true, 'wednesday' => false, 'thursday' => false, 'friday' => false, 'saturday' => true, 'sunday' => true];
         //realizamos la consulta en la bae de datos
         $model = DB::table('shifts')

         ->where('name_turn', $name_turn)
         ->join('schedules', 'schedules.id_schedule', '=', 'shifts.schedule_id')
         ->join('nurses', 'nurses.id_nurse', '=', 'schedules.nurse_id')
         ->join('assignment_times', 'assignment_times.shift_id', '=', 'shifts.id_shift')
         ->join('times', 'times.id_time', '=', 'assignment_times.time_id')
         ->select('shifts.abbreviation_name', 'schedules.*', 'times.*', 'nurses.identification');

     //validamos que exista el registro
     $validateShifts = $model->get();

     $week = [];

     foreach($validateShifts as $validateShift){

         $week[] = [
             'monday' => ['work' => $validateShift->monday, 'shift' =>  $validateShift->abbreviation_name, 'time' => ['start_time' => $validateShift->start_time, 'finish_time' => $validateShift->finish_time]], 
             'tuesday' => ['work' => $validateShift->tuesday, 'shift' => $validateShift->abbreviation_name, 'time' => ['start_time' => $validateShift->start_time, 'finish_time' => $validateShift->finish_time]], 
             'wednesday' => ['work' => $validateShift->wednesday, 'shift' => $validateShift->abbreviation_name, 'time' => ['start_time' => $validateShift->start_time, 'finish_time' => $validateShift->finish_time]], 
             'thursday' => ['work' => $validateShift->thursday, 'shift' => $validateShift->abbreviation_name, 'time' => ['start_time' => $validateShift->start_time, 'finish_time' => $validateShift->finish_time]], 
             'friday' => ['work' => $validateShift->friday, 'shift' => $validateShift->abbreviation_name, 'time' => ['start_time' => $validateShift->start_time, 'finish_time' => $validateShift->finish_time]], 
             'saturday' => ['work' => $validateShift->saturday, 'shift' => $validateShift->abbreviation_name, 'time' => ['start_time' => $validateShift->start_time, 'finish_time' => $validateShift->finish_time]], 
             'sunday' => ['work' => $validateShift->sunday, 'shift' => $validateShift->abbreviation_name, 'time' => ['start_time' => $validateShift->start_time, 'finish_time' => $validateShift->finish_time]]];

     }

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('organization_chart', ['week' => $week])->setPaper('a4', 'landscape');
        return $pdf->download($validateShifts[0]->identification.".pdf");
    }

    public function qrCode($name_turn)
    {
         //realizamos la consulta en la bae de datos
         $model = DB::table('shifts')

         ->where('name_turn', $name_turn)
         ->join('schedules', 'schedules.id_schedule', '=', 'shifts.schedule_id')
         ->join('nurses', 'nurses.id_nurse', '=', 'schedules.nurse_id')
         ->join('assignment_times', 'assignment_times.shift_id', '=', 'shifts.id_shift')
         ->join('times', 'times.id_time', '=', 'assignment_times.time_id')
         ->select('shifts.abbreviation_name', 'schedules.*', 'times.*', 'nurses.identification');

     //validamos que exista el registro
     $validateShifts = $model->get();

     $week = [];

     foreach($validateShifts as $validateShift){

         $week[] = [
             'monday' => ['work' => $validateShift->monday, 'shift' =>  $validateShift->abbreviation_name, 'time' => ['start_time' => $validateShift->start_time, 'finish_time' => $validateShift->finish_time]], 
             'tuesday' => ['work' => $validateShift->tuesday, 'shift' => $validateShift->abbreviation_name, 'time' => ['start_time' => $validateShift->start_time, 'finish_time' => $validateShift->finish_time]], 
             'wednesday' => ['work' => $validateShift->wednesday, 'shift' => $validateShift->abbreviation_name, 'time' => ['start_time' => $validateShift->start_time, 'finish_time' => $validateShift->finish_time]], 
             'thursday' => ['work' => $validateShift->thursday, 'shift' => $validateShift->abbreviation_name, 'time' => ['start_time' => $validateShift->start_time, 'finish_time' => $validateShift->finish_time]], 
             'friday' => ['work' => $validateShift->friday, 'shift' => $validateShift->abbreviation_name, 'time' => ['start_time' => $validateShift->start_time, 'finish_time' => $validateShift->finish_time]], 
             'saturday' => ['work' => $validateShift->saturday, 'shift' => $validateShift->abbreviation_name, 'time' => ['start_time' => $validateShift->start_time, 'finish_time' => $validateShift->finish_time]], 
             'sunday' => ['work' => $validateShift->sunday, 'shift' => $validateShift->abbreviation_name, 'time' => ['start_time' => $validateShift->start_time, 'finish_time' => $validateShift->finish_time]]];

     }

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('organization_chart', ['week' => $week])->setPaper('a4', 'landscape')->save('organization_charts/organigrama.pdf');

        return QrCode::size(300)->generate("http://127.0.0.1:8000/organization_charts/".$validateShifts[0]->identification.".pdf");
    }
}
