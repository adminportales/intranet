<?php

namespace App\Http\Controllers;

use App\Models\Communique;
use App\Models\CommuniqueCompany;
use App\Models\CommuniqueDepartment;
use App\Models\Employee;
use App\Models\Events;
use App\Models\NoWorkingDays;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __invoke()
    {

        $monthBirthday = '';
        $carbon = new \Carbon\Carbon();
        $date = $carbon->now();
        $date = $date->format('m');
        $employeesBirthday = [];
        foreach (Employee::all() as $employee) {
            if ($employee->birthday_date != null) {
                $birthday = explode('-', $employee->birthday_date);
                $monthAniversaryth = $birthday[1];
                if ($monthAniversaryth == $date) {
                    array_push($employeesBirthday, $employee);
                }
            }
        }

        $monthAniversarythAniversary = '';
        $carbon = new \Carbon\Carbon();
        $date = $carbon->now();
        $date = $date->format('m');
        $employeesAniversary = [];
        foreach (Employee::all() as $employee) {
            if ($employee->date_admission != null) {
                $birthday = explode('-', $employee->date_admission);
                $monthAniversaryth = $birthday[1];
                if ($monthAniversaryth == $date) {
                    array_push($employeesAniversary, $employee);
                }
            }
        }


        $eventos = Events::all();

        $communiquesImage = DB::table('communiques')->whereNotNull('image')->get();

        $noworkingdays = NoWorkingDays::orderBy('day', 'ASC')->get();

        $monthEmployeeController = MonthController::getEmpoyeeMonth();

        return view('home.index', compact('employeesBirthday', 'employeesAniversary', 'noworkingdays', 'eventos', 'communiquesImage', 'monthEmployeeController'));
    }


    public function getCommunique($id)
    {
        $comunique = Communique::all()->where('id', $id)->toArray();
        return array_values($comunique);
    }

    public function validateCommunicated(){
        $communiques =  Communique::all();

       foreach( $communiques as  $communique){
            $day = $communique->created_at->format('Y-m-d');
       
            $expiration = Carbon::parse($day)->addDays(5);
            $expirationFormat = $expiration->format('Y-m-d');

            $today = Carbon::now();
            $todayFormat = $today->format('Y-m-d');

            if( $todayFormat >=$expirationFormat   ){
                $communique->delete();
            }
       }
    }
}
