<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    // The attributes that are mass assignable.
    protected $fillable = ['name', 'description', 'start_time', 'end_time', 'user_id'];

    // Get the user associated with the event.
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Get the attendees associated with the event.
    public function attendees(): HasMany
    {
        return $this->hasMany(Attendee::class);
    }
}
