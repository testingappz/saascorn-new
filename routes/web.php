<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('home');
});
 
Route::post('/forgetpassword/email', 'PasswordforgetController@forgetpassword')->name('forgetpassword.email');
 Route::get('/password/passwordreset/{token}', 'PasswordforgetController@changepassord');
 Route::post('/resetpassword/update', 'PasswordforgetController@resetpassword')->name('resetpassword.update');
 
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/getFundAmericaUpdates', 'FundAmericaWebhookController@fundAmericaUpdates')->name('getFundAmericaUpdates');
Auth::routes(['verify' => true]);
//user profile approve
Route::get('/users/approve/{user_id}', 'ProfileapproveController@approve');
Route::post('/resend-email', 'ResendemailController@resendemail')->name('resend-email');

//owner

Route::group(['middleware' => ['role:owner']], function () {

	//Route::get('/',  'Owner\ProfileController@createProfile');
	Route::prefix('owner')->group(function() {

		Route::get('/create_owner_profile', 'Owner\ProfileController@createProfile');

		Route::post('/profile_picture_upload', 'Owner\ProfileController@createProfilePicture')->name('uploadpic');

		Route::post('/remove_profile_picture', 'Owner\ProfileController@removeProfilePicture')->name('removepic');

		Route::post('/get_country_data', 'Owner\ProfileController@getCountryData')->name('getcountrydata');

		Route::post('/add_profile_details', 'Owner\ProfileController@addProfileDetails')->name('basicdata');
		
		//pages that will be used when profile updated
		Route::group(['middleware' => 'checkProfileStatus'], function () {

			Route::post('/update_profile_details', 'Owner\ProfileController@updateProfileDetails')->name('updateProfile');

			Route::get('/view_owner_profile', 'Owner\ProfileController@viewOwnerProfile')->name('viewOwnerData');

			Route::post('/change_owner_password', 'Owner\ProfileController@changePasswordOwner')->name('changeOwnerPassword');

			Route::get('/add_investment', 'Owner\InvestmentController@showInvestmentForm')->name('addNewInvestmentForm');

			Route::post('/create_new_investment', 'Owner\InvestmentController@addNewInvestment')->name('addInvestment');

			Route::get('/investment_detail/id/{id}','Owner\InvestmentController@investmentDetail')->name('investmentDetails');

			Route::get('/view_investment_detail/id/{id}/pid/{pid}','Owner\InvestmentController@investmentProjectDetails')->name('investmentProjectDetails');

			Route::post('/update_invest_description', 'Owner\InvestmentController@updateInvestmentDesc')->name('updateDesc');

			
			Route::post('/update_doc_video', 'Owner\InvestmentController@updateInvestmentDoc')->name('updateDocOrVideo');

			Route::post('/delete_doc_video', 'Owner\InvestmentController@deleteInvestmentDoc')->name('deleteDocOrVideo');

			Route::post('/update_invest_info', 'Owner\InvestmentController@updateInvestInfo')->name('updateInvestInfo');

			Route::match(['get', 'post'], '/dashboard/{search?}', 'Owner\InvestmentController@investmentListing')->name('dashboard');

			Route::post('/update_project_data', 'Owner\InvestmentController@updateProjectData')->name('updateProjectData');

			Route::post('/sign_agreement_link', 'Owner\InvestmentController@SignAgreementLink')->name('signAgreementLink');

			Route::post('/sign_tech_service_agreement_link', 'Owner\InvestmentController@SignTechServiceAgreementLink')->name('signTechAgreementLink');

			Route::post('/load_more_data', 'Owner\InvestmentController@loadMoreInvestments')->name('loadmoreinv');

	    });

	});

	
    
});

//end of owner routes

//investor

Route::group(['middleware' => ['role:investor']], function () {

	//Route::get('/',  'Investor\ProfileController@createProfile');
	Route::prefix('investor')->group(function() {

		Route::get('/create_investor_profile', 'Investor\ProfileController@createProfile');

		Route::post('/picture_upload', 'Investor\ProfileController@createProfilePicture')->name('uploadpicinvestor');

		Route::post('/remove_profile_pic', 'Investor\ProfileController@removeProfilePicture')->name('removepicture');

		Route::post('/get_country', 'Investor\ProfileController@getCountry')->name('getcountry');

		Route::post('/add_profile_details_investor', 'Investor\ProfileController@addProfileDetailsInvestor')->name('basicdataInvestor');

		//pages that will be used when profile updated
		Route::group(['middleware' => 'checkProfileStatus'], function () {

		 	Route::get('/view_investor_profile', 'Investor\ProfileController@viewInvestorProfile')->name('viewInvestorData');

			Route::post('/update_profile_details_investor', 'Investor\ProfileController@updateProfileDetailsInvestor')->name('updateProfileInvestor');

			Route::post('/change_investor_password', 'Investor\ProfileController@changePasswordInvestor')->name('changeInvestorPassword');

			Route::post('/investment_info', 'Investor\ProfileController@updateInvestmentInformation')->name('updateInvestorInfo');

			Route::match(['get', 'post'], '/dashboard/{search?}/{minAmount?}', 'Investor\MakeInvestmentController@investmentListing')->name('investordashboard');

			Route::get('/investment_detail/id/{id}','Investor\MakeInvestmentController@investmentDetail')->name('ProjectDetails');

			Route::post('/make_investment','Investor\MakeInvestmentController@makeInvestment')->name('makeInv');

	    });
	});

});	

//end of investor routes

//admin
Route::get('/admin_login', 'Admin\AdminController@adminLogin')->name('adminLogin');

Route::post('/admin_login', 'Admin\AdminController@adminLogin')->name('adminLogin');

Route::group(['middleware' => ['auth:admin']], function () {
	
Route::get('/admin_dashboard', 'Admin\AdminController@dashboard')->name('admin_dashboard');

Route::get('/owner_list', 'Admin\AdminController@ownerList')->name('ownerList');

Route::get('/investor_list', 'Admin\AdminController@investorList')->name('investorList');

Route::get('/ownerlist_show/{userId}/', 'Admin\AdminController@showOwnerList')->name('ownerlist_show');

Route::get('/investorlist_show/{userId}/', 'Admin\AdminController@showInvestorList')->name('investorlist_show');

Route::post('/active_deactive', 'Admin\AdminController@changeUserStatus')->name('changeUserStatus');

Route::get('/logout', 'Admin\AdminController@logout')->name('logout');

//projects
Route::get('/project_list', 'Admin\ProjectController@projectList')->name('projectList');

Route::post('/investment_list_all', 'Admin\ProjectController@investmentList')->name('investmentGetList');

Route::get('/investment_list', 'Admin\ProjectController@investmentGetList')->name('investmentList');

Route::get('/investmentlist_show/{id}/', 'Admin\ProjectController@showInvetmentDetails')->name('investmentlist_show');

Route::get('/show_project_list/{id}/', 'Admin\ProjectController@showProjectList')->name('showProjectList');

Route::post('/change_project_status', 'Admin\ProjectController@changeProjectStatus')->name('changeProjectStatus');
//end of projects

//disbursements

Route::get('/disbursement_detail/{id}/', 'Admin\DisbursementController@disbursementDetail')->name('disbursementDetail');

Route::post('/disbursement_list', 'Admin\DisbursementController@disbursementList')->name('disbursementList');

Route::get('/disbursement_get_list', 'Admin\DisbursementController@disbursementGetList')->name('disbursementGetList');

Route::get('/add_new_disbursement', 'Admin\DisbursementController@renderNewDisbursement')->name('renderNewDisbursement');

Route::post('/manage_payment', 'Admin\DisbursementController@managePayment')->name('managePayment');
//end of disburements
Route::post('/get_country_admin_dashboard', 'Admin\DisbursementController@getCountryData')->name('getcountrydataadmin');

});

//end of owner routes



