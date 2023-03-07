<?php

namespace App\Http\Controllers\HumanResources;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Department;
use App\Models\Postulant;
use App\Models\PostulantBeneficiary;
use App\Models\PostulantDetails;
use App\Models\User;
use App\Models\UserDownMotive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RhController extends Controller
{
    public function stadistics()
    {
        return view('rh.stadistics');
    }

    public function newUser()
    {  
        $postulants_data = [];
        $postulants = Postulant::all();
        
        foreach($postulants as $postulant){
            $company = Company::all()->where('id', $postulant->company_id)->last();
            $department = Department::all()->where('id', $postulant->company_id)->last();

            array_push($postulants_data, (object)[
                'id' => $postulant->id,
                'fullname' => $postulant->fullname. " ". $postulant->lastname,
                'mail' =>  $postulant->mail,
                'phone' => $postulant->phone,
                'cv' => $postulant->cv,
                'status' => $postulant->status,
                'company'=>$company->name_company,
                'department' => $department->name,
                'interview_date' => $postulant->interview_date,
            ]);

        } 
        return view('rh.new-user', compact('postulants_data'));  
    }

    public function dropUser()
    {
        $users = User::all()->where('status',1);
        return view('rh.drop-user', compact('users'));
    }

    public function dropDocumentation($user)
    {
        $user = User::all()->where('id',$user)->first();
        $companies = Company::all()->pluck('name_company', 'id' );
        $departments = Department::all()->pluck('name','id');
        $user_down_motive = UserDownMotive::all()->where('user_id',$user->id);

        return view('rh.drop-documentation', compact('user', 'companies', 'departments', 'user_down_motive'));
    }
    
    public function dropDeleteUser(Request $request)
    {
        DB::table('users')->where('id', intval($request->user) )->update(['status' => 2]); 

        return redirect()->action([RhController::class, 'dropUser'])->with('message', 'El usuario se ha dado de baja correctamente');
    }

    public function buildDownDocumentation(Request $request)
    {
        //Promolife
        if($request->company_id == 1){
            $pathfile= 'files/RENUNCIAPL.doc'; 
            return  response()->download($pathfile, 'Renuncia.doc');
        }

        //BH tardemarket
        if($request->company_id == 2){
            $pathfile= 'files/RENUNCIABH.doc'; 
            return  response()->download($pathfile, 'Renuncia.doc');
        }

        //Promo zale
        if($request->company_id == 3){
            $pathfile= 'files/RENUNCIAPZ.doc'; 
            return  response()->download($pathfile, 'Renuncia.doc');
        }

        //Trademarket 57
        if($request->company_id == 4){
            $pathfile= 'files/RENUNCIATM57.doc'; 
            return  response()->download($pathfile, 'Renuncia.doc');
        } 

        //Unipromtex
        if($request->company_id == 5){
            $pathfile= 'files/RENUNCIAUNIPROMTEX.doc'; 
            return  response()->download($pathfile, 'Renuncia.doc');
        } 

    
    }

    public function createMotiveDown(Request $request)
    {
        DB::table('users_down_motive')->where('user_id', intval($request->user_id))->delete();
        
        $create_user_motive = new UserDownMotive();
        $create_user_motive->user_id  = $request->user_id;
        $create_user_motive->growth_salary  = $request->growth_salary;
        $create_user_motive->growth_promotion  = $request->growth_promotion;
        $create_user_motive->growth_activity  = $request->growth_activity;
        $create_user_motive->climate_partnet  = $request->climate_partnet;
        $create_user_motive->climate_manager  = $request->climate_manager;
        $create_user_motive->climate_boss  = $request->climate_boss;
        $create_user_motive->psicosocial_workloads  = $request->psicosocial_workloads;
        $create_user_motive->psicosocial_appreciation	  = $request->psicosocial_appreciation	;
        $create_user_motive->psicosocial_violence  = $request->psicosocial_violence;
        $create_user_motive->psicosocial_workday  = $request->psicosocial_workday;
        $create_user_motive->demographics_distance  = $request->demographics_distance;
        $create_user_motive->demographics_physical  = $request->demographics_physical;
        $create_user_motive->demographics_personal  = $request->demographics_personal;
        $create_user_motive->demographics_school  = $request->demographics_school;
        $create_user_motive->health_personal  = $request->health_personal;
        $create_user_motive->health_familiar  = $request->health_familiar;
            
        $create_user_motive->save();

        return redirect()->back()->with('message', 'Motivo de baja guardado satisfactoriamente');
    }

    public function createPostulant()
    {
        $companies = Company::all()->pluck('name_company', 'id');
        $departments = Department::all()->pluck('name','id');
        return view('rh.create-postulant', compact('companies','departments'));
    }

    public function storePostulant(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'lastname' => 'required',
            'status' => 'required',
            'mail' => 'required',
            'phone' => 'required',
            'company_id' => 'required',
            'department_id' => 'required'
        ]);

        $cv = null;

        if ($request->hasFile('cv')) {
            $filenameWithExt = $request->file('cv')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('cv')->clientExtension();
            $fileNameToStore = $filename . '.' . $extension;
            $cv = $request->file('cv')->move('storage/postulant/', $fileNameToStore);
        }

        $create_postulant = new Postulant();
        $create_postulant->name  = $request->name;
        $create_postulant->lastname  = $request->lastname;
        $create_postulant->mail  = $request->mail;
        $create_postulant->phone  = $request->phone;
        $create_postulant->cv  = $cv;
        $create_postulant->status  = $request->status;
        $create_postulant->company_id  = $request->company_id;
        $create_postulant->department_id  = $request->department_id;
        $create_postulant->interview_date = $request->interview_date;
        $create_postulant->save();  
        
        
        if($request->rfc <>null){
            $postulant_id = Postulant::all()->where('name',$request->name)->where('mail',$request->mail)->last();
            $create_postulant_details = new PostulantDetails;
            $create_postulant_details->postulant_id  = $postulant_id->postulant_id;
            $create_postulant_details->place_of_birth  = $request->place_of_birth;
            $create_postulant_details->birthdate  = $request->birthdate;
            $create_postulant_details->fathers_name  = $request->fathers_name;
            $create_postulant_details->mothers_name  = $request->mothers_name;
            $create_postulant_details->civil_status  = $request->civil_status;
            $create_postulant_details->age	  = $request->age;
            $create_postulant_details->address  = $request->address;
            $create_postulant_details->street  = $request->street;
            $create_postulant_details->colony  = $request->colony;
            $create_postulant_details->delegation  = $request->delegation;
            $create_postulant_details->postal_code  = $request->postal_code;
            $create_postulant_details->cell_phone  = $request->cell_phone;
            $create_postulant_details->home_phone  = $request->home_phone;
            $create_postulant_details->curp  = $request->curp;
            $create_postulant_details->rfc  = $request->rfc;
            $create_postulant_details->imss_number  = $request->imss_number;
            $create_postulant_details->fiscal_postal_code  = $request->fiscal_postal_code;
            $create_postulant_details->position  = $request->position;
            $create_postulant_details->area  = $request->area;
            $create_postulant_details->salary_sd  = $request->salary_sd;
            $create_postulant_details->salary_sbc  = $request->salary_sbc;
            $create_postulant_details->horary  = $request->horary;
            $create_postulant_details->date_admission  = $request->date_admission;
            $create_postulant_details->card_number  = $request->card_number;
            $create_postulant_details->bank_name  = $request->bank_name;
            $create_postulant_details->infonavit_credit  = $request->infonavit_credit;
            $create_postulant_details->factor_credit_number  = $request->factor_credit_number;
            $create_postulant_details->fonacot_credit  = $request->fonacot_credit;
            $create_postulant_details->discount_credit_number  = $request->discount_credit_number;
            $create_postulant_details->home_references  = $request->home_references;
            $create_postulant_details->house_characteristics  = $request->house_characteristics;
            $create_postulant_details->save();

            $postulant_details_id = PostulantDetails::all()->where('postulant_id',$postulant_id)->last();

            if($request->has('beneficiary1')){
                $create_postulant_beneficiary = new  PostulantBeneficiary();
                $create_postulant_beneficiary->name = $request->beneficiary1;
                $create_postulant_beneficiary->phone = null;
                $create_postulant_beneficiary->porcentage = $request->porcentage1;
                $create_postulant_beneficiary->postulant_details_id = $postulant_details_id->id;
                $create_postulant_beneficiary->save();
            }

            if($request->has('beneficiary2')){
                $create_postulant_beneficiary = new  PostulantBeneficiary();
                $create_postulant_beneficiary->name = $request->beneficiary2;
                $create_postulant_beneficiary->phone = null;
                $create_postulant_beneficiary->porcentage = $request->porcentage2;
                $create_postulant_beneficiary->postulant_details_id = $postulant_details_id->id;
                $create_postulant_beneficiary->save();
            }

            if($request->has('beneficiary3')){
                $create_postulant_beneficiary = new  PostulantBeneficiary();
                $create_postulant_beneficiary->name = $request->beneficiary3;
                $create_postulant_beneficiary->phone = null;
                $create_postulant_beneficiary->porcentage = $request->porcentage3;
                $create_postulant_beneficiary->postulant_details_id = $postulant_details_id->id;
                $create_postulant_beneficiary->save();
            }

        }

        return redirect()->action([RhController::class, 'newUser']);

    }

}