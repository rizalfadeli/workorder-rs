<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PublicWorkOrderController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\User;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/tracking/search', [PublicWorkOrderController::class, 'trackingAjax'])
    ->name('tracking.ajax');
Route::get('/', [PublicWorkOrderController::class, 'landing'])
    ->name('landing');

Route::get('/lapor', [PublicWorkOrderController::class, 'create'])
    ->name('public.report.create');

Route::post('/lapor', [PublicWorkOrderController::class, 'store'])
    ->name('public.report.store');

Route::get('/track', [PublicWorkOrderController::class, 'track'])
    ->name('public.report.track');


/*
|--------------------------------------------------------------------------
| Auth Routes (TANPA middleware guest)
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Admin Routes (TANPA middleware)
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('work-orders', Admin\WorkOrderController::class)
        ->except(['create', 'store']);

    Route::patch('work-orders/{workOrder}/status',
        [Admin\WorkOrderController::class, 'updateStatus'])
        ->name('work-orders.update-status');

    Route::post('work-orders/{workOrder}/upload-pdf',
        [Admin\WorkOrderController::class, 'uploadPdf'])
        ->name('work-orders.upload-pdf');

    // UBAH BAGIAN INI: Menggunakan match agar mendukung GET dan POST
    Route::match(['get', 'post'], 'work-orders/{workOrder}/generate-berita-acara',
        [Admin\WorkOrderController::class, 'generateBeritaAcara'])
        ->name('work-orders.generate-berita-acara');

    Route::resource('technicians', Admin\TechnicianController::class);

    Route::resource('users', Admin\UserController::class);

    Route::get('work-orders/{workOrder}/chat',
        [Admin\ChatController::class, 'show'])
        ->name('work-orders.chat');

    Route::post('work-orders/{workOrder}/chat',
        [Admin\ChatController::class, 'sendMessage'])
        ->name('work-orders.chat.send');

    Route::get('work-orders/{workOrder}/chat/messages',
        [Admin\ChatController::class, 'getMessages'])
        ->name('work-orders.chat.messages');

    Route::get('/unread-count',
        [Admin\WorkOrderController::class, 'unreadCount']
    )->name('unread-count');
});


/*
|--------------------------------------------------------------------------
| User Routes (TANPA middleware)
|--------------------------------------------------------------------------
*/

Route::prefix('user')
    ->name('user.')
    ->middleware(['auth', 'role:user'])
    ->group(function () {

    Route::get('/dashboard', [User\DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('work-orders', User\WorkOrderController::class)
        ->only(['index', 'create', 'store', 'show']);

    Route::get('work-orders/{workOrder}/chat',
        [User\ChatController::class, 'show'])
        ->name('work-orders.chat');

    Route::post('work-orders/{workOrder}/chat',
        [User\ChatController::class, 'sendMessage'])
        ->name('work-orders.chat.send');

    Route::get('work-orders/{workOrder}/chat/messages',
        [User\ChatController::class, 'getMessages'])
        ->name('work-orders.chat.messages');

    Route::get('/unread-count', function () {
        $count = \App\Models\ChatMessage::count();
        return response()->json(['count' => $count]);
    })->name('unread-count');
});

Route::get('/test-email', function () {
    Mail::raw('Tes Email Laravel', function ($message) {
        $message->to('rizalfadeli12345@gmail.com')
                ->subject('Test Email');
    });
    return 'Email dikirim';
});



use App\Exports\CompletedWorkOrdersExport;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/admin/work-orders/export/completed', function () {
    return Excel::download(new CompletedWorkOrdersExport, 'work-orders-selesai.xlsx');
})->name('admin.work-orders.export.completed');

