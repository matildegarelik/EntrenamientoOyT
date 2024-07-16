<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Topic;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('layouts.sidebar_user', function ($view) {
            $topics = Topic::with('children')->get();
            $view->with('topics', $topics);
        });

        View::composer('layouts.sidebar_admin', function ($view) {
            $topics = Topic::with('children')->get();
            $view->with('topics', $topics);
        });
    }

    public function register()
    {
        //
    }
}