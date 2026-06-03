<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        foreach ($this->activities() as $activity) {
            $activity['created_at'] = $now;
            $activity['updated_at'] = $now;

            DB::table('activities')->upsert(
                [$activity],
                ['slug'],
                [
                    'title_en',
                    'title_hi',
                    'category',
                    'description_en',
                    'description_hi',
                    'route_name',
                    'tone',
                    'cta_en',
                    'cta_hi',
                    'sort_order',
                    'is_published',
                    'updated_at',
                ]
            );
        }
    }

    private function activities(): array
    {
        return [
            [
                'title_en' => 'Breathing Exercise',
                'title_hi' => 'श्वास अभ्यास',
                'slug' => 'breathing-exercise',
                'category' => 'core',
                'description_en' => 'Use a calm 4-4-6-2 breathing pattern to slow down and settle your body.',
                'description_hi' => 'शरीर को शांत करने के लिए 4-4-6-2 पैटर्न के साथ धीमी श्वास का अभ्यास करें।',
                'route_name' => 'activities.breathing',
                'tone' => 'teal',
                'cta_en' => 'Start',
                'cta_hi' => 'शुरू करें',
                'sort_order' => 10,
                'is_published' => true,
            ],
            [
                'title_en' => 'Grounding Exercise',
                'title_hi' => 'ग्राउंडिंग अभ्यास',
                'slug' => 'grounding-exercise',
                'category' => 'core',
                'description_en' => 'Use the 5-4-3-2-1 method to return attention to the present moment.',
                'description_hi' => '5-4-3-2-1 तकनीक से ध्यान को वर्तमान क्षण में वापस लाएं।',
                'route_name' => 'activities.grounding',
                'tone' => 'cyan',
                'cta_en' => 'Start',
                'cta_hi' => 'शुरू करें',
                'sort_order' => 20,
                'is_published' => true,
            ],
            [
                'title_en' => 'Mood Check-in',
                'title_hi' => 'मूड चेक-इन',
                'slug' => 'mood-check-in',
                'category' => 'core',
                'description_en' => 'Name how you feel, reflect briefly, and get pointed toward the next helpful step.',
                'description_hi' => 'अपनी भावना पहचानें, थोड़ा रुककर सोचें और अगला मददगार कदम देखें।',
                'route_name' => 'activities.mood-check',
                'tone' => 'indigo',
                'cta_en' => 'Start',
                'cta_hi' => 'शुरू करें',
                'sort_order' => 30,
                'is_published' => true,
            ],
            [
                'title_en' => 'Crisis Support',
                'title_hi' => 'संकट सहायता',
                'slug' => 'crisis-support',
                'category' => 'core',
                'description_en' => 'Open urgent support guidance immediately if you feel unsafe or overwhelmed.',
                'description_hi' => 'यदि आप असुरक्षित या बहुत परेशान महसूस कर रहे हैं, तो तुरंत सहायता मार्गदर्शन खोलें।',
                'route_name' => 'support.crisis',
                'tone' => 'rose',
                'cta_en' => 'Open',
                'cta_hi' => 'खोलें',
                'sort_order' => 40,
                'is_published' => true,
            ],
            [
                'title_en' => 'Health Quizzes',
                'title_hi' => 'हेल्थ क्विज़',
                'slug' => 'health-quizzes',
                'category' => 'extras',
                'description_en' => 'Take short awareness and self-reflection quizzes for stress and everyday health myths.',
                'description_hi' => 'तनाव और रोज़मर्रा की हेल्थ जागरूकता के लिए छोटे क्विज़ लें।',
                'route_name' => 'quizzes.index',
                'tone' => 'indigo',
                'cta_en' => 'Open',
                'cta_hi' => 'खोलें',
                'sort_order' => 50,
                'is_published' => true,
            ],
            [
                'title_en' => 'Memory Game',
                'title_hi' => 'मेमोरी गेम',
                'slug' => 'memory-game',
                'category' => 'extras',
                'description_en' => 'Play a small card-match game designed to give your mind a gentle break.',
                'description_hi' => 'मन को हल्का विराम देने के लिए छोटा कार्ड-मैच गेम खेलें।',
                'route_name' => 'activities.games.memory',
                'tone' => 'cyan',
                'cta_en' => 'Open',
                'cta_hi' => 'खोलें',
                'sort_order' => 60,
                'is_published' => true,
            ],
            [
                'title_en' => 'Calm Tap Counter',
                'title_hi' => 'कैल्म टैप काउंटर',
                'slug' => 'calm-tap-counter',
                'category' => 'extras',
                'description_en' => 'Tap gently, count your rhythm, and pair the motion with slow breathing.',
                'description_hi' => 'धीरे-धीरे टैप करें, अपनी लय गिनें और इसे शांत श्वास के साथ जोड़ें।',
                'route_name' => 'activities.games.calm-tap',
                'tone' => 'teal',
                'cta_en' => 'Open',
                'cta_hi' => 'खोलें',
                'sort_order' => 70,
                'is_published' => true,
            ],
        ];
    }
}
