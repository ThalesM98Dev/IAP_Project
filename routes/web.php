<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FileHistoryController;
use App\Http\Controllers\GroupController;
use Illuminate\Support\Facades\Auth;
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

Route::view('/', 'welcome');

//Register and login
Auth::routes();
//Redirect to dashboard
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'throttle:web'], function () {//Rate limit middleware (Route service provider)
    Route::group(['middleware' => 'auth'], function () {
        Route::group(['middleware' => 'sysLogs'], function () {
            //admin pages
            Route::prefix('admin')->middleware('isAdmin')->namespace('Admin')->group(function () {
                //Route::view('/home', 'Admin/adminHome')->name('admin.home');
                Route::get('/all_users', [AdminController::class, 'all_users'])->name('admin.all_users');
                Route::get('/all_groups', [GroupController::class, 'allGroups'])->name('admin.all_groups');
                Route::get('/full_history', [FileHistoryController::class, 'history'])->name('history.full_history');
                Route::post('/set_limit', [AdminController::class, 'setFilesLimit'])->name('admin.set_limit');

                Route::get('/set_limit_page', [AdminController::class, 'setFilesLimitPage'])->name('admin.set_limit_page');

            });
            //user pages
            Route::prefix('user')->namespace('User')->group(function () {
                Route::get('/home', [GroupController::class, 'home'])->name('user.home');
                Route::get('/user_groups', [GroupController::class, 'userGroups'])->name('user.user_groups');
                Route::get('/history_by_user', [FileHistoryController::class, 'historyByUser'])->name('history.by_user');
                Route::post('/history_by_file', [FileHistoryController::class, 'historyByFile'])->name('history.by_file');
                Route::get('/history_choosing_file', [FileHistoryController::class, 'choosingFilePage'])->name('history.choosing_file');
                Route::get('/export_user_history', [FileHistoryController::class, 'exportToTxtUser'])->name('history.export_user_history');
                Route::post('/export_file_history', [FileHistoryController::class, 'exportToTxt'])->name('history.export_file_history');
            });
            //groups operations
            Route::prefix('groups')->group(function () {
                Route::view('/add_group_page', 'Group/addGroup')->name('groups.add_group_page');
                Route::post('/store', [GroupController::class, 'create'])->name('groups.add_group');
                Route::get('/show/{id}', [GroupController::class, 'show'])->name('groups.show_group');
                Route::get('/add_user_page/{id}', [GroupController::class, 'addUserPage'])->name('groups.add_user_page');
                Route::post('/addUser', [GroupController::class, 'addUser'])->name('groups.add_user');
                Route::post('/removeMember', [GroupController::class, 'removeUser'])->name('groups.remove_member');
                Route::post('/leaveGroup', [GroupController::class, 'leaveGroup'])->name('groups.leave_group');
                Route::post('/removeGroup', [GroupController::class, 'removeGroup'])->name('groups.remove_group');
            });
            Route::prefix('files')->group(function () {
                Route::get('/add_file_page', [FileController::class, 'addFilePage'])->name('files.add_file_page');
                Route::post('/store', [FileController::class, 'store'])->name('files.store');
                Route::get('/group_files/{id}', [FileController::class, 'groupFiles'])->name('files.group_files');
                Route::get('/show/{id}', [FileController::class, 'show'])->name('files.show_file');//not used
                Route::post('/checkin', [FileController::class, 'checkIn'])->name('files.checkin_file');
                Route::post('/checkout', [FileController::class, 'checkOut'])->name('files.checkout_file');
                Route::get('/change_file/{id}', [FileController::class, 'changeFilePage'])->name('files.change_file_page');
                Route::get('/delete/{id}', [FileController::class, 'destroy'])->name('files.delete_file');
            });

            Route::prefix('search')->group(function () {
                Route::post('/groups', [GroupController::class, 'searchForGroup'])->name('search.for_groups');
                Route::post('/files', [FileController::class, 'searchForFile'])->name('search.for_files');
            });
        });
    });
});


