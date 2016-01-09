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
    Route::post('addServiceToUser','UserController@attachChildServiceToUser');


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
    Route::get('messages/getNewThreads','MessagesController@getNewThreads');
    Route::get('messages/isUnread','MessagesController@checkThread');
    Route::put('makeRead/{thread_id}','MessagesController@makeRead');
    Route::post('userExtra','Auth\RegistrationController@insertExtra');
    Route::get('newExperts','UserController@getNewExperts');
    Route::put('activateAccount/{id}','UserController@activateAccount');
    Route::get('getExpert/{id}','UserController@getExpert');
    Route::get('payment/{assignment_id}/{user_id}','AssignmentController@doPayment');
    Route::post('payment_success','AssignmentController@successPayment');
    Route::get('payment_failure','AssignmentController@failurePayment');
    Route::get('completionPayment/{assignment_id}/{user_id}','AssignmentController@completionDoPayment');
    Route::post('completionPayment_success','AssignmentController@completionSuccessPayment');
    Route::get('completionPayment_failure','AssignmentController@completionFailurePayment');
    Route::post('insertTransactions/{assignment_id}','AssignmentController@insertTransactions');

});
//messages routes
Route::group(['prefix' => 'api/v1/messages','namespace' => 'App\Api\Controllers'], function () {
    Route::get('/', ['as' => 'messages', 'uses' => 'MessagesController@index']);
    Route::get('create', ['as' => 'messages.create', 'uses' => 'MessagesController@create']);
    Route::post('/', ['as' => 'messages.store', 'uses' => 'MessagesController@store']);
    Route::get('{id}', ['as' => 'messages.show', 'uses' => 'MessagesController@show']);
    Route::put('{id}', ['as' => 'messages.update', 'uses' => 'MessagesController@update']);
});
