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
        $imagesPath = public_path($this->study_uri);
        
        // El * es importante para FullCalendar
        $images = File::glob($imagesPath . '/*.{png,jpg,jpeg}', GLOB_BRACE);

        if (!empty($images)) {
            // Obtenemos el nombre del archivo (ej: 1234.jpg) y construimos la URL usando asset()
            $fileName = basename($images[0]);
            return asset($this->study_uri . '/' . $fileName);
        }

        // Devolver un placeholder si no hay imágenes o la carpeta no existe
        return 'https://placehold.co/100x100/A0AEC0/ffffff?text=No+Img';
    }
}
