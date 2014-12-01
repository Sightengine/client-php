<?php

/*
 * Library to detect nudity in photos by leveraging the Sightengine API
 *
 * To use the service, please follow these steps:
 *  1/ Create an account on sightengine.com
 *  2/ Enter your API_USER and API_SECRET in the "define" lines below
 *  3/ Include this file in your PHP project
 *  4/ Call Sightengine::checkNudity and provide a public URL or full local path to the image you want to moderate
 *  5/ Sightengine::checkNudity will return an array containing the result and the confidence
 *
 * For more information go to http://sightengine.com/docs
 * The following library is provided under the MIT license.
 * Version 1.0, July 2013
 *
 */



// Update the following two lines. Provide the api user code and api secret you get once your sightengine account has been created
define('API_USER', 'XXXXX');
define('API_SECRET', 'XXXXX');



/*
 * You should not need to modify/customize anything below this line
 */


define('API_ENDPOINT', 'http://api.sightengine.com');
define('API_VERSION', '1.0');


class Sightengine{

        //
        // Call this function to check if an image contains nudity
        //
        // INPUT (string): public URL or full local path to the image you want to moderate
        // OUTPUT (array):
        //      'nudity' => (BOOL, true if contains nudity, false otherwise)
        //      'confidence' => (INT, confidence of the result. The return value will be in the range [0-100]. The higher the value the higher the confidence in the result)
        // see http://sightengine.com/docs for more information on the value returned
        //
        public static function checkNudity($file){
                if(filter_var($file, FILTER_VALIDATE_URL)) {
                        // if $file is a valid URL, then perform a GET request and provide the URL
                        $result = static::getAPI($file);
                }
                else {
                        // if $file is not a valid URL, then we assume it is a local file
                        // check if file exists and is a valid image
                        $info = getimagesize($file);
                        if($info===false) {
                                /* local file does not exist or is not an image */
                                throw new Exception('File does not exist or is not an image');
                        }

                        // post local image to the API
                        $result = static::postAPI($file);
                }

                $data = json_decode($result, true);

                if($data['status']!="success"){
                        /* the API has returned an error, the error code is available in $data['error_code']
                         * see http://sightengine.com/docs for more information
                         */
                        throw new Exception($data['error_message']);
                }

                return  array(  'nudity'=>$data['nudity']['result'],
                                'confidence'=>$data['nudity']['confidence']
                        );
        }


        //
        // all functions below this line are private
        // you should not need to call them
        //

        private static function getAPI($file){
                $get = array(   'api_user'=>API_USER,
                                'api_secret'=>API_SECRET,
                                'url'=>$file
                        );

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, API_ENDPOINT.'/'.API_VERSION.'/nudity.json?'. http_build_query($get));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                return static::doCURL($ch);
        }

        // INPUT: string containing full path to photo to be moderated
        private static function postAPI($file){
                $post = array(  'api_user'=>API_USER,
                                'api_secret'=>API_SECRET,
                                'photo'=>'@'.$file
                        );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, API_ENDPOINT.'/'.API_VERSION.'/nudity.json');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                return static::doCURL($ch);
        }

        private static function doCURL($ch){
                $result = curl_exec($ch);
                $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if($result===false){
                        throw new Exception('Could not connect to the server, got CURL error '.curl_error($ch));
                }
                else if($http_status>=410 or $http_status==404){
                        throw new Exception('Received HTTP status code '.$http_status.' from server');
                }
                return $result;
        }
}
