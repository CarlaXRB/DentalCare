@extends('layouts._partials.layout')
@section('title','Radiographies')
@section('subtitle')
    {{ __('Radiografias') }}
@endsection
@section('content')
<div class="grid grid-cols-2" >
    <div><form method="POST" action="{{ route('radiography.search') }}">
        @csrf
        <input type="text" placeholder="Buscar" name="search" style="color: #333; font-size: 16px;  padding: 10px 15px; border-radius: 20px; margin-top: 5px; margin-left: 5px;"/>
        <input class="botton4" type="submit" value="Buscar"/>
    </form></div>
    <div class="flex justify-end"><a href="{{ route('radiography.index')}}" class="botton1">Atrás</a></div>
</div>
<h1 class="txt-title1">RESULTADOS</h1>
    <div class="grid grid-cols-6 gap-4 border-b border-cyan-500 mb-3">
        <h3 class="txt-head">Vista previa</h3>
        <h3 class="txt-head">Nombre del paciente</h3>    
        <h3 class="txt-head">Carnet de Identidad</h3>
        <h3 class="txt-head">Fecha de Radiografia</h3>
        <h3 class="txt-head">ID Radiografia</h3>
        <h3 class="txt-head">Tipo de Radiografia</h3>
    </div> 
    <ul>
        @forelse($radiographies as $radiography)
        <div class="grid grid-cols-6 border-b border-gray-600 gap-4 mb-3 text-white pl-6 pl-10">
        <img src="{{ asset('storage/radiographies/'.$radiography->radiography_uri)}}" width="128" />
        <a href="{{ route('radiography.show', $radiography->id) }}"> {{ $radiography->name_patient }} </a>
        <a href="{{ route('radiography.show', $radiography->id) }}"> {{ $radiography->ci_patient }} </a>
        <a href="{{ route('radiography.show', $radiography->id) }}"> {{ $radiography->radiography_date }} </a>        
        <a href="{{ route('radiography.show', $radiography->id) }}"> {{ $radiography->radiography_id }} </a>
        <a href="{{ route('radiography.show', $radiography->id) }}"> {{ $radiography->radiography_type }} </a>  
        </div>
        @empty
        <h2 class="text-white ml-5">No data</h2>
        @endforelse
    </ul>
@endsection