<?php
/**
 * Ticket.php
 * php version 8.1.2
 *
 * @category Model
 * @package  Laravel
 * @author   Sergej Sjekloca <segy993@gmail.com>
 * @license  No license
 * @link     https://github.com/Segy93/kanban
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Ticket model (tickets can be assigned to users)
 *
 * @category Test
 * @package  Laravel
 * @author   Sergej Sjekloca <segy993@gmail.com>
 * @license  No license
 * @link     https://github.com/Segy93/kanban
 */
class Ticket extends Model
{
    use HasFactory;

    /**
     * Status names
     *
     * @var array
     */
    private static $_statuses = [
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
    public function getStatusNameAttribute(): string
    {
        return self::$_statuses[$this->status];
    }

    /**
     * Returns array of statuses
     *
     * @return array
     */
    public static function getStatuses(): array
    {
        return self::$_statuses;
    }









    // Relations

    /**
     * Get the user that ticket is assigned to
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
