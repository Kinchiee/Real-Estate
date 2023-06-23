<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\UserController;


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

Route::get('/', [UserController::class, 'Index']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

//Admin Middleware
Route::middleware(['auth', 'role:admin'])->group(function () {
    //Accessing the dashboard for the Admin
    Route::get('admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
    //Logging out the Admin
    Route::get('admin/logout', [AdminController::class, 'AdminLogout'])->name('admin.logout');
    //Viewing the Profile of the Admin
    Route::get('admin/profile', [AdminController::class, 'AdminProfile'])->name('admin.profile');
    //Storing the update data in the database
    Route::post('admin/profile/store', [AdminController::class, 'AdminProfileStore'])->name('admin.profile.store');
    //Getting the Form for changing the password of the admin
    Route::get('admin/change/password', [AdminController::class, 'AdminChangePassword'])->name('admin.change.password');
    //Updating the Password of the admin
    Route::post('admin/update/password', [AdminController::class, 'AdminUpdatePassword'])->name('admin.update.password');
});

//Admin Login
Route::get('admin/login', [AdminController::class, 'AdminLogin'])->name('admin.login');

// //FIRST ROUTER // Admin Dashboard Router.
// Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');

//Agent Middleware
Route::middleware(['auth', 'role:agent'])->group(function () {
    Route::get('/agent/dashboard', [AgentController::class, 'AgentDashboard'])->name('agent.dashboard');
});
// //SECOND ROUTER // Agent Dashboard Router.
// Route::get('/agent/dashboard', [AgentController::class, 'AgentDashboard'])->name('agent.dashboard');
