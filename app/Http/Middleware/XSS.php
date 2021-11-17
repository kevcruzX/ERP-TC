<?php

namespace App\Http\Middleware;

use App\LandingPageSection;
use App\Utility;
use Closure;

class XSS
{
    use \RachidLaasri\LaravelInstaller\Helpers\MigrationsHelper;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if(\Auth::check())
        {
            \App::setLocale(\Auth::user()->lang);

            if(\Auth::user()->type == 'super admin')
            {
                $migrations             = $this->getMigrations();
                $messengerMigration     = Utility::get_messenger_packages_migration();
                $dbMigrations           = $this->getExecutedMigrations();
                $numberOfUpdatesPending = (count($migrations) + $messengerMigration) - count($dbMigrations);

                if($numberOfUpdatesPending > 0)
                {

                    Utility::addNewData();

                    return redirect()->route('LaravelUpdater::welcome');
                }

                $landingData = LandingPageSection::all()->count();
                if($landingData == 0)
                {
                    Utility::add_landing_page_data();
                }
            }
        }

        $input = $request->all();
        array_walk_recursive(
            $input, function (&$input){
            $input = strip_tags($input);
        }
        );
        $request->merge($input);

        return $next($request);
    }
}
