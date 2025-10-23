@extends('layouts._partials.layout')
@section('title','Show Tomography')
@section('subtitle')
    {{ __('Tomography') }}
@endsection
@section('content')

<div class="flex justify-end p-5 pb-1">
    <a href="{{ route('tomography.index') }}" class="botton1">{{ __('Tomografías') }}</a>
</div>
<div class="max-w-5xl pt-2 mx-auto bg-white rounded-xl p-8 text-gray-900 dark:text-white">
<div class="mb-5">
        <h1 class="title1 text-center pb-5">{{ __('Información del estudio') }}</h1>
    </div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4 pb-5 text-black dark:text-white">
        <div class="flex gap-2">
            <h3 class="title4">{{ __('Paciente:') }}</h3>
            <span class="txt">{{ $tomography->name_patient }}</span>
            @if($tomography->patient)
            <a href="{{ route('patient.show', $tomography->patient->id ) }}" class="txt text-green-500 hover:text-green-700 hover:font-bold pl-12">{{ __('Ver Paciente') }}</a>
            @else
            <p class="text-red-500 mb-3">{{ __('Paciente no registrado.') }}</p>
            @endif
        </div>

    <div class="flex gap-2">
            <h3 class="title4">{{ __('ID de la Tomografía') }}:</h3><span class="txt">{{ $tomography->tomography_id }}</span>
        </div>
        <div class="flex gap-2">
            <h3 class="title4">{{ __('Fecha del estudio') }}:</h3><span class="txt">{{ $tomography->tomography_date }}</span>
        </div>
        <div class="flex gap-2">
            <h3 class="title4">{{ __('Tipo de Tomografía') }}:</h3><span class="txt">{{ $tomography->tomography_type }}</span>
        </div>
        <div class="flex gap-2">
            <h3 class="title4">{{ __('Doctor') }}:</h3><span class="txt">{{ $tomography->tomography_doctor }}</span>
        </div>
        <div class="flex gap-2">
            <h3 class="title4">{{ __('Radiologo') }}:</h3><span class="txt">{{ $tomography->tomography_charge }}</span>
        </div>
</div>

<div class="relative flex justify-center mt-[50px] mb-[30px]">
    <div class="overflow-auto" style="width: 1100px; height: 700px; position: relative;">
        @if(!empty($images))
            @foreach($images as $key => $image)
                <img id="image-{{ $key }}" src="{{ asset('storage/tomographies/converted_images/' . $tomography->id . '/' . basename($image)) }}" 
                alt="Imagen {{ $key + 1 }}" 
                style="width: 100%; height: 100%; object-fit: contain; transition: transform 0.2s; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); {{ $key === 0 ? '' : 'display: none;' }}">
            @endforeach
        @else
            <p>{{ __('No hay imagenes disponibles') }}</p>
        @endif
    </div>
</div>
<div id="image-name" style="text-align: center; font-size: 14px; color: #808080;">{{ basename($images[0] ?? '') }}</div>

<div id="controls" class="relative flex justify-center mt-[30px] mb-[30px]">
    <button id="prev-btn" class="botton2">{{ __('Anterior') }}</button>
    <button id="next-btn" class="botton2">{{ __('Siguiente') }}</button>
    <button id="enable-scroll" class="botton3">{{ __('Habilitar cambio con rueda') }}</button>
</div>


        <div class="relative flex justify-center mt-[20px] mb-[5px]"><p>{{ __('Herramientas') }}:</p></div>
        <div class="relative flex justify-center mb-[18px]">
            <div class="group relative">
                <button id="overlayButton" class="btnimg"><img src="{{ asset('storage/assets/images/sup.png') }}" width="50" height="50"></button>
                <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-xs text-gray-800">Superposición</span></div>
            </div>
            <form id="saveImageForm" action="{{ route('tool.storeTomography', ['tomography_id' => $tomography->tomography_id, 'ci_patient' => $tomography->ci_patient, 'id' => $tomography->id]) }}" method="POST">
            @csrf
            <div class="group relative">
                <button id="save" class="btnimg" type="submit"><img src="{{ asset('storage/assets/images/filter.png') }}" width="50" height="50"></button>
                <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-sm text-gray-800">Filtros</span></div>
            </div>
            </form>
            <div class="group relative">
            <button id="report" class="btnimg" onclick="goToReport()"><img src="{{ asset('storage/assets/images/report.png') }}" width="50" height="50"></button>
                <div class="hidden group-hover:block absolute bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-sm text-gray-800">Reporte</span></div>
            </div>
        </div>
        <div>

<div class="flex justify-end pb-5">
@auth
    @if(!in_array(Auth::user()->role, ['user', 'reception']))
        <a href="{{ route('tomography.edit', $tomography->id ) }}" class="botton3">{{ __('Editar') }}</a>
    @endif
@endauth
@auth
    @if(Auth::user()->role === 'admin')
        <form method="POST" action="{{ route('tomography.destroy', $tomography->id) }}" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este estudio?');">
            @csrf
            @method('Delete')
            <input type="submit" value="{{ __('Eliminar') }}" class="bottonDelete"/>
        </form>
    </div>
    @endif
@endauth
</div>
</div>
<script>
    // SUPERPOSICIÓN
    const overlayBtn = document.getElementById('overlayButton');
    if (overlayBtn) {
        overlayBtn.onclick = function() {
            window.location.href = "{{ route('tomography.superposicion', ['id' => $tomography->id]) }}";
        };
    }

    let currentIndex = 0; 
    const images = @json(array_map(fn($image) => basename($image), $images));
    const totalImages = images.length;
    let scrollEnabled = false;
    console.log("Total de imágenes: ", totalImages); 
    // CAMBIO DE IMAGEN
    function changeImage(index) {
        if (index >= 0 && index < totalImages) {
            $(`#image-${currentIndex}`).hide();
            $(`#image-${index}`).fadeIn(100);
            $('#image-name').text(images[index]);
            currentIndex = index;
        }
    }
    // BOTONES ANTERIOR / SIGUIENTE
    $('#prev-btn').click(function() {
        if (currentIndex > 0) {
            changeImage(currentIndex - 1);
        }
    });
    $('#next-btn').click(function() {
        if (currentIndex < totalImages - 1) {
            changeImage(currentIndex + 1);
        }
    });
    // CAMBIO CON LA RUEDA DEL MOUSE
    $('#enable-scroll').click(function() {
        scrollEnabled = !scrollEnabled;
        $(this).text(scrollEnabled ? 'Deshabilitar cambio con rueda' : 'Habilitar cambio con rueda');
        if (scrollEnabled) {
            $('body').css('overflow', 'hidden');
        } else {
            $('body').css('overflow', 'auto');
        }
    });
    $(document).on('wheel', function(event) {
        if (scrollEnabled) {
            event.preventDefault();
            const delta = event.originalEvent.deltaY;

            if (delta > 0 && currentIndex < totalImages - 1) {
                changeImage(currentIndex + 1);
            } else if (delta < 0 && currentIndex > 0) {
                changeImage(currentIndex - 1);
            }
        }
    });
    // GUARDAR IMAGEN
    const saveBtn = document.getElementById('save');
    if (saveBtn) {
        saveBtn.onclick = function(event) {
            event.preventDefault();
            const img = document.querySelector(`#image-${currentIndex}`);
            if (!img) {
                alert("No hay imagen visible para guardar.");
                return;
            }
            const canvas = document.createElement('canvas');
            canvas.width = img.naturalWidth;
            canvas.height = img.naturalHeight;
            const ctx = canvas.getContext('2d');
            ctx.filter = getComputedStyle(img).filter || 'none';
            ctx.drawImage(img, 0, 0);
            const dataURL = canvas.toDataURL('image/png');
            fetch("{{ route('tool.storeTomography', ['tomography_id' => $tomography->tomography_id, 'ci_patient' => $tomography->ci_patient, 'id' => $tomography->id]) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ image: dataURL })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = `{{ route('tool.ver', ['tool' => ':tool_id']) }}`.replace(':tool_id', data.tool_id);
                } else {
                    alert("Error al guardar la imagen.");
                }
            })
            .catch(error => {
                console.error("Error al guardar la imagen:", error);
            });
        };
    }
    // REPORTE
    function goToReport() {
        const selectedImage = images[currentIndex];
        const reportUrl = `{{ route('report.form', ['type'=>'tomography','id'=>$tomography->id, 'name'=>$tomography->name_patient,'ci'=>$tomography->ci_patient]) }}?selected_image=${encodeURIComponent(selectedImage)}`;
        window.location.href = reportUrl;
    }
    const reportBtn = document.getElementById('report');
    if (reportBtn) {
        reportBtn.onclick = goToReport;
    }
</script>
@endsection
