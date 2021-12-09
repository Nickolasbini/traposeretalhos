<?php 
use Source\Controllers\CategoryController;
use Source\Controllers\PostController;
use Source\Controllers\CommentController;
use Source\Controllers\FavoriteController;
use Source\Helpers\FunctionsClass;
use Source\Models\PostPhoto;

// Maybe here create a method to valite login
$app->get('/posts', function(){
	$categoryCt = new CategoryController();
	$categories = $categoryCt->getAll(true);
	$userTemplate = new League\Plates\Engine('Source/Resourses/UserViews');
	echo $userTemplate->render('post-management', ['categories' => $categories]);
});

$app->get('/news', function(){
	$page = isset($_GET['page']) ? $_GET['page'] : 1;
	$postCt = new PostController();
	$response = json_decode($postCt->gatherPosts($page), true);
	$userTemplate = new League\Plates\Engine('Source/Resourses/UserViews');
	echo $userTemplate->render('news', ['posts' => $response['content'], 'pages' => $response['page'], 'message' => $response['message']]);
});

$app->get('/category/save', function(){
	$categoryCt = new CategoryController();
	$parameters = [
		'id'           => isset($_POST['id']) 		    ? $_POST['id'] : null,
		'categoryName' => isset($_POST['categoryName']) ? $_POST['categoryName'] : null,
		'categoryType' => isset($_POST['categoryType']) ? $_POST['categoryType'] : null
	];
	$result = $categoryCt->save($parameters);
	exit($result);
});

$app->post('/post/save', function(){
	$postCt = new PostController();
	$parameters = [
		'id'			  => isset($_POST['id']) 			  ? $_POST['id'] : null,
		'category' 		  => isset($_POST['category']) 		  ? $_POST['category'] : null,
		'postTitle'       => isset($_POST['postTitle']) 	  ? $_POST['postTitle'] : null,
		'postDescription' => isset($_POST['postDescription']) ? $_POST['postDescription'] : null,
		'postPhoto'	      => isset($_POST['postPhoto'])       ? $_POST['postPhoto'] : null
	];
	$result = $postCt->save($parameters);
	exit($result);
});

$app->post('/post/remove', function(){
	$postCt = new PostController();
	$postId = isset($_POST['id']) ? $_POST['id'] : null;
	$result = $postCt->remove($postId);
	exit($result);
});

$app->post('/post/getdata', function(){
	$postCt = new PostController();
	$postId = isset($_POST['id']) ? $_POST['id'] : null;
	$result = $postCt->getPostData($postId);
	exit($result);
});

$app->post('/comment/save', function(){
	if(!FunctionsClass::isPersonLoggedIn()){
		$errorMessage = ucfirst(translate('please, log in first'));
		$_SESSION['messageToDisplay'] = $errorMessage;
		echo json_encode([
			'success' => false,
			'performLogin' => true,
			'message' => $errorMessage
		]);
		return;
	}
	$commentId   = isset($_POST['id']) ? $_POST['id'] : null;
	$userComment = isset($_POST['userComment']) ? $_POST['userComment'] : null;
	$postId      = isset($_POST['postId']) ? $_POST['postId'] : 0;
	$personId    = isset($_POST['personId']) ? $_POST['personId'] : $_SESSION['personId'];
	$parameters = [
		'userComment' => $userComment,
		'post'		  => $postId,
		'person'	  => $personId
	];
	if(!is_null($commentId))
		$parameters['id'] = $commentId;
	$commentCt = new CommentController();
	$result = $commentCt->save($parameters);
	echo $result;
	return;
});

$app->post('/comment/remove', function(){
	$commentCt = new CommentController();
	$postId = isset($_POST['commentId']) ? $_POST['commentId'] : null;
	$result = $commentCt->remove($postId);
	echo $result;
	return;
});

$app->post('/comment/getcomments', function(){
	$postId = isset($_POST['postId']) ? $_POST['postId'] : null;
	if(is_null($postId)){
		echo json_encode([
			'success' => false,
			'message' => ucfirst(translate('no post id sent'))
		]);
		return;
	}
	$commentCt = new CommentController();
	$result = $commentCt->getComments($postId);
	echo $result;
	return;
});

$app->post('/comment/gettotalofcomment', function(){
	$postId = isset($_POST['postId']) ? $_POST['postId'] : null;
	if(is_null($postId)){
		echo json_encode([
			'success' => false,
			'message' => ucfirst(translate('no post id sent'))
		]);
		return;
	}
	$postCt = new PostController();
	$result = $postCt->getTotalOfComment($postId);
	echo $result;
	return;
});

$app->post('/comment/managelikes', function(){
	if(!FunctionsClass::isPersonLoggedIn()){
		$errorMessage = ucfirst(translate('please, log in first'));
		$_SESSION['messageToDisplay'] = $errorMessage;
		echo json_encode([
			'success' => false,
			'performLogin' => true,
			'message' => $errorMessage
		]);
		return;
	}

	$commentId = isset($_POST['commentId']) ? $_POST['commentId'] : null;
	$add 	   = isset($_POST['add']) ? $_POST['add'] : null;
	if(is_null($commentId)){
		echo json_encode([
			'success' => false,
			'message' => ucfirst(translate('no comment id sent'))
		]);
		return;
	}
	$commentCt = new CommentController();
	$result = $commentCt->updateCommentLikes($commentId, $add);
	echo $result;
	return;
});

$app->post('/favorite/save', function(){
	$favoriteCt = new FavoriteController();
	$result = $favoriteCt->save();
	echo $result;
	return;
});

$app->post('/favorite/remove', function(){
	$favoriteCt = new FavoriteController();
	$result = $favoriteCt->remove();
	echo $result;
	return;
});

$app->post('/favorite/list', function(){
	$favoriteCt = new FavoriteController();
	$result = $favoriteCt->list();
	echo $result;
	return;
});