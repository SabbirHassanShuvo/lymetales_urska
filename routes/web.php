<?php

use Illuminate\Support\Facades\Route;

// --- Temporary Database, File, and View Cleanup Script ---
Route::get('/run-cleanup', function () {
    $log = [];
    
    // 1. Delete Old Models
    $oldModels = [
        'Complaint.php', 'ContactCategory.php', 'ContactItem.php', 'DocumentCategory.php', 
        'DocumentItem.php', 'EmergencyCategory.php', 'EmergencyItem.php', 'Handbook.php', 
        'HandbookChapter.php', 'HandbookLesson.php', 'Idea.php', 'IncidentReport.php', 
        'SafetyCategory.php', 'SafetyFile.php', 'ToolboxCategory.php', 'ToolboxItem.php', 
        'WorkplaceInspection.php'
    ];
    foreach ($oldModels as $model) {
        $path = app_path("Models/{$model}");
        if (file_exists($path)) {
            unlink($path);
            $log[] = "Deleted model: {$model}";
        }
    }

    // 2. Delete Old Controllers
    $oldControllers = [
        'ComplaintController.php', 'ContactController.php', 'DocumentController.php', 
        'EmergencyController.php', 'HandbookController.php', 'IdeaController.php', 
        'IncidentReportController.php', 'SafetyController.php', 'ToolboxController.php', 
        'WorkplaceInspectionController.php'
    ];
    foreach ($oldControllers as $controller) {
        $path = app_path("Http/Controllers/Admin/{$controller}");
        if (file_exists($path)) {
            unlink($path);
            $log[] = "Deleted controller: {$controller}";
        }
    }

    // 3. Delete Old Views (recursive directory delete helper)
    $deleteDir = function ($dir) use (&$deleteDir) {
        if (!file_exists($dir)) return true;
        if (!is_dir($dir)) return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;
            if (!$deleteDir($dir . DIRECTORY_SEPARATOR . $item)) return false;
        }
        return rmdir($dir);
    };

    $oldViewDirs = [
        'complaints', 'contacts', 'documents', 'emergency', 'handbooks', 
        'ideas', 'incidents', 'inspections', 'safety', 'toolboxes'
    ];
    foreach ($oldViewDirs as $dir) {
        $path = resource_path("views/admin/{$dir}");
        if (file_exists($path)) {
            $deleteDir($path);
            $log[] = "Deleted view directory: {$dir}";
        }
    }

    // 4. Delete Old Seeders
    $oldSeeders = [
        'ContactSeeder.php', 'DocumentSeeder.php', 'EmergencySeeder.php', 
        'SafetySeeder.php', 'ToolboxSeeder.php'
    ];
    foreach ($oldSeeders as $seeder) {
        $path = database_path("seeders/{$seeder}");
        if (file_exists($path)) {
            unlink($path);
            $log[] = "Deleted seeder: {$seeder}";
        }
    }

    // 5. Delete Old Migrations (match pattern)
    $migrationDir = database_path('migrations');
    if (file_exists($migrationDir)) {
        $patterns = [
            'safety', 'document', 'toolbox', 'contact', 'emergency', 
            'workplace_inspections', 'complaints', 'ideas', 'incident_reports', 'handbooks'
        ];
        foreach (scandir($migrationDir) as $file) {
            if ($file == '.' || $file == '..') continue;
            foreach ($patterns as $pattern) {
                if (strpos($file, $pattern) !== false) {
                    unlink($migrationDir . DIRECTORY_SEPARATOR . $file);
                    $log[] = "Deleted migration: {$file}";
                    break;
                }
            }
        }
    }

    // 6. Delete Sanctum config and API routes if they exist
    @unlink(base_path('routes/api.php'));
    @unlink(config_path('sanctum.php'));
    $log[] = "Deleted api routes and sanctum config!";

    // 7. Pre-load seeders to bypass Composer classmap autoload cache
    try {
        if (file_exists(database_path('seeders/AdminSeeder.php'))) {
            require_once database_path('seeders/AdminSeeder.php');
            $log[] = "Pre-loaded AdminSeeder.";
        }
        if (file_exists(database_path('seeders/ECommerceSeeder.php'))) {
            require_once database_path('seeders/ECommerceSeeder.php');
            $log[] = "Pre-loaded ECommerceSeeder.";
        }
        if (file_exists(database_path('seeders/DatabaseSeeder.php'))) {
            require_once database_path('seeders/DatabaseSeeder.php');
            $log[] = "Pre-loaded DatabaseSeeder.";
        }
    } catch (\Exception $e) {
        $log[] = "Pre-loading seeders failed: " . $e->getMessage();
    }

    // 8. Run migrate:fresh --seed
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--seed' => true]);
        $log[] = "Database successfully reset and seeded (Admin user created)!";
    } catch (\Exception $e) {
        $log[] = "Migration failed: " . $e->getMessage();
    }

    // 8. Clear view cache
    try {
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        $log[] = "Views cache cleared!";
    } catch (\Exception $e) {
        $log[] = "View clear failed: " . $e->getMessage();
    }

    return response()->json([
        'success' => true,
        'message' => 'Cleanup, migration, and seeding complete! You can now access the admin panel.',
        'log' => $log
    ]);
});

Route::get('/', function () {
    return redirect()->route('admin.login');
});

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'nl'])) {
        session(['locale' => $locale]);
    }
    return back();
})->name('lang.switch');

// Fallback login route for Laravel Authenticate Middleware redirection
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// Admin Authentication Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Admin\AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [\App\Http\Controllers\Admin\AuthController::class, 'login']);
    Route::post('/logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('admin.logout');

    // Protected Admin Routes
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/users-request', [\App\Http\Controllers\Admin\UserRequestController::class, 'index'])->name('admin.users.index');
        Route::post('/users-request/{id}/approve', [\App\Http\Controllers\Admin\UserRequestController::class, 'approve'])->name('admin.users.approve');
        Route::post('/users-request/{id}/reject', [\App\Http\Controllers\Admin\UserRequestController::class, 'reject'])->name('admin.users.reject');
        Route::put('/users-request/{id}', [\App\Http\Controllers\Admin\UserRequestController::class, 'update'])->name('admin.users.update');
        Route::delete('/users-request/{id}', [\App\Http\Controllers\Admin\UserRequestController::class, 'destroy'])->name('admin.users.destroy');

        // Products, Categories & Coupons
        Route::patch('products/{product}/status', [\App\Http\Controllers\Admin\ProductController::class, 'toggleStatus'])->name('admin.products.status');
        Route::resource('products', \App\Http\Controllers\Admin\ProductController::class)->names('admin.products');
        Route::patch('categories/{category}/status', [\App\Http\Controllers\Admin\CategoryController::class, 'toggleStatus'])->name('admin.categories.status');
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->names('admin.categories');
        Route::patch('coupons/{coupon}/status', [\App\Http\Controllers\Admin\CouponController::class, 'toggleStatus'])->name('admin.coupons.status');
        Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class)->names('admin.coupons');
    });
});

