<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; 
use Validator;

use App\User;
use App\Companydetail;
use App\Contactdetail;

class EmployerController extends Controller
{
	public $successStatus = 200;

	public function postCompanyDetails(Request $request){ 
		$validator = Validator::make($request->all(), [
			'companyname' => 'required',
			'companynamebg' => 'required',
			'cp' => 'required',
			'cpdesignation' => 'required',
			'cpmobile' => ['required', 'regex:/(^[+]{1}[8]{2}[01]{1}[0-9]{9}|^[8]{2}[01]{1}[0-9]{9}|^[01]{2}[0-9]{9})$/'],
			'cpemail' => 'required|email'
		]);

		if ($validator->fails()) {
			$result['success'] = false;
			$result['message'] = 'Invalid inputs';
			$result['error'] = $validator->errors();		
			return response()->json($result);
		}

		$industrytypes = $request->get('industrytypes');

		$companydetail = new Companydetail;
		$companydetail->companyname=$request->get('companyname');
		$companydetail->companynamebg=$request->get('companynamebg');
		$companydetail->cp=$request->get('cp');
		$companydetail->cpdesignation=$request->get('cpdesignation');
		$companydetail->cpmobile=$request->get('cpmobile');
		$companydetail->cpemail=$request->get('cpemail');
		$companydetail->businessdescription=$request->get('businessdescription');
		$companydetail->user_id=Auth::id();

		$save=$companydetail->save();

		if($save){
			$companydetail->industrytypes()->attach($industrytypes);
			$result['success'] = true;
			$result['message'] = 'Company details created successfully';
			return response()->json($result);
		}

		$result['success'] = false;
		$result['message'] = 'Company details could not be created';		
		return response()->json($result);
	}

	public function postUpdateCompanyDetails(Request $request){ 
		$validator = Validator::make($request->all(), [
			'cpmobile' => ['regex:/(^[+]{1}[8]{2}[01]{1}[0-9]{9}|^[8]{2}[01]{1}[0-9]{9}|^[01]{2}[0-9]{9})$/'],
			'cpemail' => 'email'
		]);

		if ($validator->fails()) {
			$result['success'] = false;
			$result['message'] = 'Invalid inputs';
			$result['error'] = $validator->errors();		
			return response()->json($result);
		}

		$industrytypes = $request->get('industrytypes');

		$companydetail = Companydetail::whrer('user_id', Auth::id())->first();
		$companydetail->companyname=$request->get('companyname', $companydetail->companyname);
		$companydetail->companynamebg=$request->get('companynamebg', $companydetail->companynamebg);
		$companydetail->cp=$request->get('cp', $companydetail->cp);
		$companydetail->cpdesignation=$request->get('cpdesignation', $companydetail->cpdesignation);
		$companydetail->cpmobile=$request->get('cpmobile', $companydetail->cpmobile);
		$companydetail->cpemail=$request->get('cpemail', $companydetail->cpemail);
		$companydetail->businessdescription=$request->get('businessdescription', $companydetail->businessdescription);

		$update=$companydetail->update();

		if($update){
			if ($request->has('industrytypes')){
				$industrytypes = $request->get('industrytypes');
				$companydetail->industrytypes()->sync($industrytypes);
			}
			$result['success'] = true;
			$result['message'] = 'Company details updated successfully';
			return response()->json($result);
		}

		$result['success'] = false;
		$result['message'] = 'Company details could not be updated';		
		return response()->json($result);
	}

	public function postCompanyContactDetails(Request $request){ 
		$validator = Validator::make($request->all(), [
			'caddress' => 'required',
			'country' => 'required',
			'city' => 'required',
			'area' => 'required',
			'billingaddress' => 'required',
			'cphone' => ['required', 'regex:/(^[+]{1}[8]{2}[01]{1}[0-9]{9}|^[8]{2}[01]{1}[0-9]{9}|^[01]{2}[0-9]{9})$/'],
			'cemail' => 'required|email',
			'website' => 'required',
			'town' => 'required'
		]);

		if ($validator->fails()) {
			$result['success'] = false;
			$result['message'] = 'Invalid inputs';
			$result['error'] = $validator->errors();		
			return response()->json($result);
		}

		$contactdetail = new Contactdetail;
		$contactdetail->caddress=$request->get('caddress');
		$contactdetail->country=$request->get('country');
		$contactdetail->city=$request->get('city');
		$contactdetail->area=$request->get('area');
		$contactdetail->billingaddress=$request->get('billingaddress');
		$contactdetail->cphone=$request->get('cphone');
		$contactdetail->cemail=$request->get('cemail');
		$contactdetail->website=$request->get('website');
		$contactdetail->town=$request->get('town');
		$contactdetail->user_id=Auth::id();

		$save=$contactdetail->save();

		if($save){
			$result['success'] = true;
			$result['message'] = 'Contact details created successfully';
			return response()->json($result);
		}

		$result['success'] = false;
		$result['message'] = 'Contact details could not be created';		
		return response()->json($result);
	}

	public function postUpdateCompanyContactDetails(Request $request){ 
		$validator = Validator::make($request->all(), [
			'cphone' => ['regex:/(^[+]{1}[8]{2}[01]{1}[0-9]{9}|^[8]{2}[01]{1}[0-9]{9}|^[01]{2}[0-9]{9})$/'],
			'cemail' => 'email'
		]);

		if ($validator->fails()) {
			$result['success'] = false;
			$result['message'] = 'Invalid inputs';
			$result['error'] = $validator->errors();		
			return response()->json($result);
		}

		$contactdetail = Contactdetail::whrer('user_id', Auth::id())->first();
		$contactdetail->caddress=$request->get('caddress', $contactdetail->caddress);
		$contactdetail->country=$request->get('country', $contactdetail->country);
		$contactdetail->city=$request->get('city', $contactdetail->city);
		$contactdetail->area=$request->get('area', $contactdetail->area);
		$contactdetail->billingaddress=$request->get('billingaddress', $contactdetail->billingaddress);
		$contactdetail->cphone=$request->get('cphone', $contactdetail->cphone);
		$contactdetail->cemail=$request->get('cemail', $contactdetail->cemail);
		$contactdetail->website=$request->get('website', $contactdetail->website);
		$contactdetail->town=$request->get('town', $contactdetail->town);
		$update=$contactdetail->update();

		if($update){
			$result['success'] = true;
			$result['message'] = 'Contact details update successfully';
			return response()->json($result);
		}

		$result['success'] = false;
		$result['message'] = 'Contact details could not be update';		
		return response()->json($result);
	}
}
