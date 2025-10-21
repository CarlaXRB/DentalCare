<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Dicom extends Model
{
    use HasFactory;
        protected $fillable = ['file_name', 'image_url',
        'patient_name', 'patient_id', 'modality', 'study_date',
        'rows', 'columns', 'metadata'
    ];
    protected $casts = [
        'metadata' => 'array'
    ];
    public function radiography():BelongsTo{
        return $this->belongsTo(Radiography::class, 'patient_id', 'ci_patient');
    }
    public function tomography():BelongsTo{
        return $this->belongsTo(Tomography::class, 'patient_id', 'ci_patient');
    }
}
