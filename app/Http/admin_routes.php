<?php

//Route::get('/home', 'HomeController@index');
Route::auth();

/* ================== Access Uploaded Files ================== */
Route::get('files/{hash}/{name}', 'LA\UploadsController@get_file');

/*
|--------------------------------------------------------------------------
| Admin Application Routes
|--------------------------------------------------------------------------
*/

$as = "";
if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
	$as = config('laraadmin.adminRoute').'.';
	
	// Routes for Laravel 5.3
	Route::get('/logout', 'Auth\LoginController@logout');
}

Route::group(['as' => $as, 'middleware' => ['auth', 'permission:ADMIN_PANEL']], function () {
	
	/* ================== Dashboard ================== */
	
	Route::get(config('laraadmin.adminRoute'), 'LA\DashboardController@index');
	Route::get(config('laraadmin.adminRoute'). '/dashboard', 'LA\DashboardController@index');
	
	/* ================== Users ================== */
	Route::resource(config('laraadmin.adminRoute') . '/users', 'LA\UsersController');
	Route::get(config('laraadmin.adminRoute') . '/user_dt_ajax', 'LA\UsersController@dtajax');

    /* ================== Poems ================== */
    Route::resource(config('laraadmin.adminRoute') . '/poems', 'LA\PoemsController');
    Route::post(config('laraadmin.adminRoute') . '/poem/save', 'LA\PoemsController@store');
    Route::get(config('laraadmin.adminRoute') . '/poems/{id}', 'LA\PoemsController@show');
    Route::get(config('laraadmin.adminRoute') . '/poems/{id}/edit', 'LA\PoemsController@edit');
    Route::get(config('laraadmin.adminRoute') . '/poems/ajax/{id}', 'LA\PoemsController@ajax');
    Route::get(config('laraadmin.adminRoute') . '/poem_dt_ajax', 'LA\PoemsController@dtajax');

    Route::resource(config('laraadmin.adminRoute') . '/poem/dynasty', 'LA\PoemsController@dynasty');
    Route::resource(config('laraadmin.adminRoute') . '/poem/type', 'LA\PoemsController@type');
    Route::get(config('laraadmin.adminRoute') . '/poem_dy_ajax', 'LA\PoemsController@dy_ajax');
    Route::get(config('laraadmin.adminRoute') . '/poem_type_ajax', 'LA\PoemsController@type_ajax');

    Route::post(config('laraadmin.adminRoute') . '/poem/dynasty/save', 'LA\PoemsController@dyStore');
    Route::post(config('laraadmin.adminRoute') . '/poem/type/save', 'LA\PoemsController@typeStore');



    /* ================== authors ================== */
    Route::resource(config('laraadmin.adminRoute') . '/authors', 'LA\PoemAuthorsController');
    Route::post(config('laraadmin.adminRoute') . '/authors/save', 'LA\PoemAuthorsController@store');
    Route::get(config('laraadmin.adminRoute') . '/authors/{id}', 'LA\PoemAuthorsController@show');
    Route::get(config('laraadmin.adminRoute') . '/authors/{id}/dd', 'LA\PoemAuthorsController@dd');
    Route::get(config('laraadmin.adminRoute') . '/authors/{id}/edit', 'LA\PoemAuthorsController@edit');
    Route::get(config('laraadmin.adminRoute') . '/authors/ajax/{id}', 'LA\PoemAuthorsController@ajax');
    Route::get(config('laraadmin.adminRoute') . '/author_dt_ajax', 'LA\PoemAuthorsController@dtajax');

	/* ================== Uploads ================== */
	Route::resource(config('laraadmin.adminRoute') . '/uploads', 'LA\UploadsController');
	Route::post(config('laraadmin.adminRoute') . '/upload_files', 'LA\UploadsController@upload_files');
	Route::get(config('laraadmin.adminRoute') . '/uploaded_files', 'LA\UploadsController@uploaded_files');
	Route::post(config('laraadmin.adminRoute') . '/uploads_update_caption', 'LA\UploadsController@update_caption');
	Route::post(config('laraadmin.adminRoute') . '/uploads_update_filename', 'LA\UploadsController@update_filename');
	Route::post(config('laraadmin.adminRoute') . '/uploads_update_public', 'LA\UploadsController@update_public');
	Route::post(config('laraadmin.adminRoute') . '/uploads_delete_file', 'LA\UploadsController@delete_file');
	
	/* ================== Roles ================== */
	Route::resource(config('laraadmin.adminRoute') . '/roles', 'LA\RolesController');
	Route::get(config('laraadmin.adminRoute') . '/role_dt_ajax', 'LA\RolesController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/save_module_role_permissions/{id}', 'LA\RolesController@save_module_role_permissions');
	
	/* ================== Permissions ================== */
	Route::resource(config('laraadmin.adminRoute') . '/permissions', 'LA\PermissionsController');
	Route::get(config('laraadmin.adminRoute') . '/permission_dt_ajax', 'LA\PermissionsController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/save_permissions/{id}', 'LA\PermissionsController@save_permissions');
	
	/* ================== Departments ================== */
	Route::resource(config('laraadmin.adminRoute') . '/departments', 'LA\DepartmentsController');
	Route::get(config('laraadmin.adminRoute') . '/department_dt_ajax', 'LA\DepartmentsController@dtajax');
	
	/* ================== Employees ================== */
	Route::resource(config('laraadmin.adminRoute') . '/employees', 'LA\EmployeesController');
	Route::get(config('laraadmin.adminRoute') . '/employee_dt_ajax', 'LA\EmployeesController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/change_password/{id}', 'LA\EmployeesController@change_password');
	
	/* ================== Organizations ================== */
	Route::resource(config('laraadmin.adminRoute') . '/organizations', 'LA\OrganizationsController');
	Route::get(config('laraadmin.adminRoute') . '/organization_dt_ajax', 'LA\OrganizationsController@dtajax');

	/* ================== Backups ================== */
	Route::resource(config('laraadmin.adminRoute') . '/backups', 'LA\BackupsController');
	Route::get(config('laraadmin.adminRoute') . '/backup_dt_ajax', 'LA\BackupsController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/create_backup_ajax', 'LA\BackupsController@create_backup_ajax');
	Route::get(config('laraadmin.adminRoute') . '/downloadBackup/{id}', 'LA\BackupsController@downloadBackup');
});
