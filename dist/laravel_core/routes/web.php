<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PublicController;

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/listings', [PublicController::class, 'listings'])->name('listings.index');
Route::get('/listings/{unit}', [PublicController::class, 'show'])->name('listings.show');

// Contact Form
Route::get('/contact', [\App\Http\Controllers\ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'check.password.change'])->name('dashboard');

Route::middleware(['auth', 'check.password.change'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('change-password', [App\Http\Controllers\Auth\ChangePasswordController::class, 'show'])->name('password.change');
    Route::post('change-password', [App\Http\Controllers\Auth\ChangePasswordController::class, 'update'])->name('password.update.forced');
});

Route::middleware(['auth', 'verified', 'admin', 'check.password.change'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('properties', App\Http\Controllers\Admin\PropertyController::class);
    Route::resource('units', App\Http\Controllers\Admin\UnitController::class);
    // Add nested route for creating unit specific to a property if needed, or handle via query param
    Route::get('properties/{property}/units', [App\Http\Controllers\Admin\UnitController::class, 'index'])->name('properties.units.index');

    // Utility Management
    Route::prefix('utilities')->name('utilities.')->group(function () {
        Route::resource('categories', App\Http\Controllers\Admin\UtilityCategoryController::class);
    });

    // Unit Utility Configuration
    Route::get('units/{unit}/utilities', [App\Http\Controllers\Admin\UnitUtilityConfigController::class, 'index'])->name('units.utilities.index');
    Route::post('units/{unit}/utilities', [App\Http\Controllers\Admin\UnitUtilityConfigController::class, 'store'])->name('units.utilities.store');
    Route::delete('units/{unit}/utilities/{config}', [App\Http\Controllers\Admin\UnitUtilityConfigController::class, 'destroy'])->name('units.utilities.destroy');
    Route::get('units/{unit}/utilities/{config}/edit', [App\Http\Controllers\Admin\UnitUtilityConfigController::class, 'edit'])->name('units.utilities.edit');
    Route::put('units/{unit}/utilities/{config}', [App\Http\Controllers\Admin\UnitUtilityConfigController::class, 'update'])->name('units.utilities.update');
    Route::get('units/{unit}/utilities/{config}/entries', [App\Http\Controllers\Admin\UnitUtilityConfigController::class, 'showEntries'])->name('units.utilities.entries');
    Route::get('units/{unit}/utilities/{config}/entries/create', [App\Http\Controllers\Admin\UnitUtilityConfigController::class, 'createEntry'])->name('units.utilities.entries.create');
    Route::post('units/{unit}/utilities/{config}/entries', [App\Http\Controllers\Admin\UnitUtilityConfigController::class, 'storeEntry'])->name('units.utilities.entries.store');
    Route::get('units/{unit}/utilities/{config}/entries/{entry}/edit', [App\Http\Controllers\Admin\UnitUtilityConfigController::class, 'editEntry'])->name('units.utilities.entries.edit');
    Route::put('units/{unit}/utilities/{config}/entries/{entry}', [App\Http\Controllers\Admin\UnitUtilityConfigController::class, 'updateEntry'])->name('units.utilities.entries.update');
    Route::delete('units/{unit}/utilities/{config}/entries/{entry}', [App\Http\Controllers\Admin\UnitUtilityConfigController::class, 'destroyEntry'])->name('units.utilities.entries.destroy');

    // Nebenkosten Period Management
    Route::post('units/{unit}/utilities/{year}/finalize', [App\Http\Controllers\Admin\NebenkostenController::class, 'finalizeYear'])->name('units.utilities.finalize');
    Route::post('units/{unit}/utilities/{year}/publish', [App\Http\Controllers\Admin\NebenkostenController::class, 'publishYear'])->name('units.utilities.publish');
    Route::post('units/{unit}/utilities/{year}/unlock', [App\Http\Controllers\Admin\NebenkostenController::class, 'unlockYear'])->name('units.utilities.unlock');

    // Photo Deletion Routes
    Route::delete('photos/{photo}', [App\Http\Controllers\Admin\PropertyController::class, 'destroyPhoto'])->name('photos.destroy');

    // Tenant Management
    Route::resource('tenants', App\Http\Controllers\Admin\TenantController::class);
    Route::patch('tenants/{user}/toggle-status', [App\Http\Controllers\Admin\TenantController::class, 'toggleStatus'])->name('tenants.toggle-status');
});

// Tenant Routes
Route::middleware(['auth', 'verified', 'tenant', 'check.password.change'])->prefix('portal')->name('tenant.')->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('tenant.utilities.index');
    })->name('dashboard');

    Route::get('utilities', [App\Http\Controllers\Tenant\UtilityEntryController::class, 'index'])->name('utilities.index');
    Route::get('utilities/{config}/entry', [App\Http\Controllers\Tenant\UtilityEntryController::class, 'create'])->name('utilities.create');
    Route::post('utilities/{config}/entry', [App\Http\Controllers\Tenant\UtilityEntryController::class, 'store'])->name('utilities.store');
});

require __DIR__ . '/auth.php';
