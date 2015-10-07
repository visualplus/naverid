<?php
namespace Visualplus\Naverid;

use SocialiteProviders\Manager\SocialiteWasCalled;

class NaveridExtendSocialite {
	public function handle(SocialiteWasCalled $socialiteWasCalled) {
		$socialiteWasCalled->extendSocialite('naverid', \Visualplus\Naverid\ServiceProvider::class);
	}
}