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
        $imagesPath = storage_path("app/public/{$this->study_uri}");
        $firstImageName = null;

        if (File::isDirectory($imagesPath)) {
            $directoryIterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($imagesPath, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );
            
            $imagePattern = '/\.(png|jpg|jpeg)$/i';

            foreach ($directoryIterator as $file) {
                if ($file->isFile() && preg_match($imagePattern, $file->getFilename())) {
                    $firstImageName = $file->getFilename();
                    break;
                }
            }
        }

        if ($firstImageName) {
            return route('multimedia.image', [
                'studyCode' => $this->study_code, 
                'fileName' => $firstImageName
            ]);
        }
        return 'https://placehold.co/100x100/A0AEC0/ffffff?text=No+Img';
    }
}
