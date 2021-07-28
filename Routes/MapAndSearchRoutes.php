<?php 
use Source\Models\Person;
use Source\Helpers\FunctionsClass;

$app->get('/search', function(){
	$isLogged = FunctionsClass::isPersonLoggedIn();
	$personObj = new Person();
	if($isLogged){
		$person = $personObj->findById($_SESSION['personId']);
		$location = $person->getCity(true)->getCoordinates();
		exit($person->getCity(true)->getName());
		exit($location);

		$ip = $_SERVER['REMOTE_ADDR'];
		exit($ip);
		$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
		var_dump($details);
		exit;

		$address = 'archelau+de+almeida+torres';

		$baseURL = 'https://nominatim.openstreetmap.org/search?q=135+pilkington+avenue,+birmingham&format=json&polygon=1&addressdetails=1';

		/*$baseURL = 'https://nominatim.openstreetmap.org/search?q=596+archelau+de+almeida+torres,++,&format=json&polygon=1&addressdetails=1';
		*/
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseURL);
        curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $answer = curl_exec($ch);
        if(curl_errno($ch))
            exit('error');
        exit($answer);
        exit(json_decode($answer));
	}

	// verify if by IP I can track the city of the person and get it by the city



	// All two will have the possibility to search by address, city or state


});

