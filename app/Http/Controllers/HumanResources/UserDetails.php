<?php

namespace App\Http\Controllers\HumanResources;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserBeneficiary;
use App\Models\UserDetails as ModelsUserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class UserDetails extends Controller
{
    //
    public function moreInformation ($user_id)
    {       
        $user_details = ModelsUserDetails::all()->where('user_id', $user_id)->last();

        if($user_details == null){
            $user_details = [];
            $user_beneficiaries = [];
        }else{
            $user_beneficiaries = UserBeneficiary::all()->where('users_details_id', $user_details->id);
        }       
  
        return view('rh.more-information',compact('user_id', 'user_details', 'user_beneficiaries'));
    }
    
    public function createMoreInformation(Request $request)
    {
        $find_user_details = ModelsUserDetails::all()->where('user_id', $request->user_id)->last();
        if($find_user_details==null){
            
            $create_users_details= new ModelsUserDetails();
            $create_users_details->user_id  = $request->user_id;
            $create_users_details->place_of_birth  = $request->place_of_birth;
            $create_users_details->birthdate  = $request->birthdate;
            $create_users_details->fathers_name  = $request->fathers_name;
            $create_users_details->mothers_name  = $request->mothers_name;
            $create_users_details->civil_status  = $request->civil_status;
            $create_users_details->age	  = $request->age;
            $create_users_details->address  = $request->address;
            $create_users_details->street  = $request->street;
            $create_users_details->colony  = $request->colony;
            $create_users_details->delegation  = $request->delegation;
            $create_users_details->postal_code  = $request->postal_code;
            $create_users_details->cell_phone  = $request->cell_phone;
            $create_users_details->home_phone  = $request->home_phone;
            $create_users_details->curp  = $request->curp;
            $create_users_details->rfc  = $request->rfc;
            $create_users_details->imss_number  = $request->imss_number;
            $create_users_details->fiscal_postal_code  = $request->fiscal_postal_code;
            $create_users_details->position  = $request->position;
            $create_users_details->area  = $request->area;
            $create_users_details->horary  = $request->horary;
            $create_users_details->date_admission  = $request->date_admission;
            $create_users_details->card_number  = $request->card_number;
            $create_users_details->bank_name  = $request->bank_name;
            $create_users_details->infonavit_credit  = $request->infonavit_credit;
            $create_users_details->factor_credit_number  = $request->factor_credit_number;
            $create_users_details->fonacot_credit  = $request->fonacot_credit;
            $create_users_details->discount_credit_number  = $request->discount_credit_number;
            $create_users_details->home_references  = $request->home_references;
            $create_users_details->house_characteristics  = $request->house_characteristics;
            
            $create_users_details->nacionality  = $request->nacionality;
            $create_users_details->id_credential  = $request->id_credential;
            $create_users_details->gender  = $request->gender;
            $create_users_details->month_salary_net  = $request->month_salary_net;
            $create_users_details->month_salary_gross  = $request->month_salary_gross;
            $create_users_details->daily_salary  = $request->daily_salary;
            $create_users_details->daily_salary_letter  = $request->daily_salary_letter;
            $create_users_details->position_objetive  = $request->position_objetive;
            $create_users_details->contract_duration  = $request->contract_duration;

            $create_users_details->save();
            
            $user_details = ModelsUserDetails::all()->where('user_id', $request->user_id)->last();

            $create_user_beneficiary = new  UserBeneficiary();
            $create_user_beneficiary->name = $request->beneficiary1;
            $create_user_beneficiary->phone = null;
            $create_user_beneficiary->porcentage = $request->porcentage1;
            $create_user_beneficiary->position = 'beneficiary1';
            $create_user_beneficiary->users_details_id = $user_details->id;
            $create_user_beneficiary->save();
            
            $create_user_beneficiary = new  UserBeneficiary();
            $create_user_beneficiary->name = $request->beneficiary2;
            $create_user_beneficiary->phone = null;
            $create_user_beneficiary->porcentage = $request->porcentage2;
            $create_user_beneficiary->position = 'beneficiary2';
            $create_user_beneficiary->users_details_id = $user_details->id;
            $create_user_beneficiary->save();
            
            $create_user_beneficiary = new  UserBeneficiary();
            $create_user_beneficiary->name = $request->beneficiary3;
            $create_user_beneficiary->phone = null;
            $create_user_beneficiary->porcentage = $request->porcentage3;
            $create_user_beneficiary->position = 'beneficiary3';
            $create_user_beneficiary->users_details_id = $user_details->id;
            $create_user_beneficiary->save();
            

        }else{
            DB::table('users_details')->where('user_id', intval($request->user_id))->update([
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

            $user_details = ModelsUserDetails::all()->where('user_id',$request->user_id)->last();

            DB::table('user_beneficiary')->where('users_details_id', intval($find_user_details->id))->where('position','beneficiary1') ->update([
                'name' => $request->beneficiary1,
                'porcentage' => $request->porcentage1,
            ]);

            DB::table('user_beneficiary')->where('users_details_id', intval($find_user_details->id))->where('position','beneficiary2') ->update([
                'name' => $request->beneficiary2,
                'porcentage' => $request->porcentage2,
            ]);

            DB::table('user_beneficiary')->where('users_details_id', intval($find_user_details->id))->where('position','beneficiary3') ->update([
                'name' => $request->beneficiary3,
                'porcentage' => $request->porcentage3,
            ]);
  
        }

        return redirect()->back()->with('message', 'Información guardada correctamente');
    }
}