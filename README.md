sightengine-php
===============

Nudity detection and moderation - PHP class to connect to the Sightengine API

About
-----

Use the Sightengine nudity API to instantly moderate adult content in user-submitted photos. 


Instructions
------

1/ Create an account on sightengine.com
2/ Enter your API_USER and API_SECRET in the "define" lines of sightengine.model.php
3/ Include the file in your PHP project
4/ Call Sightengine::checkNudity and provide a public URL or full local path to the image you want to moderate


Examples
--------

><?php
>require_once '/path/to/model/sightengine.model.php';
>
>$result = Sightengine::checkNudity("http://www.australia.com/contentimages/about-landscapes-nature.jpg");
>
>var_dump($result);

or

><?php
>require_once '/path/to/model/sightengine.model.php';
>
>$result = Sightengine::checkNudity("/full/path/to/image.jpg");
>
>var_dump($result);