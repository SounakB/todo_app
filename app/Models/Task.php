<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class Task extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Prunable;

    protected $fillable = ['status'];

    /**
     * The subtasks of this task.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subTasks()
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    /**
     * Get the prunable model query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function prunable()
    {
        return static::where('deleted_at', '<=', now()->subMonth());
    }
}
