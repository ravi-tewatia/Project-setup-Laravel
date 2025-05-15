<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    private $authUser = null;
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // IF QUERY_LOG not found in .env then we will set default value to false
        if (env("QUERY_LOG", false)) {
            DB::listen(function ($query) {
                // $route = Route::getCurrentRoute()->getActionName();
                $route = is_null(Route::getCurrentRoute()) ? 'Command Line' : Route::getCurrentRoute()->getActionName();
                $userDetail = "";
                if ($this->authUser) {
                    $userDetail = "UserID: " . $this->authUser->id . " || Name: " . $this->authUser->name;
                }

                Log::channel('query')->debug(
                    sprintf(
                        "Path: %s \nUser Detail: %s \nTime Taken (ms): %s \nQuery: %s \nBindings %s\n\r",
                        $route,
                        $userDetail,
                        $query->time,
                        $query->sql,
                        json_encode($query->bindings)
                    )
                );
            });
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view) {
            if (Auth::check()) {
                $this->authUser = Auth::user();
            }
        });
        Schema::defaultStringLength(191);
    }
}
