<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            DepartmentSeeder::class,
            DiseaseSeeder::class,
            MedicineSeeder::class,
            ActivitySeeder::class,
            QuizSeeder::class,
            FaqSeeder::class,
            GeneralQuestionSeeder::class,
            ArticleSeeder::class,
        ]);
    }
}
