<?php

namespace Source\Controllers;

use Source\Helpers\FunctionsClass;
use Source\Models\Comment;
use Source\Models\Post;
use Source\Models\Person;
use datetime;

/**
 * 
 */
class CommentController
{
	/**
     * Creates a new commnet or update one, this operation updates or creates
     * @version 1.0 - 20210406
     * @param  <array> keys: 'id', 'name', 'lastName', 'email, 'password', 'language', 'country' - 
     * 				          required (any other attribute of Person may be sent)
     * @return <array> keys <bool>   'success'
     *					    <string> 'message'
     */
	public function save($parameters)
     {
          $commentObj = new Comment();
          $isUpdate = false;
          if(array_key_exists('id', $parameters)){
               $commentObj = $commentObj->findById($parameters['id']);
               if(!$commentObj){
                    return json_encode([
                         'success' => false,
                         'message' => ucfirst(translate('invalid id'))
                    ]);
               }
               unset($parameters['id']);
               $date = date('Y-m-d H:i:s');
               $commentObj->setDateOfLastUpdate($date);
               $isUpdate = true;
          }else{
               $date = date('Y-m-d H:i:s');
               $commentObj->setDateOfCreation($date);
          }
          // setting the parameters
          $postObj = new Post();
          $post = $postObj->findById($parameters['post']);
          if(!$post){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('invalid post'))
               ]);
          }
          $personObj = new Person();
          $person = $personObj->findById($parameters['person']);
          if(!$person){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('invalid person'))
               ]);
          }
          $commentObj->setPost($parameters['post']);
          $commentObj->setPerson($parameters['person']);
          $commentObj->setUserComment($parameters['userComment']);
          $result = $commentObj->save();
          if(!$result){
               return json_encode([
                   'success' => false,
                   'message' => ucfirst(translate('an error occured'))
               ]);  
          }
          // sending request to update Post number
          if(!$isUpdate)
               $post->updateNumberOfCommentsStatus();
          $message = $isUpdate ? ucfirst(translate('updated with success'))
                               : ucfirst(translate('created with success'));
          return json_encode([
               'success' => true,
               'message' => $message
          ]);
     }

     // remove a commnet
     public function remove($commentId = null)
     {
          if(is_null($commentId)){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('invalid id'))
               ]);
          }
          $commentObj = new Comment();
          $comment = $commentObj->findById($commentId);
          if(!$comment){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('invalid id'))
               ]);
          }
          $postOfComment = $comment->getPost(true);
          $removalResult = $comment->destroy();
          if(!$removalResult){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('the removal failed'))
               ]);
          }
          $postOfComment->updateNumberOfCommentsStatus(false);
          return json_encode([
               'success' => true,
               'message' => ucfirst(translate('removed with success'))
          ]);
     }

     // returns the comments data accordinaly to the Post Id
     public function getComments($postId = null)
     {
          if(is_null($postId)){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('no post id sent'))
               ]);
          }
          $commentObj = new Comment();
          $comments = $commentObj->getByPost($postId);
          if(is_null($comments)){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('this post has no comments')),
               ]);
          }
          $content = [];
          foreach($comments as $comment){
               $content[] = $comment->getFullData(true);
          }
          return json_encode([
               'success' => true,
               'message' => ucfirst(translate('comments found')),
               'content' => $content
          ]);
     }

     // add or remove a like from comment
     public function updateCommentLikes($commentId, $add = true)
     {
          if(is_null($commentId)){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('comment id is required')),
                    'performLogin' => false
               ]);
          }
          $commentObj = new Comment();
          $comment = $commentObj->findById($commentId);
          if(!$comment){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('id is invalid')),
                    'performLogin' => false
               ]);
          }
          $numberOfLikes = $comment->getLikes();
          if($add){
               $numberOfLikes++;
          }else{
               $numberOfLikes--;
          }
          // insert person which liked this comment
          $result = $comment->updatePersonWhichLiked($_SESSION['personId'], $add);
          if(!$result){
               return json_encode([
                    'success'     => true,
                    'message'     => ucfirst(translate('comment is already liked')),
                    'likesNumber' => $comment->getLikes()
               ]);
          }
          $comment->setLikes($numberOfLikes);
          $result = $comment->save();
          if(!$result){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('an error occured, try again later')),
                    'performLogin' => false
               ]);
          }
          $message = $add ? ucfirst(translate('like added')) : ucfirst(translate('like removed'));
          return json_encode([
               'success'     => false,
               'message'     => $message,
               'likesNumber' => $numberOfLikes
          ]);
     }

     // make a method which will store the user id which made the like, in a JSON field at comments
}