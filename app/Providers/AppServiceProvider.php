<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer('*', function ($view) {
            // Skip for public pages
            if (in_array($view->getName(), ['tripping', 'auth.login', 'auth.registered', 'auth.verify'])) {
                $view->with('hiddenSections', []);
                $view->with('userNotes', collect());
                $view->with('dueNotesCount', 0);
                $view->with('sysNotifs', collect());
                $view->with('unreadNotifCount', 0);
                return;
            }
            $user = auth()->user();
            if ($user) {
                // Hidden sections — admin sees all
                if ($user->isAdmin()) {
                    $view->with('hiddenSections', []);
                } else {
                    $hidden = array_values(json_decode(
                        \DB::table('app_settings')->where('key', 'hidden_pages')->value('value') ?? '[]',
                        true
                    ) ?: []);
                    $view->with('hiddenSections', $hidden);
                }
                // Notes & notifications — all users get their own
                $notes = \App\Models\Note::where('user_id', $user->id)->whereNull('completed_at')->orderBy('created_at','desc')->get();
                $view->with('userNotes', $notes);
                $view->with('dueNotesCount', $notes->filter(fn($n) => $n->isDueNow())->count());
                $sysNotifs = \App\Models\SystemNotification::where('user_id', $user->id)
                    ->orderBy('notified_at', 'desc')->limit(50)->get();
                $view->with('sysNotifs', $sysNotifs);
                $view->with('unreadNotifCount', $sysNotifs->where('is_read', false)->count());
            } else {
                $view->with('hiddenSections', []);
                $view->with('userNotes', collect());
                $view->with('dueNotesCount', 0);
                $view->with('sysNotifs', collect());
                $view->with('unreadNotifCount', 0);
            }
        });
    }
}
