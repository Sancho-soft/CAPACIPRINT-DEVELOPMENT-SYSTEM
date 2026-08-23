<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// ── Customer Controllers ──────────────────────────────────────────
use App\Http\Controllers\Customer\DashboardController as CustDashboard;
use App\Http\Controllers\Customer\PrintRequestController as CustPrintRequest;
use App\Http\Controllers\Customer\OrderController as CustOrder;
use App\Http\Controllers\Customer\QuotationController as CustQuotation;
use App\Http\Controllers\Customer\PaymentController as CustPayment;
use App\Http\Controllers\Customer\NotificationController as CustNotification;
use App\Http\Controllers\Customer\ClaimController as CustClaim;
use App\Http\Controllers\Customer\ProfileController as CustProfile;

// ── Staff Controllers ─────────────────────────────────────────────
use App\Http\Controllers\Staff\DashboardController as StaffDashboard;
use App\Http\Controllers\Staff\CustomerController as StaffCustomer;
use App\Http\Controllers\Staff\PrintRequestController as StaffPrintRequest;
use App\Http\Controllers\Staff\QuotationController as StaffQuotation;
use App\Http\Controllers\Staff\OrderController as StaffOrder;
use App\Http\Controllers\Staff\NotificationController as StaffNotification;

// ── Manager Controllers ───────────────────────────────────────────
use App\Http\Controllers\Manager\DashboardController as MgrDashboard;
use App\Http\Controllers\Manager\CapacityController as MgrCapacity;
use App\Http\Controllers\Manager\BranchRecommendationController as MgrRecommendation;
use App\Http\Controllers\Manager\ProductionPlanningController as MgrProductionPlanning;
use App\Http\Controllers\Manager\WorkloadController as MgrWorkload;
use App\Http\Controllers\Manager\BranchController as MgrBranch;
use App\Http\Controllers\Manager\ReportController as MgrReport;

// ── Production Controllers ────────────────────────────────────────
use App\Http\Controllers\Production\DashboardController as ProdDashboard;
use App\Http\Controllers\Production\JobController as ProdJob;
use App\Http\Controllers\Production\NotificationController as ProdNotification;

// ── Inventory Controllers ─────────────────────────────────────────
use App\Http\Controllers\Inventory\DashboardController as InvDashboard;
use App\Http\Controllers\Inventory\MaterialController as InvMaterial;
use App\Http\Controllers\Inventory\InventoryController as InvInventory;
use App\Http\Controllers\Inventory\StockMovementController as InvStock;
use App\Http\Controllers\Inventory\ReportController as InvReport;

// ── Management Controllers ────────────────────────────────────────
use App\Http\Controllers\Management\DashboardController as MgmtDashboard;
use App\Http\Controllers\Management\OrderController as MgmtOrder;
use App\Http\Controllers\Management\BranchController as MgmtBranch;
use App\Http\Controllers\Management\ProductionController as MgmtProduction;
use App\Http\Controllers\Management\InventoryController as MgmtInventory;
use App\Http\Controllers\Management\ReportController as MgmtReport;

use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────────────────────────
Route::redirect('/', '/login');

// ─────────────────────────────────────────────────────────────────
// GUEST ONLY — Auth
// ─────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',     [LoginController::class,    'showLoginForm'])->name('login');
    Route::post('/login',    [LoginController::class,    'login'])->name('login.submit');
    Route::get('/register',  [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')->name('logout');

// ─────────────────────────────────────────────────────────────────
// CUSTOMER (role: customer)
// ─────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard',                                  [CustDashboard::class,    'index'])->name('dashboard');
    Route::get('/print-requests',                             [CustPrintRequest::class, 'index'])->name('print-requests.index');
    Route::get('/print-requests/create',                      [CustPrintRequest::class, 'create'])->name('print-requests.create');
    Route::post('/print-requests',                            [CustPrintRequest::class, 'store'])->name('print-requests.store');
    Route::get('/print-requests/{printRequest}',              [CustPrintRequest::class, 'show'])->name('print-requests.show');
    Route::post('/print-requests/{printRequest}/cancel',      [CustPrintRequest::class, 'cancel'])->name('print-requests.cancel');
    Route::get('/orders',                                     [CustOrder::class,        'index'])->name('orders.index');
    Route::get('/orders/{order}',                             [CustOrder::class,        'show'])->name('orders.show');
    Route::get('/orders/{order}/tracking',                    [CustOrder::class,        'tracking'])->name('orders.tracking');
    Route::get('/quotations',                                 [CustQuotation::class,    'index'])->name('quotations.index');
    Route::get('/quotations/{quotation}',                     [CustQuotation::class,    'show'])->name('quotations.show');
    Route::post('/quotations/{quotation}/confirm',            [CustQuotation::class,    'confirm'])->name('quotations.confirm');
    Route::post('/quotations/{quotation}/decline',            [CustQuotation::class,    'decline'])->name('quotations.decline');
    Route::get('/quotations/{quotation}/download',            [CustQuotation::class,    'download'])->name('quotations.download');
    Route::get('/payments',                                   [CustPayment::class,      'index'])->name('payments.index');
    Route::get('/payments/{payment}',                         [CustPayment::class,      'show'])->name('payments.show');
    Route::post('/payments/{payment}/submit',                 [CustPayment::class,      'submit'])->name('payments.submit');
    Route::get('/notifications',                              [CustNotification::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read',                   [CustNotification::class, 'markRead'])->name('notifications.markRead');
    Route::post('/notifications/read-all',                    [CustNotification::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::get('/claiming',                                   [CustClaim::class,        'index'])->name('claiming.index');
    Route::get('/claiming/{orderId}',                         [CustClaim::class,        'show'])->name('claiming.show');
    Route::get('/profile',                                    [CustProfile::class,      'index'])->name('profile.index');
    Route::put('/profile',                                    [CustProfile::class,      'update'])->name('profile.update');
    Route::put('/profile/password',                           [CustProfile::class,      'updatePassword'])->name('profile.password');
    Route::get('/help',                                       fn() => view('customer.help'))->name('help');
});

// ─────────────────────────────────────────────────────────────────
// SALES / CUSTOMER SERVICE STAFF (role: staff)
// ─────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard',                             [StaffDashboard::class,    'index'])->name('dashboard');
    Route::get('/customers',                             [StaffCustomer::class,     'index'])->name('customers.index');
    Route::get('/customers/{user}',                      [StaffCustomer::class,     'show'])->name('customers.show');
    Route::get('/print-requests',                        [StaffPrintRequest::class, 'index'])->name('print-requests.index');
    Route::get('/print-requests/{printRequest}',         [StaffPrintRequest::class, 'show'])->name('print-requests.show');
    Route::post('/print-requests/{printRequest}/verify', [StaffPrintRequest::class, 'verify'])->name('print-requests.verify');
    Route::get('/quotations',                            [StaffQuotation::class,    'index'])->name('quotations.index');
    Route::get('/quotations/create',                     [StaffQuotation::class,    'create'])->name('quotations.create');
    Route::post('/quotations',                           [StaffQuotation::class,    'store'])->name('quotations.store');
    Route::get('/quotations/{quotation}',                [StaffQuotation::class,    'show'])->name('quotations.show');
    Route::put('/quotations/{quotation}',                [StaffQuotation::class,    'update'])->name('quotations.update');
    Route::post('/quotations/{quotation}/send',          [StaffQuotation::class,    'send'])->name('quotations.send');
    Route::get('/quotations/{quotation}/download',       [StaffQuotation::class,    'download'])->name('quotations.download');
    Route::get('/orders',                                [StaffOrder::class,        'index'])->name('orders.index');
    Route::get('/orders/{order}',                        [StaffOrder::class,        'show'])->name('orders.show');
    Route::post('/orders/{order}/confirm-payment',       [StaffOrder::class,        'confirmPayment'])->name('orders.confirm-payment');
    Route::post('/orders/{order}/create-job',            [StaffOrder::class,        'createProductionJob'])->name('orders.create-job');

    // Claim Verification & QR Scanner
    Route::get('/claim-scanner',                         [StaffOrder::class,        'claimScanner'])->name('claim-scanner');
    Route::post('/claim-verify',                         [StaffOrder::class,        'claimVerify'])->name('claim-verify');

    // Pricing Rules Matrix Management
    Route::get('/pricing-rules',                         [StaffQuotation::class,    'pricingRulesIndex'])->name('pricing-rules.index');
    Route::post('/pricing-rules',                        [StaffQuotation::class,    'pricingRulesStore'])->name('pricing-rules.store');
    Route::put('/pricing-rules/{pricingRule}',           [StaffQuotation::class,    'pricingRulesUpdate'])->name('pricing-rules.update');

    Route::get('/notifications',                         [StaffNotification::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read',              [StaffNotification::class, 'markRead'])->name('notifications.markRead');
    Route::post('/notifications/read-all',               [StaffNotification::class, 'markAllRead'])->name('notifications.markAllRead');
});

// ─────────────────────────────────────────────────────────────────
// BRANCH MANAGER / PRODUCTION SUPERVISOR (role: manager)
// ─────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard',                                [MgrDashboard::class,       'index'])->name('dashboard');
    Route::get('/capacity',                                 [MgrCapacity::class,        'index'])->name('capacity.index');
    Route::get('/capacity/evaluate/{printRequest}',         [MgrCapacity::class,        'evaluate'])->name('capacity.evaluate');
    Route::post('/capacity/evaluate/{printRequest}/run',    [MgrCapacity::class,        'runEvaluation'])->name('capacity.run-evaluation');
    Route::get('/branch-recommendations',                   [MgrRecommendation::class,  'index'])->name('recommendations.index');
    Route::get('/branch-recommendations/{recommendation}',  [MgrRecommendation::class,  'show'])->name('recommendations.show');
    Route::post('/branch-recommendations/{recommendation}/confirm',  [MgrRecommendation::class, 'confirm'])->name('recommendations.confirm');
    Route::get('/production-planning',                      [MgrProductionPlanning::class, 'index'])->name('production-planning.index');
    Route::get('/production-planning/{productionJob}',      [MgrProductionPlanning::class, 'show'])->name('production-planning.show');
    Route::post('/production-planning/{productionJob}/assign', [MgrProductionPlanning::class, 'assign'])->name('production-planning.assign');
    Route::get('/workload',                                 [MgrWorkload::class,        'index'])->name('workload.index');
    Route::get('/branches',                                 [MgrBranch::class,          'index'])->name('branches.index');
    Route::get('/branches/{branch}',                        [MgrBranch::class,          'show'])->name('branches.show');
    Route::get('/reports',                                  [MgrReport::class,          'index'])->name('reports.index');
    Route::get('/reports/production',                       [MgrReport::class,          'production'])->name('reports.production');
    Route::get('/reports/capacity',                         [MgrReport::class,          'capacity'])->name('reports.capacity');
});

// ─────────────────────────────────────────────────────────────────
// PRODUCTION STAFF (role: production)
// ─────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:production'])->prefix('production')->name('production.')->group(function () {
    Route::get('/dashboard',                    [ProdDashboard::class,   'index'])->name('dashboard');
    Route::get('/jobs',                         [ProdJob::class,         'index'])->name('jobs.index');
    Route::get('/jobs/{productionJob}',         [ProdJob::class,         'show'])->name('jobs.show');
    Route::get('/jobs/{productionJob}/status',  [ProdJob::class,         'statusForm'])->name('jobs.status-form');
    Route::post('/jobs/{productionJob}/status', [ProdJob::class,         'updateStatus'])->name('jobs.update-status');
    Route::get('/notifications',                [ProdNotification::class,'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read',     [ProdNotification::class,'markRead'])->name('notifications.markRead');
});

// ─────────────────────────────────────────────────────────────────
// INVENTORY STAFF (role: inventory)
// ─────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:inventory'])->prefix('inventory')->name('inventory.')->group(function () {
    Route::get('/dashboard',                            [InvDashboard::class, 'index'])->name('dashboard');
    Route::get('/materials',                            [InvMaterial::class,  'index'])->name('materials.index');
    Route::get('/materials/create',                     [InvMaterial::class,  'create'])->name('materials.create');
    Route::post('/materials',                           [InvMaterial::class,  'store'])->name('materials.store');
    Route::get('/materials/{material}',                 [InvMaterial::class,  'show'])->name('materials.show');
    Route::get('/materials/{material}/edit',            [InvMaterial::class,  'edit'])->name('materials.edit');
    Route::put('/materials/{material}',                 [InvMaterial::class,  'update'])->name('materials.update');
    Route::get('/stock',                                [InvInventory::class, 'index'])->name('stock.index');
    Route::put('/stock/{branchInventory}',              [InvInventory::class, 'update'])->name('stock.update');
    Route::get('/stock-movements',                      [InvStock::class,     'index'])->name('stock-movements.index');
    Route::get('/stock-movements/create',               [InvStock::class,     'create'])->name('stock-movements.create');
    Route::post('/stock-movements',                     [InvStock::class,     'store'])->name('stock-movements.store');
    Route::get('/availability',                         [InvInventory::class, 'availability'])->name('availability');
    Route::get('/reports',                              [InvReport::class,    'index'])->name('reports.index');
});

// ─────────────────────────────────────────────────────────────────
// OWNER / MANAGEMENT (role: management)
// ─────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:management'])->prefix('management')->name('management.')->group(function () {
    Route::get('/dashboard',   [MgmtDashboard::class,   'index'])->name('dashboard');
    Route::get('/orders',      [MgmtOrder::class,       'index'])->name('orders.index');
    Route::get('/orders/{order}',[MgmtOrder::class,     'show'])->name('orders.show');
    Route::get('/branches',    [MgmtBranch::class,      'index'])->name('branches.index');
    Route::get('/branches/{branch}', [MgmtBranch::class,'show'])->name('branches.show');
    Route::get('/capacity',    fn() => view('management.capacity'))->name('capacity');
    Route::get('/production',  [MgmtProduction::class,  'index'])->name('production.index');
    Route::get('/inventory',   [MgmtInventory::class,   'index'])->name('inventory.index');
    Route::get('/reports',     [MgmtReport::class,      'index'])->name('reports.index');
    Route::get('/reports/orders',      [MgmtReport::class, 'orders'])->name('reports.orders');
    Route::get('/reports/production',  [MgmtReport::class, 'production'])->name('reports.production');
    Route::get('/reports/inventory',   [MgmtReport::class, 'inventory'])->name('reports.inventory');
    Route::get('/reports/capacity',    [MgmtReport::class, 'capacity'])->name('reports.capacity');
});

// ─────────────────────────────────────────────────────────────────
// DEMO ROLE SWITCHER (For Testing & Presentation)
// ─────────────────────────────────────────────────────────────────
Route::middleware('auth')->get('/switch-role/{role}', function ($role) {
    if (in_array($role, ['customer', 'staff', 'manager', 'production', 'inventory', 'management', 'admin'])) {
        $user = \App\Models\User::where('role', $role)->first();
        if ($user) {
            auth()->login($user);
            return redirect()->to(match($role) {
                'customer'   => route('customer.dashboard'),
                'staff'      => route('staff.dashboard'),
                'manager'    => route('manager.dashboard'),
                'production' => route('production.dashboard'),
                'inventory'  => route('inventory.dashboard'),
                'management' => route('management.dashboard'),
                default      => route('admin.dashboard')
            });
        }
    }
    return redirect()->back();
})->name('demo.switch-role');

// ── Designer Controllers ──────────────────────────────────────────
use App\Http\Controllers\Designer\DesignController as DesignerController;

// ── Admin Controllers ─────────────────────────────────────────────
use App\Http\Controllers\Admin\UserController as AdminUser;
use App\Http\Controllers\Admin\BranchController as AdminBranch;
use App\Http\Controllers\Admin\EmployeeController as AdminEmployee;

// ─────────────────────────────────────────────────────────────────
// DESIGN & LAYOUT MANAGEMENT (role: designer, staff, admin, superadmin)
// ─────────────────────────────────────────────────────────────────
Route::middleware(['auth'])->prefix('designer')->name('designer.')->group(function () {
    Route::get('/',                                    [DesignerController::class, 'index'])->name('index');
    Route::get('/{printRequest}',                      [DesignerController::class, 'show'])->name('show');
    Route::post('/{printRequest}/proofs',              [DesignerController::class, 'storeProof'])->name('storeProof');
    Route::post('/proofs/{designProof}/production-file',[DesignerController::class, 'uploadProductionFile'])->name('uploadProductionFile');
});

// Customer Proof Review Route
Route::middleware(['auth'])->post('/customer/proofs/{designProof}/review', [DesignerController::class, 'customerReview'])->name('customer.proof.review');

// ─────────────────────────────────────────────────────────────────
// ADMIN / SUPER ADMIN (role: admin, superadmin)
// ─────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
    Route::resource('users', AdminUser::class);
    Route::resource('branches', AdminBranch::class);
    Route::resource('employees', AdminEmployee::class);
});
