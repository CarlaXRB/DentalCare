<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class MedicionesController extends Controller
{
    public function index()
    {
        return view('mediciones.index'); // resources/views/mediciones/index.blade.php
    }

    public function analyze(Request $request)
    {
        if (!$request->hasFile('image')) {
            return response()->json(['error' => 'No se envió ninguna imagen'], 400);
        }

        $file = $request->file('image');

        if (!$file->isValid()) {
            return response()->json(['error' => 'Archivo inválido'], 400);
        }

        $filePath = $file->getRealPath();

        $process = new Process(['python3', base_path('scripts/measure_study.py'), $filePath]);
        $process->run();

        if (!$process->isSuccessful()) {
            return response()->json(['error' => $process->getErrorOutput() ?: 'Error desconocido'], 500);
        }

        return response()->json(['result' => $process->getOutput()]);
    }
}

