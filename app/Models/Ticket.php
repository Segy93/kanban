<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

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
        'status_text',
    ];

    /**
     * Return status text
     *
     * @return string
     */
    public function getStatusTextAttribute(): string {
        return self::$statuses[$this->status];
    }
}
