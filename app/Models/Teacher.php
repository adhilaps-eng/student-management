<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Teacher extends Model
{
    protected $fillable = [
        'user_id',
        'employee_code',
        'phone',
        'gender',
        'date_of_birth',
        'address',
        'joining_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'joining_date' => 'date',
            'status' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function generateEmployeeCode(): string
    {
        $lastNumber = static::where('employee_code', 'like', 'TCH-%')
            ->get()
            ->map(fn ($teacher) => (int) str_replace('TCH-', '', $teacher->employee_code))
            ->max() ?? 0;

        do {
            $lastNumber++;
            $code = 'TCH-' . str_pad((string) $lastNumber, 4, '0', STR_PAD_LEFT);
        } while (static::where('employee_code', $code)->exists());

        return $code;
    }
}