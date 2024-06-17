<?php

use App\Models\Job;
use App\Models\User;
use App\Models\JobType;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\JobsController;
use App\Models\Category;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs', [JobsController::class, 'index'])->name('jobs');
Route::get('/jobs/detail/{id}', [JobsController::class, 'detail'])->name('jobDetail');
Route::post('/apply-job',[JobsController::class,'applyJob'])->name('applyJob');


// Route::get('/account/register',[AccountController::class,'registeration'] )->name('account.registeration');

// Route::post('/account/process-register',[AccountController::class,'processRegisteration'] )->name('account.processRegsiteration');
// Route::get('/account/login',[AccountController::class,'login'] )->name('account.login');

// Route::post('/account/authenticate',[AccountController::class,'authenticate'] )->name('account.authenticate');

// Route::get('/account/profile',[AccountController::class,'profile'] )->name('account.profile');

// Route::get('/account/logout',[AccountController::class,'logout'] )->name('account.logout');


Route::group(['account'], function () {


    // Guest User
    Route::group(['middleware' => 'guest'], function () {

        Route::get('/account/register', [AccountController::class, 'registeration'])->name('account.registeration');
        Route::post('/account/authenticate', [AccountController::class, 'authenticate'])->name('account.authenticate');
        Route::post('/account/process-register', [AccountController::class, 'processRegisteration'])->name('account.processRegsiteration');
        Route::get('/account/login', [AccountController::class, 'login'])->name('account.login');
    });
    // Authenticated Routes
    Route::group(['middleware' => 'auth'], function () {

    Route::get('/account/profile', [AccountController::class, 'profile'])->name('account.profile');
   
    Route::put('/account/update-profile', [AccountController::class, 'updateProfile'])->name('account.updateProfile');

    Route::post('/account/update-profile-pic',[AccountController::class,'updateProfilePic'])->name('account.updateProfilePic');
    
    
    

    Route::get('/account/logout', [AccountController::class, 'logout'])->name('account.logout');

    Route::get('/account/create-job',[AccountController::class,'createJob'])->name('account.createJob');
    Route::post('/account/save-job', [AccountController::class, 'saveJob'])->name('account.saveJob');
    Route::get('/account/my-jobs', [AccountController::class, 'myJobs'])->name('account.myJobs');
    Route::get('/account/my-jobs/edit/{jobId}', [AccountController::class, 'editJob'])->name('account.editJob');
    Route::post('/account/update-job/{jobId}', [AccountController::class, 'updateJob'])->name('account.updateJob');
    Route::post('/account/delete-job', [AccountController::class, 'deleteJob'])->name('account.deleteJob');
    


   


    });
});


