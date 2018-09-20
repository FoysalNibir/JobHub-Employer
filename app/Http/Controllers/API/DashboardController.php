<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; 
use Validator;
use Carbon\Carbon;

use App\User;
use App\Jobinfo;

class DashboardController extends Controller
{	
	public $successStatus = 200;

    public function getDrafts(){
		$drafts = Jobinfo::where('user_id',Auth::id())->where('status',0)->with('browsecategories')->with('adjobinfo')->with('candidaterequirements')->paginate(10);
		$result['success'] = true;
		$result['data'] = $drafts;
		return response()->json($result);
	}

	public function getPosts(){
		$posts = Jobinfo::where('user_id',Auth::id())->where('status',1)->with('browsecategories')->with('adjobinfo')->with('candidaterequirements')->paginate(10);
		$result['success'] = true;
		$result['data'] = $posts;
		return response()->json($result);
	}

	public function getArchives(){
		$posts = Jobinfo::where('user_id',Auth::id())->where('status',1)->where('deadline','<',Carbon::now()->toDateString())->with('browsecategories')->with('adjobinfo')->with('candidaterequirements')->paginate(10);
		$result['success'] = true;
		$result['data'] = $posts;
		return response()->json($result);
	}

	public function getUser(){
		$User = User::where('id', Auth::id())->with('companydetail')->with('contactdetail')->first();
		$result['success'] = true;
		$result['data'] = $User;
		return response()->json($result);
	}
}
