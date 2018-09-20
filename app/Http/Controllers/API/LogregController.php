<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; 
use Validator;

use App\User;

class LogregController extends Controller
{
	public $successStatus = 200;

	public function login(){		
		if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
			if (Auth::user()->role_id == 4 || Auth::user()->role_id == 1) {
				$user = Auth::user();
				$success['token'] =  $user->createToken('jobhubemployer')->accessToken;

				$result['success'] = true;
				$result['token'] = $user->createToken('jobhub')->accessToken;
				$result['message'] = 'User login successful';		
				return response()->json($result);
			}
			else{
				$result['success'] = false;
				$result['message'] = 'Unauthorised';		
				return response()->json($result);
			}			
		}
		else{
			$result['success'] = false;
			$result['message'] = 'Unauthorised';		
			return response()->json($result);
		}
	}

	public function register(Request $request){
		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'email' => 'required|email',
			'password' => 'required',
			'c_password' => 'required|same:password',
		]);

		if ($validator->fails()) {
			$result['success'] = false;
			$result['message'] = 'Invalid inputs';
			$result['error'] = $validator->errors();		
			return response()->json($result);
		}

		$input = $request->all();
		$input['password'] = bcrypt($input['password']);
		$input['role_id'] = 4;
		$user = User::create($input);


		$result['success'] = true;
		$result['email'] = $user->email;
		$result['token'] = $user->createToken('jobhubemployer')->accessToken;
		$result['message'] = 'User Registration successful';

		return response()->json($result);
	}

	public function getDetails(){ 
		$user = Auth::user(); 
		return response()->json(['success' => $user], $this->successStatus); 
	}
}
