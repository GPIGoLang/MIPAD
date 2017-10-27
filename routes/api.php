<?php

Route::post('/register', 'AuthController@register');
Route::post('/org-chk-refid', 'AuthController@checkOrgRefId');
Route::post('/nominee/test-setup', 'AuthController@setNomineeUpForTest');

Route::post('/code-resend', 'AuthController@resendVerificationCode');
// Account activation
Route::post('/verify', 'AuthController@verify');

Route::post('/login', 'AuthController@login');

Route::post('/logout', 'AuthController@logout');


//Assessments
Route::post('assessments', 'AssessmentController@tests');
Route::post('assessments/{status}', 'AssessmentController@getTestsByStatus');

Route::get('assessment/result-store', 'AssessmentController@getTestResult');
Route::post('assessment/result-display', 'AssessmentController@displayResult');
Route::post('assessment/reset', 'AssessmentController@resetTest');

Route::post('nominee/assessments', 'AssessmentController@getNomineeAssessments');

Route::post('nominations', 'NominationController@getNominations'); // Get a nomination