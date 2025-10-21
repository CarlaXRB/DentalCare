<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Radiography;
use App\Models\Tomography;
use App\Models\Tool;
use App\Models\Patient;
use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function show(Request $request, $type, $id, $name, $ci){
        $selectedImage = $request->query('selected_image', null);

        if ($type === 'radiography') {
            $study = Radiography::findOrFail($id);
            $patient = Patient::where('ci_patient', $study->ci_patient)->first();
        } elseif ($type === 'tomography') {
            $study = Tomography::findOrFail($id);
            $patient = Patient::where('ci_patient', $study->ci_patient)->first();
        } elseif ($type === 'tool') {   
            $study = Tool::findOrFail($id);
            $patient = Patient::where('ci_patient', $study->ci_patient)->first();
        }
        return view('report.reportForm', [
            'study' => $study,
            'patient' => $patient,
            'selectedImage' => $selectedImage,
            'studyType' => $type,
            'name'=>$name,
            'ci'=>$ci
        ]);
    }
    public function generatePDF(Request $request){
        $studyType = $request->input('study_type');
        $studyId = $request->input('study_id');
        $selectedImage = $request->input('selected_image', null);

        $findings = $request->input('findings', '');
        $diagnosis = $request->input('diagnosis', '');
        $conclusions = $request->input('conclusions', '');
        $recommendations = $request->input('recommendations', '');

        $storagePath = 'reports'; 

        if ($studyType === 'radiography') {
            $study = Radiography::findOrFail($studyId);
            $patient = Patient::where('ci_patient', $study->ci_patient)->first();

            $studyFields = [
                'study_id' => $study->radiography_id,
                'study_date' => $study->radiography_date,
                'study_type' => $study->radiography_type,
                'study_doctor' => $study->radiography_doctor,
                'study_charge' => $study->radiography_charge,
            ];

            $imagePath = null;
            if ($study->radiography_uri) {
                $imagePath = storage_path('app/public/radiographies/' . $study->radiography_uri);
            }

            $pdf = Pdf::loadView('report.reportPDF', compact(
                'study', 'patient', 'studyFields', 'imagePath', 'studyType', 
                'findings', 'diagnosis', 'conclusions', 'recommendations'
            ));

            $fileName = 'Informe_' . $study->ci_patient . '_'. $study->radiography_id . '_' . time() . '.pdf';
            $fullPath = base_path('public/storage/' . $storagePath . '/' . $fileName);
            $pdf->save($fullPath);

            Report::create([
                'ci_patient' => $study->ci_patient,
                'report_id' => $study->radiography_id,
                'report_date' => now()->toDateString(),
                'report_uri' => 'storage/' . $storagePath . '/' . $fileName,
                'created_by' => Auth::user() ? Auth::user()->name : 'system',
            ]);

            return response()->download($fullPath, $fileName);

        } elseif ($studyType === 'tomography') {
            $study = Tomography::findOrFail($studyId);
            $patient = Patient::where('ci_patient', $study->ci_patient)->first();

            $studyFields = [
                'study_id' => $study->tomography_id,
                'study_date' => $study->tomography_date,
                'study_type' => $study->tomography_type,
                'study_doctor' => $study->tomography_doctor,
                'study_charge' => $study->tomography_charge,
            ];

            $imagePath = null;
            if ($selectedImage) {
                $possiblePath = storage_path('app/public/tomographies/converted_images/' . $study->id . '/' . $selectedImage);
                if (file_exists($possiblePath)) {
                    $imagePath = $possiblePath;
                }
            }

            $pdf = Pdf::loadView('report.reportPDF', compact(
                'study', 'patient', 'studyFields', 'imagePath', 'studyType', 
                'findings', 'diagnosis', 'conclusions', 'recommendations'
            ));

            $fileName = 'Informe_' . $study->ci_patient . '_'. $study->tomography_id . '_' . time() . '.pdf';
            $fullPath = base_path('public/storage/' . $storagePath . '/' . $fileName);
            $pdf->save($fullPath);

            Report::create([
                'ci_patient' => $study->ci_patient,
                'report_id' => $study->tomography_id,
                'report_date' => now()->toDateString(),
                'report_uri' => 'storage/' . $storagePath . '/' . $fileName,
                'created_by' => Auth::user() ? Auth::user()->name : 'system',
            ]);

            return response()->download($fullPath, $fileName);

        } elseif ($studyType === 'tool') {
            $study = Tool::findOrFail($studyId);
            $patient = Patient::where('ci_patient', $study->ci_patient)->first();

            $report_id = null;
            if($study->tool_radiography_id != 0){
                $studyFields = [
                    'study_id' => $study->radiography_id,
                    'study_date' => $study->tool_date,
                    'study_type' => $study->radiography_type,
                    'study_doctor' => $study->radiography_doctor,
                    'study_charge' => $study->radiography_charge,
                ];
                $report_id = $study->radiography->radiography_id;
            }elseif($study->tool_tomography_id != 0){
                $studyFields = [
                    'study_id' => $study->tomography_id,
                    'study_date' => $study->tool_date,
                    'study_type' => $study->tomography_type,
                    'study_doctor' => $study->tomography_doctor,
                    'study_charge' => $study->tomography_charge,
                ];
                $report_id = $study->tomography->tomography_id;
            }

            $imagePath = null;
            if ($study->tool_uri) {
                $imagePath = storage_path('app/public/tools/' . $study->tool_uri);
            }

            $pdf = Pdf::loadView('report.reportPDF', compact(
                'study', 'patient', 'studyFields', 'imagePath', 'studyType', 
                'findings', 'diagnosis', 'conclusions', 'recommendations'
            ));

            $fileName = 'Informe_' . $study->ci_patient . '_'. $study->tool_radiography_id . '_'. $study->tool_tomography_id . '_' . time() . '.pdf';
            $fullPath = base_path('public/storage/' . $storagePath . '/' . $fileName);
            $pdf->save($fullPath);

            Report::create([
                'ci_patient' => $study->ci_patient,
                'report_id' => $report_id,
                'report_date' => now()->toDateString(),
                'report_uri' => 'storage/' . $storagePath . '/' . $fileName,
                'created_by' => Auth::user() ? Auth::user()->name : 'system',
            ]);

            return response()->download($fullPath, $fileName);

        } else {
            abort(404);
        }
    }
    public function view($id){
        $report = Report::findOrFail($id);
        $filePath = public_path($report->report_uri);
        if (!file_exists($filePath)) {
            abort(404, 'El archivo del reporte no existe.');
        }
        return response()->file($filePath);
    }
}