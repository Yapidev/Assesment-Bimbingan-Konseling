<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminRekapController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\DashboardUserController;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\InstrumenController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\PengerjaanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatementController;
use App\Http\Controllers\SubKriteriaController;
use App\Http\Controllers\UserGroupController;
use App\Http\Controllers\UserInstrumentController;
use Illuminate\Support\Facades\Route;

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

// Route untuk user yang belum terautentifikasi
Route::middleware('guest')->group(function () {
    Route::get("/login", fn () => view("auth.login"))->name("auth.page.login");
    Route::post("/login", [AuthController::class, "login"])->name("auth.login");
    Route::get("/register", fn () => view("auth.register"))->name("auth.page.register");
    Route::post("/register", [AuthController::class, "register"])->name("auth.register");
    Route::get('/', [LandingPageController::class, 'index'])->name('landing-page');
});

// Route untuk user yang sudah terautentifikasi
Route::middleware("auth")->group(function () {
    Route::post("/logout", [AuthController::class, "logout"])->name("auth.logout");
    Route::resource('profile', ProfileController::class)->only('index');
});

// Route untuk role user
Route::prefix('user')->middleware('auth', 'user')->group(function () {
    Route::get('dashboard', [DashboardUserController::class, 'index'])->name('user.dashboard');
    Route::resource("user-groups", UserGroupController::class);
    Route::resource("user-instruments", UserInstrumentController::class);
    Route::get('books', fn () => view('user.books.index'))->name('user.books.index');

    Route::controller(PengerjaanController::class)->group(function () {
        Route::get("pengerjaan/{group_id}/{instrumen_id}", "index")->name("pengerjaan.index");
        Route::post("pengerjaan/{group_id}/{instrumen_id}", "store")->name("pengerjaan.store");
        Route::get("rekap-pengerjaan/", "rekap")->name("rekap.index");
        Route::get("rekap-pengerjaan/{group_id}/{instrumen_id}", "show")->name("rekap.show");
    });
});

// Route untuk role admin
Route::prefix("admin")->middleware('auth', 'admin')->group(function () {
    Route::get("dashboard",[AdminDashboardController::class,"index"])->name("admin.dashboard");
    Route::resource("groups", GroupController::class);
    Route::resource("instruments", InstrumenController::class);
    Route::resource('books', BooksController::class);
    Route::controller(CriteriaController::class)->group(function () {
        Route::get("criteria/{instrumen_id}", "index")->name("criteria.index");
        Route::get("criteria/create/{instrumen_id}", "create")->name("criteria.create");
        Route::post("criteria/store/{instrumen_id}", "store")->name("criteria.store");
        Route::get("criteria/{criteria}/show", "show")->name("criteria.show");
        Route::get("criteria/{criteria}/edit", "edit")->name("criteria.edit");
        Route::put("criteria/{criteria}/update", "update")->name("criteria.update");
        Route::delete("criteria/{criteria}/destroy", "destroy")->name("criteria.destroy");
    });
    Route::controller(SubKriteriaController::class)->group(function () {
        Route::get("subCriteria/{instrumen_id}", "index")->name("subCriteria.index");
        Route::get("subCriteria/create/{instrumen_id}", "create")->name("subCriteria.create");
        Route::post("subCriteria/store/{instrumen_id}", "store")->name("subCriteria.store");
        Route::get("subCriteria/{subCriteria}/show", "show")->name("subCriteria.show");
        Route::get("subCriteria/{subCriteria}/edit", "edit")->name("subCriteria.edit");
        Route::put("subCriteria/{subCriteria}/update", "update")->name("subCriteria.update");
        Route::delete("subCriteria/{subCriteria}/destroy", "destroy")->name("subCriteria.destroy");
        Route::post("subCriteriaImport/{instrumen_id}", [SubKriteriaController::class, "import"])->name("subCriteria.import");
    });
    Route::controller(StatementController::class)->group(function () {
        Route::get("statements/{sub_criteria_id}", "index")->name("statements.index");
        Route::get("statements/create/{sub_criteria_id}", "create")->name("statements.create");
        Route::post("statements/store/{sub_criteria_id}", "store")->name("statements.store");
        Route::get("statements/{statements}/show", "show")->name("statements.show");
        Route::get("statements/{statements}/edit", "edit")->name("statements.edit");
        Route::put("statements/{statements}/update", "update")->name("statements.update");
        Route::delete("statements/{statements}/destroy", "destroy")->name("statements.destroy");
    });
    Route::controller(StatementController::class)->group(function () {
        Route::get("statements/{sub_criteria_id}", "index")->name("statements.index");
        Route::get("statements/create/{sub_criteria_id}", "create")->name("statements.create");
        Route::post("statements/store/{sub_criteria_id}", "store")->name("statements.store");
        Route::get("statements/{statements}/show", "show")->name("statements.show");
        Route::get("statements/{statements}/edit", "edit")->name("statements.edit");
        Route::put("statements/{statements}/update", "update")->name("statements.update");
        Route::delete("statements/{statements}/destroy", "destroy")->name("statements.destroy");
    });
    Route::controller(AnswerController::class)->group(function () {
        Route::get("answer/{instrumen_id}", "index")->name("answer.index");
        Route::get("answer/create/{instrumen_id}", "create")->name("answer.create");
        Route::post("answer/store/{instrumen_id}", "store")->name("answer.store");
        Route::get("answer/{answer}/show", "show")->name("answer.show");
        Route::get("answer/{answer}/edit", "edit")->name("answer.edit");
        Route::put("answer/{answer}/update", "update")->name("answer.update");
        Route::delete("answer/{answer}/destroy", "destroy")->name("answer.destroy");
    });

    Route::controller(AdminRekapController::class)->group(function(){
        Route::get("rekap/{user_id}/{instrumen_id}/{group_id}","index")->name("admin.rekap.index");
        Route::get("download-rekap/{instrumen_id}/{group_id}","downloadRekap")->name("admin.download.rekap");
    });
});
