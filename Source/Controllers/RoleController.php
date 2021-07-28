<?php

namespace Source\Controllers;

use Source\Helpers\FunctionsClass;
use Source\Models\Role;
use Datetime

/**
 * 
 */
class RoleController
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
          // when saving the roles create a file at tmp
          // reload the session of all users
     }

     // gets all roles
     public function getAllRoles()
     {
          $roleObj = new PersonRole();
          $roles = $roleObj->find()->fetch(true);
          $elements = [];
          if(count($roles) > 0){
               foreach($roles as $role){
                    $elements[] = [
                         'id'             => $role->getId(),
                         'roleName'       => $role->getRoleName(),
                         'dateOfCreation' => $role->getDateOfCreation()
                    ];
               }
          }
          return json_encode([
               'success' => true,
               'content' => $elements
          ]);
     }
}