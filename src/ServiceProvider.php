<?php
namespace Visualplus\Naverid;

class ServiceProvider extends \Illuminate\Support\ServiceProvider {
	
	/**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    	$socialite = $this->app->make(\Laravel\Socialite\Contracts\Factory::class);
		
		$socialite->extend(
			'naverid',
			function ($app) use ($socialite) {
				$config = config('services.naverid');
				
				return $socialite->buildProvider(NaveridProvider::class, $config);
			}
		);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}