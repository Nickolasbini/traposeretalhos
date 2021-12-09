<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;
use Source\Models\Post;
use Source\Models\Comment;

/**
 * 
 */
class Favorite extends DataLayer
{
	const POST    = 1;
	const COMMENT = 2;

	function __construct()
	{
		parent::__construct('favorites', ['person'], 'id', false);
	}
	// SETTERS
	public function setId($id){
		$this->id = $id;
	}
	public function setPerson($person){
		$this->person = $person;
	}
	public function setPost($post){
		$this->post = $post;
	}
	public function setComment($comment){
		$this->comment = $comment;
	}
	public function setFavoriteCategory($favoriteCategory){
		$this->favoriteCategory = $favoriteCategory;
	}


	// GETTERS
	public function getId(){
		return $this->id;
	}
	public function getPerson(){
		return $this->person;
	}
	public function getPost($asObject = null){
		if($asObject){
			$postObj = (new Post())->findById($this->post);
			return $postObj;
		}
		return $this->post;
	}
	public function getComment($asObject = null){
		if($asObject){
			$commentObj = (new Comment())->findById($this->comment);
			return $commentObj;
		}
		return $this->comment;
	}
	public function getFavoriteCategory(){
		return $this->favoriteCategory;
	}

	public function list($total = null, $limit = null, $offset = null, $category = null, $personId = null)
	{
		if(!is_null($total)){
			$total = $this->find('favoriteCategory = '.$category.' and person = '.$personId)->count();
			return $total;
		}
		$posts = $this->find('favoriteCategory = '.$category.' and person = '.$personId)->limit($limit)->offset($offset)->fetch(true);
		return $posts;
	}
}