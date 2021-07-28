<?php

namespace Source\Controllers;

use Source\Helpers\FunctionsClass;
use Source\Models\PersonRole;
use Source\Models\Person;
use Source\Models\Role;
use Source\Models\PersonalPage;
use Source\Models\City;

/**
 * 
 */
class PersonRoleController
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
	public function save($parameters)
     {
          $personRoleObj = new PersonRole();
          $isUpdate = false;
          if(array_key_exists('id', $parameters)){
               $personRoleObj = $personRoleObj->findById($parameters['id']);
               if(!$personRoleObj){
                    return json_encode([
                         'success' => false,
                         'message' => ucfirst(translate('invalid person role id'))
                    ]);
               }
               $isUpdate = true;
               unset($parameters['id']);
          }
          // Preventing problems with 'score'
          if(array_key_exists('score', $parameters))
               unset($parameters['score']);
          foreach($parameters as $key => $value){
               $setMethod = 'set'.ucfirst($key);
               if($key == 'role'){
                    $roleObj = new Role();
                    $role = $roleObj->findById($value);
                    if(!$role){
                         return json_encode([
                              'success' => false,
                              'message' => ucfirst(translate('invalid role'))
                         ]);
                    }
                    $value = $role->getId();
                    if($isUpdate && $personRoleObj->getRole() != $value){
                         $personRoleObj->setScore(0);
                    }
               }
               if($key == 'person'){
                    $personObj = new Person();
                    $person = $personObj->findById($value);
                    if(!$person){
                         return json_encode([
                              'success' => false,
                              'message' => ucfirst(translate('invalid person'))
                         ]);
                    }
                    $value = $person->getId();
                    // setting the attribute 'hasRole' of Person obj
                    $person->setHasRole(true);
                    $person->save();
               }

               $personRoleObj->{$setMethod}($value);
          }
          if(!$isUpdate){
               $personRoleObj->setScore(0);
               $personalPageObj = new PersonalPage();
               $personalPageResponse = $personalPageObj->createURL();
               $personRoleObj->setPersonalPage($personalPageResponse);
          }
          $result = $personRoleObj->save();
          if(!$result){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('an error occurred, try again later'))
               ]);
          }
          $message = $isUpdate ? ucfirst(translate('person role updated with success')) 
                               : ucfirst(translate('person role created with success'));
          return json_encode([
               'success' => true,
               'message' => $message
          ]);
     }
}