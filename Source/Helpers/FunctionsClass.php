<?php

namespace Source\Helpers;

use CoffeeCode\DataLayer\DataLayer;
use Source\Models\Person;
use Source\Models\Country;
use Source\Models\Language;
use Source\Models\City;
use Source\Models\Role;
use Source\Models\PersonRole;
use Datetime;

/**
 * 
 */
class FunctionsClass extends DataLayer
{
	// Document all this methods

	// Return true or false
	public static function putContents($fileName, $data)
	{
		$position = strpos($fileName, '.');
		$extension = substr($fileName, $position);
		$validExtensions = ['.txt'];
		if(!in_array($extension, $validExtensions))
			return false;
		$result = file_put_contents(TMPPATH['tmp'].$fileName, json_encode($data));
		return !$result ? false : true;
	}

	// Document me
	public static function getContents($fileName)
	{
		$result = file_get_contents(TMPPATH['tmp'].$fileName);
		return $result ? json_decode($result, true) : false;
	}

	// THis method isnt finished yet and does not work properly
	// Write the code to the 'tmp/codes' file
	public function writeToTmp($fileName = '', $value, $keyName = null)
	{	
		$currentDate = date("Y-m-d h:i:sa");
		$path = TMPPATH['tmp'].$fileName;
		$keyName  = is_null($keyName) ? 'value' : $keyName;
		if(file_exists($path)){
			$file = FunctionsClass::getContents($fileName);
			$file = !empty($file) ? $file : [];
			$file[$keyName] = [
				'date' => $currentDate,
				$keyName   => $value
			];
		}else{
			$file[$keyName] = [
				'date' => $currentDate,
				$keyName   => $value
			];
		}
		return FunctionsClass::putContents($fileName, $file);
	}

	public static function removeFromTmp($fileName, $keyName)
	{
		$file = FunctionsClass::getContents($fileName);
		if(!array_key_exists($keyName, $file))
			return true;
		unset($file[$keyName]);
		return FunctionsClass::putContents($fileName, $file);	
	}

	public static function writeToCode($fileName = '', $code, $keyName)
	{
		$currentDate = date("Y-m-d h:i:sa");
		$file = FunctionsClass::getContents($fileName);
		$file = !empty($file) ? $file : [];
		$file[$keyName] = [
			'date' => $currentDate,
			'code' => $code
		];
		return FunctionsClass::putContents($fileName, $file);	
	}

	public static function removeFromCode($keyName)
	{
		$file = FunctionsClass::getContents('codes.txt');
		if(!array_key_exists($keyName, $file))
			return true;
		unset($file[$keyName]);
		return FunctionsClass::putContents('codes.txt', $file);	
	}

	public static function cleanCodesCache()
	{
		$currentCodes = FunctionsClass::getContents('codes.txt');
		$codesRemoved = 0;
		foreach($currentCodes as $codeEmail => $values){
			$codeTime = strtotime($values['date']);
			if(time() - $codeTime > 30 * 60){
				FunctionsClass::removeFromCode($codeEmail);
				$codesRemoved++;
			}
		}
		return $codesRemoved;
	}

	public static function generateRandomValue()
	{
		return md5(uniqid(rand(), true));
	}

	public static function generateHashValue($value)
	{
		return hash('haval256,4', $value);
	}

	public static function startSession()
	{
		session_start();
		if(!isset($_SESSION)){
			// sadly it works differet, each request is a new session always
		}
	}

	public static function setPersonSession($personObj, $doNotSave = [])
	{
		$_SESSION['personId']       = $personObj->getId();
		$_SESSION['personName']     = $personObj->getName();
		$_SESSION['personCountry']  = $personObj->getCountry();
		$_SESSION['personCity']     = $personObj->getCity();
		$_SESSION['personPicURL']   = $personObj->getProfilePhoto(true);
		$_SESSION['userLanguage']   = $personObj->getLanguage(true)->getIsoCode();
		$personRole = $personObj->getPersonRole();
		$_SESSION['userRole']   	= !is_null($personRole) ? $personRole['roleId'] : null;
		foreach($doNotSave as $keyName){
			unset($_SESSION[$keyName]);
		}
		return true;
	}

	public static function verifyCookies()
	{
		if(empty($_COOKIE['authenticationToken']))
			return false;
		$token = $_COOKIE['authenticationToken'];
		$personObj = new Person();
		$person = $personObj->getByAuthentificationToken($token);
		if(!$person)
			return false;
		return FunctionsClass::setPersonSession($person->getId(), $person->getName());
	}

	public static function setPersonCookie()
	{
		$authenticationToken = FunctionsClass::generateHashValue(FunctionsClass::generateRandomValue());
		// set cookie to one day
		setcookie('authenticationToken', $authenticationToken, time() + (86400 * 30), '/');
		return $authenticationToken;
	}

	public static function parseObjArrayToArray($arrayData = [], $arrayName = 'data')
	{
		if(empty($arrayData))
			return [$arrayName => []];
		$getMethod = 'get'.ucfirst($arrayName);
		foreach($arrayData as $data){
			$response[$arrayName][] = $data->{$getMethod}();
		}
		return $response;
	}

	public static function formatDate($date)
	{
		if(is_null($date))
			return null;
		$userCountry = isset($_SESSION['userCountry']) ? $_SESSION['userCountry'] : null;
		// using this for now
		$userCountry = isset($_SESSION['userLanguage']) ? strtolower($_SESSION['userLanguage']) : null;
		$dateFormat = !empty(COUNTRIESFORMATS[$userCountry]) ? COUNTRIESFORMATS[$userCountry] : 'Y-m-d';
		$formatedDate = new Datetime($date);
		return $formatedDate->format($dateFormat);
	}

	public function setTimeZone()
	{
		date_default_timezone_set('America/Sao_Paulo');
	}

	public function isPersonLoggedIn()
	{
		if(isset($_SESSION['personId']))
			return true;
		return false;
	}

	// sets the user language based by its IP location
	public function getCountryAndCityByIp()
	{
		// returns if already has this required data;
		$sessionRequired = ['userLanguage', 'userCoordinates', 'userCity'];
		foreach($sessionRequired as $sessionName){
			if(isset($_SESSION[$sessionName]))
				return;
		}
		$client  = @$_SERVER['HTTP_CLIENT_IP'];
	    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	    $remote  = @$_SERVER['REMOTE_ADDR'];
	    $result  = array('country'=>'', 'city'=>'');
	    if(filter_var($client, FILTER_VALIDATE_IP)){
	        $ip = $client;
	    }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
	        $ip = $forward;
	    }else{
	        $ip = $remote;
	    }
	    $ipData = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));    
	    if($ipData && $ipData->geoplugin_countryName != null){
	        $result['country']  = $ipData->geoplugin_countryCode;
	        $result['city'] 	= $ipData->geoplugin_city;
	        $result['timeZone'] = $ipData->geoplugin_timezone;
	    }
	    $language = 'pt';
	    $countryISO = 'BR';
	    // set the user language
	    if(!empty($result['country'])){
	    	$countryISO = $result['country'];
	    	$countryObj = new Country();
	    	$country = $countryObj->getCountryByAlphaCode($countryISO);
	    	$languages = $country->getCountryLanguages(['id', 'language']);
	    	$languageObj = new Language();
	    	if(count($languages) > 1 && $countryISO == 'BR')
	    		$languages[0] = 15;
	    	$language = $languageObj->findById($languages[0]);
	    	$language = !is_null($language) ? strtolower($language->getIsoCode())
	    								    : 'pt';
		}
		// set the user location and city
		$coordinates = DEFAULTOPTIONS['mapLocation'];
		$cityName = null;
		if(!empty($result['city'])){
			$cityName = mb_strtolower($result['city']);
			$cityObj = new City();
			$city = $cityObj->getCityByName($cityName);
			$coordinates = !is_null($city) ? $city->getCoordinates()
										   : DEFAULTOPTIONS['mapLocation'];
		}
		// set the time zone
		if(!empty($result['timeZone'])){
	        date_default_timezone_set($result['timeZone']);
	    }else{
	    	date_default_timezone_set(DEFAULTOPTIONS['timeZone']);
	    }
	    // set Map language
		if($countryISO == 'BR')
			$mapLanguage = 'pt-BR';
		if($countryISO == 'PT')
			$mapLanguage == 'pt-PT';
		// setting $_SESSION variables
	    $_SESSION['userLanguage'] 	 = $language;
	    $_SESSION['userCoordinates'] = $coordinates;
	    $coordinatesArray = explode(' ', $coordinates);
	    $_SESSION['userLatitude'] 	 = $coordinatesArray[0];
	    $_SESSION['userLongitude'] 	 = $coordinatesArray[1];
	    if(!is_null($cityName))
	    	$_SESSION['userCity'] 	 = $cityName;
	    $_SESSION['userCountry']     = $countryISO;
	    $_SESSION['mapLanguage']     = $mapLanguage;
	    return true;
	}

	public static function getRolesToSession()
	{
		if(isset($_SESSION['roles']))
			return;
		if(file_exists(TMPPATH['files'].'roles.txt') && !isset($_SESSION['roles'])){
			$_SESSION['roles'] = file_get_contents(TMPPATH['files'].'roles.txt');			
			return;
		}
		$roleObj = new Role();
		$personRoleObj = new PersonRole();
		$roles = $roleObj->getAllRoles();
		$elements = [];
	    if(count($roles) > 0){
	       foreach($roles as $role){
	       		$totalOfProfessionals = $personRoleObj->getTotal($role->getId());
	            $elements[] = [
	                'id'             => $role->getId(),
	                'roleName'       => $role->getRoleName(),
	                'description'    => $role->getDescription(),
	                'iconUrl'        => $role->getIconUrl(),
	                'dateOfCreation' => FunctionsClass::formatDate($role->getDateOfCreation()),
	                'total'			 => $totalOfProfessionals,
	                'colorOnMap'     => $role->getColorOnMap(),
	                'isUsedOnMap'	 => $role->getIsUsedOnMap()
	            ];
	        }
	    }
	    $jsonRoles = json_encode($elements);
	    file_put_contents(TMPPATH['files'].'roles.txt', $jsonRoles);
	}

	// get sex icon .svg accordinally to sent person sex
	public static function getSexIconFile($personSex = null)
	{
		$sexIcon = '';
		switch($personSex){
			case 'M':
				$sexIcon = 'male.svg';
				break;
			case 'F':
				$sexIcon = 'female.svg';
				break;
			default:
				$sexIcon = 'undefined.svg';
				break;
		}
		return $sexIcon;
	}

	// gather from API related data accordinally to latitude and longitude
	// updates user session variables such as: country, city and state name.
	public static function updateSessionUserLocation($latitude, $longitude)
	{
		if(is_null($latitude) || is_null($longitude))
			return false;
		$url = 'https://api.bigdatacloud.net/data/reverse-geocode-client?latitude='.$latitude.'&longitude='.$longitude.'&localityLanguage='.$_SESSION['userLanguage'];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$answer = curl_exec($ch);
		if(curl_errno($ch))
		    return null;
		$answerArray = json_decode($answer, true);
		if(is_null($answerArray))
			return null;
		// sets user Country
		if(array_key_exists('countryCode', $answerArray))
			$_SESSION['userCountry'] = $answerArray['countryCode'];
		// sets user City
		if(array_key_exists('city', $answerArray))
			$_SESSION['userCity'] = mb_strtolower($answerArray['city']);
		// set user State
		if(array_key_exists('principalSubdivision', $answerArray))
			$_SESSION['userState'] = mb_strtolower($answerArray['principalSubdivision']);
		return true;
	}

	// try to fetch user city, country and state by the cookies location, if they exist
	public function setCityByCookies()
	{
		if(isset($_COOKIE['latitude']) && isset($_COOKIE['longitude'])){
			// gathering from better resources
			FunctionsClass::updateSessionUserLocation($_COOKIE['latitude'], $_COOKIE['longitude']);
			return true;
		}else if(isset($_SESSION['userCity']) && !isset($_SESSION['userState']) ){
			// getting by the IP address which is the last resource
			// Since it already has the required data on the $_SESSION, try to get the city and set the state by it 
			$cityName = $_SESSION['userCity'];
			$cityObj = new City();
			$city = $cityObj->getCityByName($_SESSION['userCity']);
			if($city){
				$_SESSION['userState'] = $city->getState(true)->getName();	
			}
			return true;
		}
		return null;
	}

	
    /**
     * convert date string to dateTime object
     * Obs: enter a date string in ISO or Y-m-d format
     * @param	<string> $dateString
     * @return DateTime|null
     */
    public static function convertDateStringToDateTimeObject($dateString)
    {
        if(is_null($dateString)){
            return null;
        }
        
        try{
            $dateTime = new DateTime($dateString);
            return $dateTime;
        }catch(Exception $e){
            return null;
        }
    }

    /**
     * convert dateTime object to dateIso
     * @param	<DateTime> $dateTime
     * @return string|null
     */
    public static function convertDateTimeObjectToDateIso($dateTime)
    {
        if($dateTime instanceof DateTime){
            return $dateTime->format(DateTime::ISO8601);
        }else{
            return null;
        }
    }

    /**
     * perform a curl request
     * @param  <array> of data such as: url, arrayValues, getMethod and aysncRequest
     * @return string|null
     */
    public static function httpPost($parameters)
    {
        $url 	      = array_key_exists('url', $parameters) ? $parameters['url'] : null;
        $getRequest   = array_key_exists('getRequest', $parameters) ? true : null;
        $arrayValues  = array_key_exists('arrayValues', $parameters) ? $parameters['arrayValues'] : null;
        $aysncRequest = array_key_exists('aysncRequest', $parameters) ? true : null;
        if(!$url){
        	return false;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if(!$getRequest){
        	curl_setopt($ch, CURLOPT_POST, 1);
		}
		if(!$getRequest && $arrayValues){
			curl_setopt($ch, CURLOPT_POSTFIELDS, $arrayValues);
		}else if($arrayValues){
			$url .= '?';
			foreach($arrayValues as $key => $val){
				$url .= $key . '=' . $val . '&';
			}
			unset($url[strlen($url) - 1]);
		}
		$result = curl_exec($ch);
		return $response;
    }

    public static function getBasePath()
    {
    	return $_SERVER['HTTP_HOST'].'/'.URL['urlDomain'].'/';
    }
}
