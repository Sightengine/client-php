<?php 
require __DIR__ . '/vendor/autoload.php';

class Check {
    private $api_user;
    private $api_secret;
    private $endpoint = 'https://api.sightengine.com/';
    private $http;
    private $models;

    function __construct($api_user, $api_secret, $models) {
      $this->api_user = $api_user;
      $this->api_secret = $api_secret;
      $this->http = new \GuzzleHttp\Client(['base_uri' => $this->endpoint]);
      $this->models = implode(",", $models);
    }

    public function image($image) {
        $url = '1.0/check.json';

        if (filter_var($image, FILTER_VALIDATE_URL)) { 
            $r = $this->http->request('GET', $url, ['query' => ['api_user' => $this->api_user, 
                'api_secret' => $this->api_secret, 
                'models' => $this->models,
                'url' => $image]]);

            return json_decode($r->getBody());
        } else {
            $file = fopen($image, 'r');
            $r = $this->http->request('POST', $url, ['query' => ['api_user' => $this->api_user, 'api_secret' => $this->api_secret, 'models' => $this->models],'multipart' => [['name' => 'media','contents' => $file]]]); 

            return json_decode($r->getBody());
        }
    }

    public function video($videoUrl, $callbackUrl) {
        $url = '1.0/video/moderation.json';
        $r = $this->http->request('GET', $url, ['query' => ['api_user' => $this->api_user, 'api_secret' => $this->api_secret, 'stream_url' => $videoUrl,'callback_url' => $callbackUrl]]);

        return json_decode($r->getBody());
    }
} 

?> 