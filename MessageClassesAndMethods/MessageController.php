<?php

namespace Source\Controllers;
use Source\Models\Message;
use Source\Models\Person;
use Source\Models\PNHPaginator;
use DateTime;

class MessageController
{
    /*
    lists messages accordingly to one of the people at it
    fetches alll messages and flag owner of message
    @param personId
    */
    public function listMessages()
    {
        $personId = isset($_POST['personId']) ? $_POST['personId'] : null;
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
        $pagerObj = new PNHPaginator();
        $pagerObj->paginate($totalOfMessages, 10, $page);
        $messages = $messageObj->getByPerson($personId, null, $pagerObj->limit(), $pagerObj->offset());
        if(!$message){
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

        /*
            saves message
            if both people have no conversation with eachother, creates a new, else increment
        */
        public function save()
        {
            $ownerOfMessageId = isset($_POST['ownerOfMessage']) ? $_POST['ownerOfMessage'] : null;
            $targetPersonId   = isset($_POST['targetPerson']) ? $_POST['targetPerson'] : null;
            $files            = isset($_POST['files']) ? $_POST['files'] : null;
            $messageText      = isset($_POST['messageText']) ? $_POST['messageText'] : null;
            if(is_null($ownerOfMessageId) || is_null($targetPersonId) || is_null($messageText)){
                return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('can not beggin a conversation'))
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
            $messageObj->setOwnerOfMessage($ownerOfMessage);
            $messageObj->setTargetPerson($targetPerson);
            $currentDateTime = new DateTime('Y-m-d H:i:s');
            $messageObj->setDateOfMessage($currentDateTime);
            if($files){
                $files = is_array($files) ? $files : [$files];
                $messageObj->setMessageFile($files);
            }
            $messageObj->setMessageText($messageText);
            $result = $messageObj->save();
            if(!$result){
                // try to remove created files
                
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
    }
}