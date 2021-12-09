<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;

use Source\Models\Person;
use DateTime;
use Source\Models\MessageFile;
use Source\Models\Document;
use Source\Helpers\FunctionsClass;

class Message extends DataLayer
{
    function __construct()
    {
        parent::__construct('messages', ['ownerOfMessage','targetPerson'], 'id', false);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getOwnerOfMessage($asObject = false)
    {
        if($asObject){
            $personObj = (new Person())->findById($this->ownerOfMessage);
            return $personObj->getFullData();
        }
        return $this->ownerOfMessage;
    }

    public function setOwnerOfMessage($personId)
    {
        $this->ownerOfMessage = $personId;
    }

    public function getDateOfMessage($format = null)
    {
        if($format){
            $date = $this->dateOfMessage;
            if(!$date instanceof DateTime){
                $date = FunctionsClass::convertDateStringToDateTimeObject($date);
            }
            // check the date format by language with a Config.php meethod
            $date = $date->format('Y-m-d H:i:s');
            return $date; 
        }
        return $this->dateOfMessage;
    }

    // check if it needs to be an object datetime
    public function setDateOfMessage($dateOfMessage)
    {
        $this->dateOfMessage = $dateOfMessage;
    }

    public function getTargetPerson($asObject = false)
    {
        if($asObject){
            $personObj = (new Person())->findById($this->targetPerson);
            return $personObj->getFullData();
        }
        return $this->targetPerson;
    }

    public function setTargetPerson($personId)
    {
        $this->targetPerson = $personId;
    }

    public function getFirstMessage()
    {
        return $this->firstMessage;
    }

    public function setFirstMessage($firstMessage)
    {
        $this->firstMessage = $firstMessage;
    }

    public function getMessageText()
    {
        return $this->messageText;
    }

    public function setMessageText($messageText)
    {
        $this->messageText = $messageText;
    }

    public function getFatherMessage($asObject = false)
    {
        if($asObject){
            $fatherMessageObj = (new Message())->findById($this->fatherMessage);
            return $fatherMessageObj;
        }
        return $this->fatherMessage;
    }

    public function setFatherMessage($messageId)
    {
        $this->fatherMessage = $messageId;
    }

    // returns an array of documentObj with this message
    public function getMessageFile($asObject = null)
    {
        if($asObject){
            $documentObj = (new Document)->findById($this->messageFile);
            return $documentObj;
        }
        return $this->messageFile;
    }

    public function setHasSeen($HasSeen)
    {
        $this->HasSeen = $HasSeen;
    }

    public function getHasSeen()
    {
        return $this->hasSeen;
    }

    /*
        creates Document obj and link it
        try to return the id
    */
    public function setMessageFile($fileObj)
    {
        if(!$fileObj)
            return null;
        $documentObj = new Document();
        $documentIds = $documentObj->saveDocuments();
        $response = $this->messageFile = $documentIds[0];
        return $response;
    }

    // gets MessageObj by person Id
    // fetches by ownerOfMessage or targetPerson
    // here it must not bring repeated one, it may not do that since a person can't be both Owner and Target, so its okay
    // order by dateOfMessage
    public function getByPerson($personId, $total = null, $limit = null, $offset = null)
    {
        // addapt to be paginated
        if($total){
            $messageObj = $this->find("ownerOfMessage = :personId or targetPerson = :personId", "personId=$personId")->count();
        }else{
            $messageObj = $this->find("ownerOfMessage = :personId or targetPerson = :personId", "personId=$personId")->order('dateOfMessage ASC')->limit($limit)->offset($offset)->fetch(true);
        }
        return $messageObj;
    }

    public function getAllMessagesOfPerson($personId, $total = null, $limit = null, $offset = null)
    {
        // addapt to be paginated
        if($total){
            $messageObj = $this->find("targetPerson = :personId", "personId=$personId")->count();
        }else{
            $messageObj = $this->find("targetPerson = :personId", "personId=$personId")->order('dateOfMessage ASC')->limit($limit)->offset($offset)->fetch(true);
        }
        return $messageObj;
    }

    /*
        checks if there is already a message like parameters
    */
    public function verifyExistence($ownerOfMessageId = null, $targetPersonId = null)
    {
        $messageObj = $this->find("(ownerOfMessage = :ownerOfMessageId and targetPerson = :targetPersonId) or (ownerOfMessage = :targetPersonId and targetPerson = :ownerOfMessageId)",
            "ownerOfMessageId=$ownerOfMessageId&targetPersonId=$targetPersonId")->fetch(true);
        return $messageObj ? $messageObj[0] : null;
    }

    // maybe will not use this method
    // try to find a created message between both people
    // return the object if found a occorence
    // @param 'firstResult' means the first message to be initiated, on false gets the more recent
    public function verifyExistenceOld($parameters, $firstResult = true){
        $ownerOfMessageId = $parameters['ownerOfMessage'];
        $targetPersonId   = $parameters['targetPerson'];
        $messageObj = 'where (ownerOfMessage = $ownerOfMessageId and targetPerson = $targetPersonId) || (ownerOfMessage = $targetPersonId and targetPerson = $ownerOfMessageId)';
        if($firstResult){
            // and where firstMessage = true;
        }else{
            // order by dateOfMessage ASC
        }
        return $messageObj;
    }

    public function getMessagesByFather()
    {
        $personId = isset($_SESSION['personId']) ? $_SESSION['personId'] : null;
        if(!$personId){
            return null;
        }
        $messageObj = $this->find("ownerOfMessage = :ownerOfMessageId and fatherMessage is null","ownerOfMessageId=$personId")->order("dateOfMessage DESC")->fetch(true);
        return $messageObj ? $messageObj[0] : null;
    }

    public function getChildren($orderBy = 'ASC'){
        $fatherMessageId = $this->getId();
        $messageObj = $this->find("fatherMessage = :fatherMessageId","fatherMessageId=$fatherMessageId")->order("dateOfMessage $orderBy")->fetch(true);
        return $messageObj ? $messageObj : null;
    }

    public function getLastChild(){
        $fatherMessageId = $this->getId();
        $messageObj = $this->find("fatherMessage = :fatherMessageId","fatherMessageId=$fatherMessageId")->order("dateOfMessage DESC")->limit(1)->fetch(true);
        return $messageObj ? $messageObj[0] : null;
    }

    public function getFullData($gatherObjects = true)
    {
        $element = [
            'id'             => $this->getId(),
            'ownerOfMessage' => $this->getOwnerOfMessage($gatherObjects),
            'targetPerson'   => $this->getTargetPerson($gatherObjects),
            'firstMessage'   => $this->getFirstMessage(),
            'fatherMessage'  => $this->getFatherMessage(),
            'messageText'    => $this->getMessageText(),
            'messageFile'    => $this->getMessageFile(),
            'dateOfMessage'  => $this->getDateOfMessage($gatherObjects),
            'hasSeen'        => $this->getHasSeen()
        ];
        return $element;
    }

    public function locateFatherMessageByPersonIds($personId, $personToLocate)
    {
        $messageObj = $this->find(
            "((ownerOfMessage = :personId and targetPerson = :personToLocate) or (ownerOfMessage = :personToLocate and targetPerson = :personId)) and fatherMessage is null","personId=$personId&personToLocate=$personToLocate"
        )->fetch(true);
        return $messageObj ? $messageObj[0] : null;
    }
}