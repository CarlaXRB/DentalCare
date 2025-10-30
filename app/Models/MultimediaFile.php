<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class MultimediaFile extends Model
{
    use HasFactory;

    protected $guarded=[];
    
    public function patient():BelongsTo{
        return $this->belongsTo(Patient::class, 'ci_patient', 'ci_patient');
    }
    public function tools():HasMany{
        return $this->hasMany(Tool::class, 'tool_radiography_id', 'radiography_id');
    }
    public function reports():HasMany{
        return $this->hasMany(Report::class, 'report_id', 'radiography_id');
    }

    public function getFirstImageUrlAttribute()
    {
        // 1. Ruta base donde se guardan los archivos
        $imagesPath = storage_path("app/public/{$this->study_uri}");
        $firstImageName = null;
        
        // 2. Búsqueda exhaustiva (recursiva) para encontrar la primera imagen
        if (File::isDirectory($imagesPath)) {
            $directoryIterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($imagesPath, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );
            
            $imagePattern = '/\.(png|jpg|jpeg)$/i';

            foreach ($directoryIterator as $file) {
                if ($file->isFile() && preg_match($imagePattern, $file->getFilename())) {
                    $firstImageName = $file->getFilename();
                    break; // Encontrada la primera imagen, salimos
                }
            }
        }

        if ($firstImageName) {
            // 3. Generamos la RUTA PROTEGIDA usando el código del estudio y el nombre del archivo
            return route('multimedia.image', [
                'studyCode' => $this->study_code, 
                'fileName' => $firstImageName
            ]);
        }

        // Si no hay imágenes, devolvemos un placeholder
        return 'https://placehold.co/100x100/A0AEC0/ffffff?text=No+Img';
    }
}
