<?php

use Illuminate\Support\Facades\Route;
use Tutor\Id\Http\TutorIdController;

Route::group(['prefix' => 'auth', 'as' => 'auth.tutor_id.', 'middleware' => ['web']], function () {
    Route::get('/id_educa_tutor', [TutorIdController::class, 'redirectToTutorId'])->name('redirect');
    Route::get('/id_educa_tutor/callback', [TutorIdController::class, 'handleTutorIdCallback'])->name('callback');
});
