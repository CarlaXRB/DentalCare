@extends('layouts._partials.layout')
@section('title','Show Herramientas')
@section('subtitle')
    {{ __('Ver Herramientas') }}
<h1 class="txt-title1">HERRAMIENTAS</h1>
<div class="grid grid-cols-4 gap-4 border-b border-cyan-500">
    <h3 class="txt-head">Vista previa</h3>  
    <h3 class="txt-head">Fecha de creación</h3>
    <h3 class="txt-head">ID del estudio</h3>
</div>
<ul>
    @forelse($tools as $tool)
    <div class="grid grid-cols-4 border-b border-gray-600 gap-4 mb-3 text-white pl-6 pl-10">
    <img src="{{ asset('storage/tools/'.$tool->tool_uri)}}" width="128" />
        <a href="{{ route('tool.show', $tool->id) }}"> {{ $tool->tool_date }} </a>
        <a href="{{ route('tool.show', $tool->id) }}"> {{ $tool->tool_radiography_id }} </a>    
        <form method="POST" action="{{ route('tool.destroy', $tool->id) }}">
            @csrf
            @method('Delete')
            <div class="flex justify-end"><input type="submit" value="Eliminar" class="botton2"/></div>
        </form>
    </div>
    @empty
    <h2 class="text-white ml-5">No data</h2>
    @endforelse
</ul>
@endsection

