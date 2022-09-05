<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

  // Clear application cache:
Route::get('/cache-clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
   return 'Application cache cleared';
});


// Auth::routes();

Route::post('check/unique/{tableName}/{columnName}/{id?}', 'Controller@checkUnique')->name('UniqueCheck');
Route::post('notificationCount', 'Controller@notificationCount')->name('notificationCount');
Route::get('/brochures/{any?}', 'Controller@brochuresDownloadPdf');
Route::get('/pay-slip/{any?}', 'Controller@paySlipDownloadPdf');

// Api Report modules

Route::get('merchandises-pdf/{userId}/{startDate}/{endDate}/{type}/{mType}/{stateId}/{cityId}/{talukaId}/{search}', 'Controller@merchandisesPdf');
Route::get('leads-pdf/{userId}/{startDate}/{endDate}/{type}/{lType}/{stateId}/{cityId}/{talukaId}/{search}', 'Controller@leadPdf');
Route::get('daily-pdf/{userId}/{startDate}/{endDate}/{type}/{sType}/{stateId}/{cityId}/{talukaId}/{search}', 'Controller@dailyPdf');

Route::post('notificationCount', 'Controller@notificationCount')->name('notificationCount');

Route::prefix('marketing-admin')->group(function () {

    Route::get('/', 'Auth\MALoginController@showLoginForm');
    Route::get('login', 'Auth\MALoginController@showLoginForm')->name('maLogin');
    Route::post('login', ['uses' => 'Auth\MALoginController@login']);

    // Forgot password

    Route::get('forgot/password', 'Auth\MAForgotPasswordController@index')->name('MAForgotPassword');
    Route::post('forgot/password', 'Auth\MAForgotPasswordController@checkUserIsAdmin')->name('MAForgotPassword');

    // Reset password

    Route::post('password/reset', 'Auth\MAResetPasswordController@changeAdminPassword')->name('ma.password.reset_process');
    Route::get('password/reset/{email}', 'Auth\MAResetPasswordController@showResetForm')->name('ma.password.reset');

    Route::middleware(['auth:marketingAdmin'])->group(function () {
        Route::get('logout', 'Auth\MALoginController@logout')->name('maLogout');
    });
});


Route::prefix('hr')->group(function () {

    Route::get('/', 'Auth\HRLoginController@showLoginForm');
    Route::get('login', 'Auth\HRLoginController@showLoginForm')->name('hrLogin');
    Route::post('login', ['uses' => 'Auth\HRLoginController@login']);

    //Forgot password
    Route::get('forgot/password', 'Auth\HRForgotPasswordController@index')->name('HRForgotPassword');
    Route::post('forgot/password', 'Auth\HRForgotPasswordController@checkUserIsAdmin')->name('HRForgotPassword');

    // Reset password
    Route::get('password/reset/{email}', 'Auth\HRResetPasswordController@showResetForm')->name('hr.password.reset');
    Route::post('password/reset', 'Auth\HRResetPasswordController@changeAdminPassword')->name('hr.password.reset_process');


    Route::middleware(['auth:hr'])->group(function () {
        Route::get('logout', 'Auth\HRLoginController@logout')->name('hrLogout');
    });
});

Route::prefix('common')->group(function () {

    Route::middleware(['auth:admin,hr,marketingAdmin'])->group(function () {

        Route::get('/dashboard/{date?}', 'Admin\AdminDashboardController@index')->name('AdminDashboard');
        Route::post('dealerBirthDateDashboard', 'Admin\AdminDashboardController@dealerBirthDateDashboard')->name('dealerBirthDateDashboard');
        Route::post('merchaindiesTypeDashboard', 'Admin\AdminDashboardController@merchaindiesTypeDashboard')->name('merchaindiesTypeDashboard');
        Route::post('userPerformanceDashboard', 'Admin\AdminDashboardController@userPerformanceDashboard')->name('userPerformanceDashboard');


        Route::get('chat-page', 'Admin\AdminChatController@index');


        Route::get('/chat/{otherUserId?}', 'Admin\AdminDashboardController@chat')->name('chat');

        // Edit profile
        Route::get('profile', 'Admin\AdminProfileController@showProfile')->name('EditAdminProfile');
        Route::post('profile', 'Admin\AdminProfileController@updateProfile')->name('UpdateAdminProfile');

        // Delete Admin Profile
        Route::post('profile/image/delete', 'Admin\AdminProfileController@profileImageDelete')->name('AdminProfileImageDelete');

        // Change Password
        Route::get('change/password', 'Admin\AdminProfileController@editAdminChangePassword')->name('EditAdminChangePassword');
        Route::post('change/password', 'Admin\AdminProfileController@updateAdminChangePassword')->name('UpdateAdminChangePassword');

        // Material-Products
        Route::resource('material-products', 'Admin\AdminMaterialProductController');
        Route::post('material-products/search', 'Admin\AdminMaterialProductController@search')->name('material-products.search');
        Route::post('material-products/status/{material_product}', 'Admin\AdminMaterialProductController@changeStatus')->name('material-products.status');

        // Brands-Products
        Route::resource('brands', 'Admin\AdminBrandController');
        Route::post('brands/search', 'Admin\AdminBrandController@search')->name('brands.search');
        Route::post('brands/status/{brand}', 'Admin\AdminBrandController@changeStatus')->name('brands.status');

         // HR - Holidays
        Route::resource('holidays', 'Admin\AdminHolidayController');
        Route::post('holidays/search', 'Admin\AdminHolidayController@search')->name('holidays.search');
        Route::post('holidays/status/{holiday}', 'Admin\AdminHolidayController@changeStatus')->name('holidays.status');
        Route::get('holidays/create', 'Admin\AdminHolidayController@create')->name('holidays.create');
        Route::get('holidays/{holiday}', 'Admin\AdminHolidayController@show')->name('holidays.show');
        Route::post('importExcel', 'Admin\AdminHolidayController@importExcel')->name('importExcel');

        // HR - Leaves
        Route::post('leaves/search', 'Admin\AdminLeavesController@search')->name('leaves.search');
        Route::get('leaves/show/{leaves}', 'Admin\AdminLeavesController@show')->name('leaves.show');
        Route::post('leaves/changeLeaveStatus/{leaves}', 'Admin\AdminLeavesController@changeLeaveStatus')->name('leaves.leaveStatus');
        Route::delete('leaves/destroy/{leaves}', 'Admin\AdminLeavesController@destroy')->name('leaves.destroy');
        Route::get('leaves', 'Admin\AdminLeavesController@index')->name('leaves.index');


        Route::get('request-pay-slip', 'Admin\AdminLeavesController@requestPaySlip')->name('requestPaySlip');
        Route::post('request-pay-slip/search', 'Admin\AdminLeavesController@requestPaySlipSearch')->name('requestPaySlipSearch');
        Route::post('request-pay-slip/changeSlipStatus', 'Admin\AdminLeavesController@changeSlipStatus')->name('changeSlipStatus');

        // HR - Reimbursements
        Route::post('reimbursements/search', 'Admin\AdminReimbursementsController@search')->name('reimbursements.search');
        Route::get('reimbursements/show/{reimbursements}', 'Admin\AdminReimbursementsController@show')->name('reimbursements.show');
        Route::post('reimbursements/changeReimbursementsStatus/{reimbursements}', 'Admin\AdminReimbursementsController@changeReimbursementsStatus')->name('reimbursements.reimbursementsStatus');
        Route::delete('reimbursements/destroy/{reimbursements}', 'Admin\AdminReimbursementsController@destroy')->name('reimbursements.destroy');
        Route::get('reimbursements', 'Admin\AdminReimbursementsController@index')->name('reimbursements.index');

         // Expense-Type-Reimbursements

         Route::resource('expense-type-reimbursements', 'Admin\AdminExpenseTypeReimbursementController');
         Route::post('expense-type-reimbursements/search', 'Admin\AdminExpenseTypeReimbursementController@search')->name('expense-type-reimbursements.search');
         Route::post('expense-type-reimbursements/status/{expense_type_reimbursement}', 'Admin\AdminExpenseTypeReimbursementController@changeStatus')->name('expense-type-reimbursements.status');

        // Page contents List
        Route::resource('page-contents', 'Admin\AdminPageContentController');
        Route::post('page-contents/search', 'Admin\AdminPageContentController@search')->name('page-contents.search');
        Route::get('page-contents/show/{page_content}', 'Admin\AdminPageContentController@show')->name('page-contents.show');

        // Admin User Controller
        Route::post('users/search', 'Admin\AdminUserController@search')->name('users.search');
        Route::post('users/searchLeave', 'Admin\AdminUserController@searchLeave')->name('users.searchLeave');
        Route::post('users/pdfSearch', 'Admin\AdminUserController@pdfSearch')->name('users.pdfSearch');
        Route::delete('users/deletePdf/{users}', 'Admin\AdminUserController@deletePdf')->name('users.deletePdf');

        Route::get('users/create', 'Admin\AdminUserController@create')->name('users.create');
        Route::get('users/editSalesMarketingUser/{id?}', 'Admin\AdminUserController@editSalesMarketingUser')->name('users.editSalesMarketingUser');
        Route::post('users/insertUpdateSalesMarketingUser', 'Admin\AdminUserController@insertUpdateSalesMarketingUser')->name('users.insertUpdateSalesMarketingUser');

        Route::get('users/edit/{users}', 'Admin\AdminUserController@edit')->name('users.edit');
        Route::post('users/update/{users}', 'Admin\AdminUserController@update')->name('users.update');
        // Route::get('users/show/{users}', 'Admin\AdminUserController@show')->name('users.show');
        Route::get('users/show/{parameter}/{users}', 'Admin\AdminUserController@show')->name('users.show');
        Route::post('users/status/{users}', 'Admin\AdminUserController@changeStatus')->name('users.status');
        Route::post('users/changeUserStatus/{users}', 'Admin\AdminUserController@changeUserStatus')->name('users.userStatus');
        Route::delete('users/destroy/{users}', 'Admin\AdminUserController@destroy')->name('users.destroy');
        Route::get('users/{parameter?}', 'Admin\AdminUserController@index')->name('users.index');
        Route::get('users/create/{parameter?}', 'Admin\AdminUserController@createDiffUser')->name('users.create.param');
        Route::get('users/diff_user_edit/{parameter?}/{id?}', 'Admin\AdminUserController@editDiffUser')->name('users.edit.param');
        Route::post('users/diff_user_create_update', 'Admin\AdminUserController@diffUserInsertUpdate')->name('users.diff_insert_update');

        Route::delete('delete-image/{id}/{type}', 'Admin\AdminUserController@deleteImage')->name('deleteImage');

        // Manage Dealer

        Route::post('dealers/search', 'Admin\AdminDealerController@search')->name('dealers.search');

        Route::get('dealers/edit/{dealers}', 'Admin\AdminDealerController@edit')->name('dealers.edit');
        Route::post('dealers/store', 'Admin\AdminDealerController@store')->name('dealers.store');
        Route::post('dealers/update/{dealers}', 'Admin\AdminDealerController@update')->name('dealers.update');
        Route::get('dealers/show/{dealers}', 'Admin\AdminDealerController@show')->name('dealers.show');
        Route::post('dealers/status/{dealers}', 'Admin\AdminDealerController@changeStatus')->name('dealers.status');
        Route::post('dealers/changeUserStatus/{dealers}', 'Admin\AdminDealerController@changeUserStatus')->name('dealers.userStatus');
        Route::delete('dealers/destroy/{dealers}', 'Admin\AdminDealerController@destroy')->name('dealers.destroy');
        Route::get('dealers/create/{type?}', 'Admin\AdminDealerController@create')->name('dealers.create');
        Route::get('dealers/{type?}', 'Admin\AdminDealerController@index')->name('dealers.index');

        // Schedules
        Route::resource('schedules', 'Admin\AdminScheduleController');
        Route::post('schedules/search', 'Admin\AdminScheduleController@search')->name('schedules.search');
        Route::post('schedules/status/{schedule}', 'Admin\AdminScheduleController@changeStatus')->name('schedules.status');
        Route::get('schedules/create', 'Admin\AdminScheduleController@create')->name('schedules.create');
        Route::get('schedules/{schedule}', 'Admin\AdminScheduleController@show')->name('schedules.show');
        Route::post('getDealerDetail', 'Admin\AdminScheduleController@getDealerDetail')->name('getDealerDetail');
        Route::post('getDealerByType', 'Admin\AdminScheduleController@getDealerByType')->name('getDealerByType');
        Route::get('schedules-map', 'Admin\AdminScheduleController@scheduleMap')->name('schedules.map');

        // Manage Daily Update
        Route::resource('dailyUpdates', 'Admin\AdminDailyUpdateController');
        Route::post('dailyUpdates/search', 'Admin\AdminDailyUpdateController@search')->name('dailyUpdates.search');
        Route::post('dailyUpdates/status/{dailyUpdate}', 'Admin\AdminDailyUpdateController@changeStatus')->name('dailyUpdates.status');
        Route::get('dailyUpdates/create', 'Admin\AdminDailyUpdateController@create')->name('dailyUpdates.create');
        Route::get('dailyUpdates/{dailyUpdate}', 'Admin\AdminDailyUpdateController@show')->name('dailyUpdates.show');

        // Knowledge Center
        Route::post('knowledge/search', 'Admin\AdminKnowledgeController@search')->name('knowledge.search');
        Route::get('knowledge/create', 'Admin\AdminKnowledgeController@create')->name('knowledge.create');
        Route::get('knowledge/edit/{knowledge}', 'Admin\AdminKnowledgeController@edit')->name('knowledge.edit');
        Route::post('knowledge/update/{knowledge}', 'Admin\AdminKnowledgeController@update')->name('knowledge.update');
        Route::get('knowledge/show/{knowledge}', 'Admin\AdminKnowledgeController@show')->name('knowledge.show');
        Route::post('knowledge/status/{knowledge}', 'Admin\AdminKnowledgeController@changeStatus')->name('knowledge.status');
        Route::post('knowledge/changeUserStatus/{knowledge}', 'Admin\AdminKnowledgeController@changeUserStatus')->name('knowledge.userStatus');
        Route::delete('knowledge/destroy/{knowledge}', 'Admin\AdminKnowledgeController@destroy')->name('knowledge.destroy');
        Route::get('knowledge', 'Admin\AdminKnowledgeController@index')->name('knowledge.index');

        // Manage Leads
        Route::get('leads', 'Admin\AdminLeadController@index')->name('leads.index');
        Route::post('leads/search', 'Admin\AdminLeadController@search')->name('leads.search');
        Route::get('leads/create', 'Admin\AdminLeadController@create')->name('leads.create');
        Route::get('leads/edit/{leads}', 'Admin\AdminLeadController@edit')->name('leads.edit');
        Route::post('leads/update/{leads}', 'Admin\AdminLeadController@update')->name('leads.update');
        Route::get('leads/show/{leads}', 'Admin\AdminLeadController@show')->name('leads.show');
        Route::post('leads/status/{leads}', 'Admin\AdminLeadController@changeStatus')->name('leads.status');
        Route::post('leads/changeUserStatus/{leads}', 'Admin\AdminLeadController@changeUserStatus')->name('leads.userStatus');
        Route::delete('leads/destroy/{leads}', 'Admin\AdminLeadController@destroy')->name('leads.destroy');

        // Manage In/Out
        Route::get('inOuts', 'Admin\AdminInOutController@index')->name('inOuts.index');
        Route::post('inOuts/search', 'Admin\AdminInOutController@search')->name('inOuts.search');
        Route::get('inOuts/show/{inOuts}', 'Admin\AdminInOutController@show')->name('inOuts.show');
        Route::delete('inOuts/destroy/{inOuts}', 'Admin\AdminInOutController@destroy')->name('inOuts.destroy');
        Route::get('in-out-map/{userId}/{date}', 'Admin\AdminInOutController@inOutMap')->name('inOutMap');

        // Manage Voice Recording
        Route::get('voiceRecordings', 'Admin\AdminVoiceRecordingController@index')->name('voiceRecordings.index');
        Route::post('voiceRecordings/search', 'Admin\AdminVoiceRecordingController@search')->name('voiceRecordings.search');
        Route::post('voiceRecordings/status/{voiceRecordings}', 'Admin\AdminVoiceRecordingController@changeStatus')->name('voiceRecordings.status');
        Route::delete('voiceRecordings/destroy/{voiceRecordings}', 'Admin\AdminVoiceRecordingController@destroy')->name('voiceRecordings.destroy');

        // Manage Complaints
        Route::post('complaints/search', 'Admin\AdminComplaintController@search')->name('complaints.search');
        Route::get('complaints/create', 'Admin\AdminComplaintController@create')->name('complaints.create');
        Route::get('complaints/edit/{knowledge}', 'Admin\AdminComplaintController@edit')->name('complaints.edit');
        Route::post('complaints/update/{complaints}', 'Admin\AdminComplaintController@update')->name('complaints.update');
        Route::get('complaints/show/{complaints}', 'Admin\AdminComplaintController@show')->name('complaints.show');
        Route::post('complaints/status/{complaints}', 'Admin\AdminComplaintController@changeStatus')->name('complaints.status');
        Route::post('complaints/changeUserStatus/{complaints}', 'Admin\AdminComplaintController@changeUserStatus')->name('complaints.userStatus');
        Route::delete('complaints/destroy/{complaints}', 'Admin\AdminComplaintController@destroy')->name('complaints.destroy');
        Route::get('complaints', 'Admin\AdminComplaintController@index')->name('complaints.index');

        // Contact Us
        Route::post('contactUs/search', 'Admin\AdminContactUsController@search')->name('contactUs.search');
        Route::get('contactUs/show/{contactUs}', 'Admin\AdminContactUsController@show')->name('contactUs.show');
        Route::delete('contactUs/destroy/{contactUs}', 'Admin\AdminContactUsController@destroy')->name('contactUs.destroy');
        Route::get('contactUs', 'Admin\AdminContactUsController@index')->name('contactUs.index');

        // Products
        Route::resource('products', 'Admin\AdminProductsController');
        Route::post('products/search', 'Admin\AdminProductsController@search')->name('products.search');
        Route::post('products/status/{product}', 'Admin\AdminProductsController@changeStatus')->name('products.status');
        Route::get('products/create', 'Admin\AdminProductsController@create')->name('products.create');
        Route::get('products/{product}', 'Admin\AdminProductsController@show')->name('products.show');

        // Products-gift

        Route::resource('products-gift', 'Admin\AdminProductGiftController');
        Route::post('products-gift/search', 'Admin\AdminProductGiftController@search')->name('products-gift.search');
        Route::post('products-gift/status/{products_gift}', 'Admin\AdminProductGiftController@changeStatus')->name('products-gift.status');
        Route::get('products-gift/create', 'Admin\AdminProductGiftController@create')->name('products-gift.create');
        Route::get('products-gift/{products_gift}', 'Admin\AdminProductGiftController@show')->name('products-gift.show');

        Route::get('products-gift-allocation-user', 'Admin\AdminProductGiftController@productGiftAllocationUser')->name('products-gift.allocation_user');
        Route::post('products-gift-allocation-user-store', 'Admin\AdminProductGiftController@productGiftAllocationUserStore')->name('products-gift.allocation_user.store');

        // Merchandise
        Route::post('merchandise/search', 'Admin\AdminMerchandiseController@search')->name('merchandise.search');
        Route::get('merchandise/create/{type}', 'Admin\AdminMerchandiseController@create')->name('merchandise.create');
        Route::post('merchandise/store', 'Admin\AdminMerchandiseController@store')->name('merchandise.store');
        Route::get('merchandise/edit/{merchandise}', 'Admin\AdminMerchandiseController@edit')->name('merchandise.edit');
        Route::get('merchandise/show/{merchandise}/{type?}', 'Admin\AdminMerchandiseController@show')->name('merchandise.show');
        Route::post('merchandise/status/{merchandise}', 'Admin\AdminMerchandiseController@changeStatus')->name('merchandise.status');
        Route::post('merchandise/changeUserStatus/{merchandise}', 'Admin\AdminMerchandiseController@changeUserStatus')->name('merchandise.userStatus');
        Route::delete('merchandise/destroy/{merchandise}', 'Admin\AdminMerchandiseController@destroy')->name('merchandise.destroy');
        Route::get('merchandise/{type}', 'Admin\AdminMerchandiseController@index')->name('merchandise.index');

        // Products
        Route::resource('followups', 'Admin\AdminFollowUpController');
        Route::post('followups/search', 'Admin\AdminFollowUpController@search')->name('followups.search');


        Route::resource('departments', 'Admin\AdminDepartmentsController');
        Route::post('departments/search', 'Admin\AdminDepartmentsController@search')->name('departments.search');
        Route::post('departments/status/{department}', 'Admin\AdminDepartmentsController@changeStatus')->name('departments.status');
        Route::get('departments/{department}', 'Admin\AdminDepartmentsController@show')->name('departments.show');


        // Brochures
        Route::resource('brochures', 'Admin\AdminBrochuresController');
        Route::post('brochures/search', 'Admin\AdminBrochuresController@search')->name('brochures.search');
        Route::post('brochures/status/{brochure}', 'Admin\AdminBrochuresController@changeStatus')->name('brochures.status');

        // Company Profiles
        Route::resource('companyProfiles', 'Admin\AdminCompanyProfileController');
        Route::post('companyProfiles/search', 'Admin\AdminCompanyProfileController@search')->name('companyProfiles.search');
        Route::post('companyProfiles/status/{companyProfile}', 'Admin\AdminCompanyProfileController@changeStatus')->name('companyProfiles.status');
        Route::get('companyProfiles/{companyProfile}', 'Admin\AdminCompanyProfileController@show')->name('companyProfiles.show');

        // Sliders
        Route::resource('sliders', 'Admin\AdminSlidersController');
        Route::post('sliders/search', 'Admin\AdminSlidersController@search')->name('sliders.search');
        Route::post('sliders/status/{slider}', 'Admin\AdminSlidersController@changeStatus')->name('sliders.status');

        // Notification

        Route::resource('notifications', 'Admin\AdminNotificationController');
        Route::post('notifications/search', 'Admin\AdminNotificationController@search')->name('notifications.search');



          // State (State, City, Talukas, Regions)
          Route::resource('states', 'Admin\AdminStateController');
          Route::post('states/search', 'Admin\AdminStateController@search')->name('states.search');
          Route::post('states/status/{state}', 'Admin\AdminStateController@changeStatus')->name('states.status');
          Route::get('states/create', 'Admin\AdminStateController@create')->name('states.create');
          Route::get('states/{state}', 'Admin\AdminStateController@show')->name('states.show');

          // City
          Route::resource('cities', 'Admin\AdminCitiesController');
          Route::post('cities/search', 'Admin\AdminCitiesController@search')->name('cities.search');
          Route::post('cities/status/{city}', 'Admin\AdminCitiesController@changeStatus')->name('cities.status');
          Route::get('cities/create', 'Admin\AdminCitiesController@create')->name('cities.create');
          Route::get('cities/{city}', 'Admin\AdminCitiesController@show')->name('cities.show');

          // Talukas
          Route::resource('talukas', 'Admin\AdminTalukaController');
          Route::post('talukas/search', 'Admin\AdminTalukaController@search')->name('talukas.search');
          Route::post('talukas/status/{taluka}', 'Admin\AdminTalukaController@changeStatus')->name('talukas.status');
          Route::get('talukas/create', 'Admin\AdminTalukaController@create')->name('talukas.create');
          Route::get('talukas/{taluka}', 'Admin\AdminTalukaController@show')->name('talukas.show');
          Route::post('get-city', 'Admin\AdminTalukaController@getCity')->name('get-city');
          Route::post('get-taluka', 'Admin\AdminTalukaController@getTaluka')->name('get-taluka');

          // Regions
          Route::resource('regions', 'Admin\AdminRegionController');
          Route::post('regions/search', 'Admin\AdminRegionController@search')->name('regions.search');
          Route::post('regions/status/{region}', 'Admin\AdminRegionController@changeStatus')->name('regions.status');
          Route::get('regions/create', 'Admin\AdminRegionController@create')->name('regions.create');
          Route::get('regions/{region}', 'Admin\AdminRegionController@show')->name('regions.show');


        // Reports
        Route::prefix('reports')->group(function () {

            // User Reports
            Route::get('report-users', 'Admin\AdminReportController@userReportView')->name('admin.userReport');
            Route::post('report-users', 'Admin\AdminReportController@userReport')->name('admin.userReport');

            // Complaint Reports
            Route::get('report-complaints', 'Admin\AdminReportController@complaintReportView')->name('admin.complaintReport');
            Route::post('report-complaints', 'Admin\AdminReportController@complaintReport')->name('admin.complaintReport');

            // Visit Reports
            Route::get('report-visits', 'Admin\AdminReportController@visitReportView')->name('admin.visitReport');
            Route::post('report-visits', 'Admin\AdminReportController@visitReport')->name('admin.visitReport');

            // Merchandise Gift Reports
            Route::get('report-merchandises', 'Admin\AdminReportController@merchandiseReportView')->name('admin.merchandiseReport');
            Route::post('report-merchandises', 'Admin\AdminReportController@merchandiseReport')->name('admin.merchandiseReport');

            // Merchandise Order Reports
            Route::get('report-merchandise-orders', 'Admin\AdminReportController@merchandiseOrderReportView')->name('admin.merchandiseOrderReport');
            Route::post('report-merchandise-orders', 'Admin\AdminReportController@merchandiseOrderReport')->name('admin.merchandiseOrderReport');

            // Knowledge Reports
            Route::get('report-knowledges', 'Admin\AdminReportController@knowledgeReportView')->name('admin.knowledgeReport');
            Route::post('report-knowledges', 'Admin\AdminReportController@knowledgeReport')->name('admin.knowledgeReport');

            // Dealer Reports
            Route::get('report-dealers', 'Admin\AdminReportController@dealerReportView')->name('admin.dealerReport');
            Route::post('report-dealers', 'Admin\AdminReportController@dealerReport')->name('admin.dealerReport');

            Route::get('report-other-actors', 'Admin\AdminReportController@otherActorReportView')->name('admin.otherActorReport');
            Route::post('report-other-actors', 'Admin\AdminReportController@otherActorReport')->name('admin.otherActorReport');

            // Reimbursement Reports
            Route::get('report-reimbursements/{type?}', 'Admin\AdminReportController@reimbursementReportView')->name('admin.reimbursementReport');
            Route::post('report-reimbursements', 'Admin\AdminReportController@reimbursementReport')->name('admin.reimbursementReport1');


            // Birthday Reports

            // Route::get('report-birthday', 'Admin\AdminReportController@reimbursementReportView')->name('admin.birthReport');
            // Route::get('report-incentive', 'Admin\AdminReportController@reimbursementReportView')->name('admin.incentiveReport');

            // Leave Reports
            Route::get('report-leaves', 'Admin\AdminReportController@leaveReportView')->name('admin.leaveReport');
            Route::post('report-leaves', 'Admin\AdminReportController@leaveReport')->name('admin.leaveReport');

            // Material Reports
            Route::get('report-materials', 'Admin\AdminReportController@materialReportView')->name('admin.materialReport');
            Route::post('report-materials', 'Admin\AdminReportController@materialReport')->name('admin.materialReport');

            // Marketing Van Request Report
            Route::get('report-marketing-vans', 'Admin\AdminReportController@marketingVanReportView')->name('admin.marketingVanReport');
            Route::post('report-marketing-vans', 'Admin\AdminReportController@marketingVanReport')->name('admin.marketingVanReport');

            // Lead Report

            Route::get('report-lead', 'Admin\AdminReportController@leadReportView')->name('admin.leadReport');
            Route::post('report-lead', 'Admin\AdminReportController@leadReport')->name('admin.leadReport');

             // Attendance Report

             Route::get('report-attendance', 'Admin\AdminReportController@attendanceReportView')->name('admin.attendanceReport');
             Route::post('report-attendance', 'Admin\AdminReportController@attendanceReport')->name('admin.attendanceReport');

        });
    });
});


Route::prefix('admin')->group(function () {

    Route::get('/', 'Auth\AdminLoginController@showLoginForm');
    Route::get('login', 'Auth\AdminLoginController@showLoginForm')->name('adminLogin');
    Route::post('login', ['uses' => 'Auth\AdminLoginController@login']);

    //Forgot password
    Route::get('forgot/password', 'Auth\AdminForgotPasswordController@index')->name('AdminForgotPassword');
    Route::post('forgot/password', 'Auth\AdminForgotPasswordController@checkUserIsAdmin')->name('AdminForgotPassword');
    // Reset password
    Route::post('password/reset', 'Auth\AdminResetPasswordController@changeAdminPassword')->name('admin.password.reset_process');
    Route::get('password/reset/{email}', 'Auth\AdminResetPasswordController@showResetForm')->name('password.reset');

    Route::middleware(['auth:admin'])->group(function () {

        Route::get('logout', 'Auth\AdminLoginController@logout')->name('AdminLogout');

        // // State (State, City, Talukas, Regions)
        // Route::resource('states', 'Admin\AdminStateController');
        // Route::post('states/search', 'Admin\AdminStateController@search')->name('states.search');
        // Route::post('states/status/{state}', 'Admin\AdminStateController@changeStatus')->name('states.status');
        // Route::get('states/create', 'Admin\AdminStateController@create')->name('states.create');
        // Route::get('states/{state}', 'Admin\AdminStateController@show')->name('states.show');

        // // City
        // Route::resource('cities', 'Admin\AdminCitiesController');
        // Route::post('cities/search', 'Admin\AdminCitiesController@search')->name('cities.search');
        // Route::post('cities/status/{city}', 'Admin\AdminCitiesController@changeStatus')->name('cities.status');
        // Route::get('cities/create', 'Admin\AdminCitiesController@create')->name('cities.create');
        // Route::get('cities/{city}', 'Admin\AdminCitiesController@show')->name('cities.show');

        // // Talukas
        // Route::resource('talukas', 'Admin\AdminTalukaController');
        // Route::post('talukas/search', 'Admin\AdminTalukaController@search')->name('talukas.search');
        // Route::post('talukas/status/{taluka}', 'Admin\AdminTalukaController@changeStatus')->name('talukas.status');
        // Route::get('talukas/create', 'Admin\AdminTalukaController@create')->name('talukas.create');
        // Route::get('talukas/{taluka}', 'Admin\AdminTalukaController@show')->name('talukas.show');
        // Route::post('get-city', 'Admin\AdminTalukaController@getCity')->name('get-city');
        // Route::post('get-taluka', 'Admin\AdminTalukaController@getTaluka')->name('get-taluka');

        // // Regions
        // Route::resource('regions', 'Admin\AdminRegionController');
        // Route::post('regions/search', 'Admin\AdminRegionController@search')->name('regions.search');
        // Route::post('regions/status/{region}', 'Admin\AdminRegionController@changeStatus')->name('regions.status');
        // Route::get('regions/create', 'Admin\AdminRegionController@create')->name('regions.create');
        // Route::get('regions/{region}', 'Admin\AdminRegionController@show')->name('regions.show');

    });
});
