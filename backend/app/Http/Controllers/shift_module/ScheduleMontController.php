<?php

namespace App\Http\Controllers\shift_module;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleMontController extends Controller
{
    //
    public function show($name)
    {
        $name_turn = strtolower($name);

        //realizamos la consulta en la bae de datos
        $model = DB::table('shifts')

            ->where('name_turn', $name_turn)
            ->join('schedules', 'schedules.id_schedule', '=', 'shifts.schedule_id')
            ->join('nurses', 'nurses.id_nurse', '=', 'schedules.nurse_id')
            ->join('assignment_times', 'assignment_times.shift_id', '=', 'shifts.id_shift')
            ->join('times', 'times.id_time', '=', 'assignment_times.time_id')
            ->select('shifts.abbreviation_name', 'schedules.*', 'times.*');

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

        // return $validateShift;
        // $week  = [
        //     'monday' => ['work' => $validateShift->monday, 'shift' =>  $validateShift->abbreviation_name, 'time' => ['start_time' => $validateShift->start_time, 'finish_time' => $validateShift->finish_time]], 
        //     'tuesday' => ['work' => $validateShift->tuesday, 'shift' => $validateShift->abbreviation_name, 'time' => ['start_time' => $validateShift->start_time, 'finish_time' => $validateShift->finish_time]], 
        //     'wednesday' => ['work' => $validateShift->wednesday, 'shift' => $validateShift->abbreviation_name, 'time' => ['start_time' => $validateShift->start_time, 'finish_time' => $validateShift->finish_time]], 
        //     'thursday' => ['work' => $validateShift->thursday, 'shift' => $validateShift->abbreviation_name, 'time' => ['start_time' => $validateShift->start_time, 'finish_time' => $validateShift->finish_time]], 
        //     'friday' => ['work' => $validateShift->friday, 'shift' => $validateShift->abbreviation_name, 'time' => ['start_time' => $validateShift->start_time, 'finish_time' => $validateShift->finish_time]], 
        //     'saturday' => ['work' => $validateShift->saturday, 'shift' => $validateShift->abbreviation_name, 'time' => ['start_time' => $validateShift->start_time, 'finish_time' => $validateShift->finish_time]], 
        //     'sunday' => ['work' => $validateShift->sunday, 'shift' => $validateShift->abbreviation_name, 'time' => ['start_time' => $validateShift->start_time, 'finish_time' => $validateShift->finish_time]]];

        //return $week;

        if (count($week) != 0) {
            
            return response(content: ['query' => true, 'schedules' => $week], status: 200);

        }else{

            return response(content: ['query' => false, 'no existe ese role en el sistema'], status: 404);
        }

    }
}
