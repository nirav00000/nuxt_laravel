<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controller\Api\v1\FeedbackController;

/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a Group which
    | is assigned the "api" middleware Group. Enjoy building your API!
    |
*/

Route::middleware('auth:api')->get(
    '/user',
    function (Request $request) {
        return $request->user();
    }
);

Route::prefix('v1')->group(
    function () {

        Route::get(
            'test',
            function () {
                return "testing - " . Auth::id();
            }
        );

        Route::middleware(['api'])->group(
            function () {
                Route::namespace('Api\v1')->group(
                    // Controllers Within The "App\Http\Controllers\Api
                    function () {

                        // User Authentication
                        Route::middleware(['auth.user'])->group(function () {
                            Route::resource('/stages', StageController::class);

                            Route::resource('/users', UserController::class);

                            Route::resource('/candidates', CandidateController::class);

                            Route::resource('/coding-challenges', CodingChallengeController::class);
                            Route::resource('/coding-submissions', CodingSubmissionController::class);

                            Route::get('/candidacies', 'CandidacyController@index');
                            Route::get('/candidacies/{candidacy}', 'CandidacyController@show');
                            Route::post('/candidacies/{candidacy}/close', 'CandidacyController@close');
                            Route::post('/candidacies/{candidacy}/assignStage/{stage}', 'CandidacyController@assignStage');




                            Route::post('/candidacies/{candidacy}/closeStage', 'CandidacyController@closeStage');

                            Route::get('/candidacy_histories/{candidacy}', 'CandidacyHistoryController@index');

                            Route::get('/questionnaire_submissions/{questionnaire_submission}', 'QuestionnaireSubmissionController@show');
                            Route::get('/questionnaires', 'QuestionnaireController@index');
                            Route::post('/questionnaires', 'QuestionnaireController@store');
                            Route::put('/questionnaires/{questionnaire}', 'QuestionnaireController@update');
                            Route::delete('/questionnaires/{questionnaire}', 'QuestionnaireController@destroy');
                            Route::get('/questionnaires/{questionnaire}', 'QuestionnaireController@show');

                            Route::post('/logout', 'AuthController@logout');
                            Route::post('/users/me', 'AuthController@me');
                            Route::get('/groups', 'AuthController@getGroups');

                            Route::get('candidacies/{candidacy}/feedback', 'FeedbackController@index');
                            Route::get('feedback/{feedback}', 'FeedbackController@show');
                            Route::post('candidacies/{candidacy}/feedback', 'FeedbackController@store');
                            Route::put('feedback/{feedback}', 'FeedbackController@update');
                            Route::delete('feedback/{feedback}', 'FeedbackController@delete');

                            // Position API
                            Route::get('/positions', 'PositionController@index');

                            // Admin routes
                            Route::middleware(['auth.admin'])->group(function () {
                                Route::namespace('admin')->group(function () {
                                    Route::get('/admin/candidacies', 'CandidacyController@index');
                                    Route::get('/admin/candidacies/{candidacy}', 'CandidacyController@show');
                                    Route::post('/admin/candidacies/{candidacy}/close', 'CandidacyController@close');
                                    Route::post('/admin/candidacies/{candidacy}/assignStage/{stage}', 'CandidacyController@assignStage');
                                    Route::post('/admin/candidacies/{candidacy}/closeStage', 'CandidacyController@closeStage');


                                    Route::get('/admin/candidacy_histories/{candidacy}', 'CandidacyHistoryController@index');
                                });
                                Route::post('/admin/candidacies', 'CandidacyController@store');
                            });
                        });

                        // Coding Session authentication
                        Route::middleware(['auth.coding.session'])->group(function () {
                            Route::post('/coding-sessions/fetch-session', 'CodingSessionController@fetchSession');
                            Route::post('/coding-sessions/run', 'CodingSessionController@run');
                            Route::post('/coding-sessions/submit', 'CodingSessionController@submit');
                        });

                        // Google Authentication and $request->email must passed
                        Route::middleware(['auth.google'])->group(function () {
                            Route::post('/candidacies', 'CandidacyController@store');
                        });

                        // Individual routes
                        Route::post('/questionnaire_submissions/{questionnaire}', 'QuestionnaireSubmissionController@store');
                        Route::get('/seed_questionnaire', 'QuestionnaireController@seedQuestionnaire');
                    }
                );
            }
        );
    }
);
