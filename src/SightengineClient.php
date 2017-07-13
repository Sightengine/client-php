<?php
namespace Sightengine;

class SightengineClient  { 
	private $api_user;
	private $api_secret;
	private $endpoint = 'https://api.sightengine.com/';
    private $http;

    function __construct($api_user, $api_secret) {
      $this->api_user = $api_user;
      $this->api_secret = $api_secret;
      $this->http = new \GuzzleHttp\Client(['base_uri' => $this->endpoint, 'User-Agent' => 'SE-SDK-PHP' . '1.0']);
    }

    public function feedback($model, $modelClass, $image) {
        $url = '1.0/feedback.json';

    	if (filter_var($image, FILTER_VALIDATE_URL)) { 
    		$r = $this->http->request('GET', $url, ['query' => ['api_user' => $this->api_user, 'api_secret' => $this->api_secret, 'model' => $model,'class' => $modelClass,'url' => $image]]);

    		return json_decode($r->getBody());
		  } 
      else {
			   $file = fopen($image, 'r');
         $r = $this->http->request('POST', $url, ['query' => ['api_user' => $this->api_user, 'api_secret' => $this->api_secret, 'model' => $model,'class' => $modelClass],'multipart' => [['name' => 'media','contents' => $file]]]); 

        return json_decode($r->getBody());
		  }
    }

    public function check($models) {
      return new Check($this->api_user, $this->api_secret, $models);
    }
}