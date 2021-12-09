<?php

namespace Source\Models;

use Source\Models\Person;
use Source\Models\PostPhoto;
use CoffeeCode\DataLayer\DataLayer;

/**
 * 
 */
class Post extends DataLayer
{
	
	function __construct()
	{
		parent::__construct('posts', ['category','postTitle', 'postDescription'], 'id', false);
	}

	// SETTERS
	public function setCategory($category){
		$this->category = $category;
	}
	public function setPostTitle($postTitle){
		$this->postTitle = $postTitle;
	}
	public function setPostDescription($postDescription){
		$this->postDescription = $postDescription;
	}
	public function setDateOfCreation($dateOfCreation){
		$this->dateOfCreation = $dateOfCreation;
	}
	public function setDateOfUpdate($dateOfUpdate){
		$this->dateOfUpdate = $dateOfUpdate;
	}
	public function setNumberOfViews($numberOfViews){
		$this->numberOfViews = $numberOfViews;
	}
	public function setNumberOfClicks($numberOfClicks){
		$this->numberOfClicks = $numberOfClicks;
	}
	public function setNumberOfComments($numberOfComments){
		$this->numberOfComments = $numberOfComments;
	}
	public function setNumberOfInFavoriteList($numberOfInFavoriteList){
		$this->numberOfInFavoriteList = $numberOfInFavoriteList;
	}
	public function setPerson($Person){
		$this->Person = $Person;
	}

	// GETTERS
	public function getId(){
		return $this->id;
	}
	public function getCategory($asObject = false){
		if($asObject){
			$categoryObj = (new Category())->findById($this->category);
			return $categoryObj;
		}
		return $this->category;
	}
	public function getPostTitle(){
		return $this->postTitle;
	}
	public function getPostDescription(){
		return $this->postDescription;
	}
	public function getDateOfCreation(){
		return $this->dateOfCreation;
	}
	public function getDateOfUpdate(){
		return $this->dateOfUpdate;
	}
	public function getNumberOfViews(){
		return $this->numberOfViews;
	}
	public function getNumberOfClicks(){
		return $this->numberOfClicks;
	}
	public function getNumberOfComments($parseAsNumber = false){
		return $parseAsNumber && is_null($this->numberOfComments) ? 0 : $this->numberOfComments;
	}
	public function getNumberOfInFavoriteList(){
		return $this->numberOfInFavoriteList;
	}
	public function getPerson($asObject = false){
		if($asObject){
			$personObj = (new Person())->findById($this->person);
			return $personObj;
		}
		return $this->person;
	}

	public function list($total = null, $limit = null, $offset = null, $category = null)
	{
		if(!is_null($total)){
			$total = $this->find()->count();
			return $total;
		}
		$posts = $this->find()->limit($limit)->offset($offset)->order('dateOfCreation or dateOfUpdate')->fetch(true);
		return $posts;
	}
	public function getFullData($photos = null)
	{
		$response = [
			'id' => $this->getId(),
			'postTitle' => $this->getPostTitle(),
			'postDescription' => $this->getPostDescription(),
			'dateOfCreation' => $this->getDateOfCreation(),
			'dateOfUpdate' => $this->getDateOfUpdate(),
			'numberOfComments' => $this->getNumberOfComments(),
			'numberOfClicks' => $this->getNumberOfClicks(),
			'numberOfInFavoriteList' => $this->getNumberOfInFavoriteList(),
			'postTitle' => $this->getPostTitle(),
			'categoryId' => $this->getCategory(),
			'categoryName' => $this->getCategory(true)->getCategoryName(),
			'personId' => $this->getPerson(),
			'postMainPhoto' => null,
			'postPhotos' => null,
		];
		return $response;
	}

	// set one more comment to the attribute 'numberOfComments'
	// param works for adding one more on true or removing one for false
	public function updateNumberOfCommentsStatus($newComment = true)
	{
		$total = $this->getNumberOfComments();
		$total = $newComment ? $total + 1 : $total - 1;
		$this->setNumberOfComments($total);
		$result = $this->save();
		if(!$result)
			return false;
		return true;
	}

	// return whether logged in user is owenr of $this post
	public function isOwnerOfPost()
	{
		$personId = isset($_SESSION['personId']) ? $_SESSION['personId'] : null;
		if(is_null($personId))
			return false;
		if($this->getPerson() == $personId)
			return true;
		return false;
	}
}