<?php

namespace Source\Controllers;


/**
 * 
 */
class APIController
{
	// fetches data from LOCATION IQ API
	public function getDataFromLocationIQ()
	{
		$address = isset($_POST['addressToSearch']) ? $_POST['addressToSearch'] : null;
		if(!$address){
			return json_encode([
				'success' => false,
				'message' => ucfirst(translate('no address sent'))
			]);
		}
		$_SESSION['usedLocationIQAPI'] = true;
		$ch = curl_init();
		$urlEncodedAddress = urlencode($address);
		$url = 'https://eu1.locationiq.com/v1/search.php?key=pk.b01ea7ce0a6525f9512874eec8216720&q='.$urlEncodedAddress.'&format=json';
		curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $answer = curl_exec($ch);
        if(curl_errno($ch)){
            return json_encode([
            	'success' => false,
            	'message' => 'error when searching for your address, try again later'
            ]);
        }
        $answerArray = json_decode($answer, true);
        if(count($answerArray) == 0){
        	return json_encode([
        		'success' => false,
        		'message' => ucfirst(translate('no location found'))
        	]);
        }
        $possibleLocations = [];
        foreach($answerArray as $location){
        	if(!array_key_exists('lat', $location) || !array_key_exists('lon', $location)){
        		continue;
        	}
        	$possibleLocations[] = [
        		'latitude'  => $location['lat'],
        		'longitude' => $location['lon']
        	];
        }
        return json_encode([
        	'success' => true,
        	'message' => ucfirst(translate('location found')),
        	'content' => $possibleLocations,
        	'numberOfLocations' => count($possibleLocations)
        ]);
	}	
}