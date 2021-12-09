<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;
use Source\Models\Translation;

/**
 * 
 */
class Category extends DataLayer
{
	
	function __construct()
	{
		parent::__construct('categories', ['categoryName','translation','categoryType'], 'id', false);
	}

	const POSTS    = 'posts';
	const COMMENTS = 'comments';
	const REVIEWS  = 'reviews';
	const MISC     = 'misc';

	// SETTERS
	// This reflects in the Translation obj.
	public function setCategoryName($categoryName){
		$translationObj = new Translation();
		if(is_null($this->getTranslation())){
			$translationObj->setBaseWord($categoryName);
			$translationObj->setCategory(TRANSLATION::CATEGORY_POST);
			$result = $translationObj->save();
			if($result){
				$this->setTranslation($translationObj->data->id);
			}
		}else{
			$translationId = $this->getTranslation();
			$translationObj = $translationObj->findById($translationId);
			$translationObj->setBaseWord($categoryName);
			$translationObj->save();
		}
		$this->categoryName = $categoryName;
	}
	public function setTranslation($translation){
		$this->translation = $translation;
	}
	public function setCategoryType($categoryType){
		$this->categoryType = $categoryType;
	}

	// GETTERS
	public function getId(){
		return $this->id;
	}
	public function getCategoryName(){
		return $this->categoryName;
	}
	public function getTranslation($asObject = false){
		if($asObject){
			$translationObj = (new Translation())->findById($this->translation);
			return $translationObj;
		}
		return $this->translation;
	}
	public function getCategoryType(){
		return $this->categoryType;
	}

	public function getAllCategoryTypes()
	{
		$types = ['posts', 'comments', 'reviews', 'misc'];
		return $types;
	}

	// get translated to session user language category type
	public function getCategoryNameTranslated()
	{
		$languageOfUser = $_SESSION['userLanguage'];
		$translationObj = $this->getTranslation(true);
		$getMethod = 'get'.ucfirst($languageOfUser);
		$translatedTerm = $translationObj->{$getMethod}();
		return $translatedTerm;
	}

	public function getFullData()
	{
		$elements = [
			'id' 		   => $this->getId(),
			'categoryName' => $this->getCategoryName(),
			'categoryType' => $this->getCategoryType(),
			'translation'  => $this->getTranslation(true)->getFullData()
		];
		return $elements;
	}
}