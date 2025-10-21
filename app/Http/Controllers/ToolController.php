<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Tool;
use Barryvdh\DomPDF\Facade\Pdf;
use Imagick;

class ToolController extends Controller
{
    public function index():View{
        $tools = Tool::get();
        return view('tool.index', compact('tools'));
    }
    public function new(Request $request, $tomography_id, $ci_patient, $id)
    {
        $tool = new Tool();
        $tool->tool_tomography_id = $tomography_id; 
        $tool->ci_patient = $ci_patient;
        $tool->tool_date = now();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = public_path('storage/tools/' . $imageName);
            $image->move(public_path('storage/tools'), $imageName);
            $tool->tool_uri = $imageName;
        }
        $tool->save();
        return response()->json(['success' => true]);
    }
    public function storeTool(Request $request, $radiography_id, $tomography_id, $ci_patient, $id) {
        $tool = new Tool();
        if($radiography_id>0){
            $tool->tool_radiography_id = $radiography_id;
        }else{
            $tool->tool_tomography_id = $tomography_id;
        }
        $tool->ci_patient = $ci_patient;
        $tool->tool_date = now();

        if ($request->has('image')) {
            $imageData = $request->input('image');

            $image = str_replace('data:image/png;base64,', '', $imageData);
            $image = str_replace(' ', '+', $image);
            $imageName = uniqid() . '.png';
            $filePath = 'storage/tools/' . $imageName;
            $absolutePath = public_path($filePath);
            file_put_contents($absolutePath, base64_decode($image));
            $tool->tool_uri = $imageName;
        }
        $tool->save();
        return response()->json(['success' => true]);
    }
    public function storeTomography(Request $request, $tomography_id, $ci_patient, $id){
        try {
            $tool = new Tool();
            $tool->tool_tomography_id = $tomography_id;
            $tool->ci_patient = $ci_patient;
            $tool->tool_date = now();
    
            if ($request->has('image')) {
                $imageData = $request->input('image');
                $image = str_replace('data:image/png;base64,', '', $imageData);
                $image = str_replace(' ', '+', $image);
                $imageName = uniqid() . '.png';
                $filePath = public_path('storage/tools/' . $imageName);
    
                if (!file_put_contents($filePath, base64_decode($image))) {
                    return redirect()->back()->with('error', 'No se pudo guardar la imagen.');
                }
                $tool->tool_uri = $imageName;
            }
            $tool->save();
            return response()->json([
                'success' => true,
                'tool_id' => $tool->id,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function show(Tool $tool):View{
        return view('tool.show', compact('tool'));
    }
    public function ver(Tool $tool):View{
        $tools = Tool::where('tool_tomography_id',$tool->tool_tomography_id)->get();
        return view('tool.ver', compact('tool', 'tools'));
    }
    public function destroy(Tool $tool){   
        $tool->delete();
        return redirect()->back()->with('success', 'Tool deleted successfully');
    }
    public function measurements($id):View{
        $tool = Tool::findOrFail($id); 
        $tool_uri = $tool->tool_uri; 
        return view('tool.measurements', compact('tool','tool_uri'));
    }
    public function report(Tool $tool):View{
        return view('tool.report', compact('tool'));
    }
    public function search($id){
        $tool = Tool::find($id);
        if (!$tool) {
            return redirect()->back()->with('error', 'Herramienta no encontrada.');
        }
        $searchId = $tool->tool_tomography_id != 0 ? $tool->tool_tomography_id : $tool->tool_radiography_id;
        $tools = Tool::where('tool_tomography_id', $searchId)
                     ->orWhere('tool_radiography_id', $searchId)
                     ->get();
        return view('tool.search', compact('tools', 'id'));
    }
}
