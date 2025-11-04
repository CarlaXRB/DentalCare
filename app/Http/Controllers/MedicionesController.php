<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class MedicionesController extends Controller
{
public function analyze(Request $request)
    {
        $filePath = $request->file('image')->getRealPath();

        $process = new Process(['python3', base_path('scripts/measure_study.py'), $filePath]);
        $process->run();

        if (!$process->isSuccessful()) {
            return response()->json(['error' => $process->getErrorOutput()], 500);
        }

        return response()->json(['result' => $process->getOutput()]);
    }
}
