<?php

namespace Source\Controllers;

use Source\Helpers\FunctionsClass;
use Source\Models\Category;

/**
 * 
 */
class CategoryController
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
          $categoryObj = new Category();
          $id = array_key_exists('id', $parameters) ? $parameters['id'] : false;
          if($id){
               $categoryObj = $categoryObj->findById($id);
               if(!$categoryObj){
                    return json_encode([
                         'success' => false,
                         'message' => ucfirst(translate('invalid id'))
                    ]);
               }
               unset($parameters['id']);
          }
          foreach($parameters as $key => $value){
               $setMethod = 'set'.ucfirst($key);
               if($key == 'categoryType'){
                    $typesArray = $categoryObj->getAllCategoryTypes();
                    if(!in_array($value, $typesArray)){
                         return json_encode([
                              'success' => false,
                              'message' => ucfirst(translate('invalid category type'))
                         ]);
                    }
               }
               $categoryObj->{$setMethod}($value);
          }
          $result = $categoryObj->save();
          if(!$result){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('saving error, try again later'))
               ]);
          }
          $message = !is_null($id) ? ucfirst(translate('updated with success'))
                                   : ucfirst(translate('created with success'));
          return json_encode([
               'success' => true,
               'message' => $message,
               'id'      => $categoryObj->data->id
          ]);
     }

     public function getAll($onlyCategories = null)
     {
          $categoryObj = new Category;
          $categories = $categoryObj->find()->fetch(true);
          $elements = [];
          foreach($categories as $category){
               $element = $category->getFullData();
               $userLanguage = strtolower($_SESSION['userLanguage']);
               $element['categoryTranslation'] = $element['translation'][$userLanguage];
               $elements[] = $element;
          }
          if($onlyCategories){
               return $elements;
          }
          return json_encode([
               'success' => true,
               'content' => $categories
          ]);
     }
}