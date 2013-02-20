<?php
/*

Stan Scates
blr | further

stan@sc8s.com
blrfurther.com

Basic OAuth and caching layer for Seaofclouds' tweet.js, designed
to introduce compatibility with Twitter's v1.1 API.

Version: 1.0
Created: 2013.02.20

Utilizes:
https://github.com/seaofclouds/tweet
https://github.com/themattharris/tmhOAuth

*/

class ezTweet {
	/*************************************** config ***************************************/

	// Your Twitter App Consumer Key
	private $consumer_key    = 'YOUR_CONSUMER_KEY';

	// Your Twitter App Consumer Secret
	private $consumer_secret = 'YOUR_CONSUMER_SECRET';

	// Your Twitter App Access Token
	private $user_token      = 'YOUR_APP_ACCESS_TOKEN';

	// Your Twitter App Access Token Secret
	private $user_secret     = 'YOUR_ACCESS_TOKEN_SECRET';


	// Enable caching?
	private $cache_enabled   = true;

	// Cache interval (in minutes)
	private $cache_interval  = 15;

	// Full path to cache directory (must be writable)
	private $cache_dir       = './';

	// Enable debug messages in JS console? (only enabled for development)
	private $debug           = false;

	/**************************************************************************************/




	private $message = '';

	public function __construct() {
		if(!$_GET['url'] || !$_GET['screen_name']) {
			die("<h1>URL and Screen Name are required.</h1>");
		}
	}

	public function fetch() {
		$response = $this->getJSON();
		$JSON = json_encode(array('message' => ($this->debug) ? $this->message : false, 'response' => json_decode($response, true)));

		echo $JSON;
	}

	private function getJSON() {
 		if($this->cache_enabled === true) {
			$CFID = $this->generateCFID();
			$cache_file = $this->cache_dir.$CFID;
			if(file_exists($cache_file) && (filemtime($cache_file) > (time() - 60 * intval($this->cache_interval)))) {
				return file_get_contents($cache_file, FILE_USE_INCLUDE_PATH);
			} else {
				$JSONraw = $this->getTwitterJSON();
				$JSON = $JSONraw['response'];
				if(is_writable($this->cache_dir)) {
					if(file_put_contents($cache_file, $JSON, LOCK_EX) === false) {
						$this->message .= "Error writing cache file\n";
					}
				} else {
					$this->message .= "Cache directory is not writable\n";
				}
				return $JSON;
			}
		} else {
			$JSONraw = $this->getTwitterJSON();
			return $JSONraw['response'];
		}
	}

	private function getTwitterJSON() {
		require './tmhOAuth.php';
		require './tmhUtilities.php';

		$tmhOAuth = new tmhOAuth(array(
		    'consumer_key'    		=> $this->consumer_key,
		    'consumer_secret' 		=> $this->consumer_secret,
		    'user_token'      		=> $this->user_token,
		    'user_secret'     		=> $this->user_secret,
			'curl_ssl_verifypeer'   => false
		));

		$tmhOAuth->request('GET', $tmhOAuth->url(urldecode($_GET['url'])), array('screen_name' => $_GET['screen_name'], 'count' => $_GET['count']));

		$response = $tmhOAuth->response;
		return $response;
	}

	private function generateCFID() {
		// The cached filename ID is comprised of the screen name + the md5 of the url.
		return $_GET['screen_name'].'-'.md5($_GET['url']).'.json';
	}
}

$ezTweet = new ezTweet;
$ezTweet->fetch();

?>
