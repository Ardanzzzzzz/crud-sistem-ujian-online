<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ExamAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'exam_id',
        'started_at',
        'finished_at',
        'score',
        'total_questions',
        'correct_answers',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
            'score' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class);
    }

    public function isExpired()
    {
        if ($this->status === 'completed') {
            return false;
        }

        $duration = $this->exam->duration; // dalam menit
        $expiryTime = $this->started_at->addMinutes($duration);
        
        return Carbon::now()->gt($expiryTime);
    }

    public function getRemainingTimeAttribute()
    {
        if ($this->status === 'completed') {
            return 0;
        }

        $duration = $this->exam->duration; // dalam menit
        $expiryTime = $this->started_at->addMinutes($duration);
        $remaining = Carbon::now()->diffInSeconds($expiryTime, false);
        
        return max(0, $remaining);
    }

    public function calculateScore()
    {
        $totalQuestions = $this->total_questions;
        $correctAnswers = $this->correct_answers;
        
        if ($totalQuestions > 0) {
            return round(($correctAnswers / $totalQuestions) * 100, 2);
        }
        
        return 0;
    }
}