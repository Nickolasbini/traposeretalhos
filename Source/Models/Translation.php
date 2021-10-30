<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;

/**
 * 
 */
class Translation extends DataLayer
{
	
	function __construct()
	{
		parent::__construct('translations', ['baseWord'], 'id', false);
	}

	const CATEGORY_POST    = 'category-post';
	const CATEGORY_COUNTRY = 'category-country';

	// SETTERS
	public function setCategory($category){
		$this->category = $category;
	}
	public function setBaseWord($baseWord){
		$this->baseWord = $baseWord;
	}
	public function setPt($pt){
		$this->pt = $pt;
	}
	public function setPtbr($ptbr){
		$this->ptbr = $ptbr;
	}
	public function setEn($en){
		$this->en = $en;
	}
	public function setEs($es){
		$this->es = $es;
	}

	// GETTERS
	public function getId(){
		return $this->id;
	}
	public function getCategory(){
		return $this->category;
	}
	public function getBaseWord(){
		return $this->baseWord;
	}
	public function getPt(){
		return $this->pt;
	}
	public function getPtbr(){
		return $this->ptbr;
	}
	public function getEn(){
		return $this->en;
	}
	public function getEs(){
		return $this->es;
	}

	public function saveTranslation($parameters)
	{
		$translationObj = new Translation();
		$id = array_key_exists('id', $parameters) ? $parameters['id'] : false;
		if($id){
			$translationObj = $translationObj->findById($id);
			if(!$translationObj){
				return json_encode([
					'success' => false,
					'message' => ucfirst(translate('invalid id'))
				]);
			}
			unset($parameters['id']);
		}
		foreach($parameters as $key => $value){
			$setMethod = 'set'.ucfirst($key);
			$translationObj->{$setMethod}($value);
		}
		$result = $translationObj->save();
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
			'id'	  => $translationObj->data->id
		]);
	}

	// get by baseWord and category, both are necessary
	public function getByBaseWordAndCategory($baseWord, $category)
	{
		$translationObj = $this->find("baseWord = :baseWord and category = :category", "baseWord=$baseWord&category=$category")->fetch(true);
		return $translationObj ? $translationObj : null;
	}

	/**
     * Returns an array containing all data of translation 
     * @version 1.0 - 20211030
     * @return <array> of translation data 
     */
	public function getFullData()
	{
		$response = [
			'id' 	   => $this->getId(),
			'category' => $this->getCategory(),
			'baseWord' => $this->getBaseWord(),
			'en'       => $this->getEn(),
			'pt' 	   => $this->getPt(),
			'es' 	   => $this->getEs()
		];
		return $response;
	}
}