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

	const CATEGORY_POST = 'category-post';

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
}