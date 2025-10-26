<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/app.css')}}">
    <title>Crear Paciente</title>

    <!-- Fuente -->
    <link href="https://fonts.bunny.net/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Estilos -->
    <style>
        :root {
            --primary-color: #0d00bd;
            --secundary-color: #5bb5ff;
            --secundaryp-color: #861efc;
            --dark-color: #000000;
            --light-color: #fff;
            --gray-color: #222224;
            --purpled-color: #0f0044;
            --purple-color: #310081;
            --purpleh-color: #4b00ad;
            --blued-color: #001b42;
            --blue-color: #0d5fda;
            --blueh-color: #2b80ff;
            --greend-color: #004b3e;
            --green-color: #026151;
            --greenh-color: #01947b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f7fafc;
            color: var(--dark-color);
        }

        .container {
            max-width: 800px;
            margin: 60px auto;
            background-color: var(--light-color);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }

        h1 {
            color: var(--purpled-color);
            text-align: center;
            text-transform: uppercase;
            font-size: 26px;
            margin-bottom: 30px;
        }

        form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        label {
            font-weight: 600;
            color: var(--blued-color);
            margin-bottom: 5px;
            display: block;
            text-transform: uppercase;
            font-size: 14px;
        }

        input, select {
            width: 100%;
            padding: 10px 14px;
            border-radius: 10px;
            border: 2px solid #ccc;
            font-size: 15px;
            transition: border 0.3s ease;
        }

        input:focus, select:focus {
            border-color: var(--blue-color);
            outline: none;
        }

        .error {
            background-color: rgba(0, 8, 117, 0.8);
            color: var(--light-color);
            border-radius: 6px;
            padding: 5px;
            margin-top: 5px;
            text-align: center;
            font-size: 14px;
        }

        .actions {
            grid-column: 1 / -1;
            text-align: center;
            margin-top: 25px;
        }

        .botton1 {
            color: var(--light-color);
            background-color: var(--purple-color);
            border-radius: 50px;
            padding: 10px 25px;
            text-decoration: none;
            font-weight: bold;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .botton1:hover {
            background-color: var(--purpleh-color);
        }

        .botton2 {
            color: var(--light-color);
            background-color: var(--blue-color);
            border-radius: 50px;
            padding: 10px 25px;
            border: none;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .botton2:hover {
            background-color: var(--blueh-color);
        }

        .header {
            display: flex;
            justify-content: flex-end;
            padding: 20px;
        }

        .header a {
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="header">
        <a href="{{ route('patient.index') }}" class="botton1">Pacientes</a>
    </div>

    <div class="container">
        <h1>Información del Paciente</h1>

        <form method="POST" action="{{ route('patient.store') }}">
            @csrf

            <!-- Nombre del paciente -->
            <div>
                <label for="name_patient">Nombre del Paciente</label>
                <input type="text" name="name_patient" id="name_patient" value="{{ old('name_patient') }}" placeholder="Ejemplo: Juan Pérez">
                @error('name_patient') <p class="error">{{ $message }}</p> @enderror
            </div>

            <!-- C.I. -->
            <div>
                <label for="ci_patient">C.I.</label>
                <input type="text" name="ci_patient" id="ci_patient" value="{{ old('ci_patient') }}" placeholder="Ejemplo: 1234567">
                @error('ci_patient') <p class="error">{{ $message }}</p> @enderror
            </div>

            <!-- Fecha de nacimiento -->
            <div>
                <label for="birth_date">Fecha de Nacimiento</label>
                <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}">
                @error('birth_date') <p class="error">{{ $message }}</p> @enderror
            </div>

            <!-- Género -->
            <div>
                <label for="gender">Género</label>
                <select name="gender" id="gender">
                    <option value="">-- Seleccionar género --</option>
                    <option value="femenino" {{ old('gender') == 'femenino' ? 'selected' : '' }}>Femenino</option>
                    <option value="masculino" {{ old('gender') == 'masculino' ? 'selected' : '' }}>Masculino</option>
                </select>
                @error('gender') <p class="error">{{ $message }}</p> @enderror
            </div>

            <!-- Número de celular -->
            <div>
                <label for="patient_contact">Número de Celular</label>
                <input type="text" name="patient_contact" id="patient_contact" value="{{ old('patient_contact') }}" placeholder="Ejemplo: 71234567">
                @error('patient_contact') <p class="error">{{ $message }}</p> @enderror
            </div>

            <!-- Botón -->
            <div class="actions">
                <button type="submit" class="botton2">Crear Paciente</button>
            </div>
        </form>
    </div>

</body>
</html>
