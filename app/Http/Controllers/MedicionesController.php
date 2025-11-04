<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MedicionesController extends Controller
{
    public function run(Request $request)
    {
        $image = public_path('storage/' . $request->image);
        $x1 = $request->x1;
        $y1 = $request->y1;
        $x2 = $request->x2;
        $y2 = $request->y2;
        $zoom = $request->zoom;

        // Ejecutar script Python
        $command = escapeshellcmd("python " . base_path('scripts/measure.py') . " \"$image\" $x1 $y1 $x2 $y2 $zoom");
        $output = shell_exec($command);

        return response()->json(json_decode($output, true));
    }
}
