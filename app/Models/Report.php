<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class Report extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    protected $guarded=[];

    public function patient():BelongsTo{
        return $this->belongsTo(Patient::class, 'ci_patient', 'ci_patient');
    }
    public function radiography():BelongsTo{
        return $this->belongsTo(Radiography::class, 'radiography_id', 'report_id');
    }
    public function tomography():BelongsTo{
        return $this->belongsTo(Tomography::class, 'tomography_id', 'report_id');
    }
    public function tool():BelongsTo{
        return $this->belongsTo(Tool::class, 'ci_patient', 'ci_patient');
    }
}
