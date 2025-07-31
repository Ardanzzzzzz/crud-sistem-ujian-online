<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Exam;

class QuestionSeeder extends Seeder
{
    public function run()
    {
        $mathExam = Exam::where('title', 'Matematika Dasar')->first();
        
        if ($mathExam) {
            // Question 1
            $question1 = Question::create([
                'exam_id' => $mathExam->id,
                'question_text' => 'Berapa hasil dari 15 + 25?',
            ]);

            QuestionOption::create([
                'question_id' => $question1->id,
                'option_text' => '30',
                'is_correct' => false,
            ]);

            QuestionOption::create([
                'question_id' => $question1->id,
                'option_text' => '40',
                'is_correct' => true,
            ]);

            QuestionOption::create([
                'question_id' => $question1->id,
                'option_text' => '35',
                'is_correct' => false,
            ]);

            QuestionOption::create([
                'question_id' => $question1->id,
                'option_text' => '45',
                'is_correct' => false,
            ]);

            // Question 2
            $question2 = Question::create([
                'exam_id' => $mathExam->id,
                'question_text' => 'Berapa hasil dari 8 × 7?',
            ]);

            QuestionOption::create([
                'question_id' => $question2->id,
                'option_text' => '54',
                'is_correct' => false,
            ]);

            QuestionOption::create([
                'question_id' => $question2->id,
                'option_text' => '56',
                'is_correct' => true,
            ]);

            QuestionOption::create([
                'question_id' => $question2->id,
                'option_text' => '58',
                'is_correct' => false,
            ]);

            QuestionOption::create([
                'question_id' => $question2->id,
                'option_text' => '64',
                'is_correct' => false,
            ]);

            // Question 3
            $question3 = Question::create([
                'exam_id' => $mathExam->id,
                'question_text' => 'Berapa hasil dari 100 ÷ 5?',
            ]);

            QuestionOption::create([
                'question_id' => $question3->id,
                'option_text' => '15',
                'is_correct' => false,
            ]);

            QuestionOption::create([
                'question_id' => $question3->id,
                'option_text' => '20',
                'is_correct' => true,
            ]);

            QuestionOption::create([
                'question_id' => $question3->id,
                'option_text' => '25',
                'is_correct' => false,
            ]);

            QuestionOption::create([
                'question_id' => $question3->id,
                'option_text' => '30',
                'is_correct' => false,
            ]);

            // Update total questions
            $mathExam->updateTotalQuestions();
        }

        $indonesiaExam = Exam::where('title', 'Bahasa Indonesia')->first();
        
        if ($indonesiaExam) {
            // Question 1
            $question1 = Question::create([
                'exam_id' => $indonesiaExam->id,
                'question_text' => 'Apa yang dimaksud dengan pantun?',
            ]);

            QuestionOption::create([
                'question_id' => $question1->id,
                'option_text' => 'Puisi bebas',
                'is_correct' => false,
            ]);

            QuestionOption::create([
                'question_id' => $question1->id,
                'option_text' => 'Puisi lama yang terdiri dari 4 baris dengan pola rima a-b-a-b',
                'is_correct' => true,
            ]);

            QuestionOption::create([
                'question_id' => $question1->id,
                'option_text' => 'Cerita rakyat',
                'is_correct' => false,
            ]);

            QuestionOption::create([
                'question_id' => $question1->id,
                'option_text' => 'Lagu daerah',
                'is_correct' => false,
            ]);

            // Update total questions
            $indonesiaExam->updateTotalQuestions();
        }
    }
}