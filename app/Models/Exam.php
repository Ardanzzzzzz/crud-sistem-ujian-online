<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'duration',
        'total_questions',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function attempts()
    {
        return $this->hasMany(Attempt::class); // kalau sudah dibuat
    }

    public function examAttempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }

    public function updateTotalQuestions()
    {
        $this->total_questions = $this->questions()->count();
        $this->save();
    }
}