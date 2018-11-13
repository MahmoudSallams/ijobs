<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

$optionalLanguageRoutes = function()
{
	Route::resource('pages', 'PageAPIController');
	
	Route::resource('profiles', 'ProfileAPIController');
	
	Route::resource('jobs', 'JobAPIController');
	
	Route::resource('groups', 'GroupAPIController');
	
	//Route::resource('contacts', 'ContactAPIController');
	
	Route::resource('companies', 'CompanyAPIController');
	
	Route::resource('user_companies', 'UserCompanyAPIController');
	
	Route::resource('group_users', 'GroupUserAPIController');
	
	Route::resource('group_jobs', 'GroupJobAPIController');
	
	Route::resource('job_users', 'JobUserAPIController');
	
	
	
	
	
	Route::post('register', 'ProfileAPIController@register');
	Route::post('editProfile', 'ProfileAPIController@editProfile');
	Route::post('login', 'ProfileAPIController@login');
	Route::post('inviteContact', 'ProfileAPIController@inviteContact');
	Route::post('syncContacts', 'ProfileAPIController@syncContacts');
	Route::post('changeToken', 'ProfileAPIController@changeToken');
	Route::post('updateWorkExperience', 'ProfileAPIController@updateWorkExperience');
	
	Route::get('verifyMobile', 'ProfileAPIController@verifyMobile');
	Route::get('verifyMobileRequest', 'ProfileAPIController@verifyMobileRequest');
	Route::get('login', 'ProfileAPIController@login');
	Route::get('changeMobile', 'ProfileAPIController@changeMobile');
	Route::get('changeStatus', 'ProfileAPIController@changeStatus');
	Route::get('getStatusHistory', 'ProfileAPIController@getStatusHistory');
	Route::get('getNewStatusContacts', 'ProfileAPIController@getNewStatusContacts');
	Route::get('friendsDetails', 'ProfileAPIController@friendsDetails');
	Route::get('friendsGroups', 'ProfileAPIController@friendsGroups');
	Route::get('listAppliedJobs', 'ProfileAPIController@listAppliedJobs');
	Route::get('listForwardedJobs', 'ProfileAPIController@listForwardedJobs');
	Route::get('showProfile/{d1}/{d2}', 'ProfileAPIController@showProfile');
	Route::get('friendsDetailsBystatus/{d1}', 'ProfileAPIController@friendsDetailsBystatus');
	Route::get('Applyforjob/{d1}', 'ProfileAPIController@Applyforjob');
	Route::get('contacts/counts', 'ProfileAPIController@getCounts');
	Route::get('user/notifications', 'ProfileAPIController@getNotifications');
	//Route::get('getProfileBymobile', 'ProfileAPIController@getProfileBymobile');
	
	
	
	
	Route::get('myPublishedJobs', 'JobAPIController@myPublishedJobs');
	Route::get('deactivatejob/{d1}', 'JobAPIController@deactivatejob');
	Route::get('removeJobFromGroup/{d1}/{d2}', 'JobAPIController@removeJobFromGroup');
	Route::get('searchJob', 'JobAPIController@search');
	Route::get('forwardJob/{d1}/{d2}', 'JobAPIController@forwardJob');
	Route::get('hideJob/{d1}', 'JobAPIController@hideJob');
	Route::get('job/counts', 'JobAPIController@getCounts');
	Route::get('repostjob/{d1}', 'JobAPIController@repostjob');
	Route::get('listAppliers/{d1}', 'JobAPIController@listAppliers');
	
	
	
	
	Route::post('addMemberToGroup/{d1}', 'GroupAPIController@addMemberToGroup');
	
	Route::get('approveJoinRequest/{d1}/{d2}', 'GroupAPIController@approveJoinRequest');
	Route::get('rejectJoinRequest/{d1}/{d2}', 'GroupAPIController@rejectJoinRequest');
	Route::get('blockMember/{d1}/{d2}', 'GroupAPIController@blockMember');
	Route::get('reinviteMember/{d1}/{d2}', 'GroupAPIController@reinviteMember');
	Route::get('leaveGroup/{d}', 'GroupAPIController@leaveGroup');
	Route::get('requestJoinGroup/{d1}', 'GroupAPIController@requestJoinGroup');
	Route::get('deactivategroup/{d1}', 'GroupAPIController@deactivategroup');
	Route::get('searchGroup', 'GroupAPIController@search');
	Route::get('getJoinRequests/{d}', 'GroupAPIController@getJoinRequests');
	Route::get('addSubAdmin/{d1}/{d2}', 'GroupAPIController@addSubAdmin');
	
	Route::get('static/about', 'PageAPIController@getAboutUs');
	Route::get('static/privacy', 'PageAPIController@getPrivacyPolicy');
	Route::get('static/faq', 'PageAPIController@getFAQ');
	
	
	Route::get('updateByjob/{d1}', 'ContactAPIController@updateByjob');
};

// Add routes with lang-prefix
Route::group([
		'prefix' => '/{lang}/', 
		'where' => ['lang' => 'ar|en']	
	],
	$optionalLanguageRoutes
);

// Add routes without prefix
$optionalLanguageRoutes();

Route::group(['middleware' => 'jwt.auth'], function () {
   
	
});




