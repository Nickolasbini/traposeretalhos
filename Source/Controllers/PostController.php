<?php

namespace Source\Controllers;

use Source\Helpers\FunctionsClass;
use Source\Models\Post;
use Source\Models\Person;
use Source\Models\category;
use Source\Models\PostPhoto;
use Source\Models\Comment;
use Datetime;
use CoffeeCode\Paginator\Paginator;
use Source\Support\Table;

/**
 * 
 */
class PostController
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
          $postObj = new Post();
          // check session of user, this should be done by the midleware or something else
          if(!isset($_SESSION['personId']) || is_null($_SESSION['personId'])){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('please log in'))
               ]);
          }
          $parameters['person'] = $_SESSION['personId'];
          if(!is_null($parameters['id'])){
               $postObj = $postObj->findById($parameters['id']);
               if(!$postObj){
                    return json_encode([
                         'success' => false,
                         'message' => ucfirst(translate('invalid id'))
                    ]);
               }
          }else{
               $postObj->setNumberOfViews(0);
               $postObj->setNumberOfClicks(0);
               $postObj->setNumberOfInFavoriteList(0);
               $postObj->setDateOfCreation(date("Y-m-d h:i:sa"));
          }
          // check if file has photos
          $hasPhotos = false;
          if(array_key_exists('postPhoto', $parameters)){
               $hasPhotos = $parameters['postPhoto'];
               unset($parameters['postPhoto']);
          }
          // set data
          foreach($parameters as $key => $value){
               if($key == 'id' || is_null($value))
                    continue;

               $setMethod = 'set'.ucfirst($key);
               if($key == 'category'){
                    $categoryObj = new Category();
                    $category = $categoryObj->findById($value);
                    if(!$category){
                         return json_encode([
                              'success' => false,
                              'message' => ucfirst(translate('invalid post type'))
                         ]);
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
               }
               $postObj->{$setMethod}($value);
          }
          $fullData = $postObj->getFullData();
          $result = $postObj->save();
          if(!$result){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('saving error, try again later'))
               ]);
          }
          $postId = $postObj->data->id;
          $message = !is_null($parameters['id']) ? ucfirst(translate('updated with success'))
                                                 : ucfirst(translate('created with success'));
          // now create the photos and link it
          if($hasPhotos){
               $postPhotoObj = new PostPhoto();
               $result = $postPhotoObj->savePostPhoto($postId, $hasPhotos);
               if(!$result)
                    $message = $message.' - '.ucfirst(translate('your photo(s) have not been created'));
          }
          return json_encode([
               'success' => true,
               'message' => $message,
               'id'      => $postId,
               'post'    => $fullData
          ]);
     }

     // Remove the post and its related documents 
     public function remove($postId = null)
     {
          if(is_null($postId)){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('a post id is required')),
               ]);         
          }
          $postObj = new Post();
          $post = $postObj->findById($postId);
          if(!$post){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('invalid id')),
               ]); 
          }          
          $postPhotoObj = new PostPhoto();
          $docsRemoveResponse = $postPhotoObj->removePostPhotosAndDocuments($post->getId());
          if(!$docsRemoveResponse){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('unexpected error, try again later')),
               ]); 
          }
          $commentObj = new Comment();
          $commentsRemovalResponse = $commentObj->removeComments($post->getId());
          if(!$commentsRemovalResponse){
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

     public function list($page = 1, $limit = 10)
     {
          $postObj = new Post();
          $total = $postObj->list(true);
          $paginator = new Paginator();
          $paginator->pager($total, $limit, $page);
          $posts = $postObj->list(null, $paginator->limit(), $paginator->offset());
          $elements = [];
          if(count($posts) > 1){
               foreach($posts as $post){
                    $personData = $post->getPerson(true)->getFullData();
                    $elements[] = [
                         'id'                           => $post->getId(),
                         translate('name')              => $personData['profilePhoto'],
                         translate('title description') => $post->getPostTitle(),
                         translate('post description')  => $post->getPostDescription(),
                         translate('date of creation')  => $post->getDateOfCreation(),
                         translate('date of update')    => $post->getDateOfUpdate()
                    ];
               }
          }
          $table = new Table();
          $postsTable = $table->generateHTMLTable($elements, 'id');      

          exit($postsTable);
     }

     public function gatherPosts($page = 1, $limit = 10)
     {
          $postObj = new Post();
          $total = $postObj->list(true);
          $paginator = new Paginator();
          $paginator->pager($total, $limit, $page);
          $posts = $postObj->list(null, $paginator->limit(), $paginator->offset());
          $response = [];
          $message = null;
          if($posts && count($posts) > 0){
               foreach($posts as $post){
                    $personObj = $post->getPerson(true);
                    $personData = $personObj->getFullData();
                    $response[] = [
                         'id' => $personData['id'],
                         'name' => $personData['abbreviationName'],
                         'profilePhoto' => $personData['profilePhoto'],
                         'city&State' => ucfirst($personData['cityData']['name']).' - '.$personData['cityData']['state']['isoCode'],
                         'personDescription' => $personData['personDescription'],
                         'postId' => $post->getId(),
                         'postTitle' => $post->getPostTitle(),
                         'postDescription' => $post->getPostDescription(),
                         'postCategory'=> $post->getCategory(true)->getCategoryNameTranslated(),
                         'postNumberOfComments' => $post->getNumberOfComments(true),
                         'postPhotos' => $this->getPhotosOfPostPath($post->getId()),
                         'hasRole' => $personData['hasRole'],
                         'roleData' => $personData['hasRole'] ? $personObj->getPersonRole() : null,
                    ];
               }
          }else{
               $message = ucfirst(translate('no post'));
          }
          return json_encode([
               'success' => true,
               'content' => $response,
               'page'    => $paginator->render(),
               'message' => $message
          ]);
     }

     // returns $this post photos Web path url as array 
     public function getPhotosOfPostPath($postId)
     {
          $postPhotoObj = new PostPhoto();
          $photosOfThePost = $postPhotoObj->getByPost($postId);
          $documentWebPhotosURL = [];
          if(is_null($photosOfThePost))
               return null;
          foreach($photosOfThePost as $photo){
               $documentWebPhotosURL[] = $photo->getDocument(true)->getPhotoWebPath();
          }
          return $documentWebPhotosURL;
     }

     // returns the total of comments of a certain Post
     public function getTotalOfComment($postId = null)
     {
          if(is_null($postId)){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('invalid post id'))
               ]);
          }
          $postObj = new post();
          $post = $postObj->findById($postId);
          if(!$post){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('invalid post id'))
               ]);
          }
          $response = $post->getNumberOfComments(true);
          return $response;
     }

     public function getPostData($postId = null)
     {
          if(is_null($postId)){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('no id sent'))
               ]);
          }
          $postObj = new Post();
          $post = $postObj->findById($postId);
          if(is_null($post)){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('invalid id'))
               ]);
          }
          $data = $post->getFullData();
          return json_encode([
               'success' => true,
               'message' => ucfirst(translate('data gathered with success')),
               'content' => $data
          ]);
     }
}