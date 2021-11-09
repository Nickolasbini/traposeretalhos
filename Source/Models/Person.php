<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;
use Source\Models\Country;
use Source\Models\Language;
use Source\Models\State;
use Source\Models\City;
use Source\Models\PersonRole;
use Source\Models\PersonalPage;
use Source\Models\Company;
use Source\Models\CompanyUnit;
use DOMDocument;
use Source\Helpers\FunctionsClass;

/**
 * 
 */
class Person extends DataLayer
{
	
	function __construct()
	{
		parent::__construct('person', ['name','lastName', 'email', 'password', 'language', 'country'], 'id', false);
	}

	const NOT_VERIFIED_ACCOUNT = 1;
	const VERIFIED_ACCOUNT 	   = 2;

	// SETTERS
	public function setName($name){
		$this->name = $name;
	}
	public function setLastName($lastName){
		$this->lastName = $lastName;
	}
	public function setEmail($email){
		$this->email = $email;
	}
	public function setPassword($password){
		$this->password = $password;
	}
	public function setLanguage($language){
		$this->language = $language;
	}
	public function setCountry($country){
		$this->country = $country;
	}
	public function setDateOfBirth($dateOfBirth){
		$this->dateOfBirth = $dateOfBirth;
	}
	public function setSex($sex){
		$this->sex = $sex;
	}
	public function setAddress($address){
		$this->address = $address;
	}
	public function setAddressNumber($addressNumber){
		$this->addressNumber = $addressNumber;
	}
	public function setCity($city){
		$this->city = $city;
	}
	public function setCep($cep){
		$this->cep = $cep;
	}
	public function setCpf($cpf){
		$this->cpf = $cpf;
	}
	public function setAuthenticationToken($authenticationToken){
		$this->authenticationToken = $authenticationToken;
	}
	public function setPostsRemaining($postsRemaining){
		$this->postsRemaining = $postsRemaining;
	}
	public function setProductsRemaining($productsRemaining){
		$this->productsRemaining = $productsRemaining;
	}
	public function setStatus($status){
		$this->status = $status;
	}
	public function setPersonDescription($personDescription){
		$this->personDescription = $personDescription;
	}
	public function setPersonHabilities($personHabilities){
		$this->personHabilities = $personHabilities;
	}
	public function setHasRole($hasRole){
		$this->hasRole = $hasRole;
	}
	public function setLatitude($latitude){
		$this->latitude = $latitude;
	}
	public function setLongitude($longitude){
		$this->longitude = $longitude;
	}
	public function setState($state){
		$this->state = $state;
	}

	// GETTERS
	public function getId(){
		return $this->id;
	}
	public function getName(){
		return $this->name;
	}
	public function getLastName(){
		return $this->lastName;
	}
	public function getEmail(){
		return $this->email;
	}
	public function getPassword(){
		return $this->password;
	}
	public function getLanguage($asObject = false){
		if($asObject){
			$languageObj = (new Language())->findById($this->language);
			return $languageObj;
		}
		return $this->language;
	}
	public function getCountry($asObjetc = false){
		if($asObjetc){
			$countryObj = (new Country())->findById($this->country);
			return $countryObj;
		}
		return $this->country;
	}
	public function getDateOfBirth(){
		return $this->dateOfBirth;
	}
	public function getSex(){
		return $this->sex;
	}
	public function getAddress(){
		return $this->address;
	}
	public function getAddressNumber(){
		return $this->addressNumber;
	}
	public function getCity($asObjetc = false){
		if($asObjetc){
			$cityObj = (new City())->findById($this->city);
			return $cityObj;
		}
		return $this->city;
	}
	public function getCep(){
		return $this->cep;
	}
	public function getCpf(){
		return $this->cpf;
	}
	public function getAuthenticationToken(){
		return $this->authenticationToken;
	}
	public function getPostsRemaining(){
		return $this->postsRemaining;
	}
	public function getProductsRemaining(){
		return $this->productsRemaining;
	}
	public function getStatus(){
		return $this->status;
	}
	public function getPersonDescription(){
		return $this->personDescription;
	}
	public function getPersonHabilities(){
		return $this->personHabilities;
	}
	public function getHasRole(){
		return $this->hasRole;
	}
	public function getLatitude(){
		return $this->latitude;
	}
	public function getLongitude(){
		return $this->longitude;
	}
	public function getState($asObjetc = false){
		if($asObjetc){
			$stateObj = (new State())->findById($this->state);
			return $stateObj;
		}
		return $this->state;
	}

	public function getFullName()
	{
		return $this->getName().' '.$this->getLastName();
	}

	/**
     * Removes a Person
     * @version 1.0 - 20210406
     * @return <bool> 
     */
	public function remove()
	{
		if(is_null($this)){
			return false;
		}
		$response = $this->destroy();
		return $response ? true : false; 
	}

	/**
     * Get Person object correspondent to sent email
     * @version 1.0 - 20210406
     * @param  <string> the email to look for
     * @return <obj> of Person or <null> 
     */
	public function getByEmail($email)
	{
		$personObj = $this->find("email = :email", "email=$email")
		->limit(1)
		->fetch(true);
		return $personObj ? $personObj[0] : null;
	}

	/**
     * Get Person object correspondent to sent name
     * @version 1.0 - 20211108
     * @param  <string> the firstName - required
     * @param  <string> the lastName  - required
     * @return <obj> of Person or <null> 
     */
	public function getByName($firstName, $lastName)
	{
		$personObj = $this->find("firstName = :firstName and lastName = :lastName", "firstName=$firstName&$lastName=$lastName")
		->limit(1)
		->fetch(true);
		return $personObj ? $personObj[0] : null;
	}

	/**
     * Method which validates the informed CPF format
     * @version 1.0 - 20210406
     * @param  <string> the CPF informed
     * @return <bool> 
     */
	function validateCPF($cpf)
	{
	    $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
	    if (strlen($cpf) != 11) {
	        return false;
	    }
	    if (preg_match('/(\d)\1{10}/', $cpf)) {
	        return false;
	    }
	    for ($t = 9; $t < 11; $t++) {
	        for ($d = 0, $c = 0; $c < $t; $c++) {
	            $d += $cpf[$c] * (($t + 1) - $c);
	        }
	        $d = ((10 * $d) % 11) % 10;
	        if ($cpf[$c] != $d) {
	            return false;
	        }
	    }
	    return true;
	}

	/**
     * Tries to get data from Brazil Correios oficial website
     * @version 1.0 - 20210406
     * @param  <string> the CEP informed
     * @return @return  <array> keys 'streetName' | 'neighborhood' |'state' or <bool> on failure
     */
    public function getCEPData($cep)
    {
    	// using another web site
    	$url = 'https://viacep.com.br/ws/'.$cep.'/json/';
    	$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $answer = curl_exec($ch);
        $answerArray  =json_decode($answer, true);
        if(curl_errno($ch) || is_null($answerArray)){
        	return null;
        }
        if(!array_key_exists('cep', $answerArray))
        	return null;
        $response = [
        	'cep' 		   => $answerArray['cep'],
        	'streetName'   => $answerArray['logradouro'],
        	'neighborhood' => $answerArray['bairro'],
        	'cityName'     => $answerArray['localidade'],
        	'stateCode'    => $answerArray['uf']
        ];
		return $response;


        $baseRequestURL = "http://www.buscacep.correios.com.br/sistemas/buscacep/resultadoBuscaCepEndereco.cfm";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseRequestURL);
        curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'relaxation='.$cep.'&tipoCEP=ALL&semelhante=N');
        $answer = curl_exec($ch);
        if(curl_errno($ch))
            exit('error');
        $pageResults = new DOMDocument();
        libxml_use_internal_errors(true);
        $pageResults->loadHTML($answer);
        $form = $pageResults->getElementById('Geral');
        if(is_null($form))
            return false;
        $table = $form->nextSibling->nextSibling;
        $tds = $table->getElementsByTagName('td');
        $results = [
            'streetName'   => $tds->item(0)->nodeValue,
            'neighborhood' => $tds->item(1)->nodeValue,
            'state'        => $tds->item(2)->nodeValue
        ];
        return $results;
    }

    /**
     * Get Person object correspondent to authentificationToken
     * @version 1.0 - 20210406
     * @param  <string> the authentificationToken to look for
     * @return <obj> of Person or <null> 
     */
	public function getByAuthentificationToken($authenticationToken)
	{
		$personObj = $this->find("authentificationToken = :authenticationToken", "authenticationToken=$authenticationToken")
		->limit(1)
		->fetch(true);
		return $personObj ? $personObj[0] : null;
	}

	/**
     * Returns an array containing all data of person 
     * @version 1.0 - 20210418
     * @return <array> of person data 
     */
	public function getFullData()
	{
		$dataArray = explode(' ', $this->getLastName());
		if(is_array($dataArray)){
			$personNameAbbreviation = $dataArray[count($dataArray) - 1][0]; 
		}else{
			$personNameAbbreviation = $dataArray[0];
		}
		$response = [
			'id' 				=> $this->getId(),
			'countryData' 		=> $this->getCountry(true)->getFullData(),
			'language' 			=> $this->getLanguage(true)->getFullData(),
			'cityData' 			=> $this->getCity(true)->getFullData(),
			'name' 				=> $this->getName(),
			'lastName' 			=> $this->getLastName(),
			'fullName'			=> $this->getFullName(),
			'abbreviationName'  => $this->getName().' '.$personNameAbbreviation.'.',
			'dateOfBirth' 		=> FunctionsClass::formatDate($this->getDateOfBirth()),
			'sex' 				=> $this->getSex(),
			'sexIcon'			=> FunctionsClass::getSexIconFile($this->getSex()),
			'email' 			=> $this->getEmail(),
			'address' 			=> $this->getAddress(),
			'addressNumber' 	=> $this->getAddressNumber(),
			'city' 				=> $this->getCity(),
			'cityName' 			=> $this->getCity(true)->getName(),
			'cep' 				=> $this->getCep(),
			'postsRemaining'    => $this->getPostsRemaining(),
			'productsRemaining' => $this->getProductsRemaining(),
			'cpf' 				=> $this->getCpf(),
			'status' 		    => $this->getStatus(),
			'personDescription' => $this->getPersonDescription(),
			'personHabilities'  => $this->getPersonHabilities(),
			'hasRole'			=> $this->getHasRole(),
			'profilePhoto'		=> $this->getProfilePhoto(true),
			'allPhotos'			=> $this->getProfilePhoto()
		];
		if($this->getHasRole()){
			$response['role'] = $this->getPersonRole();
		}
		$elements = $response;
		return $response;
	}

	// Return profile photo 
	public function getProfilePhoto($onlyMainPicuture = false)
	{
		$personPhotoObj = new PersonPhoto();
		if($onlyMainPicuture){
			$personPhoto = $personPhotoObj->getByPerson($this->getId(), true);
			if(!$personPhoto)
				return null;
			return $personPhoto->getDocument(true)->getPhotoWebPath();
		}else{
			$personPhotos = $personPhotoObj->getByPerson($this->getId());
			if(!$personPhotos)
				return null;
			$imagesArray = [];
			foreach($personPhotos as $photo){
				$imagesArray[] = $photo->getDocument(true)->getPhotoWebPath();
			}
			return $imagesArray;
		}
	}

	// returns the person correspondent role full data
	public function getPersonRole()
	{
		if(!$this->getHasRole())
			return null;
		$personRoleObj = new PersonRole();
		$personRole = $personRoleObj->getPersonRoleByPerson($this->getId());
		if(is_null($personRole))
			return null;
		$personScore = $personRole->getScore();
		$personScoreStars = $personRole->getScore(true);
		$roleData = $personRole->getRole(true)->getFullData();
		$personalPageObj = $personRole->getPersonalPage(true);
		$responseData = [
			'personScore'        => $personScore,
			'personScoreStars'   => $personScoreStars,
			'roleId'		     => $roleData['id'],
			'roleName'		     => $roleData['roleName'],
			'roleDescription'    => $roleData['description'],
			'roleIconURL'	     => $roleData['iconURL'],
			'roleDateOfCreation' => $roleData['dateOfCreation'],
			'personalPageURL'	 => $personalPageObj->getPageURL().$personalPageObj->getId(),
			'backgroundPhoto'    => $personalPageObj->getBackgroundPhoto(true)->getFullData(),
			'personalPageId'     => $personalPageObj->getId()
		];
		return $responseData;
	}

	public function getByCityAndRole($cityId = null, $hasRole = true)
	{
		$hasRole = $hasRole ? 'is not null' : 'is null';
		$personObjArray = $this->find("city = :cityId AND hasRole $hasRole", "cityId=$cityId")->fetch(true);
		return $personObjArray;
	}

	public function getByStateAndRole($stateId = null, $hasRole = true)
	{
		$hasRole = $hasRole ? 'is not null' : 'is null';
		$personObjArray = $this->find("state = :stateId AND hasRole $hasRole", "stateId=$stateId")->fetch(true);
		return $personObjArray;
	}

	/* parses personObjects by role
	*  @param <array> the person objects array to gather role and data of professional
	*  @param <array> wiht the id(s) of role to be gathered 
	// @return <array> with key as current roles in the system and with the latitude and longitude of found person  
	*/
	public function parseAsEachRoleWithCoordinates($personObjArray = [], $onlyThisRoles = [])
	{
		$rolesOfPeople = [];
		if(file_exists('Source/Files/roles.txt')){
			$roles = json_decode(file_get_contents('Source/Files/roles.txt'), true);
			foreach($roles as $role){
				$rolesOfPeople[$role['id']] = [];
			}
		}
		$personRoleObj = new PersonRole();
		$companyUnitObj = new CompanyUnit();
		$companiesInserted = [];
		foreach($personObjArray as $person){
			$personRole = $personRoleObj->getPersonRoleByPerson($person->getId());
			if(is_null($personRole) || (!empty($onlyThisRoles) && !in_array($personRole->getRole(), $onlyThisRoles)) )
				continue;
			$elements = [
				'latitude'  => $person->getLatitude(),
				'longitude' => $person->getLongitude(),
				'url'		=> PERSONALPAGE::BASE_URL.base64_encode($person->getId())
			];
			$personalPageObj = $personRole->getPersonalPage(true);
			if(is_null($personalPageObj))
				continue;
			if(is_null($personalPageObj->getCompanyUnit())){
				// Is a service offer
				$elements['title'] = $person->getFullName();
				$elements['description'] = $person->getPersonDescription();
				$elements['logo'] = $person->getProfilePhoto(true);
				$elements['score'] = $personRole->getScore();
				$elements['role'] = $personRole->getRole();
				$elements['isCompany'] = false;
			}else{
				// Is a company
				$companyUnitId = $personalPageObj->getCompanyUnit();
				if(!in_array($companyUnitId, $companiesInserted)){
					$companiesInserted[] = $companyUnitId;
					// $elements['title'] = $comp
					// Gather data of the company,and flag it with something for the front to see 
					$responseOfCompany = $companyUnitObj->gatherCompaniesData($companyUnitId);
					$elements['isCompany'] = true;
					$elements = array_merge($responseData, $elements);
				}else{
					continue;
				}
			}
			$rolesOfPeople[$person->getHasRole()][] = $elements;
		}
		return $rolesOfPeople;
	}

	public function getByAuthenticationToken($authenticationToken)
	{
		$personObjArray = $this->find("authenticationToken = :authenticationToken", "authenticationToken=$authenticationToken")->fetch(true);
		return $personObjArray;
	}
}