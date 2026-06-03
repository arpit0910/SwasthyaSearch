<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Support\Facades\Schema;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = collect();

        if (Schema::hasTable('activities')) {
            $activities = Activity::query()
                ->published()
                ->ordered()
                ->get()
                ->groupBy('category');
        }

        return view('activities.index', [
            'locale' => app()->getLocale(),
            'activityGroups' => $activities,
        ]);
    }

    public function breathing()
    {
        return view('activities.breathing', ['locale' => app()->getLocale()]);
    }

    public function grounding()
    {
        return view('activities.grounding', ['locale' => app()->getLocale()]);
    }

    public function moodCheck()
    {
        return view('activities.mood-check', ['locale' => app()->getLocale()]);
    }

    public function crisis()
    {
        return view('support.crisis', ['locale' => app()->getLocale()]);
    }

    public function memoryGame()
    {
        return view('activities.memory-game', ['locale' => app()->getLocale()]);
    }

    public function calmTap()
    {
        return view('activities.calm-tap', ['locale' => app()->getLocale()]);
    }
}
