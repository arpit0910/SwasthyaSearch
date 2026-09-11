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
            GeneralMedicalQaBulkSeeder::class,
            DiseaseFaqSeeder::class,
            ArticleSeeder::class,
            HomeRemedyCategorySeeder::class,
            HomeRemedyIngredientSeeder::class,
            HomeRemedySeeder::class,
        ]);
    }
}
