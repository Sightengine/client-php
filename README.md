Sightengine-php
===============

Nudity detection and moderation - PHP class to connect to the Sightengine API

About
-----

Use the Sightengine nudity API to instantly moderate adult content in user-submitted photos. See http://sightengine.com for more information.


Instructions
------

1. Create an account on http://sightengine.com, you will get your own API_USER and API_SECRET values
2. Enter your API_USER and API_SECRET in the "define" lines of sightengine.model.php
3. Include the sightengine.model.php in your PHP project
4. Call Sightengine::checkNudity and provide as parameter a public URL or full local path to the image you want to moderate


Examples
--------

Moderate an image accessible through a public URL:

	<?php
	require_once '/path/to/model/sightengine.model.php';
	
	$result = Sightengine::checkNudity("http://www.australia.com/contentimages/about-landscapes-nature.jpg");
	
	var_dump($result);


Moderate a local image

	<?php
	require_once '/path/to/model/sightengine.model.php';
	
	$result = Sightengine::checkNudity("/full/path/to/image.jpg");
	
	var_dump($result);