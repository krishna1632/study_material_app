<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quiz extends Model
{
    use HasFactory;
    protected $table = 'quizzes';
    protected $fillable = ['subject_type', 'department', 'semester', 'subject_name', 'faculty_name', 'date', 'start_time', 'end_time', 'status'];


    public function questions()
    {
        return $this->hasMany(Question::class, 'quiz_id');
    }

}