<?php

use Illuminate\Support\Facades\Route;
use Laravel\Pennant\Middleware\EnsureFeaturesAreActive;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();
Route::get('/', function () {
    // return view('welcome');
    if(auth()->guest()){
        return redirect()->route('login');
    }else{
        return redirect()->route('home');
    }
});

Route::middleware(['auth'])->group(function(){
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::middleware(EnsureFeaturesAreActive::using('isUser'))->group(function () {
        Route::get('/tickets/datas/user', [App\Http\Controllers\TicketController::class, 'user_datas'])->name('tickets.datas.user');
        Route::get('/tickets/create', [App\Http\Controllers\TicketController::class, 'create'])->name('tickets.create');
        Route::post('/tickets/attachments/store', [App\Http\Controllers\TicketController::class, 'attachments_store'])->name('tickets.attachments.store');
    });
    Route::post('/tickets/{ticket}/store_reply', [App\Http\Controllers\TicketController::class, 'store_reply'])->name('tickets.store_reply');

    Route::middleware(EnsureFeaturesAreActive::using('isAdmin'))->group(function () {
        Route::get('/categories/{category}/subcategories', [App\Http\Controllers\CategoryController::class, 'subcategories'])->name('categories.subcategories');
        Route::resource('categories', App\Http\Controllers\CategoryController::class);
        Route::resource('subcategories', App\Http\Controllers\SubCategoryController::class);
        
        Route::resource('roles', App\Http\Controllers\RoleController::class);
        Route::get('/roles/{role}/users', [App\Http\Controllers\RoleController::class, 'users'])->name('roles.users');
        Route::post('/roles/{role}/attach_users', [App\Http\Controllers\RoleController::class, 'attachUsers'])->name('roles.attach_users');
        Route::post('/roles/{role}/detach_users', [App\Http\Controllers\RoleController::class, 'detachUsers'])->name('roles.detach_users');
        Route::get('/users/without_role', [App\Http\Controllers\UserController::class, 'withoutRole'])->name('users.without_role');
        Route::resource('users', App\Http\Controllers\UserController::class);
        Route::post('/users/{user}/update_password', [App\Http\Controllers\UserController::class, 'updatePassword'])->name('users.update_password');
        Route::resource('staff', App\Http\Controllers\StaffController::class);
        Route::post('/staff/{user}/update_password', [App\Http\Controllers\StaffController::class, 'updatePassword'])->name('staff.update_password');
        Route::delete('/tickets/{ticket}', [App\Http\Controllers\TicketController::class, 'destroy'])->name('tickets.destroy');
    });

    Route::middleware(EnsureFeaturesAreActive::using('isAdminOrStaff'))->group(function () {
       Route::get('/tickets/datas/staff', [App\Http\Controllers\TicketController::class, 'datas'])->name('tickets.datas.staff'); 
       Route::post('/tickets/{ticket}/change_status', [App\Http\Controllers\TicketController::class, 'updateStatus'])->name('tickets.change_status');
    });
    
    
    Route::prefix('profile')->middleware('auth.session')->group(function () {
        Route::get('/', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
        Route::post('/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update'); 
    });
    Route::resource('tickets', App\Http\Controllers\TicketController::class)->except([
        'destroy','create'
    ]);
});


