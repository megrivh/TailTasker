<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Task extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'task_name',
        'task_description',
        'task_due_date',
        'is_completed',
        'is_recurring',
        'recurring_frequency',
        'is_reminder_needed',
    ];

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }
}
