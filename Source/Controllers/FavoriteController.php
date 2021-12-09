<?php

namespace Source\Controllers;

use Source\Models\Favorite;
use Source\Models\Person;
use Source\Models\Post;
use Source\Models\Comment;
use CoffeeCode\Paginator\Paginator;
use DateTime;

class FavoriteController
{
    public function list()
    {
        $favoriteCategory = isset($_POST['favoriteCategory']) ? $_POST['favoriteCategory'] : null;
        $personId   = isset($_SESSION['personId']) ? $_SESSION['personId'] : null;
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        if($favoriteCategory != 1 && $favoriteCategory != 2){
            return json_encode([
                'success' => false,
                'message' => 'invalid categories'
            ]);
        }
        $favoriteObj = new Favorite();
        $total = $favoriteObj->list(true, null, null, $favoriteCategory, $personId);
        $paginator = new Paginator();
        $paginator->pager($total, 10, $page);
        $favorites = $favoriteObj->list(null, $paginator->limit(), $paginator->offset(), $favoriteCategory, $personId);
        $response = [];
        $message = null;
        if($favoriteCategory == 'comment'){
            $getMethod = 'getComment';
        }else{
            $getMethod = 'getPost';
        }
        $elements = [];
        if($favorites && count($favorites) > 0){
            foreach($favorites as $favorite){
                $elements[] = $favorite->$getMethod(true)->getFullData(true);
            }
        }else{
           $message = ucfirst(translate('no post'));
        }
        return json_encode([
           'success' => true,
           'content' => $elements,
           'page'    => $paginator->render(),
           'message' => $message
        ]);
    }

    public function remove()
    {
        $personId   = isset($_SESSION['personId']) ? $_SESSION['personId'] : null;
        $favoriteId = isset($_POST['favoriteId'])      ? $_POST['favoriteId'] : null;
        if(!$personId || !$favoriteId){
            return json_encode([
                'success' => false,
                'message' => 'required parameters missing'
            ]);
        }
        $favoriteObj = new Favorite();
        $favorite = $favoriteObj->findById($favoriteId);
        if(!$favorite){
            return json_encode([
                'success' => false,
                'message' => 'invalid favorite'
            ]);
        }
        $result = $favorite->destroy();
        if(!$favorite){
            return json_encode([
                'success' => false,
                'message' => ucfirst(translate('was not removed'))
            ]);
        }
        return json_encode([
            'success' => true,
            'message' => ucfirst(translate('removed with success'))
        ]);
    }

    public function save()
    {
        $personId  = isset($_SESSION['personId']) ? $_SESSION['personId'] : null;
        $postId    = isset($_POST['postId'])      ? $_POST['postId'] : null;
        $commentId = isset($_POST['commentId'])  ? $_POST['commentId'] : null;
        if(!$personId || (!$postId && !$commentId)){
            return json_encode([
                'success' => false,
                'message' => 'required parameters missing'
            ]);
        }
        $person = (new Person())->findById($personId);
        $favoriteObj = new Favorite();
        $favoriteObj->setPerson($person);
        $savedSomething = false;
        if($postId){
            $postObj = new Post();
            $post = $postObj->findById($postId);
            if(!$post){
                return json_encode([
                    'success' => false,
                    'message' => 'invalid post'
                ]);
            }
            $favoriteObj->setPost($post);
            $favoriteObj->setFavoriteCategory('post');
            $result = $favoriteObj->save();
            if($result){
                $savedSomething = true;
            }
        }
        if($commentId){
            $commentObj = new Comment();
            $comment = $commentObj->findById($commentId);
            if(!$comment){
                return json_encode([
                    'success' => false,
                    'message' => 'invalid comment'
                ]);
            }
            $favoriteObj->setComment($comment);
            $favoriteObj->setFavoriteCategory('comment');
            $result = $favoriteObj->save();
            if($result){
                $savedSomething = true;
            }
        }
        if(!$savedSomething){
            return json_encode([
                'success' => false,
                'message' => 'required parameters missing'
            ]);
        }
        return json_encode([
            'success' => true,
            'message' => ucfirst(translate('saved on favorites'))
        ]);
    }
}