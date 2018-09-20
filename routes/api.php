<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});

Route::post('login', 'API\LogregController@login');
Route::post('register', 'API\LogregController@register');

Route::group(['middleware' => 'auth:api'], function(){

	Route::get('get-details', 'API\LogregController@getDetails');
	Route::post('user/companydetails', 'API\EmployerController@postCompanyDetails');
	Route::post('user/companydetails/update', 'API\EmployerController@postUpdateCompanyDetails');
	Route::post('user/contactdetails', 'API\EmployerController@postCompanyContactDetails');
	Route::post('user/contactdetails/update', 'API\EmployerController@postUpdateCompanyContactDetails');

	Route::post('jobpost', 'API\JobController@postJobInfo');
	Route::post('jobpost/update/{id}', 'API\JobController@postUpdateJobInfo')->where('id', '[0-9]+');
	Route::post('jobpost/moreinfo/{id}', 'API\JobController@postAddJobInfo')->where('id', '[0-9]+');
	Route::post('jobpost/moreinfo/update/{id}', 'API\JobController@postUpdateAddJobInfo')->where('id', '[0-9]+');
	Route::post('jobpost/candidateinfo/{id}', 'API\JobController@postCandidateInfo')->where('id', '[0-9]+');
	Route::post('jobpost/candidateinfo/update/{id}', 'API\JobController@postUpdateCandidateInfo')->where('id', '[0-9]+');
	Route::post('jobpost/status/{id}', 'API\JobController@postUpdateJobStatus')->where('id', '[0-9]+');

	Route::get('user', 'API\DashboardController@getUser');
	Route::get('user/getdrafts', 'API\DashboardController@getDrafts');
	Route::get('user/getposts', 'API\DashboardController@getPosts');
	Route::get('user/getarchives', 'API\DashboardController@getArchives');
});