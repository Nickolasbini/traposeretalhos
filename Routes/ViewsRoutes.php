<?php
use Source\Controllers\CountryController;
use Source\Controllers\PersonController;
use Source\Controllers\FavoriteController;

$app->get('/map', function(){
	$roles = file_get_contents('Source/Files/roles.txt');
	$userTemplate = new League\Plates\Engine('Source/Resourses/UserViews');
	echo $userTemplate->render('map', [
		'title' => ucfirst(translate('real time map')),
		'roles' => json_decode($roles, true)
	]);
});

$app->get('/tips', function(){
	$userTemplate = new League\Plates\Engine('Source/Resourses/UserViews');
	echo $userTemplate->render('tips', ['title' => ucfirst(translate('the best tips'))]);
});

$app->get('/courses', function(){
	$userTemplate = new League\Plates\Engine('Source/Resourses/UserViews');
	echo $userTemplate->render('courses', ['title' => ucfirst(translate('our courses'))]);
});

$app->get('/favorites', function(){
	$favoriteCt = new FavoriteController();
	$_POST['favoriteCategory'] = '1';
	$results = $favoriteCt->list();
	$results = json_decode($results, true);
	$userTemplate = new League\Plates\Engine('Source/Resourses/UserViews');
	echo $userTemplate->render('favorites', [
		'title' => ucfirst(translate('favorites')),
		'favorites' => $results['content'],
		'pagination' => $results['page']
	]);
});

$app->get('/newaccount', function(){
	$countryCt = new CountryController();
	$countries = $countryCt->fetchAllContries(true);
	$userTemplate = new League\Plates\Engine('Source/Resourses/UserViews');
	echo $userTemplate->render('new-account', [
		'title' => ucfirst(translate('create account')),
		'countries' => $countries,
		'roles' => $_SESSION['roles']
	]);
});
