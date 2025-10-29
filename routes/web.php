<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RadiographyController;
use App\Http\Controllers\TomographyController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DicomController;

use App\Models\Patient;
use App\Models\Radiography;

Route::get('/', function () {return view('welcome');});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    //Route::get('/dashboard', function () { return view('dashboard');})->name('dashboard'); 
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
    
    Route::resource('/patient',PatientController::class);
    Route::post('/search',[PatientController::class, 'search'])->name('patient.search');

    Route::get('admin/users/create', [AdminUserController::class, 'create'])->name('admin.create');
    Route::post('admin/users/new', [AdminUserController::class, 'store'])->name('admin.store');
    Route::get('admin/users/list', [AdminUserController::class, 'index'])->name('admin.users');
    Route::delete('/admin/destroy/{user}', [AdminUserController::class, 'destroy'])->name('admin.destroy');
    Route::get('/data', [AdminUserController::class, 'data'])->name('admin.data');


    Route::get('/new-radiography',[RadiographyController::class,'new'])->name('radiography.new');
    Route::get('/radiography',[RadiographyController::class,'index'])->name('radiography.index');
    Route::get('/radiography/create',[RadiographyController::class,'create'])->name('radiography.create');
    Route::post('/radiography/store',[RadiographyController::class,'store'])->name('radiography.store');
    Route::get('/radiography/edit/{radiography}', [RadiographyController::class, 'edit'])->name('radiography.edit');
    Route::put('/radiography/update/{radiography}', [RadiographyController::class, 'update'])->name('radiography.update');
    Route::get('/radiography/show/{radiography}',[RadiographyController::class,'show'])->name('radiography.show');
    Route::get('/radiography/tool/{radiography}',[RadiographyController::class,'tool'])->name('radiography.tool');
    Route::get('/radiography/measurements/{radiography}',[RadiographyController::class,'measurements'])->name('radiography.measurements');
    Route::get('/radiography/report/{radiography}',[RadiographyController::class,'report'])->name('radiography.report');
    Route::post('/radiography/{radiography}/pdfreport', [RadiographyController::class, 'pdfreport'])->name('radiography.pdfreport');
    Route::post('/radiography/{radiography}/apply-filters', [RadiographyController::class, 'applyFilters'])->name('radiography.applyFilters');
    Route::post('/radiography/search',[RadiographyController::class, 'search'])->name('radiography.search');
    Route::delete('/radiography/destroy/{radiography}',[RadiographyController::class, 'destroy'])->name('radiography.destroy');
    Route::get('/files',[RadiographyController::class,'files'])->name('files.select');

    Route::get('/new-tomography',[TomographyController::class,'new'])->name('tomography.new');
    Route::get('/tomography',[TomographyController::class,'index'])->name('tomography.index');
    Route::get('/tomography/create',[TomographyController::class,'create'])->name('tomography.create');
    Route::post('/tomography/store',[TomographyController::class,'store'])->name('tomography.store');
    Route::get('/tomography/edit/{tomography}', [TomographyController::class, 'edit'])->name('tomography.edit');
    Route::put('/tomography/update/{tomography}', [TomographyController::class, 'update'])->name('tomography.update');
    Route::get('/tomography/tool/{tomography}',[TomographyController::class,'tool'])->name('tomography.tool');
    Route::get('/tomography/measurements/{tomography}',[TomographyController::class,'measurements'])->name('tomography.measurements');
    Route::get('/tomography/report/{tomography}',[TomographyController::class,'report'])->name('tomography.report');
    Route::post('/tomography/{tomography}/pdfreport', [TomographyController::class, 'pdfreport'])->name('tomography.pdfreport');
    Route::get('/tomography/convert/{id}', [TomographyController::class, 'convert'])->name('tomography.convert');
    Route::get('/tomography/show/{id}', [TomographyController::class, 'show'])->name('tomography.show');
    Route::get('tomography/image/{tomographyId}/{image}', [TomographyController::class, 'showSelectedImage'])->name('tomography.image');
    Route::get('/tomography/superposicion/{id}', [TomographyController::class, 'superposicion'])->name('tomography.superposicion');
    Route::post('/tomography/search',[TomographyController::class, 'search'])->name('tomography.search');
    Route::get('/tomography/dcm/create', [TomographyController::class, 'createdcm'])->name('tomography.createdcm');
    Route::post('/tomography/dcm/store', [TomographyController::class, 'storedcm'])->name('tomography.storedcm');
    Route::delete('/tomography/destroy/{tomography}',[tomographyController::class, 'destroy'])->name('tomography.destroy');

    Route::get('/dicom/upload-radiography', [DicomController::class, 'uploadFormRadiography'])->name('dicom.uploadRadiography');
    Route::get('/dicom/upload-tomography', [DicomController::class, 'uploadFormTomography'])->name('dicom.uploadTomography');
    Route::post('/dicom/process', [DicomController::class, 'processDicom'])->name('process.dicom');
    Route::post('/dicom/process-folder', [DicomController::class, 'processFolder'])->name('process.folder');
    Route::get('/dicom/show-images/{folderName}', [DicomController::class, 'showFolderImages'])->name('dicom.showFolderImages');
    Route::get('/dicom-form', [DicomController::class, 'showForm'])->name('dicom.data');
    Route::post('/dicom-upload', [DicomController::class, 'uploadDicom'])->name('dicom.updata');
    Route::get('/dicom-records', [DicomController::class, 'showRecords'])->name('dicom.viewdata');
    Route::post('/dicom/radiography/save', [DicomController::class, 'saveRadiography'])->name('dicom.saveradiography');
    Route::post('/dicom/tomography/save', [DicomController::class, 'saveTomography'])->name('dicom.savetomography');

    Route::get('/tool',[ToolController::class,'index'])->name('tool.index');
    Route::post('/tool/new/tool/{tomography_id}/{ci_patient}/{id}', [ToolController::class, 'new'])->name('tool.new');
    Route::post('/tool/store/tool/{radiography_id}/{tomography_id}/{ci_patient}/{id}', [ToolController::class, 'storeTool'])->name('tool.store');
    Route::post('/tool/store/tomography/{tomography_id}/{ci_patient}/{id}', [ToolController::class, 'storeTomography'])->name('tool.storeTomography');
    Route::get('/tool/show/{tool}',[ToolController::class,'show'])->name('tool.show'); 
    Route::get('/tool/ver/{tool}', [ToolController::class, 'ver'])->name('tool.ver');
    Route::get('/tool/search/{id}', [ToolController::class, 'search'])->name('tool.search');
    Route::delete('/tool/destroy/{tool}', [ToolController::class, 'destroy'])->name('tool.destroy');
    Route::post('/save-image', [ToolController::class, 'saveImage'])->name('tool.image');
    Route::get('/tool/measurements/{id}',[ToolController::class,'measurements'])->name('tool.measurements');
    Route::get('/tool/report/{tool}',[ToolController::class,'report'])->name('tool.report');
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
    Route::resource('/treatments',TreatmentController::class);
    Route::post('/budget-search',[BudgetController::class, 'search'])->name('budgets.search');
    Route::post('/treatment-search',[TreatmentController::class, 'search'])->name('treatments.search');
    
    Route::prefix('treatments/{treatment}/payments')->group(function () {
        Route::get('/', [PaymentController::class, 'show'])->name('payments.show');
        Route::get('/create', [PaymentController::class, 'create'])->name('payments.create');
        Route::post('/store', [PaymentController::class, 'store'])->name('payments.store');
        Route::delete('/{id}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    });
    Route::post('/payments/search',[PaymentController::class, 'search'])->name('payments.search');
    Route::get('/payments/index', [PaymentController::class, 'index'])->name('payments.index');

    Route::resource('multimedia', App\Http\Controllers\MultimediaFileController::class);
Route::get('multimedia/search', [App\Http\Controllers\MultimediaFileController::class, 'search'])->name('multimedia.search');
});
