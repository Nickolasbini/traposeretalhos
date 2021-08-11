<?php

namespace Source\Controllers;

use Source\Helpers\FunctionsClass;
use Source\Models\Language;
use Source\Models\Country;
use Source\Models\Person;
use Source\Models\City;
use Source\Models\PersonRecoveryData;
use Source\Support\Mail;
use Datetime;

/**
 * 
 */
class PersonController
{
	/**
     * Creates a new person or update one, this operation updates or creates
     * a PersonRecoveryData. It also sends confirmation email on creation
     * @version 1.0 - 20210406
     * @param  <array> keys: 'id', 'name', 'lastName', 'email, 'password', 'language', 'country' - 
     * 				          required (any other attribute of Person may be sent)
     * @return <array> keys <bool>   'success'
     *					    <string> 'message'
     */
	public function save($savingParameters)
	{
		if(is_null($savingParameters) || !is_array($savingParameters)){
			return json_encode([
				'success' => false,
				'message' => ucfirst(translate('invalid parameters'))
			]);
		}
		$isUpdate = false;
		$personObj = new Person();
		$personObj->setStatus(PERSON::NOT_VERIFIED_ACCOUNT);
		if(array_key_exists('id', $savingParameters)){
			$personObj = $personObj->findById($savingParameters['id']);
			if(!$personObj){
				return json_encode([
					'success' => false,
					'message' => ucfirst(translate('invalid person id'))
				]);
			}
			unset($savingParameters['id']);
			$personObj->setStatus(PERSON::VERIFIED_ACCOUNT);
			$isUpdate = true;
		}
		// Verify if email is in use
		$isInUse = $personObj->getByEmail($savingParameters['email']);
		if($isInUse && !$isUpdate){
			return json_encode([
				'success' => false,
				'message' => ucfirst(translate('email is already in use'))
			]);
		}
		foreach($savingParameters as $parameterName => $value){
			$setMethod = 'set'.ucfirst($parameterName);
			if($parameterName == 'country'){
				$countryObj = new Country();
				$countryObj = $countryObj->findById($value);
				if(is_null($countryObj)){
					return json_encode([
						'success' => false,
						'message' => ucfirst(translate('invalid country id'))
					]);
				}
			}
			if($parameterName == 'language'){
				$languageObj = new Language();
				$languageObj = $languageObj->findById($value);
				if(is_null($languageObj)){
					return json_encode([
						'success' => false,
						'message' => ucfirst(translate('invalid language id'))
					]);
				}
			}
			if($parameterName == 'password'){
				$value = FunctionsClass::generateHashValue($savingParameters['password']);
			}
			if($parameterName == 'cpf'){
				$response = $this->validateCPF($value);
				if(!$response){
					return json_encode([
						'success' => false,
						'message' => ucfirst(translate('cpf is invalid'))
					]);	
				}
			}
			$personObj->{$setMethod}($value);
		}
		$result = $personObj->save();
		if(!$result){
			return json_encode([
				'success' => false,
				'message' => ucfirst(translate('saving error, try again later'))
			]);
		}

		// Gathering PersonRecoveryData information
		$personRecoveryDataWk = new PersonRecoveryData();
		$personId = $personObj->data->id;
		$result = $personRecoveryDataWk->saveRecoveryData($personId);
		if(!$result['saveResponse']){
			$personObj->remove();
			return json_encode([
				'success' => false,
				'message' => ucfirst(translate('saving error, try again later'))
			]);
		}

		if(!$isUpdate){
			// Make this email a template or something like it
			$newCode = FunctionsClass::generateRandomValue();
			// send email
			$mail = new Mail();
			$sendTo = [$savingParameters['email']];
			/*$attachment[] = [
				'Logo' => 'logo-CosU-top.jpg'
			];*/
			$title   = ucfirst(translate('your authentification token'));
			$message = ucfirst(translate('<a href="'.$_SERVER['REQUEST_URI'].'/accountconfirmation/'.$savingParameters['email'].'with'.$newCode.'">this is your code')).' '.$newCode.'</a>'; 
			$mail->sendMail($sendTo, $title, $message);
			FunctionsClass::writeToCode('codes.txt', $newCode, $savingParameters['email']);
		}

		$message = $isUpdate ? ucfirst(translate('updated with success')) 
		                     : ucfirst(translate('an email has been sent to')).':'.$savingParameters['email'];
		if(array_key_exists('differentIp', $result)){
			$personRecoveryDataWk->sendDifferentIpMail($savingParameters['email']);
		}
		return json_encode([
			'success'   => true,
			'message'   => $message
		]);
	}

	/**
     * Sets Person to verified account by checks and saved tmp codes
     * @version 1.0 - 20210406
     * @param  <string> the email owner of account
     * @param  <string> the code received on this email
     * @return <array> keys <bool>   'success'
     *					    <string> 'message'
     */
	public function verifyAccountEmail($email = null, $code = null)
	{
		if(is_null($email) || is_null($code)){
			return json_encode([
				'success' => false,
				'message' => ucfirst(translate('invalid email or code'))
			]);	
		}
		$codesFile = $this->getContents('codes.txt');
		if(!array_key_exists($email, $codesFile)){
			return json_encode([
				'success' => false,
				'message' => ucfirst(translate('email is invalid'))
			]);
		}
		if($codesFile[$email]['code'] != $code){
			return json_encode([
				'success' => false,
				'message' => ucfirst(translate('code is invalid'))
			]);
		} 
		$dbtimestamp = strtotime($codesFile[$email]['date']);
		if (time() - $dbtimestamp > 15 * 60) {
		    return json_encode([
				'success' => false,
				'message' => ucfirst(translate('code is invalid and outdated'))
			]);
		}
		$personObj = new Person();
		$person = $personObj->getByEmail($email);
		$person->setStatus(PERSON::VERIFIED_ACCOUNT);
		$result = $person->save();
		if(!$result){
			return json_encode([
				'success' => false,
				'message' => ucfirst(translate('saving error, try again later'))
			]);
		}
		FunctionsClass::removeFromCode($email);
		return json_encode([
			'success' => true,
			'message' => ucfirst(translate('account verified, be welcome to ').APP['appName'])
		]);
	}

	/**
     * Login action
     * @version 1.0 - 20210406
     * @param  <string> the email 
     * @param  <string> the password
     * @return <array> keys <bool>   'success'
     *					    <string> 'message'
     */
	public function login($email = null, $password = null)
	{
		if(is_null($email) || is_null($password)){
			return json_encode([
				'success' => false,
				'message' => ucfirst(translate('email and password are required'))
			]);
		}
		$personObj = new Person();
		$person = $personObj->getByEmail($email);
		if(!$person){
			return json_encode([
				'success' => false,
				'message' => ucfirst(translate('email is incorrect'))
			]);
		}
		$personPassword = $person->getPassword();
		$password = FunctionsClass::generateHashValue($password);
		if($personPassword != $password){
			return json_encode([
				'success' => false,
				'message' => ucfirst(translate('password is incorrect'))
			]);
		}
		if($person->getStatus() == PERSON::NOT_VERIFIED_ACCOUNT){
			return json_encode([
				'success' => false,
				'message' => ucfirst(translate('account is not verified, please check your email'))
			]);
		}
		// set session variables
		FunctionsClass::setPersonSession($person);
		// set cookie, check how to use this data
		$authenticationToken = FunctionsClass::setPersonCookie();
		$person->setAuthenticationToken($authenticationToken);
		$person->save();
		return json_encode([
			'success' => true,
			'message' => ucfirst(translate('acces granted, be welcome'))
		]);
	}

	/**
     * Resets the Person password by sending the email, its former password and the new one.
     * If a 'password' is not sent, the method will send a new generated password to the email
     * @version 1.0 - 20210406
     * @param  <string> the email 
     * @param  <string> the former password
     * @param  <string> the new password
     * @return <array> keys <bool>   'success'
     *					    <string> 'message'
     */
	public function resetPassword($email = null, $password = null, $newPassword = null)
	{
		if(is_null($email)){
			return json_encode([
				'success' => false,
				'message' => ucfirst(translate('email is required'))
			]);
		}
		$personObj = new Person();
		$person = $personObj->getByEmail($email);
		if(!$person || $person->getStatus() == PERSON::NOT_VERIFIED_ACCOUNT){
			return json_encode([
				'success' => false,
				'message' => ucfirst(translate('email is invalid'))
			]);
		}

		if(is_null($password)){
			$newPassword = FunctionsClass::generateRandomValue();
			$newPassword = substr($newPassword, 0, 6);
			$mail = new Mail();
			$sendTo = [$email];
			$title   = ucfirst(translate('password recovery'));
			$message = ucfirst(translate('this is your new password')).' '.$newPassword; 
			$mail->sendMail($sendTo, $title, $message);
			FunctionsClass::writeToTmp('password.txt', $newPassword, $email);
			return json_encode([
				'success' => true,
				'message' => ucfirst(translate('an email with a new password has been sent'))
			]);
		}

		$passwordsFile = FunctionsClass::getContents('password.txt');
		if(!array_key_exists($email, $passwordsFile) || $passwordsFile[$email][$email] != $password){
			return json_encode([
				'success' => false,
				'message' => ucfirst(translate('invalid password'))
			]);
		}

		$passwordDate = strtotime($passwordsFile[$email]['date']);
		if(time() - $passwordDate > 15 * 60){
		    return json_encode([
				'success'     => false,
				'message'     => ucfirst(translate('password is invalid and outdated')),
				'resendEmail' => true
			]);
		}

		$newPassword = is_null($newPassword) ? $password : $newPassword;
		$newPassword = FunctionsClass::generateHashValue($newPassword);

		$personRecoveryDataWk = new PersonRecoveryData();
		$recoveryDataResult = $personRecoveryDataWk->save($person->getId());
		if(!$recoveryDataResult['saveResponse']){
			return json_encode([
				'success' => false,
				'message' => ucfirst(translate('an error occured, try again later'))
			]);
		}
		// Send new email informing of Difference in IP
		if(array_key_exists('differentIp', $recoveryDataResult)){
			$personRecoveryDataWk->sendDifferentIpMail($email);
		}

		$person->setPassword($newPassword);
		$result = $person->save();
		if(!$result){
			return json_encode([
				'success' => false,
				'message' => ucfirst(translate('an erro occured, try again later'))
			]);
		}
		FunctionsClass::removeFromTmp('password.txt', $email);
		return json_encode([
			'success' => true,
			'message' => ucfirst(translate('password updated with success'))
		]);
	}

	/**
     * Removes a Person and its related PersonRecoveryData object
     * @version 1.0 - 20210406
     * @param  <int>  the Person id to remove
     * @return <bool> 
     */
	public function remove($personId)
	{
		if(is_null($personId) || !is_numeric($personId)){
			return json_encode([
				'success' => false,
				'message' => ucfirst(translate('id is not informed or is not a number'))
			]);
		}
		$personObj = new Person();
		$person = $personObj->findById($personId);
		if(!$person){
			return json_encode([
				'success' => false,
				'message' => ucfirst(translate('invalid id'))
			]);
		}
		$personRecoveryDataObj = new PersonRecoveryData();
		$removalOfRecoveryData = $personRecoveryDataObj->remove($personId);
		if(!$removalOfRecoveryData){
			return json_encode([
				'success' => false,
				'message' => ucfirst(translate('an error occured, try again later'))
			]);
		}
		$removalResponse = $person->remove();
		if(!$removalResponse){
			return json_encode([
				'success' => false,
				'message' => ucfirst(translate('unexpected error, please contact the support'))
			]);
		}
		return json_encode([
			'success' => true,
			'message' => ucfirst(translate('person removed with success, until next time'))
		]);
	}

	/**Retrieves CEP data from external sources 
     * @version 1.0 - 20210406
     * @param  <int>  the CPF to look for
     * @return <array> keys: <bool>   'success'
     *                       <string> 'message'
     *						 <array>  'content' keys: 'streetName' | 'neighborhood' | 'state'
     */
	public function findCEPData($cep)
	{
		if(is_null($cep) || !is_numeric($cep)){
			return json_encode([
				'success' => false,
				'message' => ucfirst(translate('invalid cep format'))
			]);
		}
		$personObj = new Person();
		$cepData = $personObj->getCEPData($cep);
		$message = $cepData ? ucfirst(translate('data found')) : ucfirst(translate('cep not found'));
		return json_encode([
			'success' => true,
			'message' => $message,
			'content' => $cepData
		]);
	}

	// Remove a photo
    public function removePhoto($personId = null, $photoId = null)
    {
	    if(is_null($personId) || is_null($photoId)){
	        return json_encode([
	            'success' => false,
	            'message' => ucfirst(translate('ids are required')),
	        ]);         
	    }
	    $personObj = new Person();
	    $person = $postObj->findById($personObj);
	    if(!$person){
	       return json_encode([
	            'success' => false,
	            'message' => ucfirst(translate('invalid id')),
	       ]); 
	    }          
	    $personPhotoObj = new PersonPhoto();
	    $removalParameters = [
	    	'personId'   => $personId,
	    	'documentId' => $documentId
	    ];
	    $docsRemoveResponse = $personPhotoObj->removePersonPhotosAndDocuments($removalParameters);
	    if(!$docsRemoveResponse){
	        return json_encode([
	            'success' => false,
	            'message' => ucfirst(translate('unexpected error, try again later')),
	        ]); 
	   }
	    $removalResponse = $post->destroy();
	    if(!$removalResponse){
	        return json_encode([
	            'success' => false,
	            'message' => ucfirst(translate('the removal failed')),
	        ]); 
	    }
	    return json_encode([
	       'success' => true,
	       'message' => ucfirst(translate('removed with success')),
	    ]); 
	}

	// return data related to logged in user to view
	public function retrieveUserAccount($doNotReturn = ['id', 'password', 'authentificationToken'])
	{
		if(!FunctionsClass::isPersonLoggedIn()){
			return json_encode([
				'success' => false,
				'message' => ucfirst(translate('user must be logged in'))
			]);
		}
		$personObj = new Person();
		$dataFromPerson = $personObj->findById($_SESSION['personId'])->getFullData();
		foreach($doNotReturn as $keyName){
			unset($dataFromPerson[$keyName]);
		}
		$dataFromPerson = $dataFromPerson;
		return json_encode([
			'success' => true,
			'content' => $dataFromPerson
		]);
	}

    // get location of personObjects which possess a role accordinally to session city 
    // used to gather professionals from the selected city
    public function fetchAllProfessionalOfThisCity()
    {
        $userCity = isset($_SESSION['userCity']) ? $_SESSION['userCity'] : null;
        if(is_null($userCity))
            return null;
        $cityObj = new City();
        $city = $cityObj->getCityByName($userCity);
        if(!$city)
            return null;
        $cityId = $city->getId();
        $personObj = new Person();
        $people = $personObj->getByCityAndRole($cityId);
        if(is_null($people)){
        	return null;
        }
        $elementsArray = $personObj->parseAsEachRoleWithCoordinates($people);
        return json_encode([
        	'success' => true,
        	'message' => ucfirst(translate('professionals fetched with success')),
        	'content' => $elementsArray
        ]);
    }
}