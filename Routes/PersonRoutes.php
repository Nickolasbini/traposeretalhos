<?php 
use Source\Controllers\PersonController;
use Source\Controllers\CityController;
use Source\Controllers\StateController;
use Source\Controllers\PersonalPageController;
use Source\Controllers\MessageController;
use Source\Models\PersonRole;
use Source\Models\PersonalPage;
use Source\Models\City;
use Source\Helpers\FunctionsClass;

$app->get('/person/personalpage/identification:id', function($id){
	$personalPageObj = new PersonalPage();
	//$personalPage = $personalPageObj->findById(base64_decode($id));
	$personalPage = $personalPageObj->findById($id);
	if(!$personalPage){
		$_SESSION['viewMessage'] = ucfirst(translate('personal page profile does not exit'));
		header('Location: /'.URL['urlDomain']);
	}
	$personRoleObj = new PersonRole();
	$personRole = $personRoleObj->getPersonByPersonalPage($personalPage->getId());
	if(is_null($personRole)){
		$_SESSION['viewMessage'] = ucfirst(translate('personal page profile unreachable, try again later'));
		header('Location: /'.URL['urlDomain']);
	}
	$personFullData = $personRole->getPerson(true)->getFullData();
	if(is_null($personFullData)){
		$_SESSION['viewMessage'] = ucfirst(translate('personal page profile unreachable, try again later'));
		header('Location: /'.URL['urlDomain']);
	}
	$userTemplate = new League\Plates\Engine('Source/Resourses/UserViews');
	$templatePersonalPage = $personalPage->getTemplatePersonalPage();
	if(!is_null($templatePersonalPage)){
		echo $userTemplate->render('PersonalPages/templatePersonalPage'.$templatePersonalPage, [
			'person'	 => $personFullData
		]);
		exit;
	}

	echo $userTemplate->render('PersonalPages/customPersonalPage', [
		'customHTML' => $personalPage->getCustomHTML(),
		'customCSS'  => $personalPage->getCustomCSS(),
		'person'	 => $personFullData
	]);
});

$app->post('/resetpassword', function(){
	$email    = isset($_POST['email']) ? $_POST['email'] : null;
	$password = isset($_POST['password']) ? $_POST['password'] : null;
	$personCt = new PersonController();
	$result = $personCt->resetPassword($email, $password);
	exit($result);
});

$app->post('/trytofindcep', function(){
	$cep      = isset($_POST['cep']) ? $_POST['cep'] : null;
	$personCt = new PersonController();
	$result = $personCt->findCEPData($cep);
	exit($result);
});

$app->post('/login', function(){
	$email    = isset($_POST['email']) ? $_POST['email'] : null;
	$password = isset($_POST['password']) ? $_POST['password'] : null;
	$personCt = new PersonController();
	$result = $personCt->login($email, $password);
	$result = json_decode($result, true);
	if(!$result['success']){
		$userTemplate = new League\Plates\Engine('Source/Resourses/UserViews');
		echo $userTemplate->render('login-area');
	}
	$_SESSION['viewMessage'] = ucfirst(translate('be welcome'));
	if(isset($_SESSION['history']) && !empty(json_decode($_SESSION['history'], true)[1])){
		$routeToGo = json_decode($_SESSION['history'], true)[1];
	}else{
		$routeToGo = '/'.URL['urlDomain'];
	}
	header('Location: '.$routeToGo);
	exit;
});

$app->get('/accountconfirmation/:emailwith:code', function($email, $code){
	$personCt = new PersonController();
	$result = $personCt->verifyAccountEmail($email, $code);
	exit($result);
});

// Person create/edit
$app->post('/person/save', function(){
	$personCt = new PersonController();
	// all parameters which it would use
	$parameters = [
		'name' => 'Nickolas',
		'lastName' => 'Alvaro Bini',
		'email' => 'tete@hotmail.com',
		'password' => 'nica21',
		'language' => '15',
		'country' => '32'
	];

	exit($personCt->save($parameters));
});

// remove a photo
$app->post('/person/removephoto', function(){
	$personCt = new PersonController();
    $id 		= isset($_POST['personId'])   ? $_POST['personId'] : null;
    $documentId = isset($_POST['documentId']) ? $_POST['documentId'] : null;
	$result = $personCt->removePhoto($id, $documentId);
	exit($result);
});

$app->get('/person/remove', function(){
	$personCt = new PersonController();
    $id = isset($_POST['id']) ? $_POST['id'] : null;
	$result = $personCt->remove($id);
	exit($result);
});

// View with my account data
$app->get('/myaccount', function(){
	if(!FunctionsClass::isPersonLoggedIn()){
		$_SESSION['messages'] = ucfirst(translate('please, log in first'));
		header('Location: /'.URL['urlDomain'].'/login');
		exit;
	}
	$personCt = new PersonController();
	$data = json_decode($personCt->retrieveUserAccount(), true);
	if(!$data['success']){
		$_SESSION['messages'] = ucfirst(translate('please, log in first'));
		header('Location: '.URL['urlDomain'].'/login');
		exit;	
	}
	$userTemplate = new League\Plates\Engine('Source/Resourses/UserViews');
	echo $userTemplate->render('my-account', [
		'title'   => ucfirst(translate('my account')),
		'content' => $data['content']
	]);
});

// getting cities
$app->get('/city/getcitiesofcountry', function(){
	$cityCt = new CityController();
	$content = $cityCt->getAllCountryCities();
	return json_encode([
		'success' => true,
		'content' => $content
	]);
});

// getting states of country
$app->post('/state/getstatesofcountry', function(){
	$countryId = isset($_POST['countryId']) ? $_POST['countryId'] : null;
	$stateCt = new StateController();
	$states = $stateCt->getStatesOfCountry($countryId);
	echo $states;
	return $states;
});

// getting cities of state
$app->post('/city/getcitiesofstate', function(){
	$stateId = isset($_POST['stateId']) ? $_POST['stateId'] : null;
	$cityCt = new CityController();
	$cities = $cityCt->getCitiesOfState($stateId);
	echo $cities;
	return $cities;
});

// getting cities of state
$app->post('/person/fetchprofessionalsformap', function(){
	$onlyThisRoles = isset($_POST['onlyThisRoles']) ? $_POST['onlyThisRoles'] : [];
	$personCt = new PersonController();
	$professionals = $personCt->fetchAllProfessionalOfThisState($onlyThisRoles);
	//$professionals = $personCt->fetchAllProfessionalOfThisCity($onlyThisRoles);
	echo $professionals;
	return $professionals;
});

// get personal page works
$app->post('/personalpage/getmyworks', function(){
	$personalPageId = isset($_POST['personalPageId']) ? $_POST['personalPageId'] : null;
	$personalPageCt = new PersonalPageController();
	$myWorks = $personalPageCt->getMyWorks($personalPageId);
	echo $myWorks;
	return $myWorks;
});

// messages routes
$app->post('/message/listmessages', function(){
	$messageCt = new MessageController();
	$myWorks = $messageCt->listMessages();
	echo $myWorks;
	return $myWorks;
});

$app->post('/message/save', function(){
	$messageCt = new MessageController();
	$myWorks = $messageCt->save();
	echo $myWorks;
	return $myWorks;
});

$app->post('/message/sendmessage', function(){
	$messageCt = new MessageController();
	$myWorks = $messageCt->sendMessage();
	echo $myWorks;
	return $myWorks;
});