@extends('layouts._partials.layout')
@section('title', 'Superposición')
@section('subtitle')
    {{ __('Superposición') }}
@endsection
@section('content')
<div class="flex justify-end p-5">
    <a href="{{ route('tomography.show', ['id' => $tomography->id]) }}" class="botton1">{{ __('Atrás') }}</a>
</div>
<div class="container">
    <h1 class="title1 pb-5">{{ __('Sobreponer') }}</h1>
    <div style="display: flex; justify-content: center;">
        <canvas id="tomographyCanvas"></canvas>
        <div id="thumbnailContainer" style="width: 180px; overflow-y: auto; max-height: 600px; padding: 10px; margin-left: 20px; margin-right: 20px;"></div>
    </div>

    <div style="display: flex; justify-content: center; margin-top: 20px; margin-bottom: 20px;">
        <button id="autoOverlayButton" class="botton2">{{ __('Activar superpsoción automática') }}</button>
    </div>
</div>

<script>
    const imageFiles = @json($images);

    const thumbnailContainer = document.getElementById('thumbnailContainer');
    const canvas = document.getElementById('tomographyCanvas');
    const ctx = canvas.getContext('2d');
    canvas.width = 600;
    canvas.height = 600;

    thumbnailContainer.innerHTML = '';
    imageFiles.forEach((imageUrl, index) => {
        const img = new Image();
        img.src = imageUrl;
        img.classList.add('thumbnail');
        img.style.width = '100%';
        img.style.cursor = 'pointer';
        img.style.border = '2px solid transparent';
        
        if (index === 0) { 
            img.style.border = '2px solid cyan';
        }

        img.onclick = () => toggleSelection(index, img);

        const imageName = document.createElement('div');
        imageName.style.fontSize = '12px';
        imageName.style.color = '#808080';
        imageName.style.textAlign = 'center';
        imageName.textContent = imageUrl.split('/').pop(); 

        const imageContainer = document.createElement('div');
        imageContainer.style.marginBottom = '10px';
        imageContainer.appendChild(img);
        imageContainer.appendChild(imageName);

        thumbnailContainer.appendChild(imageContainer);
    });

    let selectedIndexes = [0]; 
    let autoOverlay = false;

    document.getElementById('autoOverlayButton').onclick = function() {
        autoOverlay = !autoOverlay;
        this.textContent = autoOverlay ? "Superposición automática activada" : "Superposición automática desactivada";
        if (autoOverlay) {
            this.classList.remove('botton2');
            this.classList.add('botton3');
        } else {
            this.classList.remove('botton3');
            this.classList.add('botton2'); 
        }
    };

    function toggleSelection(index, imgElement) {
        const selectedIndex = selectedIndexes.indexOf(index);
        if (selectedIndex === -1) {
            selectedIndexes.push(index);
            imgElement.style.border = '2px solid cyan';
        } else {
            selectedIndexes.splice(selectedIndex, 1);
            imgElement.style.border = '2px solid transparent';
        }
        displaySelectedImages();
    }

    async function displaySelectedImages() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        if (selectedIndexes.length === 0) {
            return;
        }

        for (let i = 0; i < selectedIndexes.length; i++) {
            let alpha = (i === 0) ? 1 : 0.5;
            const img = new Image();
            img.src = imageFiles[selectedIndexes[i]];
            img.onload = function() {
                ctx.globalAlpha = alpha;
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            };
        }
    }

    displaySelectedImages();

    canvas.addEventListener('wheel', function(event) {
        if (autoOverlay) {
            let currentIndex = selectedIndexes[selectedIndexes.length - 1];
            if (event.deltaY < 0 && currentIndex < imageFiles.length - 1) {
                selectedIndexes.push(currentIndex + 1); 
            } else if (event.deltaY > 0 && currentIndex > 0) {
                selectedIndexes.push(currentIndex - 1); 
            }
            displaySelectedImages();
            event.preventDefault();
        }
    });
</script>

<style>
.thumbnail {
    margin-bottom: 10px;
    transition: border 0.3s ease;
}
</style>
@endsection
