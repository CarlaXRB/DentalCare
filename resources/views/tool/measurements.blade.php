@extends('layouts._partials.layout') 
@section('title', 'Herramientas de Medición')
@section('subtitle')
{{ __('Herramientas de Medición') }}
@endsection
@section('content')
<div class="flex justify-end p-5 pb-1">
    <a href="{{ route('dashboard')}}" class="botton1">{{ __('Inicio') }}</a>
</div>
<h1 class="title1">{{ __('Herramientas de Medición') }}</h1>

<div class="relative flex justify-center space-x-2">
    <div class="group relative">
        <button id="distance" class="btnimg"><img src="{{ asset('assets/images/distance.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-xs text-gray-800">Medir_Distancia</span></div>
    </div>
    <div class="group relative">
        <button id="delimited" class="btnimg"><img src="{{ asset('assets/images/distances.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-xs text-gray-800">Marcar_Contorno</span></div>
    </div>
    <div class="group relative">
        <button id="angle" class="btnimg"><img src="{{ asset('assets/images/angle.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-xs text-gray-800">Medir_Ángulo</span></div>
    </div>
    <div class="group relative">
        <button id="arco" class="btnimg"><img src="{{ asset('assets/images/arco.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-xs text-gray-800">Medir_Arco</span></div>
    </div>
    <div class="group relative">
        <button id="paint" class="btnimg"><img src="{{ asset('assets/images/paint.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-xs text-gray-800">Pintar</span></div>
    </div>
    <form action="{{ route('tool.store',['radiography_id' => $tool->tool_radiography_id,'tomography_id' => $tool->tool_tomography_id,'ci_patient' => $tool->ci_patient, 'id' => $tool->id]) }}" method="POST" enctype="multipart/form-data">
    @csrf
        <div class="group relative">
            <button id="save" class="btnimg" type="submit"><img src="{{ asset('assets/images/save.png') }}" width="50" height="50"></button>
            <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-sm text-gray-800">Guardar</span></div>
        </div>
    </form>
    <div class="group relative">
        <button id="downloadImage" class="btnimg"><img src="{{ asset('assets/images/download.png') }}" width="50" height="50"></button>
        <div class="hidden group-hover:block absolute left-0 mt-2 bg-blue-300 bg-opacity-50 text-center rounded-md px-2 py-1"><span class="text-xs text-gray-800">Decargar</span></div>
    </div>
</div>
<div class="flex justify-end mb-4">
    <label for="scaleSelect" class="mr-3 text-blue-900" style="font-size: 18px; margin-top: 8px;">{{ __('Escala') }}:</label>
    <select id="scaleSelect" style="padding: 8px; padding-right: 30px; padding-left: 12px; border-radius: 10px; background-color: #ffffffff;color: #2000d4ff;border: 2px solid #0063d4ff;font-size: 16px; font-weight: bold; transition: background-color 0.3s, border-color 0.3s;margin-right: 70px;appearance: none; /* Elimina la flecha por defecto */-webkit-appearance: none; /* Para Safari */-moz-appearance: none; /* Para Firefox */">
        <option value="1" style="background-color: white; color: black;">1:1</option>
        <option value="0.5" style="background-color: white; color: black;">1:2</option>
        <option value="0.33333" style="background-color: white; color: black;">1:3</option>
        <option value="0.25" style="background-color: white; color: black;">1:4</option>
        <option value="0.2" style="background-color: white; color: black;">1:5</option>
        <option value="2" style="background-color: white; color: black;">2:1</option>
        <option value="3" style="background-color: white; color: black;">3:1</option>
        <option value="4" style="background-color: white; color: black;">4:1</option>
        <option value="5" style="background-color: white; color: black;">5:1</option>
    </select>
</div>

<div class="flex justify-center mt-[40px] mb-[30px]"><canvas id="canvas"></canvas></div>
<div id="scaleMessage" style="display:none; color: white;font-size: 18px;padding: 10px;text-align: center;">
</div>

<div class="flex justify-center mb-4"><button id="clearButton" class="botton2">{{ __('Limpiar') }}</button></div>


<div class="flex justify-end mb-[30px] mr-[30px]">
    <a href="{{ route('tool.search',$tool->id)}}" class="botton3">{{ __('Herramientas aplicadas') }}</a>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.5.0/fabric.min.js"></script>
<script>
const canvas = new fabric.Canvas('canvas');
const imgUrl = "{{ asset('storage/tools/'.$tool->tool_uri) }}";
const img = new Image();
img.src = imgUrl;
let imageScaled = false;

img.onload = function() {
    const canvasWidth = img.width;
    const canvasHeight = img.height;

    function redondearEscala(valor) {
        const fracciones = [1, 2, 3, 4, 5, 6, 7, 8, 10, 12, 15, 20];
        let fraccionCercana = null;
        let diferenciaMinima = null;

        fracciones.forEach(function(fraccion) {
            const diferencia = Math.abs(valor - fraccion);
            if (diferenciaMinima === null || diferencia < diferenciaMinima) {
                diferenciaMinima = diferencia;
                fraccionCercana = fraccion;
            }
        });

        return fraccionCercana;
    }

    if (canvasWidth > 1100) {
        const scaleFactor = 1100 / canvasWidth;
        const newWidth = 1100;
        const newHeight = canvasHeight * scaleFactor;
        
        if (!imageScaled) {
            const scaleMessage = document.getElementById('scaleMessage');
            const escalaRedondeada = redondearEscala(1 / scaleFactor);
            scaleMessage.textContent = `La imagen fue escalada a 1:${escalaRedondeada}`;
            scaleMessage.style.display = 'block';
            imageScaled = true;
        }

        canvas.setWidth(newWidth);
        canvas.setHeight(newHeight);
        fabric.Image.fromURL(imgUrl, function(fabricImg) {
            fabricImg.set({ left: 0, top: 0, selectable: false });
            fabricImg.scale(scaleFactor);
            canvas.setBackgroundImage(fabricImg, canvas.renderAll.bind(canvas), {
                scaleX: fabricImg.scaleX,
                scaleY: fabricImg.scaleY
            });
        });
    } else {
        canvas.setWidth(canvasWidth);
        canvas.setHeight(canvasHeight);

        fabric.Image.fromURL(imgUrl, function(fabricImg) {
            fabricImg.set({ left: 0, top: 0, selectable: false });
            canvas.setBackgroundImage(fabricImg, canvas.renderAll.bind(canvas), {
                scaleX: canvasWidth / fabricImg.width,
                scaleY: canvasHeight / fabricImg.height
            });
        });
    }
};

    let measuringArcs = false; 
    let pointsArc = []; 
    let arc;
    let arcText;
    let centerPoint;
    let scaleFactor = 1;

    document.getElementById('scaleSelect').addEventListener('change', function(e) {
        scaleFactor = parseFloat(e.target.value);
        console.log(`Escala seleccionada: 1:${1 / scaleFactor}`);
    });
    document.getElementById('arco').onclick = function() {
        measuringArcs = !measuringArcs; 
        if (measuringArcs) {
            pointsArc = []; 
            console.log("Medición de arcos activada.");
        } else {
            console.log("Medición de arcos desactivada.");
        }
    };
    canvas.on('mouse:down', function(options) {
        if (!measuringArcs) return; 

        const pointer = canvas.getPointer(options.e);

        if (pointsArc.length < 2) {
            pointsArc.push({ x: pointer.x, y: pointer.y });
            const circle = new fabric.Circle({
                left: pointer.x - 4,
                top: pointer.y - 4,
                radius: 4,
                fill: 'purple',
                selectable: false
            });
            canvas.add(circle);
        } else if (pointsArc.length === 2) {
            centerPoint = { x: pointer.x, y: pointer.y };
            const circle = new fabric.Circle({
                left: pointer.x - 4,
                top: pointer.y - 4,
                radius: 4,
                fill: 'blue',
                selectable: false
            });
            canvas.add(circle);
            drawArc(pointsArc[0], pointsArc[1], centerPoint);
            pointsArc = []; 
        }
    });

    function drawArc(pointArc1, pointArc2, center) {
        const radius = Math.sqrt(Math.pow(center.x - pointArc1.x, 2) + Math.pow(center.y - pointArc1.y, 2));
        const angle1 = Math.atan2(pointArc1.y - center.y, pointArc1.x - center.x);
        const angle2 = Math.atan2(pointArc2.y - center.y, pointArc2.x - center.x);

        const arcPath = new fabric.Path(`M ${pointArc1.x} ${pointArc1.y} A ${radius} ${radius} 0 0 1 ${pointArc2.x} ${pointArc2.y}`, {
            strokeWidth: 1,
            stroke: 'cyan',
            fill: '',
            selectable: false
        });
        canvas.add(arcPath);

        const completeArc = new fabric.Path(`M ${pointArc1.x} ${pointArc1.y} A ${radius} ${radius} 0 0 0 ${pointArc2.x} ${pointArc2.y}`, {
        strokeWidth: 2,
        stroke: '#29ff1b',
        fill: '',
        selectable: false
        });
        canvas.add(completeArc);

        const angle = Math.abs((angle2 - angle1) * (180 / Math.PI));
        const arcLengthPx = Math.abs(radius * (angle * Math.PI / 180));
        const arcLengthMm = arcLengthPx * scaleFactor;

        arcText = new fabric.Text(`Longitud: ${Math.round(arcLengthMm)} mm`, {
            left: (pointArc1.x + pointArc2.x) / 2,
            top: (pointArc1.y + pointArc2.y) / 2 - 30,
            fontSize: 16,
            fill: 'blue',
            selectable: false
        });
        canvas.add(arcText);
    }
    document.getElementById('clearButton').onclick = function() {
        canvas.clear();
        resetCanvasImage();

        pointsArc = []; 
        if (arc) {
            canvas.remove(arc);
            arc = null;
        }
        if (arcText) {
            canvas.remove(arcText);
            arcText = null;
        }
        centerPoint = null;
    };

    function resetCanvasImage() {
    fabric.Image.fromURL(imgUrl, function(fabricImg) {
        fabricImg.set({ 
            left: 0, 
            top: 0, 
            selectable: false 
        });
        canvas.setBackgroundImage(fabricImg, canvas.renderAll.bind(canvas), {
            scaleX: scaleFactor,
            scaleY: scaleFactor
        });
    }, {
        crossOrigin: 'Anonymous'
    });
}
    let measuringDist = false; 
    let pointdist1 = null;
    let pointdist2 = null;
    let line;
    let lineLengthText;

    document.getElementById('distance').onclick = function() {
        measuringDist = !measuringDist; 
        if (measuringDist) {
            pointsDist = []; 
            console.log("Medición de distancias activada.");
        } else {
            console.log("Medición de distancias desactivada.");
        }
    };

    canvas.on('mouse:down', function(options) {
        if (!measuringDist) return;
        const pointer = canvas.getPointer(options.e);

        if (pointdist1 === null) {
            pointdist1 = { x: pointer.x, y: pointer.y };
            const circle = new fabric.Circle({
                left: pointer.x - 4,
                top: pointer.y - 4,
                radius: 4,
                fill: '#29ff1b',
                selectable: false
            });
            canvas.add(circle);
        } else {
            pointdist2 = { x: pointer.x, y: pointer.y };
            const circle = new fabric.Circle({
                left: pointer.x - 4,
                top: pointer.y - 4,
                radius: 4,
                fill: '#29ff1b',
                selectable: false
            });
            canvas.add(circle);

            drawLine(pointdist1, pointdist2);
            pointdist1 = null;
            pointdist2 = null;
        }
    });

    function drawLine(point1, point2) {
        line = new fabric.Line([point1.x, point1.y, point2.x, point2.y], {
            strokeWidth: 2,
            stroke: 'cyan',
            selectable: false
        });
        canvas.add(line);

        const distancePx = Math.sqrt(Math.pow(point2.x - point1.x, 2) + Math.pow(point2.y - point1.y, 2));
        const distanceMm = distancePx * scaleFactor; // Aplicar el factor de escala

        lineLengthText = new fabric.Text(`Distancia: ${Math.round(distanceMm)} mm`, {
            left: (point1.x + point2.x) / 2,
            top: (point1.y + point2.y) / 2 - 30,
            fontSize: 16,
            fill: 'cyan',
            selectable: false
        });
        canvas.add(lineLengthText);
    }

    let measuringAngles = false; 
    let anglePoints = [];

    document.getElementById('angle').onclick = function() {
        measuringAngles = !measuringAngles; 
        if (measuringAngles) {
            anglePoints = []; 
            console.log("Medición de ángulos activada.");
        } else {
            console.log("Medición de ángulos desactivada.");
        }
    };

    canvas.on('mouse:down', function(options) {
        if (!measuringAngles) return;
        const pointer = canvas.getPointer(options.e);

        if (anglePoints.length < 2) {
            anglePoints.push({ x: pointer.x, y: pointer.y });
            const circle = new fabric.Circle({
                left: pointer.x - 4,
                top: pointer.y - 4,
                radius: 4,
                fill: 'cyan',
                selectable: false
            });
            canvas.add(circle);
        } else if (anglePoints.length === 2) {
            anglePoints.push({ x: pointer.x, y: pointer.y });
            const circle = new fabric.Circle({
                left: pointer.x - 4,
                top: pointer.y - 4,
                radius: 4,
                fill: 'cyan',
                selectable: false
            });
            canvas.add(circle);

            drawAngle(anglePoints[0], anglePoints[1], anglePoints[2]);
            anglePoints = []; 
        }
    });

    function drawAngle(pointA, pointB, pointC) {
        const lineAB = new fabric.Line([pointA.x, pointA.y, pointB.x, pointB.y], {
            strokeWidth: 2,
            stroke: '#29ff1b',
            selectable: false
        });
        canvas.add(lineAB);
        
        const lineBC = new fabric.Line([pointB.x, pointB.y, pointC.x, pointC.y], {
            strokeWidth: 2,
            stroke: '#29ff1b',
            selectable: false
        });
        canvas.add(lineBC);

        const angleText = new fabric.Text(`Ángulo: ${Math.round(calculateAngle(pointA, pointB, pointC))}°`, {
            left: (pointA.x + pointB.x + pointC.x) / 3,
            top: (pointA.y + pointB.y + pointC.y) / 3 - 30,
            fontSize: 16,
            fill: '#29ff1b',
            selectable: false
        });
        canvas.add(angleText);
    }

    let measuringContours = false; 
    let contourPoints = []; 
    let contourLine;
    let contourText;

    document.getElementById('delimited').onclick = function() {
        measuringContours = !measuringContours; 
        if (measuringContours) {
            contourPoints = []; 
            console.log("Medición de contornos activada.");
        } else {
            console.log("Medición de contornos desactivada.");
        }
    };

        canvas.on('mouse:down', function(options) {
        if (!measuringContours) return; 

        const pointer = canvas.getPointer(options.e);
        contourPoints.push({ x: pointer.x, y: pointer.y });

        const circle = new fabric.Circle({
            left: pointer.x - 4,
            top: pointer.y - 4,
            radius: 4,
            fill: '#8607f7',
            selectable: false
        });
        canvas.add(circle);

        drawContour();
    });

    function drawContour() {
        if (contourLine) {
            canvas.remove(contourLine);
        }

        if (contourPoints.length < 2) return; 

        const contourPath = contourPoints.map((point, index) => {
            if (index === 0) return `M ${point.x} ${point.y}`;
            return `L ${point.x} ${point.y}`;
        }).join(' ');

        contourLine = new fabric.Path(contourPath, {
            strokeWidth: 2,
            stroke: '#8607f7',
            fill: '',
            selectable: false
        });

        canvas.add(contourLine);

        const contourLength = calculateContourLength(contourPoints);
        
        if (contourText) {
            canvas.remove(contourText);
        }
        contourText = new fabric.Text(`Longitud: ${Math.round(contourLength)} mm`, {
            left: contourPoints[contourPoints.length - 1].x,
            top: contourPoints[contourPoints.length - 1].y - 20,
            fontSize: 16,
            fill: '#8607f7',
            selectable: false
        });
        canvas.add(contourText);
    }


    function calculateContourLength(points) {
    let lengthPx = 0;
    for (let i = 1; i < points.length; i++) {
        const dx = points[i].x - points[i - 1].x;
        const dy = points[i].y - points[i - 1].y;
        lengthPx += Math.sqrt(dx * dx + dy * dy);
    }
    return lengthPx * scaleFactor; // Convertir a mm
    }

    function calculateAngle(pointA, pointB, pointC) {
        const ab = Math.sqrt(Math.pow(pointB.x - pointA.x, 2) + Math.pow(pointB.y - pointA.y, 2));
        const bc = Math.sqrt(Math.pow(pointC.x - pointB.x, 2) + Math.pow(pointC.y - pointB.y, 2));
        const ac = Math.sqrt(Math.pow(pointC.x - pointA.x, 2) + Math.pow(pointC.y - pointA.y, 2));

        const angle = Math.acos((ab * ab + bc * bc - ac * ac) / (2 * ab * bc));
        return angle * (180 / Math.PI); 
    }

let painting = false;
let previousPoint = null;

document.getElementById('paint').onclick = function() {
    painting = !painting;
    if (painting) {
        console.log("Pintura activada. Haz clic en el canvas para empezar a pintar.");
        canvas.selection = false;
    } else {
        console.log("Pintura desactivada.");
        canvas.selection = true;
        canvas.off('mouse:move', paint);
    }
};

canvas.on('mouse:down', function(options) {
    if (painting) {
        const pointer = canvas.getPointer(options.e);
        previousPoint = { x: pointer.x, y: pointer.y };
        canvas.on('mouse:move', paint);
    }
});

function paint(options) {
    if (!painting || !previousPoint) return;
    const pointer = canvas.getPointer(options.e);
    const line = new fabric.Line([previousPoint.x, previousPoint.y, pointer.x, pointer.y], {
        strokeWidth: 1,
        stroke: 'red',
        selectable: false
    });
    canvas.add(line);
    previousPoint = { x: pointer.x, y: pointer.y };
    canvas.renderAll();
}

canvas.on('mouse:up', function() {
    if (painting) {
        console.log("Pintura finalizada.");
        painting = false;
        canvas.off('mouse:move', paint);
        previousPoint = null;
        canvas.selection = true;
    }
});

canvas.setCursor('crosshair');

const downloadImageButton = document.getElementById('downloadImage');
downloadImageButton.addEventListener('click', () => {
    const dataURL = canvas.toDataURL({
        format: 'png',
        quality: 1.0,
    });
    const link = document.createElement('a');
    const ci_patient = "{{ $tool->ci_patient }}";  
    const tomography_id = "{{ $tool->tool_tomography_id }}"; 
    const radiography_id = "{{ $tool->tool_radiography_id }}"; 
    link.download = `mediciones_${radiography_id}_${tomography_id}_${ci_patient}_${new Date().toISOString().slice(0, 10)}.png`;
    link.href = dataURL;
    link.click();
});

document.querySelector('form').addEventListener('submit', function(event) {
    const dataURL = canvas.toDataURL({
        format: 'png',
        quality: 1.0,
    });
    document.getElementById('canvasData').value = dataURL;
    console.log(dataURL);
});

document.getElementById('save').onclick = function(event) {
    event.preventDefault();

    const dataURL = canvas.toDataURL('image/png');

    fetch("{{ route('tool.store', ['radiography_id' => $tool->tool_radiography_id,'tomography_id' => $tool->tool_tomography_id,'ci_patient' => $tool->ci_patient, 'id' => $tool->id]) }}", {
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
            alert("Imagen guardada exitosamente");
        } else {
            alert("Error al guardar la imagen");
        }
    })
    .catch(error => {
        console.error("Error al guardar la imagen:", error);
    });
};
document.getElementById('updateButton').addEventListener('click', function () {
    location.reload();
});

</script>
@endsection
