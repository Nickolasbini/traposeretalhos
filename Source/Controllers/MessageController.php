<?php

namespace Source\Controllers;

use Source\Models\Message;
use Source\Models\Person;
use Source\Models\Document;
use CoffeeCode\Paginator\Paginator;
use DateTime;

class MessageController
{
    /*
    lists messages accordingly to one of the people at it
    fetches all messages and flag owner of message
    @param personId
    */
    public function listMessages()
    {
        $personId = isset($_SESSION['personId']) ? $_SESSION['personId'] : null;
        $page     = isset($_POST['page'])     ? $_POST['page'] : 1;
        if(is_null($personId)){
            return json_encode([
                'success' => false,
                'message' => ucfirst(translate('required parameters missing'))
            ]);
        }
        $personObj = new Person();
        $person = $personObj->findById($personId);
        if(!$person){
            return json_encode([
                'success' => false,
                'message' => ucfirst(translate('person not found'))
            ]);
        }
        $messageObj = new Message();
        $totalOfMessages = $messageObj->getByPerson($personId, true);
        if(!$totalOfMessages){
            return json_encode([
                'success' => false,
                'message' => ucfirst(translate('person has no conversations'))
            ]);
        }
        $pagerObj = new Paginator();
        $pagerObj->pager($totalOfMessages, 10, $page);
        $messages = $messageObj->getByPerson($personId, null, $pagerObj->limit(), $pagerObj->offset());
        if(!$messages){
            return json_encode([
                'success' => false,
                'message' => ucfirst(translate('person has no conversations'))
            ]);
        }
        $elements = [];
        foreach($messages as $message){
            $elements[] = [
                'id'             => $message->getId(),
                'ownerOfMessage' => $message->getOwnerOfMessage(true),
                'targetPerson'   => $message->getTargetPerson(true),
                'dateOfMessage'  => $message->getDateOfMessage(true),
            ];
        }
        // maybe parse to table
        return json_encode([
            'success'  => true,
            'message'  => ucfirst(translate('messages gathered')),
            'messages' => $elements
        ]);
    }

    /*
        saves message
        if both people have no conversation with eachother, creates a new, else increment
    */
    public function save()
    {
        $ownerOfMessageId = isset($_POST['ownerOfMessageId']) ? $_POST['ownerOfMessageId'] : null;
        $targetPersonId   = isset($_POST['targetPersonId']) ? $_POST['targetPersonId'] : null;
        $messageFile      = isset($_FILES['messageFile']) ? $_FILES['messageFile'] : null;
        $messageText      = isset($_POST['messageText']) ? $_POST['messageText'] : null;
        $fatherMessageId  = $_POST['fatherMessageId'];
        if(is_null($ownerOfMessageId) || is_null($targetPersonId) || (!$messageText && !$messageFile)){
            return json_encode([
                'success' => false,
                'message' => ucfirst(translate('can not beggin a conversation, missing data'))
            ]);
        }
        $personObj = new Person();
        $ownerOfMessage = $personObj->findById($ownerOfMessageId);
        if(!$ownerOfMessage){
            return json_encode([
                'success' => false,
                'message' => ucfirst(translate('can not beggin a conversation'))
            ]);
        }
        $targetPerson = $personObj->findById($targetPersonId);
        if(!$targetPerson){
            return json_encode([
                'success' => false,
                'message' => ucfirst(translate('can not beggin a conversation'))
            ]);
        }

        $messageObj = new Message();
        if(!is_null($fatherMessageId)){
            $fatherMessageObj = $messageObj->findById($fatherMessageId);
            if(!$fatherMessageObj){
                return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('can not beggin a conversation'))
                ]); 
            }
            $messageObj->setFatherMessage($fatherMessageObj->getId());
        }else{
            $messageObj->setFirstMessage(true);
        }

        $messageObj->setOwnerOfMessage($ownerOfMessage->getId());
        $messageObj->setTargetPerson($targetPerson->getId());
        $currentDateTime = (new DateTime())->format('Y-m-d H:i:s');
        $messageObj->setDateOfMessage($currentDateTime);
        $documentId = null;
        if($messageFile){
            $response = $messageObj->setMessageFile($messageFile);
            if(!$response){
                return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('could not send file'))
                ]); 
            }
            $documentId = $response[0];
        }
        $messageObj->setMessageText($messageText);
        $result = $messageObj->save();
        if(!$result){
            // try to remove created files
            if($documentId){
                $documentObj = (new Document())->findById($documentId);
                if($documentObj)
                    $documentObj->destroy();
            }
            return json_encode([
                'success' => false,
                'message' => ucfirst(translate('message was not sent'))
            ]);
        }
        return json_encode([
            'success' => true,
            'message' => ucfirst(translate('message sent'))
        ]);
    }

    /*
        Start a conversation by creating a fatherMessage which will be father of the
        next message
    */
    public function sendMessage()
    {
        $sessionPersonId = isset($_SESSION['personId']) ? $_SESSION['personId'] : null;
        $targetPersonId  = isset($_POST['targetPersonId']) ? $_POST['targetPersonId'] : null;
        if(is_null($sessionPersonId) || is_null($targetPersonId)){
            return json_encode([
                'success' => false,
                'message' => ucfirst(translate('required parameters missing'))
            ]);
        }
        $personObj = new Person();
        $ownerOfMessage = $personObj->findById($sessionPersonId);
        if(!$ownerOfMessage){
            return json_encode([
                'success' => false,
                'message' => ucfirst(translate('can not beggin a conversation'))
            ]);
        }
        $targetPerson = $personObj->findById($targetPersonId);
        if(!$targetPerson){
            return json_encode([
                'success' => false,
                'message' => ucfirst(translate('can not beggin a conversation'))
            ]);
        }
        $messageObj = new Message();
        $allreadyExists = $messageObj->verifyExistence($sessionPersonId, $targetPersonId);
        $_POST['ownerOfMessageId'] = $sessionPersonId;
        if($allreadyExists){
            $_POST['fatherMessageId'] = $allreadyExists->getId();
        }else{
            $_POST['fatherMessageId'] = null;
        }
        $response = $this->save();
        exit($response);

    }
}