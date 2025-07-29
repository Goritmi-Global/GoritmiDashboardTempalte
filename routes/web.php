<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Inertia\Inertia;

// Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GlobalPageController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;

use App\Http\Controllers\Accounting\{
    ExpenseTypeController,
    IncomeTypeController,
    ExpenseController,
    IncomeController,
    AccountController,
    BankController,
    CashbookController,
    ForecastingController,
    TransactionController,
    ReportController
};

Route::resource('expense-types', ExpenseTypeController::class);
Route::resource('income-types', IncomeTypeController::class);
Route::resource('expenses', ExpenseController::class);
Route::resource('incomes', IncomeController::class);
Route::resource('accounts', AccountController::class);
Route::resource('banks', BankController::class);
Route::resource('cashbooks', CashbookController::class);
Route::resource('forecastings', ForecastingController::class);


// Public
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Authenticated Dashboard
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin-Only Routes (Role: admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
   
});

// Authenticated Routes (Any Logged In User)
Route::middleware('auth')->group(function () {
     // User Management
    Route::resource('users', UserController::class);
    Route::get('users/{user}/roles', [UserController::class, 'editRoles'])->name('users.roles.edit');
    Route::put('users/{user}/roles', [UserController::class, 'updateRoles'])->name('users.roles.update');

    // Role Management
        Route::get('roles-permissions', [RoleController::class, 'roles_and_permissions'])->name('roles-permissions');

        
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::get('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    
    // Permission Management
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::get('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');


    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard + Modules
    Route::get('/dashboard', [GlobalPageController::class, 'dashboard'])->name('dashboard');
    Route::get('/crm', [GlobalPageController::class, 'crm'])->name('crm');
    Route::get('/hr', [GlobalPageController::class, 'hr'])->name('hr');

    // Projects
    Route::get('/projects', [GlobalPageController::class, 'projects'])->name('projects');
    Route::get('/projects/new', [GlobalPageController::class, 'projects'])->name('projects.new');
    Route::get('/projects/existing', [GlobalPageController::class, 'projects'])->name('projects.existing');
    Route::get('/projects/dealing', [GlobalPageController::class, 'projects'])->name('projects.dealing');

    // Accounting
    Route::get('/accounting/create', [GlobalPageController::class, 'createAccounting'])->name('accounting.create');
    Route::post('/accounting/store', [GlobalPageController::class, 'storeAccounting'])->name('accounting.store');
    Route::get('/accounting/show/{id}', [GlobalPageController::class, 'showAccounting'])->name('accounting.show');
    Route::delete('/accounting/delete/{id}', [GlobalPageController::class, 'deleteAccounting'])->name('accounting.delete');
    Route::get('/accounting', [GlobalPageController::class, 'accounting'])->name('accounting');
 
    
    
    
    
});
// Resource Controllers for Accounting

Route::prefix('accounting')
    ->middleware('auth')
    ->name('accounting.') // ✅ Add this line
    ->group(function () {
        Route::resource('expense-types', ExpenseTypeController::class)->except(['show']);
        Route::resource('income-types', IncomeTypeController::class)->except(['show']);

        Route::resource('expenses', ExpenseController::class);
        Route::get('/accounting/expense-types/{id}/details', [ExpenseTypeController::class, 'details'])->name('expense-types.details');

        Route::resource('incomes', IncomeController::class);
        Route::get('/accounting/income-types/{id}/details', [IncomeTypeController::class, 'details'])->name('income-types.details');
    

        Route::resource('accounts', AccountController::class);
        Route::resource('banks', BankController::class);
        Route::resource('forecastings', ForecastingController::class);
        Route::resource('transactions', TransactionController::class); // ← now this becomes `accounting.transactions.*`
        Route::resource('cashbooks', CashbookController::class);
        Route::put('cashbook/{cashbook}', [CashbookController::class, 'update'])->name('cashbook.update');


        Route::resource('reports', ReportController::class);
Route::get('forecasting', [ReportController::class, 'generateMonthlyExpenseForecast'])
     ->name('forecasting.index');
 



    });




// Auth scaffolding
require __DIR__ . '/auth.php';
