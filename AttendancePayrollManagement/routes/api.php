<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// EMPLOYEE ROUTE
Route::get('get-employees', 'EmployeeController@show');
Route::get('get-total-employees', 'EmployeeController@totalEmployees');
Route::get('get-employee/{id}', 'EmployeeController@get');
Route::post('add-employee', 'EmployeeController@store');
Route::post('upload-image', 'EmployeeController@update_image');
Route::post('update-employee', 'EmployeeController@update');
Route::post('delete-employee', 'EmployeeController@destroy');


// POSITION ROUTE
Route::get('get-positions', 'PositionController@show');
Route::post('add-position', 'PositionController@store');
Route::put('update-position/{id}', 'PositionController@update');
Route::delete('delete-position/{id}', 'PositionController@destroy');

// SCHEDULE ROUTE
Route::get('get-schedules', 'ScheduleController@show');
Route::post('add-schedule', 'ScheduleController@store');
Route::put('update-schedule/{id}', 'ScheduleController@update');
Route::delete('delete-schedule/{id}', 'ScheduleController@destroy');

// ATTENDANCE ROUTE
Route::post('attendance', 'AttendanceController@store');
Route::post('add-attendance', 'AttendanceController@add');
Route::post('update-attendance', 'AttendanceController@update');
Route::get('get-attendance_date', 'AttendanceController@getDate');
Route::get('get-attendance', 'AttendanceController@get_Status');
Route::post('attendance-month', 'AttendanceController@getDate');
Route::get('get-attendance-month', 'AttendanceController@getDate');
Route::delete('delete-attendance/{id}', 'AttendanceController@destroy');
Route::get('get-attendance-status', 'AttendanceController@attendance_Total_per_Month');

// BENEFITS ROUTE
Route::get('get-benefits', 'BenefitController@show');
Route::post('add-benefit', 'BenefitController@store');
Route::post('pluck-benefit', 'BenefitController@pluck');
Route::post('pluck-add-benefit', 'BenefitController@pluckAdd');
Route::put('pluck-updated-benefit/{id}', 'BenefitController@pluckUpdate');
Route::put('update-benefit/{id}', 'BenefitController@update');
Route::delete('delete-benefit/{id}', 'BenefitController@destroy');
Route::post('delete-benefit-pivot', 'BenefitController@destroyPivot');

// DEDUCTIONS ROUTE
Route::post('deduction', 'DeductionController@store');
Route::put('update-deduction/{id}', 'DeductionController@update');
Route::get('get-deduction', 'DeductionController@show');
Route::delete('delete-deduction/{id}', 'DeductionController@destroy');

Route::get('get-employee-deductions','DeductionController@employeeDeduction');
Route::post('add-employee-deduction','DeductionController@addDeduction');
Route::post('update-employee-deduction','DeductionController@updateDeduction');
Route::post('delete-employee-deduction','DeductionController@deleteDeduction');

// COMPENSATION ROUTE
Route::post('compensation', 'CompensationController@store');
Route::put('update-compensation/{id}', 'CompensationController@update');
Route::get('get-compensation', 'CompensationController@show');
Route::delete('delete-compensation/{id}', 'CompensationController@destroy');

// LEAVES ROUTE
Route::post('leave', 'LeaveController@store');
Route::put('update-leave/{id}', 'LeaveController@update');
Route::get('get-leave', 'LeaveController@show');
Route::delete('delete-leave/{id}', 'LeaveController@destroy');

// EMAIL ROUTE
Route::get('send-mails/{id}', 'SendEmailController@index'); //glen

// DASHBOARD ROUTE
Route::get('get-summary', 'DashboardController@index'); //glen

// PAYROLL ROUTE
Route::get('get-payrolls', 'PayrollController@show');
Route::post('create-payroll', 'PayrollController@computation');
Route::put('update-payroll/{id}', 'PayrollController@update');
Route::post('delete-payroll', 'PayrollController@destroy'); //glen
// Route::delete('delete-payroll/{id}', 'PayrollController@destroy'); //glen
Route::get('current_month_date','PayrollController@currentMonthDate'); //glen

// PAYSLIP ROUTE
// Route::get('get-payslips', 'PayslipController@index'); //glen
// Route::post('get-payslips', 'PayslipController@payslip'); //glen
Route::get('get-payslips/{id}', 'PayslipController@payslips'); //glen
Route::get('get-employee-payslip/{id}', 'PayslipController@employeePayslip'); //glen
Route::get('get-employee-deduction/{date}', 'PayslipController@employeeDeduction'); //glen
// Route::get('get-employee-deduction/{id}', 'PayslipController@employeeDeduction'); //glen
// Route::delete('delete-payslip/{id}', 'PayslipController@destroy'); //glen
Route::post('delete-payslip', 'PayslipController@destroy'); //glen

// USER ROUTE
Route::prefix('/user')->group(function(){
    Route::post('login','AdminController@login');
    Route::post('register','AdminController@register');
    Route::post('change-password','AdminController@change_password');
});
// ADMIN ROUTE
Route::middleware('auth:api')->group(function () {
    Route::get('/current','AdminController@currentUser');
    Route::post('/logout','AdminController@logout');
    Route::get('/get-roles','RolePermissionController@getRoles');
    Route::post('addRole', 'RolePermissionController@createRole');
    Route::get('/get-info','AdminCOntroller@getInfo');
    Route::get('/get-qoutes','AdminCOntroller@qoutes_of_the_day');

    Route::post('/request-leave','AdminController@requestLeave');
    Route::get('/get-leaves','AdminController@getLeaves');
    Route::delete('/delete-leave/{id}', 'AdminController@deleteLeave');
    Route::put('/update-leave','AdminController@editLeave');

    Route::get('/admin_get-leaves', 'AdminController@admin_leaves');
    Route::post('/admin_request-leave','AdminController@admin_requestLeave');
    Route::get('/my_payroll', 'AdminController@my_payroll');
});

