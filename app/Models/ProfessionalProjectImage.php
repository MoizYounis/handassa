<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProfessionalProjectImage extends Model
{
    use HasFactory;
    protected $table = 'professional_project_images';
    protected $fillable = [
        'professional_id',
        'image'
    ];

    /**
     * Get the user that owns the ProfessionalProjectImage
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'professional_id');
    }
}
