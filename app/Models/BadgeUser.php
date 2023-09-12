<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BadgeUser extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'badge_name ',
        'user_id'
    ];

    /**
     * Get the user that wrote the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
