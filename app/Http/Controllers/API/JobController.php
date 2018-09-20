<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; 
use Validator;

use App\User;
use App\Companydetail;
use App\Contactdetail;
use App\Jobinfo;
use App\Addjobinfo;
use App\Candidaterequirement;

class JobController extends Controller
{
	public $successStatus = 200;

	public function postJobInfo(Request $request){ 
		$validator = Validator::make($request->all(), [
			'title' => 'required',
			'employeestatus' => 'required|in:fulltime,parttime,contractual,internship,freelance',
			'deadline' => 'required|date',
			'resumeopt' => 'in:applyonline,email,hardcopy,walkininterview',
			'vacancies' => 'numeric',
			'photoopt' => 'required|numeric|between:0,1'
		]);

		if ($validator->fails()) {
			$result['success'] = false;
			$result['message'] = 'Invalid inputs';
			$result['error'] = $validator->errors();		
			return response()->json($result);
		}

		$jobcategory = $request->get('jobcategory');

		$jobinfo = new Jobinfo;
		$jobinfo->title=$request->get('title');
		$jobinfo->vacancies=$request->get('vacancies');
		$jobinfo->employeestatus=$request->get('employeestatus');		
		$jobinfo->deadline=$request->get('deadline');
		$jobinfo->resumeopt=$request->get('resumeopt');
		$jobinfo->photoopt=$request->get('photoopt');
		$jobinfo->user_id=Auth::id();

		$save=$jobinfo->save();

		if($save){
			$jobinfo->browsecategories()->attach($jobcategory);
			$result['success'] = true;
			$result['message'] = 'Job information created successfully';
			$result['next_req_url'] = url('/').'/jobpost/moreinfo/'.$jobinfo->id;
			return response()->json($result);
		}

		$result['success'] = false;
		$result['message'] = 'Job information could not be created';		
		return response()->json($result);
	}

	public function postAddJobInfo(Request $request, $id){ 
		$validator = Validator::make($request->all(), [
			'level' => 'in:entry,mid,top',
			'location' => 'in:inbd,outbd',
			'salaryrange' => 'numeric',
			'salarytype' => 'in:hourly,daily,monthly,yearly',
			'negotiable' => 'numeric|between:0,1',
			'salaryreview' => 'in:halfyearly,yearly',
			'festivebonus' => 'numeric|between:0,4'

		]);

		if ($validator->fails()) {
			$result['success'] = false;
			$result['message'] = 'Invalid inputs';
			$result['error'] = $validator->errors();		
			return response()->json($result);
		}

		$benefits = $request->get('benefits');

		$addjobinfo = new Addjobinfo;
		$addjobinfo->level=$request->get('level');
		$addjobinfo->context=$request->get('context');
		$addjobinfo->responsibilities=$request->get('responsibilities');		
		$addjobinfo->location=$request->get('location');
		$addjobinfo->area=$request->get('area');
		$addjobinfo->salaryrange=$request->get('salaryrange');
		$addjobinfo->salarytype=$request->get('salarytype');
		$addjobinfo->negotiable=$request->get('negotiable');
		$addjobinfo->addsalaryinfo=$request->get('addsalaryinfo');
		$addjobinfo->salaryreview=$request->get('salaryreview');
		$addjobinfo->festivebonus=$request->get('festivebonus');
		$addjobinfo->others=$request->get('others');
		$addjobinfo->jobinfo_id=$id;

		$save=$addjobinfo->save();

		if($save){
			$addjobinfo->benefits()->attach($benefits);
			$result['success'] = true;
			$result['message'] = 'Additional details for the post created successfully';
			$result['next_req_url'] = url('/').'/jobpost/candidateinfo/'.$id;
			return response()->json($result);
		}

		$result['success'] = false;
		$result['message'] = 'Additional details for the post could not be created';		
		return response()->json($result);
	}

	public function postCandidateInfo(Request $request, $id){ 
		$validator = Validator::make($request->all(), [
			'minage' => 'numeric',
			'maxage' => 'numeric',
			'fresher' => 'numeric|between:0,1',
			'gender' => 'in:male,female,both',
			'prefarmy' => 'numeric|between:0,1'
		]);

		if ($validator->fails()) {
			$result['success'] = false;
			$result['message'] = 'Invalid inputs';
			$result['error'] = $validator->errors();		
			return response()->json($result);
		}

		$candinfo = new Candidaterequirement;
		$candinfo->degreelevel=$request->get('degreelevel');
		$candinfo->degreename=$request->get('degreename');
		$candinfo->degreemajor=$request->get('degreemajor');		
		$candinfo->preferedinstitute=$request->get('preferedinstitute');
		$candinfo->othereduqualification=$request->get('othereduqualification');
		$candinfo->course=$request->get('course');
		$candinfo->certification=$request->get('certification');
		$candinfo->experience=$request->get('experience');
		$candinfo->fresher=$request->get('fresher');
		$candinfo->areaofexp=$request->get('areaofexp');
		$candinfo->areaofbusiness=$request->get('areaofbusiness');
		$candinfo->skills=$request->get('skills');
		$candinfo->addrequirements=$request->get('addrequirements');
		$candinfo->gender=$request->get('gender');
		$candinfo->minage=$request->get('minage');
		$candinfo->maxage=$request->get('maxage');
		$candinfo->prefarmy=$request->get('prefarmy');
		$candinfo->jobinfo_id=$id;

		$save=$candinfo->save();

		if($save){
			$result['success'] = true;
			$result['message'] = 'Candidate requirements for the post created successfully';
			$result['next_req_url'] = null;
			return response()->json($result);
		}

		$result['success'] = false;
		$result['message'] = 'Candidate requirements for the post could not be created';		
		return response()->json($result);
	}

	public function postUpdateJobInfo(Request $request, $id){ 
		$validator = Validator::make($request->all(), [
			'employeestatus' => 'in:fulltime,parttime,contractual,internship,freelance',
			'deadline' => 'date',
			'resumeopt' => 'in:applyonline,email,hardcopy,walkininterview',
			'vacancies' => 'numeric',
			'photoopt' => 'numeric|between:0,1'
		]);

		if ($validator->fails()) {
			$result['success'] = false;
			$result['message'] = 'Invalid inputs';
			$result['error'] = $validator->errors();		
			return response()->json($result);
		}		

		$jobinfo = Jobinfo::where('user_id', Auth::id())->where('id', $id)->first();
		$jobinfo->title=$request->get('title', $jobinfo->title);
		$jobinfo->vacancies=$request->get('vacancies', $jobinfo->vacancies);
		$jobinfo->employeestatus=$request->get('employeestatus', $jobinfo->employeestatus);		
		$jobinfo->deadline=$request->get('deadline', $jobinfo->deadline);
		$jobinfo->resumeopt=$request->get('resumeopt', $jobinfo->resumeopt);
		$jobinfo->photoopt=$request->get('photoopt', $jobinfo->photoopt);

		$update=$jobinfo->update();

		if($update){
			if ($request->has('jobcategory')){
				$jobcategory = $request->get('jobcategory');
				$jobinfo->browsecategories()->sync($jobcategory);
			}
			$result['success'] = true;
			$result['message'] = 'Job information updated successfully';
			$result['next_req_url'] = url('/').'/jobpost/moreinfo/update/'.$id;
			return response()->json($result);
		}

		$result['success'] = false;
		$result['message'] = 'Job information could not be updated';		
		return response()->json($result);
	}

	public function postUpdateAddJobInfo(Request $request, $id){ 
		$validator = Validator::make($request->all(), [
			'level' => 'in:entry,mid,top',
			'location' => 'in:inbd,outbd',
			'salaryrange' => 'numeric',
			'salarytype' => 'in:hourly,daily,monthly,yearly',
			'negotiable' => 'numeric|between:0,1',
			'salaryreview' => 'in:halfyearly,yearly',
			'festivebonus' => 'numeric|between:0,4'

		]);

		if ($validator->fails()) {
			$result['success'] = false;
			$result['message'] = 'Invalid inputs';
			$result['error'] = $validator->errors();		
			return response()->json($result);
		}

		$addjobinfo = Addjobinfo::where('jobinfo_id', $id)->first();
		$addjobinfo->level=$request->get('level', $addjobinfo->level);
		$addjobinfo->context=$request->get('context', $addjobinfo->context);
		$addjobinfo->responsibilities=$request->get('responsibilities', $addjobinfo->responsibilities);		
		$addjobinfo->location=$request->get('location', $addjobinfo->location);
		$addjobinfo->area=$request->get('area', $addjobinfo->area);
		$addjobinfo->salaryrange=$request->get('salaryrange', $addjobinfo->salaryrange);
		$addjobinfo->salarytype=$request->get('salarytype', $addjobinfo->salarytype);
		$addjobinfo->negotiable=$request->get('negotiable', $addjobinfo->negotiable);
		$addjobinfo->addsalaryinfo=$request->get('addsalaryinfo', $addjobinfo->addsalaryinfo);
		$addjobinfo->salaryreview=$request->get('salaryreview', $addjobinfo->salaryreview);
		$addjobinfo->festivebonus=$request->get('festivebonus', $addjobinfo->festivebonus);
		$addjobinfo->others=$request->get('others', $addjobinfo->others);

		$update=$addjobinfo->update();

		if($update){
			if ($request->has('benefits')){
				$benefits = $request->get('benefits');
				$addjobinfo->benefits()->sync($benefits);
			}			
			$result['success'] = true;
			$result['message'] = 'Additional details for the post updated successfully';
			$result['next_req_url'] = url('/').'/jobpost/candidateinfo/update/'.$id;
			return response()->json($result);
		}

		$result['success'] = false;
		$result['message'] = 'Additional details for the post could not be updated';		
		return response()->json($result);
	}

	public function postUpdateCandidateInfo(Request $request, $id){ 
		$validator = Validator::make($request->all(), [
			'minage' => 'numeric',
			'maxage' => 'numeric',
			'fresher' => 'numeric|between:0,1',
			'gender' => 'in:male,female,both',
			'prefarmy' => 'numeric|between:0,1'
		]);

		if ($validator->fails()) {
			$result['success'] = false;
			$result['message'] = 'Invalid inputs';
			$result['error'] = $validator->errors();		
			return response()->json($result);
		}

		$candinfo = Candidaterequirement::where('jobinfo_id', $id)->first();
		$candinfo->degreelevel=$request->get('degreelevel', $candinfo->degreelevel);
		$candinfo->degreename=$request->get('degreename', $candinfo->degreename);
		$candinfo->degreemajor=$request->get('degreemajor', $candinfo->degreemajor);		
		$candinfo->prefeduinstitute=$request->get('prefeduinstitute', $candinfo->prefeduinstitute);
		$candinfo->othereduqualification=$request->get('othereduqualification', $candinfo->othereduqualification);
		$candinfo->course=$request->get('course', $candinfo->course);
		$candinfo->certification=$request->get('certification', $candinfo->certification);
		$candinfo->experience=$request->get('experience', $candinfo->experience);
		$candinfo->fresher=$request->get('fresher', $candinfo->fresher);
		$candinfo->areaofexp=$request->get('areaofexp', $candinfo->areaofexp);
		$candinfo->areaofbusiness=$request->get('areaofbusiness', $candinfo->areaofbusiness);
		$candinfo->skills=$request->get('skills', $candinfo->skills);
		$candinfo->addrequirements=$request->get('addrequirements', $candinfo->addrequirements);
		$candinfo->gender=$request->get('gender', $candinfo->gender);
		$candinfo->minage=$request->get('minage', $candinfo->minage);
		$candinfo->maxage=$request->get('maxage', $candinfo->maxage);
		$candinfo->prefarmy=$request->get('prefarmy', $candinfo->prefarmy);

		$update=$candinfo->update();

		if($update){
			$result['success'] = true;
			$result['message'] = 'Candidate requirements for the post updated successfully';
			$result['next_req_url'] = false;
			return response()->json($result);
		}

		$result['success'] = false;
		$result['message'] = 'Candidate requirements for the post could not be updated';		
		return response()->json($result);
	}

	public function postUpdateJobStatus($id){ 
		$jobinfo = Jobinfo::where('id', $id)->first();
		$jobinfo->status = 1;

		$update=$jobinfo->update();

		if($update){
			$result['success'] = true;
			$result['message'] = 'This job is now posted';
			return response()->json($result);
		}

		$result['success'] = false;
		$result['message'] = 'This job has problems';		
		return response()->json($result);
	}
}
