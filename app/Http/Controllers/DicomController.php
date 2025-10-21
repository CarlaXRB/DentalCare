<?php

namespace App\Http\Controllers;

use App\Models\Dicom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Models\Radiography;
use App\Models\Tomography;
use App\Models\Patient;

class DicomController extends Controller
{
    public function uploadFormRadiography(){
        return view('dicom.uploadRadiography');
    }
    public function uploadFormTomography(){
        return view('dicom.uploadTomography');
    }
    public function processDicom(Request $request){
        $request->validate([
            'file' => 'required|file'
        ]);
        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, ['', 'dcm'])) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['file' => 'El archivo no es correcto. Solo se permiten archivos DICOM (.dcm) o sin extensión.']);
        }

        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $filePath = $file->storeAs('public/dicoms', $fileName);

        $pythonScript = 'C:\Users\Gustavo\Desktop\CareRadiologyProject\careradiology\procesar_archivo.py';
        $command = "python \"$pythonScript\" \"" . storage_path("app/public/dicoms/$fileName") . "\"";
        shell_exec($command);

        $imageUrl = Storage::url("dicoms/{$fileName}.png");
        $dataScript = 'C:\Users\Gustavo\Desktop\CareRadiologyProject\careradiology\data.py';
        $dataCommand = "python \"$dataScript\" " . escapeshellarg(storage_path("app/public/dicoms/$fileName"));
        $output = shell_exec($dataCommand);
        $dicomData = json_decode($output, true);

        if (isset($dicomData['error'])) {
            return response()->json(['error' => $dicomData['error']], 400);
        }

        $extractLoValue = function(string $dicomString): string {
            if (preg_match("/(LO|PN):\s*'([^']+)'/", $dicomString, $matches)) {
                return $matches[2];
            }
            return '';
        };

        $dicomInfo = $dicomData['dicom_info'] ?? [];

        $requestedProcedureDescription = isset($dicomInfo[3280992]) ? $extractLoValue($dicomInfo[3280992]) : '';
        $referringPhysicianName = isset($dicomInfo[3280946]) ? $extractLoValue($dicomInfo[3280946]) : '';
        $operatorsName = isset($dicomInfo[528496]) ? $extractLoValue($dicomInfo[528496]) : '';
        $performingPhysicianName = isset($dicomInfo[528528]) ? $extractLoValue($dicomInfo[528528]) : '';

        $finalDicomData = [
            'patient_name' => $dicomData['patient_name'] ?? '',
            'patient_id' => $dicomData['patient_id'] ?? '',
            'modality' => $dicomData['modality'] ?? '',
            'study_date' => $dicomData['study_date'] ?? '',
            'rows' => $dicomData['rows'] ?? '',
            'columns' => $dicomData['columns'] ?? '',
            'requested_procedure_description' => $requestedProcedureDescription,
            'referring_physician_name' => $referringPhysicianName,
            'operators_name' => $operatorsName,
            'performing_physician_name' => $performingPhysicianName,
            'dicom_info' => $dicomInfo,
        ];

        Dicom::create([
            'file_name' => $fileName,
            'image_url' => $imageUrl,
            'patient_name' => $finalDicomData['patient_name'],
            'patient_id' => $finalDicomData['patient_id'],
            'modality' => $finalDicomData['modality'],
            'study_date' => $finalDicomData['study_date'],
            'rows' => $finalDicomData['rows'],
            'columns' => $finalDicomData['columns'],
            'metadata' => json_encode($finalDicomData['dicom_info'])
        ]);
        $patients = Patient::all();
        session([
            'dicom_data' => $dicomData,
            'image_url' => $imageUrl,
            'file_name' => $fileName,
        ]);
        return view('dicom.show', ['imageUrl' => $imageUrl, 'dicomData' => $finalDicomData, 'patients' => $patients]);
    }
    public function processFolder(Request $request){
        $request->validate([
            'files' => 'required|array'
        ]);

        $folderName = "dicom_" . time();
        $folderPath = storage_path("app/public/dicoms/$folderName");

        File::makeDirectory($folderPath, 0755, true);
        session(['tomography_folder_path' => $folderPath]);
        session(['tomography_folder_name' => $folderName]);

        $firstFilePath = null;

        foreach ($request->file('files') as $index => $file) {
            $fileName = $file->getClientOriginalName();
            $file->move($folderPath, $fileName);

            if ($index === 0) {
                $firstFilePath = $folderPath . DIRECTORY_SEPARATOR . $fileName;
            }
        }

        $pythonScript = 'C:\Users\Gustavo\Desktop\CareRadiologyProject\careradiology\procesar_carpeta.py';
        $command = "python \"$pythonScript\" \"$folderPath\"";
        shell_exec($command);

        $folderUrl = Storage::url("dicoms/$folderName");

        $dicomData = null;
        if ($firstFilePath) {
            $dataScript = 'C:\Users\Gustavo\Desktop\CareRadiologyProject\careradiology\data.py';
            $dataCommand = "python \"$dataScript\" " . escapeshellarg($firstFilePath);
            $output = shell_exec($dataCommand);
            $dicomData = json_decode($output, true);
        }

        Dicom::create([
            'file_name' => $folderName,
            'image_url' => $folderUrl,
            'patient_name' => $dicomData['patient_name'] ?? 'Desconocido',
            'patient_id' => $dicomData['patient_id'] ?? 'Desconocido',
            'modality' => $dicomData['modality'] ?? 'N/A',
            'study_date' => $dicomData['study_date'] ?? 'N/A',
            'rows' => $dicomData['rows'] ?? 0,
            'columns' => $dicomData['columns'] ?? 0,
            'metadata' => isset($dicomData['dicom_info']) ? json_encode($dicomData['dicom_info']) : null
        ]);
        
        return response()->json([
            'message' => 'La carpeta se ha procesado correctamente.',
            'folderUrl' => route('dicom.showFolderImages', $folderName),
            'patient_name' => $dicomData['patient_name'] ?? 'Desconocido',
            'patient_id' => $dicomData['patient_id'] ?? 'Desconocido',
            'modality' => $dicomData['modality'] ?? 'N/A',
            'study_date' => $dicomData['study_date'] ?? 'N/A'
        ]);
        session([
            'dicom_data' => $dicomData,
            'file_name' => $folderName,
        ]);
    }
    public function showFolderImages($folderName){
        $folderPath = storage_path("app/public/dicoms/$folderName");
    
        $images = [];
        foreach (File::files($folderPath) as $file) {
            if ($file->getExtension() === 'png') {
                $images[] = 'storage/dicoms/' . $folderName . '/' . $file->getFilename();   
            }
        }
        $dicomRecord = Dicom::where('file_name', $folderName)->first();
        $patients = Patient::all();
        return view('dicom.showFolderImages', compact('images', 'dicomRecord','patients'));
    }
    public function showForm(){
        return view('dicom.data');
    }
    public function uploadDicom(Request $request){
        $request->validate([
            'dicom_file' => 'required|file'
        ]);

        $file = $request->file('dicom_file');
        $filePath = $file->getPathname(); 

        $command = "python C:\Users\Gustavo\Desktop\dicom\care\data.py " . escapeshellarg($filePath);

        $output = shell_exec($command);

        $dicomData = json_decode($output, true);

        if (isset($dicomData['error'])) {
            return response()->json(['error' => $dicomData['error']], 400);
        }

        $record = Dicom::create([
            'patient_name' => $dicomData['patient_name'],
            'patient_id' => $dicomData['patient_id'],
            'modality' => $dicomData['modality'],
            'study_date' => $dicomData['study_date'],
            'rows' => $dicomData['rows'],
            'columns' => $dicomData['columns'],
            'metadata' => $dicomData['dicom_info']
        ]);
        
        return view('dicom.data', [
            'dicomInfo' => $dicomData['dicom_info'],
            'patientName' => $dicomData['patient_name'],
            'patientID' => $dicomData['patient_id'],
            'modality' => $dicomData['modality'],
            'studyDate' => $dicomData['study_date'],
            'rows' => $dicomData['rows'],
            'columns' => $dicomData['columns']
        ]);
    }
    public function showRecords(){
        $records = Dicom::latest()->get();
        return view('dicom.records', compact('records'));
    }
    public function saveRadiography(Request $request){
        $dicomData = session('dicom_data');
        $imageUrl = session('image_url');
        $fileName = session('file_name');
        $request->validate(['patient_id' => 'required|exists:patients,id']);

        if (!$dicomData || !$imageUrl || !$fileName) {
            return redirect()->back()->withErrors('No hay datos para guardar.');
        }
        $pngFileName = basename($imageUrl); 
        $dicomFileName = $fileName;

        $originPng = storage_path("app/public/dicoms/{$pngFileName}");
        $originDicom = storage_path("app/public/dicoms/{$dicomFileName}");

        $destPng = storage_path("app/public/radiographies/{$pngFileName}");
        $destDicom = storage_path("app/public/radiographies/{$dicomFileName}");

        if (file_exists($originPng)) {
            rename($originPng, $destPng);
        }
        if (file_exists($originDicom)) {
            rename($originDicom, $destDicom);
        }

        $radiographyUri = $pngFileName;
        $radiographyDicomUri = $dicomFileName;
        $patient = Patient::findOrFail($request->input('patient_id'));

        Radiography::create([
            'name_patient' => $patient->name_patient,
            'ci_patient' => $patient->ci_patient,
            'radiography_id' => $dicomData['sop_instance_uid'] ?? $dicomData['study_instance_uid'] ?? $dicomData['patient_id'] ?? 11111111, 
            'radiography_date' => $dicomData['study_date'] ?? now()->toDateString(),
            'radiography_type' => $dicomData['requested_procedure_description'] ?? 'Radiografia',
            'radiography_uri' => $radiographyUri,
            'radiography_dicom_uri' => $radiographyDicomUri,
            'radiography_doctor' => $dicomData['referring_physician_name'] ?? 'NA',
            'radiography_charge' => $dicomData['operators_name'] ?? 'NA',
            //'radiography_performing' => $dicomData['performing_physician_name'] ?? 'NA', 
        ]);


        session()->forget(['dicom_data', 'image_url', 'file_name']);
        return redirect()->route('radiography.index')->with('success', 'Estudio guardado correctamente.');
    }
    public function saveTomography(Request $request){
        $request->validate(['patient_id' => 'required|exists:patients,id']);

        $dicomData = session('dicom_data');
        $newFolderPath = session('tomography_folder_path');
        $newFolderName = session('tomography_folder_name');
        $patient = Patient::findOrFail($request->input('patient_id'));

        $tomography = new Tomography();
        $tomography->name_patient = $patient->name_patient;
        $tomography->ci_patient = $patient->ci_patient;
        $tomography->tomography_id = (isset($dicomData['patient_id']) && is_numeric($dicomData['patient_id'])) ? $dicomData['patient_id'] : 100000;
        $tomography->tomography_date = $dicomData['study_date'] ?? now()->toDateString();
        $tomography->tomography_type = $dicomData['requested_procedure_description'] ?? 'Tomografía';
        $tomography->tomography_doctor = $dicomData['referring_physician_name'] ?? 'NA';
        $tomography->tomography_charge = $dicomData['operators_name'] ?? 'NA';
        //$tomography->tomography_performing = $dicomData['performing_physician_name'] ?? 'NA';
        $tomography->save();

        $generatedFolderName = 'converted_images/' . $tomography->id;
        $destinationFolder = storage_path('app/public/tomographies/converted_images/' . $tomography->id);
        if (!is_dir($destinationFolder)) {
            mkdir($destinationFolder, 0777, true);
        }
        $imageFiles = glob($newFolderPath . '/*.{jpg,jpeg,png,JPG,JPEG,PNG}', GLOB_BRACE);

        foreach ($imageFiles as $imageFile) {
            $fileName = basename($imageFile);
            copy($imageFile, $destinationFolder . '/' . $fileName);
        }
        $tomography->tomography_uri = 'tomographies/' . $generatedFolderName;
        $tomography->tomography_dicom_uri = $newFolderName;
        $tomography->save();
        session()->forget(['dicom_data', 'image_url', 'tomography_folder_path', 'tomography_folder_name']);
        
        return redirect()->route('tomography.index')->with('success', 'Tomografía guardada correctamente.');
    }
}
