<?php
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::group(['namespace' => 'Api'], function () {

    Route::post('register', 'AuthController@register')->name('register');
    Route::post('login', 'AuthController@login')->name('login');
    Route::post('forgotPassword', 'AuthController@forgotPassword')->name('login');
    Route::post('verifyOtp', 'AuthController@verifyOtp')->name('verifyOtp');
    Route::post('resetPassword', 'AuthController@resetPassword')->name('resetPassword');
    Route::post('uploadFile', 'CommonController@uploadFile')->name('uploadImage');
    Route::post('updateDeviceInfo', 'AuthController@updateDeviceInfo')->name('updateDeviceInfo');

    // Regions,State,City,Taluka modules

    Route::post('getStates', 'StateCityController@getStates')->name('getStates');
    Route::post('getCities', 'StateCityController@getCities')->name('getCities');
    Route::post('getTalukas', 'StateCityController@getTalukas')->name('getTalukas');
    Route::post('getRegions', 'StateCityController@getRegions')->name('getRegions');

    // Chat Routes
    Route::post('users/list', 'UserController@getUsersList')->name('getUsersList');
    Route::post('chatUser', 'UserController@chatUser')->name('chatUser');

    //Get Term And Condition.
    Route::post('cmsPage', 'CmsPageController@cmsPage')->name('cmsPage');

    //Company Profile Modules
    Route::post('getCompanyProfile', 'CompanyProfileController@getCompanyProfile')->name('getCompanyProfile');

    //Brochures Modules
    Route::post('getBrochures', 'BrochuresController@getBrochures')->name('getBrochures');

    Route::group(['middleware' => ['auth:api', 'Activation']], function () {

        Route::post('getUserDetails', 'UserController@getUserDetails')->name('getUserDetails');
        Route::post('notificationOnOff', 'UserController@notificationOnOff')->name('notificationOnOff');
        Route::post('updateProfile', 'UserController@updateProfile')->name('updateProfile');
        //changePassword
        Route::post('changePassword', 'UserController@changePassword')->name('changePassword');
        Route::post('logout', 'AuthController@logout')->name('logout');

        // In Out Modules
        Route::post('getInOutStatus', 'InOutController@getInOutStatus')->name('getInOutStatus');
        Route::post('inOutUser', 'InOutController@InOutUser')->name('inOutUser');

        // Dealer Model
        Route::post('getDealerId', 'DealerController@getDealerId')->name('getDealerId');
        Route::post('addDealer', 'DealerController@addDealer')->name('addDealer');
        Route::post('deleteDealer', 'DealerController@deleteDealer')->name('deleteDealer');
        Route::post('getDealers', 'DealerController@getDealers')->name('getDealers');
        Route::post('dealerDetails', 'DealerController@dealerDetails')->name('dealerDetails');

        // Contact Us Query
        Route::post('getDepartments', 'ContactUsController@getDepartments')->name('getDepartments');
        Route::post('getHeadDepartments', 'ContactUsController@getHeadDepartments')->name('getHeadDepartments');
        Route::post('getOfficeAddress', 'ContactUsController@getOfficeAddress')->name('getOfficeAddress');
        Route::post('sendContactUsRequest', 'ContactUsController@sendContactUsRequest')->name('sendContactUsRequest');

        //Schedual Modules

        Route::post('createSchedule', 'ScheduleController@createSchedule')->name('createSchedule');
        Route::post('startVisit', 'ScheduleController@startVisit')->name('startVisit');
        Route::post('endVisit', 'ScheduleController@endVisit')->name('endVisit');
        Route::post('cancelSchedule', 'ScheduleController@cancelSchedule')->name('cancelSchedule');
        Route::post('getSchedules', 'ScheduleController@getSchedules')->name('getSchedules');
        Route::post('schedulesDetails', 'ScheduleController@schedulesDetails')->name('schedulesDetails');

         //DailyReportingModule Modules

         Route::post('getDailyUpdate', 'DRMController@getDailyUpdate')->name('getDailyUpdate');
         Route::post('reportDailyUpdate', 'DRMController@reportDailyUpdate')->name('reportDailyUpdate');
        //  Route::post('getDailyReportDetails', 'DRMController@getDailyReportDetails')->name('getDailyReportDetails');

        // Lead Modules

        Route::post('getMaterialType', 'LeadController@getMaterialType')->name('getMaterialType');
        Route::post('createLead', 'LeadController@createLead')->name('createLead');
        Route::post('getLeads', 'LeadController@getLeads')->name('getLeads');
        Route::post('moveLead', 'LeadController@moveLead')->name('moveLead');
        Route::post('leadDetails', 'LeadController@leadDetails')->name('leadDetails');

        // Knowledge Modules

        Route::post('createKnowledge', 'KnowledgeController@createKnowledge')->name('createKnowledge');
        Route::post('getKnowledges', 'KnowledgeController@getKnowledges')->name('getKnowledges');
        Route::post('knowledgeDetails', 'KnowledgeController@knowledgeDetails')->name('knowledgeDetails');

        // Complaint Modules

        Route::post('createComplaint', 'ComplaintController@createComplaint')->name('createComplaint');
        Route::post('getComplains', 'ComplaintController@getComplains')->name('getComplains');
        Route::post('complainDetails', 'ComplaintController@complainDetails')->name('complainDetails');

        // HR Modules

        Route::post('getHolidays', 'HrController@getHolidays')->name('getHolidays');
        Route::post('getPaySlips', 'HrController@getPaySlips')->name('getPaySlips');
        Route::post('getExpenseTypes', 'HrController@getExpenseTypes')->name('getExpenseTypes');
        Route::post('addReimbursement', 'HrController@addReimbursement')->name('addReimbursement');
        Route::post('getReimbursements', 'HrController@getReimbursements')->name('getReimbursements');
        Route::post('reimbursementDetails', 'HrController@reimbursementDetails')->name('reimbursementDetails');
        Route::post('applyLeave', 'HrController@applyLeave')->name('applyLeave');
        Route::post('getLeaves', 'HrController@getLeaves')->name('getLeaves');
        Route::post('applyPaySleep', 'HrController@applyPaySleep')->name('applyPaySleep');

        // Voice Recording Modules

        Route::post('getVoiceRecording', 'VoiceRecordingController@getVoiceRecording')->name('getVoiceRecording');
        Route::post('addVoiceRecording', 'VoiceRecordingController@addVoiceRecording')->name('addVoiceRecording');
        Route::post('deleteVoiceRecording', 'VoiceRecordingController@deleteVoiceRecording')->name('deleteVoiceRecording');

         // Follow Up Modules

         Route::post('getFollowUps', 'FollowUpController@getFollowUps')->name('getFollowUps');
         Route::post('addFollowUp', 'FollowUpController@addFollowUp')->name('addFollowUp');
         Route::post('followUpDetails', 'FollowUpController@followUpDetails')->name('followUpDetails');
         Route::post('followUpDelete', 'FollowUpController@followUpDelete')->name('followUpDelete');
         Route::post('isFollowUpDone', 'FollowUpController@isFollowUpDone')->name('isFollowUpDone');

         // Merchandises Modules

         Route::post('getProducts', 'MerchandisesController@getProducts')->name('getProducts');
         Route::post('addMerchandises', 'MerchandisesController@addMerchandises')->name('addMerchandises');
         Route::post('getMerchandises', 'MerchandisesController@getMerchandises')->name('getMerchandises');
         Route::post('merchandisesDetails', 'MerchandisesController@merchandisesDetails')->name('merchandisesDetails');

         // notification list

        Route::post('getNotificationList', 'NotificationController@getNotificationList')->name('getNotificationList');

        // Report  Modules
        Route::post('merchandisesReport', 'ReportController@merchandisesReport')->name('merchandisesReport');
        Route::post('leadReport', 'ReportController@leadReport')->name('leadReport');
        Route::post('dailyUpdateReport', 'ReportController@dailyUpdateReport')->name('dailyUpdateReport');

        // Track Modules Api

        Route::post('trackLocation', 'TrackController@trackLocation')->name('trackLocation');
        Route::post('trackLocationEvent', 'TrackController@trackLocationEvent')->name('trackLocationEvent');

    });
});
