<?php

use App\Api\Controllers\S3Provider;

Route::get('/awsUpload', function()
{
    $s3 = new S3Provider('scaledesk');
    return $s3->access_token();
});
Route::group(['prefix' => 'api/v1', 'namespace' => 'App\Api\Controllers'], function () {

    //Registration Routes
    Route::post('auth/register','Auth\RegistrationController@register');
    Route::get('register/verify/{confirmationCode}','Auth\RegistrationController@confirm');


    Route::post('auth/login', function() {
        return Response::json(Authorizer::issueAccessToken());
    });

    Route::post('auth/git','Auth\AuthController@github');
    Route::post('auth/google','Auth\AuthController@google');

    Route::resource('services', 'ServiceController');

    Route::get('resource', ['middleware' => 'oauth:scope_admin', function() {
        // return the protected resource
    }]);
    Route::get('resource1', ['middleware' => 'oauth:scope_user', function() {
        // return the protected resource
    }]);
    Route::resource('packages', 'PackageController');
    Route::resource('addons', 'AddonController');
    Route::resource('categories', 'CategoryController');
    Route::resource('package_types', 'PackageTypeController');
    Route::resource('payment_types', 'PaymentTypeController');
    Route::resource('package_types', 'PackageTypeController');
    Route::resource('delivery_types', 'DeliveryTypeController');
    Route::resource('tags', 'TagController');
    Route::resource('reviews', 'ReviewController');
    Route::resource('package_statuses', 'PackageStatusController');
    Route::resource('assignments', 'AssignmentController');
    Route::get('my-assignments/{statusId}', 'AssignmentController@getUserAssignmentsByStatus');
    Route::get('all-assignments/{statusId}', 'AssignmentController@getAllAssignmentsByStatus');
    Route::resource('assignment_statuses', 'AssignmentStatusController');
    Route::resource('child_services', 'ChildServiceController');
    Route::resource('parent_services', 'ParentServiceController');
    Route::resource('users', 'UserController');
    Route::get('my-profile', 'UserController@myProfile');
    Route::put('update-profile', 'UserController@updateProfile');
    Route::get('expert-undergoing-assignments', 'AssignmentController@getExpertUndergoingAssignments');
    Route::get('expert-assignments/{statusId}', 'AssignmentController@getExpertAssignmentsByStatus');
    Route::get('expert-available-assignments', 'AssignmentController@getExpertAvailableAssignments');
    Route::get('experts-of-service/{serviceId}', 'UserController@getExpertsOfService');
    Route::post('bidders-of-assignment/{assignmentId}', 'AssignmentController@updateAssignmentBidders');

    Route::resource('roles', 'RoleController');
    Route::resource('bids', 'BidController');
});
