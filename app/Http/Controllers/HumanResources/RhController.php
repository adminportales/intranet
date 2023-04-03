<?php

namespace App\Http\Controllers\HumanResources;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Department;
use App\Models\Postulant;
use App\Models\PostulantBeneficiary;
use App\Models\PostulantDetails;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\UserBeneficiary;
use App\Models\UserDetails;
use App\Models\UserDownMotive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RhController extends Controller
{
    public function stadistics()
    {
        return view('rh.stadistics');
    }

    public function postulants()
    {  
        $postulants_data = [];
        $postulants = Postulant::all()->whereIn('status', ['postulante','candidato']);
        
        foreach($postulants as $postulant){
            $company = Company::all()->where('id', $postulant->company_id)->last();
            $department = Department::all()->where('id', $postulant->department_id)->last();

            array_push($postulants_data, (object)[
                'id' => $postulant->id,
                'fullname' => $postulant->name. " ". $postulant->lastname,
                'mail' =>  $postulant->mail,
                'phone' => $postulant->phone,
                'cv' => $postulant->cv,
                'status' => $postulant->status,
                'company'=>$company->name_company,
                'department' => $department->name,
                'interview_date' => $postulant->interview_date,
            ]);

        } 
        return view('rh.postulants', compact('postulants_data'));  
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
        $company = "";
        //Promolife
        if($request->company_id == 1){
            $company = "PROMO LIFE, S. DE R.L. DE C.V.";
        }

        //BH tardemarket
        if($request->company_id == 2){
            $company = "BH TRADE MARKET, S.A. DE C.V.";
        }

        //Promo zale
        if($request->company_id == 3){
            $company = "PROMO ZALE S.A. DE C.V."; 
        }

        //Trademarket 57
        if($request->company_id == 4){
            $company = "TRADE MARKET 57, S.A. DE C.V."; 
        } 

        //Unipromtex
        if($request->company_id == 5){
            $company = "UNIPROMTEX S.A. DE C.V."; 
        } 

        $employee_down =new EmployeeDown();
        $employee_down->employeeDown($request->name, $request->lastname,$company);
    }

    public function createMotiveDown(Request $request)
    {

        $down_motive = UserDownMotive::all()->where('user_id', intval($request->user_id))->last();

        if($down_motive == null){
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
            $create_user_motive->other_motive  = $request->other_motive;
                
            $create_user_motive->save();
        }else{
            DB::table('users_down_motive')->where('user_id', intval($request->user_id))->update([
            'growth_salary'  => $request->growth_salary,
            'growth_promotion'  => $request->growth_promotion,
            'growth_activity'  => $request->growth_activity,
            'climate_partnet'  => $request->climate_partnet,
            'climate_manager'  => $request->climate_manager,
            'climate_boss'  => $request->climate_boss,
            'psicosocial_workloads'  => $request->psicosocial_workloads,
            'psicosocial_appreciation'   => $request->psicosocial_appreciation,
            'psicosocial_violence' => $request->psicosocial_violence,
            'psicosocial_workday'  => $request->psicosocial_workday,
            'demographics_distance'  => $request->demographics_distance,
            'demographics_physical'  => $request->demographics_physical,
            'demographics_personal'  => $request->demographics_personal,
            'demographics_school'  => $request->demographics_school,
            'health_personal'  => $request->health_personal,
            'health_familiar'  => $request->health_familiar,
            'other_motive'   => $request->other_motive,
            ]);

        }
        
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
        ]);

        $cv = null;

        if ($request->hasFile('cv')) {
            $filenameWithExt = $request->file('cv')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('cv')->clientExtension();
            $fileNameToStore = time(). $filename . '.' . $extension;
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

        return redirect()->action([RhController::class, 'postulants']);

    }

    public function editPostulant($post)
    {
        $postulant = Postulant::all()->where('id',$post)->last();
        $companies = Company::all()->pluck('name_company', 'id');
        $departments = Department::all()->pluck('name','id');
        $postulant_details = PostulantDetails::all()->where('postulant_id',$postulant->id)->last();
        if($postulant_details == null){
            $postulant_beneficiaries  = [];
        }else{
            $postulant_beneficiaries_data = PostulantBeneficiary::all()->where('postulant_details_id',$postulant_details->id);
            $postulant_beneficiaries  = [];
            foreach($postulant_beneficiaries_data as $beneficiary){
                array_push($postulant_beneficiaries, (object)[
                    'id' => $beneficiary->id,
                    'name' => $beneficiary->name,
                    'phone' =>  $beneficiary->phone,
                    'porcentage' => $beneficiary->porcentage,
                    'postulant_details_id' => $beneficiary->postulant_details_id,
                ]);
            }
        }

        return view('rh.edit-postulant', compact('postulant', 'companies', 'departments', 'postulant_details', 'postulant_beneficiaries'));
    }
    

    public function updatePostulant(Request $request)
    {
        
        if($request->status <> "postulante")
            $request->validate([
            'date_admission' => 'required',
            'birthdate' => 'required',
        ]); 


        $cv = null;

        if ($request->hasFile('cv')) {
            $find_postulant = Postulant::all()->where('id', $request->postulant_id)->last();
            File::delete($find_postulant->cv);
            $filenameWithExt = $request->file('cv')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('cv')->clientExtension();
            $fileNameToStore = time(). $filename . '.' . $extension;
            $cv = $request->file('cv')->move('storage/postulant/', $fileNameToStore);
        }

        DB::table('postulant')->where('id', intval($request->postulant_id))->update([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'mail' => $request->mail,
            'phone' => $request->phone,
            'cv' => $cv,
            'status' => $request->status,
            'company_id' => $request->company_id,
            'department_id' => $request->department_id,
            'interview_date' => $request->interview_date,
        ]); 

        $find_postulant_details = PostulantDetails::all()->where('postulant_id', $request->postulant_id)->last();
        
        if($find_postulant_details==null){
            $create_postulant_details = new PostulantDetails;
            $create_postulant_details->postulant_id  = $request->postulant_id;
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
            
            $create_postulant_details->nacionality  = $request->nacionality;
            $create_postulant_details->id_credential  = $request->id_credential;
            $create_postulant_details->gender  = $request->gender;
            $create_postulant_details->month_salary_net  = $request->month_salary_net;
            $create_postulant_details->month_salary_gross  = $request->month_salary_gross;
            $create_postulant_details->daily_salary  = $request->daily_salary;
            $create_postulant_details->daily_salary_letter  = $request->daily_salary_letter;
            $create_postulant_details->position_objetive  = $request->position_objetive;
            $create_postulant_details->contract_duration  = $request->contract_duration;

            $create_postulant_details->save();
            
            $find_postulant_details = PostulantDetails::all()->where('postulant_id', $request->postulant_id)->last();

           
                $create_postulant_beneficiary = new  PostulantBeneficiary();
                $create_postulant_beneficiary->name = $request->beneficiary1;
                $create_postulant_beneficiary->phone = null;
                $create_postulant_beneficiary->porcentage = $request->porcentage1;
                $create_postulant_beneficiary->position = 'beneficiary1';
                $create_postulant_beneficiary->postulant_details_id = $find_postulant_details->id;
                $create_postulant_beneficiary->save();
           
       
                $create_postulant_beneficiary = new  PostulantBeneficiary();
                $create_postulant_beneficiary->name = $request->beneficiary2;
                $create_postulant_beneficiary->phone = null;
                $create_postulant_beneficiary->porcentage = $request->porcentage2;
                $create_postulant_beneficiary->position = 'beneficiary2';
                $create_postulant_beneficiary->postulant_details_id = $find_postulant_details->id;
                $create_postulant_beneficiary->save();
         
                $create_postulant_beneficiary = new  PostulantBeneficiary();
                $create_postulant_beneficiary->name = $request->beneficiary3;
                $create_postulant_beneficiary->phone = null;
                $create_postulant_beneficiary->porcentage = $request->porcentage3;
                $create_postulant_beneficiary->position = 'beneficiary3';
                $create_postulant_beneficiary->postulant_details_id = $find_postulant_details->id;
                $create_postulant_beneficiary->save();
           

        }else{
            DB::table('postulant_details')->where('postulant_id', intval($request->postulant_id))->update([
                'place_of_birth' => $request->place_of_birth,
                'birthdate' => $request->birthdate,
                'fathers_name' => $request->fathers_name,
                'mothers_name' => $request->mothers_name,
                'civil_status' => $request->civil_status,
                'age' => $request->age,
                'address' => $request->address,
                'street' => $request->street,
                'colony' => $request->colony,
                'delegation' => $request->delegation,
                'postal_code' => $request->postal_code,
                'cell_phone' => $request->cell_phone,
                'home_phone' => $request->home_phone,
                'curp' => $request->curp,
                'rfc' => $request->rfc,
                'imss_number' => $request->imss_number,
                'fiscal_postal_code' => $request->fiscal_postal_code,
                'position' => $request->position,
                'area' => $request->area,
                'horary' => $request->horary,
                'date_admission' => $request->date_admission,
                'card_number' => $request->card_number,
                'bank_name' => $request->bank_name,
                'infonavit_credit' => $request->infonavit_credit,
                'factor_credit_number' => $request->factor_credit_number,
                'fonacot_credit' => $request->fonacot_credit,
                'discount_credit_number' => $request->discount_credit_number,
                'home_references' => $request->home_references,
                'house_characteristics' => $request->house_characteristics,

                'nacionality' => $request->nacionality,
                'id_credential' => $request->id_credential,
                'gender' => $request->gender,
                'month_salary_net' => $request->month_salary_net,
                'month_salary_gross' => $request->month_salary_gross,
                'daily_salary' => $request->daily_salary,
                'daily_salary_letter' => $request->daily_salary_letter,
                'position_objetive' => $request->position_objetive,
                'contract_duration' => $request->contract_duration,
            ]); 

            $postulant_details = PostulantDetails::all()->where('postulant_id', $request->postulant_id)->last();

            DB::table('postulant_beneficiary')->where('postulant_details_id', intval($postulant_details->id))->where('position','beneficiary1') ->update([
                'name' => $request->beneficiary1,
                'porcentage' => $request->porcentage1,
            ]);

            DB::table('postulant_beneficiary')->where('postulant_details_id', intval($postulant_details->id))->where('position','beneficiary2') ->update([
                'name' => $request->beneficiary2,
                'porcentage' => $request->porcentage2,
            ]);

            DB::table('postulant_beneficiary')->where('postulant_details_id', intval($postulant_details->id))->where('position','beneficiary3') ->update([
                'name' => $request->beneficiary3,
                'porcentage' => $request->porcentage3,
            ]);

            
        }

        if($request->status == 'empleado'){

            $pass = Str::random(8);

            $user = new User();
            $user->name = $request->name;
            $user->image = null;
            $user->lastname = $request->lastname;
            $user->email = $request->mail;
            $user->password = Hash::make($pass);
            $user->save();

            $user->employee->birthday_date = $request->birthdate;
            $user->employee->date_admission = $request->date_admission;
            $user->employee->status = 1;
            $user->employee->jefe_directo_id = null;
            $user->employee->position_id = null;
            $user->employee->save();

            $find_user= User::all()->where('name', $request->name)->where('lastname',$request->lastname)->last();
                
            $role = new RoleUser();
            $role->role_id = 5;
            $role->user_id = $find_user->id;
            $role->user_type = 'App\Models\User';
            $role->save();

            $user_details = new UserDetails();
                
            $user_details->user_id  = $find_user->id;
            $user_details->place_of_birth  = $request->place_of_birth;
            $user_details->birthdate  = $request->birthdate;
            $user_details->fathers_name  = $request->fathers_name;
            $user_details->mothers_name  = $request->mothers_name;
            $user_details->civil_status  = $request->civil_status;
            $user_details->age	  = $request->age;
            $user_details->address  = $request->address;
            $user_details->street  = $request->street;
            $user_details->colony  = $request->colony;
            $user_details->delegation  = $request->delegation;
            $user_details->postal_code  = $request->postal_code;
            $user_details->cell_phone  = $request->cell_phone;
            $user_details->home_phone  = $request->home_phone;
            $user_details->curp  = $request->curp;
            $user_details->rfc  = $request->rfc;
            $user_details->imss_number  = $request->imss_number;
            $user_details->fiscal_postal_code  = $request->fiscal_postal_code;
            $user_details->position  = $request->position;
            $user_details->area  = $request->area;
            $user_details->horary  = $request->horary;
            $user_details->date_admission  = $request->date_admission;
            $user_details->card_number  = $request->card_number;
            $user_details->bank_name  = $request->bank_name;
            $user_details->infonavit_credit  = $request->infonavit_credit;
            $user_details->factor_credit_number  = $request->factor_credit_number;
            $user_details->fonacot_credit  = $request->fonacot_credit;
            $user_details->discount_credit_number  = $request->discount_credit_number;
            $user_details->home_references  = $request->home_references;
            $user_details->house_characteristics  = $request->house_characteristics;
                
            $user_details->nacionality  = $request->nacionality;
            $user_details->id_credential  = $request->id_credential;
            $user_details->gender  = $request->gender;
            $user_details->month_salary_net  = $request->month_salary_net;
            $user_details->month_salary_gross  = $request->month_salary_gross;
            $user_details->daily_salary  = $request->daily_salary;
            $user_details->daily_salary_letter  = $request->daily_salary_letter;
            $user_details->position_objetive  = $request->position_objetive;
            $user_details->contract_duration  = $request->contract_duration;

            $user_details->save();
                
            $find_user_details = UserDetails::all()->where('curp', $request->curp)->last();

            $user_beneficiary = new UserBeneficiary();
            $user_beneficiary->name = $request->beneficiary1;
            $user_beneficiary->phone = null;
            $user_beneficiary->porcentage = $request->porcentage1;
            $user_beneficiary->position = 'beneficiary1';
            $user_beneficiary->users_details_id = $find_user_details->id;
            $user_beneficiary->save();
            
            $user_beneficiary = new  UserBeneficiary();
            $user_beneficiary->name = $request->beneficiary2;
            $user_beneficiary->phone = null;
            $user_beneficiary->porcentage = $request->porcentage2;
            $user_beneficiary->position = 'beneficiary2';
            $user_beneficiary->users_details_id = $find_user_details->id;
            $user_beneficiary->save();
            
            $user_beneficiary = new  UserBeneficiary();
            $user_beneficiary->name = $request->beneficiary3;
            $user_beneficiary->phone = null;
            $user_beneficiary->porcentage = $request->porcentage3;
            $user_beneficiary->position = 'beneficiary3';
            $user_beneficiary->users_details_id = $find_user_details->id;
            $user_beneficiary->save();
           
        }

        return redirect()->back()->with('message', 'Información guardada correctamente');
    }

    public function createPostulantDocumentation($postulant_id)
    {
        $postulant = Postulant::all()->where('id',$postulant_id)->last();
        return View('rh.create-postulant-documentation', compact('postulant'));
    }

    public function buildPostulantDocumentation(Request $request)
    {
        
        $postulant = Postulant::all()->where('id',$request->postulant)->last();
        $postulant_details = PostulantDetails::all()->where('postulant_id',$request->postulant)->last();
        $postulant_beneficiaries = PostulantBeneficiary::all()->where('postulant_details_id',$postulant_details->id)->values('name','porcentage');

        if($request->document == null){
            return redirect()->back()->with('error', 'No has seleccionado ningun documento a generar');          
        }
        if($request->document =='up_personal'){ 
            $up_document = new UpDocument();
            $up_document->upDocument($postulant, $postulant_details, $postulant_beneficiaries);
        }

        if($request->document == 'determined_contract'){
            $determined_contract = new DeterminateContract();
            $determined_contract->determinateContract($postulant, $postulant_details);
        }

        if($request->document == 'indetermined_contract'){
            $indeterminate_contract = new IndeterminateContract();
            $indeterminate_contract->indeterminateContract($postulant, $postulant_details,$request->company, $request->determined_contract_duration );
        }

        if($request->document == 'confidentiality_agreement'){
            $confidentiality_agreement = new confidentialityAgreement();
            $confidentiality_agreement->confidentialityAgreement($postulant, $postulant_details);
        }

        if($request->document == 'work_condition_update'){
            $work_condition_update = new WorkConditionUpdate();
            $work_condition_update->workConditionUpdate($postulant, $postulant_details);
        }

        if($request->document == 'no_compete_agreement'){

            //Promo zale
            if(intval($postulant->company_id) == 3){
                return redirect()->back()->with('error', 'Archivo no disponible para la empresa Promo Zale');          
            }   
            //Unipromtex
            if(intval($postulant->company_id)== 5){
                return redirect()->back()->with('error', 'Archivo no disponible para la empresa Unipromtex');          
            }
            $no_compete_agreement = new NoCompeteAgreement();
            $no_compete_agreement->noCompeteAgreement($postulant, $postulant_details);
        }   

        if($request->document == 'letter_for_bank'){
            $letter_for_bank = new LetterForBank();
            $letter_for_bank->letterForBank($postulant,$postulant_details,intval($request->company));
        }   
    }

    public function downUsers()
    {
        $users = User::all()->where('status',2);
        return view('rh.down-users', compact('users'));  
    }

    public function upUsers(Request $request)
    {
        DB::table('users')->where('id', intval($request->user_id) )->update(['status' => 1]); 
        return redirect()->back()->with('message', 'Usuario dado de alta satisfactoriamente');
    }

    public function convertToEmployee(Request $request)
    {
     
        $postulant = Postulant::all()->where('id',$request->postulant_id)->last();

        $postulant_details = PostulantDetails::all()->where('postulant_id',$postulant->id)->last();
        
        $postulant_beneficiaries = PostulantBeneficiary::all()->where('postulant_details_id',$postulant->id);

        $pass = Str::random(8);

        $user = new User();
        $user->name = $postulant->name;
        $user->image = null;
        $user->lastname = $postulant->lastname;
        $user->email = $postulant->mail;
        $user->password = Hash::make($pass);
        $user->save();

        $user->employee->birthday_date = $postulant_details->birthdate;
        $user->employee->date_admission = $postulant_details->date_admission;
        $user->employee->status = 1;
        $user->employee->jefe_directo_id = null;
        $user->employee->position_id = null;
        $user->employee->save();

        $find_user= User::all()->where('name', $postulant->name)->where('lastname',$postulant->lastname)->last();
                
        $role = new RoleUser();
        $role->role_id = 5;
        $role->user_id = $find_user->id;
        $role->user_type = 'App\Models\User';
        $role->save();

        $user_details = new UserDetails();
                
        $user_details->user_id  = $find_user->id;
        $user_details->place_of_birth  = $postulant_details->place_of_birth;
        $user_details->birthdate  = $postulant_details->birthdate;
        $user_details->fathers_name  = $postulant_details->fathers_name;
        $user_details->mothers_name  = $postulant_details->mothers_name;
        $user_details->civil_status  = $postulant_details->civil_status;
        $user_details->age	  = $postulant_details->age;
        $user_details->address  = $postulant_details->address;
        $user_details->street  = $postulant_details->street;
        $user_details->colony  = $postulant_details->colony;
        $user_details->delegation  = $postulant_details->delegation;
        $user_details->postal_code  = $postulant_details->postal_code;
        $user_details->cell_phone  = $postulant_details->cell_phone;
        $user_details->home_phone  = $postulant_details->home_phone;
        $user_details->curp  = $postulant_details->curp;
        $user_details->rfc  = $postulant_details->rfc;
        $user_details->imss_number  = $postulant_details->imss_number;
        $user_details->fiscal_postal_code  = $postulant_details->fiscal_postal_code;
        $user_details->position  = $postulant_details->position;
        $user_details->area  = $postulant_details->area;
        $user_details->horary  = $postulant_details->horary;
        $user_details->date_admission  = $postulant_details->date_admission;
        $user_details->card_number  = $postulant_details->card_number;
        $user_details->bank_name  = $postulant_details->bank_name;
        $user_details->infonavit_credit  = $postulant_details->infonavit_credit;
        $user_details->factor_credit_number  = $postulant_details->factor_credit_number;
        $user_details->fonacot_credit  = $postulant_details->fonacot_credit;
        $user_details->discount_credit_number  = $postulant_details->discount_credit_number;
        $user_details->home_references  = $postulant_details->home_references;
        $user_details->house_characteristics  = $postulant_details->house_characteristics;
                
        $user_details->nacionality  = $postulant_details->nacionality;
        $user_details->id_credential  = $postulant_details->id_credential;
        $user_details->gender  = $postulant_details->gender;
        $user_details->month_salary_net  = $postulant_details->month_salary_net;
        $user_details->month_salary_gross  = $postulant_details->month_salary_gross;
        $user_details->daily_salary  = $postulant_details->daily_salary;
        $user_details->daily_salary_letter  = $postulant_details->daily_salary_letter;
        $user_details->position_objetive  = $postulant_details->position_objetive;
        $user_details->contract_duration  = $postulant_details->contract_duration;

        $user_details->save();
               
        $find_user_details = UserDetails::all()->where('user_id', $find_user->id)->last();

        $postulant_beneficiaries = PostulantBeneficiary::all()->where('postulant_details_id', $postulant_details    ->id);

        foreach($postulant_beneficiaries as $beneficiary ){
            if($beneficiary->position == 'beneficiary1' ){
                $user_beneficiary = new UserBeneficiary();
                $user_beneficiary->name = $beneficiary->beneficiary1;
                $user_beneficiary->phone = null;
                $user_beneficiary->porcentage = $beneficiary->porcentage1;
                $user_beneficiary->position = 'beneficiary1';
                $user_beneficiary->users_details_id = $find_user_details->id;
                $user_beneficiary->save();
            }

            if($beneficiary->position == 'beneficiary2' ){
                $user_beneficiary = new  UserBeneficiary();
                $user_beneficiary->name = $beneficiary->beneficiary2;
                $user_beneficiary->phone = null;
                $user_beneficiary->porcentage = $beneficiary->porcentage2;
                $user_beneficiary->position = 'beneficiary2';
                $user_beneficiary->users_details_id = $find_user_details->id;
                $user_beneficiary->save();
            }

            if($beneficiary->position == 'beneficiary3' ){
                $user_beneficiary = new  UserBeneficiary();
                $user_beneficiary->name = $beneficiary->beneficiary3;
                $user_beneficiary->phone = null;
                $user_beneficiary->porcentage = $beneficiary->porcentage3;
                $user_beneficiary->position = 'beneficiary3';
                $user_beneficiary->users_details_id = $find_user_details->id;
                $user_beneficiary->save();
            }
        }

        DB::table('postulant')->where('id', $request->postulant_id)->update(['status' => 'empleado']); 

        return redirect()->back()->with('message', 'Candidato ha sido promovido a empleado satisfactoriamente');

    }

}

