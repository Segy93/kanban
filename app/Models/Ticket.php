<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Ticket model (tickets can be assigned to users)
 */
class Ticket extends Model
{
    use HasFactory;

    /**
     * Status names
     *
     * @var array
     */
    private static $statuses = [
        0 => 'To Do',
        1 => 'In Progress',
        2 => 'Done',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'title'       => 'string',
        'description' => 'string',
        'status'      => 'int',
        'priority'    => 'int',
        'user_id'     => 'int',
    ];

    protected $appends = [
        'status_name',
    ];

    /**
     * Return status name
     *
     * @return string
     */
    public function getStatusNameAttribute(): string {
        return self::$statuses[$this->status];
    }









    // Relations

    /**
     * Get the user that ticket is assigned to
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
