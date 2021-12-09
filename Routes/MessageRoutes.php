<?php 
use Source\Controllers\MessageController;
use Source\Models\Message;
use Source\Models\Person;
use Source\Helpers\FunctionsClass;

$app->get('/messages', function(){
	$personObj = (new Person())->findById($_SESSION['personId']);
	$personData = $personObj->getFullData();
	$messageData = (new MessageController())->listMessages();
	$messageData = json_decode($messageData, true);
	$userTemplate = new League\Plates\Engine('Source/Resourses/UserViews');
	echo $userTemplate->render('messages-view', ['title' => ucfirst(translate('my messages')), 'person' => $personData, 'messages' => $messageData['messages'], 'fatherMessages' => $messageData['fatherElements']]);
});

$app->post('/message/checkifhasconversation', function(){
	$messageCt = new MessageController();
	$result = $messageCt->checkIfHasConversation();
	exit($result);
});

$app->post('/message/save', function(){
	$messageCt = new MessageController();
	$myWorks = $messageCt->save();
	echo $myWorks;
	return $myWorks;
});

$app->post('/message/listmessages', function(){
	$messageCt = new MessageController();
	$myWorks = $messageCt->listMessages();
	echo $myWorks;
	return $myWorks;
});

$app->post('/message/sendmessage', function(){
	$messageCt = new MessageController();
	$myWorks = $messageCt->sendMessage();
	echo $myWorks;
	return $myWorks;
});

$app->post('/message/fetchdatabyid', function(){
	$messageCt = new MessageController();
	$myWorks = $messageCt->fetchDataById();
	echo $myWorks;
	return $myWorks;
});

$app->post('/message/getfathermessagebypersonids', function(){
	$messageCt = new MessageController();
	$myWorks = $messageCt->getFatherMessageByPersonIds();
	echo $myWorks;
	return $myWorks;
});

$app->post('/message/setasseen', function(){
	$messageCt = new MessageController();
	$myWorks = $messageCt->setMessageAsSeen();
	echo $myWorks;
	return $myWorks;
});