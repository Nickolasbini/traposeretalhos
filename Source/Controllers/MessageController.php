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
        $isProfessional = isset($_SESSION['userRole']) ? $_SESSION['userRole'] : null;
        if($isProfessional){
            $totalOfMessages = $messageObj->getAllMessagesOfPerson($personId, true);
        }else{
            $totalOfMessages = $messageObj->getByPerson($personId, true);
        }
        if(!$totalOfMessages){
            return json_encode([
                'success' => false,
                'message' => ucfirst(translate('person has no conversations'))
            ]);
        }
        $pagerObj = new Paginator();
        $pagerObj->pager($totalOfMessages, 10, $page);
        if($isProfessional){
            $messages = $messageObj->getAllMessagesOfPerson($personId, null, $pagerObj->limit(), $pagerObj->offset());
        }else{
            $messages = $messageObj->getByPerson($personId, null, $pagerObj->limit(), $pagerObj->offset());
        }
        if(!$messages){
            return json_encode([
                'success' => false,
                'message' => ucfirst(translate('person has no conversations'))
            ]);
        }
        $elements = [];
        $fatherElements = [];
        foreach($messages as $message){
            $element = [
                'id'             => $message->getId(),
                'ownerOfMessage' => $message->getOwnerOfMessage(true),
                'targetPerson'   => $message->getTargetPerson(true),
                'firstMessage'   => $message->getFirstMessage(),
                'fatherMessage'  => $message->getFatherMessage(),
                'messageText'    => $message->getMessageText(),
                'messageFile'    => $message->getMessageFile(),
                'dateOfMessage'  => $message->getDateOfMessage(true),
            ];
            $elements[] = $element;
            if(is_null($message->getFatherMessage())){
                $lastChild = $message->getLastChild();
                $element['child'] = [];
                if($lastChild){
                    $element['child'] = [
                        'messageText'   => $lastChild->getMessageText(),
                        'dateOfMessage' => $lastChild->getDateOfMessage()
                    ];
                }
                $fatherElements[] = $element; 
            } 
        }
        // maybe parse to table
        return json_encode([
            'success'        => true,
            'message'        => ucfirst(translate('messages gathered')),
            'messages'       => $elements,
            'fatherElements' => $fatherElements
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
        $hasPhoto         = isset($_POST['hasPhoto']) ? $_POST['hasPhoto'] : null;
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
        return $response;
    }

    public function checkIfHasConversation()
    {
        $sessionPersonId = isset($_SESSION['personId'])    ? $_SESSION['personId']    : null;
        $targetPersonId  = isset($_POST['targetPersonId']) ? $_POST['targetPersonId'] : null;
        if(!$sessionPersonId || !$targetPersonId){
            return json_encode([
                'success' => false,
                'message' => ucfirst(translate('no data to check'))
            ]);
        }
        $messageObj = new Message();
        $exists = $messageObj->verifyExistence($sessionPersonId, $targetPersonId);
        return json_encode([
            'success' => true,
            'message' => ucfirst(translate('data gathered')),
            'hasMessage' => $exists ? true : false,
            'messageId'  => $exists ? $exists->getFatherMessage(true)->getId() : null
        ]);
    }

    public function fetchDataById($fullChildData = true)
    {
        $fatherMessageId = isset($_POST['fatherMessageId']) ? $_POST['fatherMessageId'] : null;
        $returnObj       = isset($_POST['returnObj']) ? $_POST['returnObj'] : null;
        if(!$fatherMessageId){
            return json_encode([
                'success' => false,
                'message' => ucfirst(translate('no message id sent'))
            ]);
        }
        $messageObj = new Message();
        $message = $messageObj->findById($fatherMessageId);
        if($message->getFatherMessage()){
            return json_encode([
                'success' => false,
                'message' => ucfirst(translate('invalid message type'))
            ]);
        }
        $data[] = $message->getFullData(true);
        $children = $message->getChildren();
        if($returnObj){
            $response = [
                'father'   => $message,
                'children' => $children
            ];
            return $response;
        }
        if(!is_null($children)){
            foreach($children as $child){
                $data[] = $child->getFullData($fullChildData);
            }
        }

        return json_encode([
            'success'          => true,
            'message'          => ucfirst(translate('messages gathered')),
            'numberOfMessages' => count($data),
            'messages'         => $data
        ]);
    }

    // fetches all Messages by the father message id and set them all to 'hasSeen = true' if it belongs to session PersonId
    public function setMessageAsSeen()
    {
        $_POST['returnObj'] = true;
        $result = $this->fetchDataById(null);
        $objects = [];
        $objects[] = $result['father'];
        if($result['children']){
            $objects = array_merge($objects, $result['children']);
        }
        $updatedMessages = 0;
        foreach($objects as $messageObject){
            $targetPersonId = $messageObject->getTargetPerson();
            if($targetPersonId != $_SESSION['personId'])
                continue;
            $messageObject->setHasSeen(true);
            $messageObject->save();
            $updatedMessages++;
        }
        return json_encode([
            'success'         => true,
            'message'         => 'done',
            'messagesUpdated' => $updatedMessages
        ]);
    }

    public function getFatherMessageByPersonIds()
    {
        $personToLocate = isset($_POST['personToLocate']) ? $_POST['personToLocate'] : null;
        $personId       = isset($_SESSION['personId'])    ? $_SESSION['personId']       : null;
        if(!$personToLocate || !$personId){
            return json_encode([
                'success' => false,
                'message' => ucfirst(translate('required parameters missing'))
            ]);
        }
        $messageObj = new Message();
        $message = $messageObj->locateFatherMessageByPersonIds($personId, $personToLocate);
        return json_encode([
            'success' => $message ? true : false,
            'message' => ucfirst(translate('finished procedure')),
            'content' => ($message ? $message->getFullData(false) : null)
        ]);
    }
}