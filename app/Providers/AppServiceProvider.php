<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
        
        Gate::define('admin', function($user){
            return $user->role_id === User::ADMIN_ROLE_ID;
        });

        View::composer('modals.message', function($view) {
            $followingUsers = collect();


            if(Auth::check()) {
                $followingIds = Auth::user()->following()->pluck('following_id');
                $followingUsers = User::whereIn('id', $followingIds)->get();
            }

            $view->with('followingUsers', $followingUsers);
        });
    }
}
