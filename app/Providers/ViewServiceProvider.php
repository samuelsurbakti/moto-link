<?php

namespace App\Providers;

use App\Models\Sys\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer(['ui.elements.button', 'ui.layouts.vertical', 'ui.layouts.horizontal'], function ($view) {
            $subdomain = Request::getHost();
            $currentPath = Request::segment(1);

            // Ambil App berdasarkan subdomain
            $app = App::where('subdomain', $subdomain)->first();

            $menus = [];
            $app_list = [];
            $page_title = null;
            $page_icon = null;

            if ($app && Auth::check()) {
                // Ambil role user pertama
                $roleId = Auth::user()->getRoleIds()->first();

                // Ambil menu yang bisa diakses user
                $menus = $app->menus()->with(['get_child', 'app'])
                    ->whereHas('menu_permission.roles', function ($query) use ($roleId) {
                        $query->where('uuid', $roleId);
                    })
                    ->whereNull('parent')
                    ->get();

                $app_list = App::where('subdomain', '!=', $subdomain)
                    ->whereHas('app_permission.roles', function($query) use ($roleId) {
                        $query->where('uuid', $roleId);
                    })->get();

                // Coba cocokkan menu aktif berdasarkan URL
                $active = $app->menus()
                    ->where('url', $currentPath)
                    ->first();

                if ($active) {
                    $page_title = $active->title;
                    $page_icon = $active->icon;
                }
            }

            $view->with([
                'app' => $app,
                'app_list' => $app_list,
                'menus' => $menus,
                'page_title' => $page_title ?? 'Tidak Diketahui',
                'page_icon' => $page_icon ?? 'bx bx-question-mark',
            ]);
        });
    }
}
