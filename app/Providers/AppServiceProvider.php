<?php

/* 
* Author: Muhammad Bagus Harianto (GitHub: github.com/bronze21)
* Project: https://github.com/bronze21/helpdesk_ticket
*/

namespace App\Providers;

use App\Features\RoleCheck;
use App\Models\Ticket;
use App\Models\TicketsAgent;
use App\Models\TicketsComment;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Laravel\Pennant\Feature;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Pennant\Middleware\EnsureFeaturesAreActive;

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
        Feature::discover();
        Ticket::observe(new \App\Observers\TicketObserver());
        TicketsAgent::observe(new \App\Observers\TicketsAgentObserver());
        TicketsComment::observe(new \App\Observers\TicketsCommentObserver());
        EnsureFeaturesAreActive::whenInactive(
            function (Request $request, array $features) {
                abort(404);
                // return redirect()->route('home')->withErrors('You do not have access to this feature.');
            }
        );
        Carbon::setLocale('id');
    }
}
