<?php
namespace Visualplus\Naverid;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;
use GuzzleHttp\Psr7;
class NaveridProvider extends AbstractProvider implements ProviderInterface {
	/**
	 * XML -> array 형식 변환.
	 * 
	 * @param string
	 * @return array
	 */
	private function parseXML($xml) {
		$simpleXml = simplexml_load_string($xml, null, LIBXML_NOCDATA);
		$json = json_encode($simpleXml);

		return json_decode($json, true);
	}
	
	/**
     * Get the authentication URL for the provider.
     *
     * @param  string  $state
     * @return string
     */
	protected function getAuthUrl($state) {
		return $this->buildAuthUrlFromBase("https://nid.naver.com/oauth2.0/authorize", $state);
	}
	
	/**
     * Get the token URL for the provider.
     *
     * @return string
     */
	protected function getTokenUrl() {
		return 'https://nid.naver.com/oauth2.0/token';
	}
	
	/**
     * Get the access token for the given code.
     *
     * @param  string  $code
     * @return string
     */
	public function getAccessToken($code) {
		$response = $this->getHttpClient()->request('POST', $this->getTokenUrl(), [
			'headers'	 	=> ['Accept' => 'application/json'],
			'form_params'	=> $this->getTokenFields($code),
        ]);
		
        return $this->parseAccessToken($response->getBody());
	}
	
	/**
     * Get the POST fields for the token request.
     *
     * @param  string  $code
     * @return array
     */
	protected function getTokenFields($code) {
		return array_add(
            parent::getTokenFields($code), 'grant_type', 'authorization_code'
        );
	}
	
	/**
     * Get the raw user for the given access token.
     *
     * @param  string  $token
     * @return array
     */
	protected function getUserByToken($token) {
		$response = $this->getHttpClient()->request('GET', 'https://openapi.naver.com/v1/nid/getUserProfile.xml', [
			'headers' => [
				'Authorization' => 'Bearer ' . $token,
		    ],
		]);
		
		$xml = $response->getBody()->getContents();
		
		return $this->parseXML($xml)['response'];
		
	}
	
	/**
     * Map the raw user array to a Socialite User instance.
     *
     * @param  array  $user
     * @return \Laravel\Socialite\User
     */
	protected function mapUserToObject(array $user) {
		return (new User)->setRaw($user)->map([
			'email'		=> $user['email'],
			'nickname' 	=> $user['nickname'],
            'enc_id'   	=> $user['enc_id'],
            'profile_image' => $user['profile_image'],
            'age'		=> $user['age'],
            'gender'	=> $user['gender'],
            'id'		=> $user['id'],
            'name'		=> $user['name'],
            'birthday'	=> $user['birthday'],
		]);
	}
}
