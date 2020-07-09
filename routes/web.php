<?php

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
	return view('auth/login');
})->name('signin');

Auth::routes();
Auth::routes(['verify' => true]);

Route::post('/authenticate', 'Auth\CustomLoginController@authenticate')->name('authenticate');
Route::get('/sign-out', 'Auth\CustomLoginController@logout')->name('signout');
Route::get('/email','Messages\MailController@MailWelcome')->middleware('auth.basic');

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth.basic');
Route::get('/overview', 'HomeController@overview')->name('overview')->middleware('auth.basic');

Route::get('settings/profile','Users\Profile\ProfileController@profileSettings')->name('profile-settings');
Route::get('settings/account','Users\Profile\ProfileController@accountSettings')->name('account-settings');
Route::post('settings/account/{id}','Users\Profile\ProfileController@updateAccount')->name('account.update');

Route::post('notification/get','Messages\NotificationController@getNotifications');
Route::get('notifications','Messages\NotificationController@markAllRead')->name('readAll');


Route::get('help-desk','Documentation\DocumentationController@index')->name('userguide');
Route::get('about-CodeSolutionTech','Documentation\DocumentationController@CompanyDetails')->name('aboutCST');

Route::get('sms','Messages\SmsController@index')->name('sms');
Route::get('send-sms','Messages\SmsController@SendSMS')->name('sms.store');


Route::get('logs/truncate','Logs\LogsController@truncateLogs')->name('logs.truncate')->middleware('password.confirm');


Route::get('events/event-form','Events\EventsController@ShowEventForm')->name('events.showForm');
Route::get('events/{event}','Events\EventsController@update')->name('update.event');
Route::get('calendar', 'EventsController@loadCalendar')->name('calendar.home');

Route::get('applications/membership-savings','Applications\ApplicationsController@membershipAndSaving')->name('membershipApplications');

Route::get('payments/mobileMoney','Payments\PaymentsController@momoIndex')
->name('mobileMoney.index');
Route::get('payments/creditcard','Payments\PaymentsController@creditCardIndex')->name('creditcard.index');
Route::get('accounts/transferFunds','Payments\PaymentsController@accountFundsTransferIndex')->name('funds.transfer');
Route::post('accounts/deposit/mobileMoney','Payments\PaymentsController@transactMomo')->name('momo.deposit');

Route::get('send-sms','Messages\SmsController@SendSMS')->name('sms.store');


Route::get('users/staff','Users\UserController@staffIndex')
->name('staff');
Route::get('users/members','Users\UserController@membersIndex')->name('members');
Route::get('inactive-users/admin','Users\UserController@inactiveUsersbyAdmin')->name('inactive-a');
Route::get('inactive-users/system','Users\UserController@inactiveUsersbySystem')->name('inactive-s');
Route::get('/change-account/{id}/{status}/{name}','Users\UserController@ChangeAccountStatus')->name('user.changestatus');

Route::get('/changPriv/Admin/{id}/{priv}/{role}','Users\RolesController@changeAdminPrivileges')->name('adminPriv');
Route::get('/changePriv/superAdmin/{id}/{priv}/{role}','Users\RolesController@changeSuperAdminPrivileges')->name('superAdminPriv');



Route::get('loans/records','Loans\LoansController@loanHistory')->name('loan-history.index');
Route::get('loans/defaulters','Loans\DefaultersController@index')->name('defaulters.home');


Route::get('applications/approved','Accounts\Applications\ApplicationsController@approved')->name('approvedApplications');
Route::get('applications/pending','Accounts\Applications\ApplicationsController@pending')->name('pendingApplications');
Route::get('applications/rejected','Accounts\Applications\ApplicationsController@rejected')->name('deniedApplications');

Route::post('applications/approve/{id}/{account}','Accounts\Applications\ApplicationsController@approveApplication')->name('approve.app');
Route::post('applications/reject/{id}/{account}','Accounts\Applications\ApplicationsController@denyApplication')->name('deny.app');

Route::get('applications/approved/truncate','Accounts\Applications\ApplicationsController@truncateApprovedApps')->name('approvedApps.truncate')->middleware('password.confirm');
Route::get('applications/pending/truncate','Accounts\Applications\ApplicationsController@truncatePendingApps')->name('pendingApps.truncate')->middleware('password.confirm');
Route::get('applications/denied/truncate','Accounts\Applications\ApplicationsController@truncateDeniedApps')->name('deniedApps.truncate')->middleware('password.confirm');



Route::get('accounts/main-saving', 'Accounts\MainSavingsController@index')->name('MainSavingAccounts.home');
Route::get('accounts/shares', 'Accounts\SharesSavingsController@index')->name('shares.home');
Route::get('accounts/education', 'Accounts\EducationSavingsController@index')->name('education.home')->middleware('auth.basic');
Route::get('accounts/retirement', 'Accounts\RetirementSavingsController@index')->name('retirement.home');


Route::post('accounts/education', 'Accounts\EducationSavingsController@filter')->name('education.search');
Route::post('accounts/shares', 'Accounts\SharesSavingsController@filter')->name('shares.search');
Route::post('accounts/retirement', 'Accounts\RetirementSavingsController@filter')->name('retirement.search');

Route::post('accounts/main-saving', 'Accounts\MainSavingsController@filter')->name('MainSavingAccounts.search');

Route::post('accounts/main/search', 'Accounts\MainSavingsController@searchAccountNumber')
->name('mainaccount.search');

Route::post('accounts/main/search-names', 'Accounts\MainSavingsController@searchAccountNames')
->name('accountnames.search');

Route::post('accounts/education/search', 'Accounts\EducationSavingsController@searchAccountNumber')
->name('educaccount.search');

Route::post('accounts/retirement/search', 'Accounts\RetirementSavingsController@searchAccountNumber')
->name('retraccount.search');

Route::post('accounts/shares/search', 'Accounts\SharesSavingsController@searchAccountNumber')
->name('sharesaccount.search');

Route::get('loans/loanees', 'Loans\LoansController@index')
->name('loanees.home');
Route::get('loans/due-loans', 'Loans\LoansController@dueLoansIndex')->name('dueLoans.index');
Route::get('loans/paid-loans', 'Loans\PaidLoansController@index')->name('paidLoans.home');
Route::get('loans/due-loans', 'Loans\DueLoansController@index')->name('dueLoans.home');
Route::get('loans/loan-application', 'Loans\LoansController@loanApplication')->name('loan-application.index');

Route::get('loans/pending-requests', 'Loans\LoansController@pendingLoanRequests')->name('p-loan-requests');
Route::get('loans/denied-requests',
	'Loans\LoansController@deniedLoanRequests')->name('d-loan-requests');
Route::get('loans/approved-requests', 'Loans\LoansController@approvedLoanRequests')->name('a-loan-requests');



Route::post('loans/approve/{id}','Loans\LoansController@approveLoanRequest')->name('approve.loan');
Route::post('loans/reject/{id}','Loans\LoansController@denyLoanRequest')->name('deny.loan');


Route::get('users/roles', 'Users\RolesController@index')->name('roles.home');

Route::get('forms/accounts/application', 'Accounts\Applications\ApplicationsController@accountApplicationform')->name('general-form');

Route::get('charts/savings', 'Charts\ChartsController@chartSavingsData')->name('savings-data.chart');



Route::get('accounts/settings/main', 'Accounts\Settings\MainSettingsController@index')
->name('main-settings.home');
Route::get('accounts/settings/education', 'Accounts\Settings\EducationSettingsController@index')
->name('education-settings.home');
Route::get('accounts/settings/retirement', 'Accounts\Settings\RetirementSettingsController@index')
->name('retirement-settings.home');
Route::get('accounts/settings/shares', 'Accounts\Settings\SharesSettingsController@index')
->name('shares-settings.home');


Route::get('membership/application', 'Users\MembershipController@index')
->name('membership.app');
Route::get('loans/settings', 'Loans\LoanSettingsController@index')
->name('loan.settings');

Route::get('investments/running', 'Benefits\InvestmentsController@index')
->name('investments.running');
Route::get('investments/unapproved', 'Benefits\InvestmentsController@unapprovedInvestments')
->name('investments.pending');
Route::post('investments/approve/{id}','Benefits\InvestmentsController@approveInvestment')
->name('investment.approve');
Route::post('investments/reject/{id}','Benefits\InvestmentsController@denyInvestment')
->name('investment.disapprove');

Route::get('performance', 'Users\MembershipController@generalPerformance')
->name('general.performance');


Route::post('import/investments', 'Benefits\InvestmentsController@importInvestments')->name('investments.import');
Route::post('import/roles', 'Users\RolesController@importRoles')->name('roles.import');
Route::post('import/staff', 'Users\UserController@importStaff')->name('staff.import');
Route::post('import/loanees', 'Loans\LoansController@importLoanees')->name('loanees.import');

Route::post('import/loan-interest-rates', 'Loans\LoanSettingsController@importLoanInterestRates')
->name('loan-rates.import');

Route::post('import/main-savings', 'Accounts\MainSavingsController@importSavings')
->name('main-savings.import');
Route::post('import/education-savings', 'Accounts\EducationSavingsController@importSavings')
->name('education-savings.import');
Route::post('import/retirement-savings', 'Accounts\RetirementSavingsController@importSavings')
->name('retirement-savings.import');
Route::post('import/shares-savings', 'Accounts\SharesSavingsController@importSavings')
->name('shares-savings.import');



Route::resources([

	'applications' => 'Accounts\Applications\ApplicationsController',
	'MainSavingAccounts' => 'Accounts\MainSavingsController',
	'shares' => 'Accounts\SharesSavingsController',
	'education' => 'Accounts\EducationSavingsController',
	'retirement' => 'Accounts\RetirementSavingsController',
	'main-settings' => 'Accounts\Settings\MainSettingsController',
	'education-settings' => 'Accounts\Settings\EducationSettingsController',
	'retirement-settings' => 'Accounts\Settings\RetirementSettingsController',
	'shares-settings' => 'Accounts\Settings\SharesSettingsController',

	'users' => 'Users\UserController',
	'roles' => 'Users\RolesController',
	'profile' => 'Users\Profile\ProfileController',

	'events' => 'Events\EventsController',
	'payments' => 'Payments\PaymentsController',
	'mail' => 'Messages\MailController',
	'loan' => 'Loans\LoansController',
	'loan-settings' => 'Loans\LoanSettingsController',
	'defaulters' => 'Loans\DefaultersController',
	'paidLoans' => 'Loans\PaidLoansController',
	'dueLoans' => 'Loans\DueLoansController',
	'logs' => 'Logs\LogsController',
	'membership' => 'Users\MembershipController',
	'investments' => 'Benefits\InvestmentsController',


]);
