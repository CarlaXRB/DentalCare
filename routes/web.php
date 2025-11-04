<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MultimediaFileController;
use App\Models\Patient;

Route::get('/', function () { return view('welcome'); });

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user->role === 'doctor') {
            return view('dashboard.doctor');
        } elseif ($user->role === 'recepcionist') {
            return view('dashboard.recepcionist');
        } elseif ($user->role === 'admin') {
            return view('dashboard.admin');
        } elseif ($user->role === 'radiology') {
            return view('dashboard.radiology');
        } elseif ($user->role === 'user') {
            $patient = Patient::where('email', $user->email)->first();
            if (!$patient) {
                return view('dashboard.user')->with('error', 'No tienes información de paciente asociada.');
            }
            return view('dashboard.user', compact('patient'));
        } else {
            abort(403, 'Rol no permitido.');
        }
    })->name('dashboard');

    Route::resource('/patient', PatientController::class);
    Route::post('/search', [PatientController::class, 'search'])->name('patient.search');

    Route::get('admin/users/create', [AdminUserController::class, 'create'])->name('admin.create');
    Route::post('admin/users/new', [AdminUserController::class, 'store'])->name('admin.store');
    Route::get('admin/users/list', [AdminUserController::class, 'index'])->name('admin.users');
    Route::delete('/admin/destroy/{user}', [AdminUserController::class, 'destroy'])->name('admin.destroy');
    Route::get('/data', [AdminUserController::class, 'data'])->name('admin.data');

    Route::get('/tool', [ToolController::class, 'index'])->name('tool.index');
    Route::post('/tool/new/tool/{tomography_id}/{ci_patient}/{id}', [ToolController::class, 'new'])->name('tool.new');
    Route::post('/tool/store/tool/{radiography_id}/{tomography_id}/{ci_patient}/{id}', [ToolController::class, 'storeTool'])->name('tool.store');
    Route::post('/tool/store/tomography/{tomography_id}/{ci_patient}/{id}', [ToolController::class, 'storeTomography'])->name('tool.storeTomography');
    Route::get('/tool/show/{tool}', [ToolController::class, 'show'])->name('tool.show');
    Route::get('/tool/ver/{tool}', [ToolController::class, 'ver'])->name('tool.ver');
    Route::get('/tool/search/{id}', [ToolController::class, 'search'])->name('tool.search');
    Route::delete('/tool/destroy/{tool}', [ToolController::class, 'destroy'])->name('tool.destroy');
    Route::post('/save-image', [ToolController::class, 'saveImage'])->name('tool.image');
    Route::get('/tool/measurements/{id}', [ToolController::class, 'measurements'])->name('tool.measurements');
    Route::get('/tool/report/{tool}', [ToolController::class, 'report'])->name('tool.report');
    Route::post('/tool/{tool}/pdfreport', [ToolController::class, 'pdfreport'])->name('tool.pdfreport');

    Route::get('/report/form/{type}/{id}/{name}/{ci}', [ReportController::class, 'show'])->name('report.form');
    Route::post('/report/pdf', [ReportController::class, 'generatePDF'])->name('report.pdfreport');
    Route::get('/report/view/{id}', [ReportController::class, 'view'])->name('report.view');

    Route::get('/calendar', [EventController::class, 'calendar'])->name('events.index');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events/store', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}/update', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/destroy/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

    Route::resource('/budgets', BudgetController::class);
    Route::resource('/treatments', TreatmentController::class);
    Route::post('/budget-search', [BudgetController::class, 'search'])->name('budgets.search');
    Route::post('/treatment-search', [TreatmentController::class, 'search'])->name('treatments.search');
    Route::post('/treatment-download/{id}', [TreatmentController::class, 'downloadPdf'])->name('treatments.downloadPdf');

    Route::prefix('treatments/{treatment}/payments')->group(function () {
        Route::get('/', [PaymentController::class, 'show'])->name('payments.show');
        Route::get('/create', [PaymentController::class, 'create'])->name('payments.create');
        Route::post('/store', [PaymentController::class, 'store'])->name('payments.store');
        Route::delete('/{id}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    });
    Route::post('/payments/search', [PaymentController::class, 'search'])->name('payments.search');
    Route::get('/payments/index', [PaymentController::class, 'index'])->name('payments.index');

    Route::resource('multimedia', MultimediaFileController::class);
    Route::post('multimedia/search', [MultimediaFileController::class, 'search'])->name('multimedia.search');
    Route::get('/multimedia/image/{studyCode}/{fileName}', [MultimediaFileController::class, 'serveImage'])
        ->where('fileName', '.*')->name('multimedia.image');
Route::post('/analyze', [App\Http\Controllers\MedicionesController::class, 'analyze'])->name('analyze');
Route::get('/mediciones', [App\Http\Controllers\MedicionesController::class, 'index'])->name('mediciones');
});

