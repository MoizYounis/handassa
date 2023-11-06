<?php

namespace App\Models;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostProposal extends Model
{
    use HasFactory;
    protected $table = 'post_proposals';
    protected $fillable = [
        'post_id',
        'professional_id',
        'price',
        'description',
        'status'
    ];

    /**
     * Get the user that owns the PostProposal
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function professional(): BelongsTo
    {
        return $this->belongsTo(User::class, 'professional_id');
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
