<?php

use Illuminate\Support\Facades\Route;

// --- Cache Clearing Route ---
Route::get('/clear-cache', function() {
    $res = [];
    if (function_exists('opcache_reset')) {
        $res['opcache_reset'] = opcache_reset();
    } else {
        $res['opcache_reset'] = 'not available';
    }
    
    // Invalidate migration files from OPCache
    $migrationDir = database_path('migrations');
    if (file_exists($migrationDir)) {
        $invalidated = 0;
        foreach (scandir($migrationDir) as $file) {
            if ($file == '.' || $file == '..') continue;
            $path = $migrationDir . DIRECTORY_SEPARATOR . $file;
            if (function_exists('opcache_invalidate')) {
                if (@opcache_invalidate($path, true)) {
                    $invalidated++;
                }
            }
        }
        $res['invalidated_migrations'] = $invalidated;
    }

    // Invalidate seeder files from OPCache
    $seederDir = database_path('seeders');
    if (file_exists($seederDir)) {
        $invalidatedSeeders = 0;
        foreach (scandir($seederDir) as $file) {
            if ($file == '.' || $file == '..') continue;
            $path = $seederDir . DIRECTORY_SEPARATOR . $file;
            if (function_exists('opcache_invalidate')) {
                if (@opcache_invalidate($path, true)) {
                    $invalidatedSeeders++;
                }
            }
        }
        $res['invalidated_seeders'] = $invalidatedSeeders;
    }
    
    try {
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        $res['optimize_clear'] = \Illuminate\Support\Facades\Artisan::output();
    } catch(\Exception $e) {
        $res['optimize_clear'] = $e->getMessage();
    }
    return response()->json($res);
});

// --- Temporary Database, File, and View Cleanup Script ---
Route::get('/run-cleanup', function () {
    if (function_exists('opcache_reset')) {
        opcache_reset();
    }
    
    $oldMigration = database_path('migrations/2026_06_10_100002_add_featured_image_id_to_products_table.php');
    if (file_exists($oldMigration)) {
        @unlink($oldMigration);
    }
    
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
            'safety', 'document', 'toolbox', 'emergency', 
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

    // 6. Delete Sanctum config if it exists
    @unlink(config_path('sanctum.php'));
    $log[] = "Deleted sanctum config!";

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
        Route::get('products/{product}/customization', [\App\Http\Controllers\Admin\ProductController::class, 'getCustomization'])->name('admin.products.customization');
        Route::resource('products', \App\Http\Controllers\Admin\ProductController::class)->names('admin.products');
        Route::patch('categories/{category}/status', [\App\Http\Controllers\Admin\CategoryController::class, 'toggleStatus'])->name('admin.categories.status');
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->names('admin.categories');
        Route::patch('subcategories/{subcategory}/status', [\App\Http\Controllers\Admin\SubcategoryController::class, 'toggleStatus'])->name('admin.subcategories.status');
        Route::resource('subcategories', \App\Http\Controllers\Admin\SubcategoryController::class)->except(['index', 'create', 'show', 'edit'])->names('admin.subcategories');

        // Site Categories
        Route::patch('site-categories/{id}/status', [\App\Http\Controllers\Admin\SiteCategoryController::class, 'toggleStatus'])->name('admin.site-categories.status');
        Route::patch('site-subcategories/{id}/status', [\App\Http\Controllers\Admin\SiteCategoryController::class, 'toggleSubcategoryStatus'])->name('admin.site-subcategories.status');
        Route::get('site-categories', [\App\Http\Controllers\Admin\SiteCategoryController::class, 'index'])->name('admin.site-categories.index');
        Route::post('site-categories', [\App\Http\Controllers\Admin\SiteCategoryController::class, 'store'])->name('admin.site-categories.store');
        Route::put('site-categories/{id}', [\App\Http\Controllers\Admin\SiteCategoryController::class, 'update'])->name('admin.site-categories.update');
        Route::delete('site-categories/{id}', [\App\Http\Controllers\Admin\SiteCategoryController::class, 'destroy'])->name('admin.site-categories.destroy');
        Route::post('site-subcategories', [\App\Http\Controllers\Admin\SiteCategoryController::class, 'storeSubcategory'])->name('admin.site-subcategories.store');
        Route::put('site-subcategories/{id}', [\App\Http\Controllers\Admin\SiteCategoryController::class, 'updateSubcategory'])->name('admin.site-subcategories.update');
        Route::delete('site-subcategories/{id}', [\App\Http\Controllers\Admin\SiteCategoryController::class, 'destroySubcategory'])->name('admin.site-subcategories.destroy');
        Route::patch('coupons/{coupon}/status', [\App\Http\Controllers\Admin\CouponController::class, 'toggleStatus'])->name('admin.coupons.status');
        Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class)->names('admin.coupons');
        Route::patch('offers/{offer}/status', [\App\Http\Controllers\Admin\OfferController::class, 'toggleStatus'])->name('admin.offers.status');
        Route::resource('offers', \App\Http\Controllers\Admin\OfferController::class)->names('admin.offers');

        // Orders & Reports
        Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders.index');
        Route::delete('/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'destroy'])->name('admin.orders.destroy');
        Route::patch('/orders/{order}/order-status', [\App\Http\Controllers\Admin\OrderController::class, 'updateOrderStatus'])->name('admin.orders.order-status');
        Route::patch('/orders/{order}/payment-status', [\App\Http\Controllers\Admin\OrderController::class, 'updatePaymentStatus'])->name('admin.orders.payment-status');
        Route::get('/orders/{order}/receipt', [\App\Http\Controllers\Admin\OrderController::class, 'receipt'])->name('admin.orders.receipt');
        
        Route::get('/reports/revenue', [\App\Http\Controllers\Admin\RevenueReportController::class, 'index'])->name('admin.reports.revenue');

        // Settings
        Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('admin.settings.index');
        Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('admin.settings.update');

        // Dynamic Pages
        Route::resource('pages', \App\Http\Controllers\Admin\PageController::class)->names('admin.pages');

        // Home Content (Hero, Gifts, FAQs, Features, Promo, Gift Giver, Newsletter, Footer)
        Route::get('/home-content', [\App\Http\Controllers\Admin\HomeContentController::class, 'index'])->name('admin.home-content.index');
        Route::post('/home-content/hero', [\App\Http\Controllers\Admin\HomeContentController::class, 'storeHero'])->name('admin.home-content.hero.store');
        Route::delete('/home-content/hero/{hero}', [\App\Http\Controllers\Admin\HomeContentController::class, 'destroyHero'])->name('admin.home-content.hero.destroy');
        Route::post('/home-content/gift', [\App\Http\Controllers\Admin\HomeContentController::class, 'storeGift'])->name('admin.home-content.gift.store');
        Route::delete('/home-content/gift/{gift}', [\App\Http\Controllers\Admin\HomeContentController::class, 'destroyGift'])->name('admin.home-content.gift.destroy');
        Route::post('/home-content/faq', [\App\Http\Controllers\Admin\HomeContentController::class, 'storeFaq'])->name('admin.home-content.faq.store');
        Route::delete('/home-content/faq/{faq}', [\App\Http\Controllers\Admin\HomeContentController::class, 'destroyFaq'])->name('admin.home-content.faq.destroy');
        
        // Highlight Features
        Route::post('/home-content/feature', [\App\Http\Controllers\Admin\HomeContentController::class, 'storeFeature'])->name('admin.home-content.feature.store');
        Route::delete('/home-content/feature/{feature}', [\App\Http\Controllers\Admin\HomeContentController::class, 'destroyFeature'])->name('admin.home-content.feature.destroy');

        // Promo Section
        Route::post('/home-content/promo', [\App\Http\Controllers\Admin\HomeContentController::class, 'updatePromo'])->name('admin.home-content.promo.update');

        // Legendary Gift Giver Section
        Route::post('/home-content/gift-giver', [\App\Http\Controllers\Admin\HomeContentController::class, 'updateGiftGiver'])->name('admin.home-content.gift-giver.update');

        // Newsletter Text Settings & Subscribers
        Route::post('/home-content/newsletter', [\App\Http\Controllers\Admin\HomeContentController::class, 'updateNewsletter'])->name('admin.home-content.newsletter.update');
        Route::delete('/home-content/subscriber/{subscriber}', [\App\Http\Controllers\Admin\HomeContentController::class, 'destroySubscriber'])->name('admin.home-content.subscriber.destroy');

        // Footer Section & Items
        Route::post('/home-content/footer-section', [\App\Http\Controllers\Admin\HomeContentController::class, 'storeFooterSection'])->name('admin.home-content.footer-section.store');
        Route::put('/home-content/footer-section/{section}', [\App\Http\Controllers\Admin\HomeContentController::class, 'updateFooterSection'])->name('admin.home-content.footer-section.update');
        Route::post('/home-content/footer-section/reorder', [\App\Http\Controllers\Admin\HomeContentController::class, 'reorderFooterSections'])->name('admin.home-content.footer-section.reorder');
        Route::delete('/home-content/footer-section/{section}', [\App\Http\Controllers\Admin\HomeContentController::class, 'destroyFooterSection'])->name('admin.home-content.footer-section.destroy');
        Route::post('/home-content/footer-item', [\App\Http\Controllers\Admin\HomeContentController::class, 'storeFooterItem'])->name('admin.home-content.footer-item.store');
        Route::put('/home-content/footer-item/{item}', [\App\Http\Controllers\Admin\HomeContentController::class, 'updateFooterItem'])->name('admin.home-content.footer-item.update');
        Route::delete('/home-content/footer-item/{item}', [\App\Http\Controllers\Admin\HomeContentController::class, 'destroyFooterItem'])->name('admin.home-content.footer-item.destroy');

        // Footer Brand Info & Social Links
        Route::post('/home-content/footer-brand-socials', [\App\Http\Controllers\Admin\HomeContentController::class, 'updateFooterBrandSocials'])->name('admin.home-content.footer-brand-socials.update');

        // Gifts Management
        Route::resource('gifts', \App\Http\Controllers\Admin\GiftController::class)->names('admin.gifts');

        // Contact Messages
        Route::resource('contact-messages', \App\Http\Controllers\Admin\ContactMessageController::class)->only(['index', 'show', 'destroy'])->names('admin.contact-messages');
    });
});

