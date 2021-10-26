<?php

namespace Source\Models;

use Source\Models\Person;
use DateTime;
use Source\Models\MessageFile;
use Source\Models\Document;

class Message extends Datalayer
{
    private $id;
    private $ownerOfMessage;
    private $dateOfMessage;
    private $targetPerson;
    private $firstMessage;
    private $messageText;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id($id);
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
        $this->ownerOfMessage($personId);
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

    // returns an array of documentObj with this message
    public function getMessageFiles($messageId)
    {
        if(!$messageId)
            return [];
        $messageFilesObj = new MessageFile();
        $messageFiles = $messageFilesObj->getByMessageId($messageId, true);
        if(!$messageFiles)
            return [];
        return $messageFiles;
    }

    /*
        creates Document obj and link it to messageFile Obj 
        param <array> of images object
        try to return the id
    */
    public function setMessageFile($filesArray = null)
    {
        if(!$filesArray || !is_array($filesArray))
            return null;
        $documentObj = new Document();
        $response = null;
        foreach($filesArray as $file){
            // first create Document obj

            $messageFileObj = new MessageFile();
            $messageFileObj->setMessage($this->getId());
            $messageFileObj->setDocument($result['id']);
            
            $response = $messageFileObj->save();
        }
        return $response;
    }

    // gets MessageObj by person Id
    // fetches by ownerOfMessage or targetPerson
    // here it must not bring repeated one, it may not do that since a person can't be both Owner and Target, so its okay
    // order by dateOfMessage
    public function getByPerson($personId, $total = null, $limit = null, $offset = null)
    {
        // addapt to be paginated
        $messageObj = 'where ownerOfMessage = $personId or targetPerson = $personId';
        return $messageObj;
    }

    // get by owner
    public function getByOwner($ownerId)
    {
        $messageObj = 'where ownerOfMessage = $ownerId';
        return $messageObj;
    }

    // maybe will not use this method
    // try to find a created message between both people
    // return the object if found a occorence
    // @param 'firstResult' means the first message to be initiated, on false gets the more recent
    public function verifyExistence($parameters, $firstResult = true){
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



    /**
     * convert date string to dateTime object
     * Obs: enter a date string in ISO or Y-m-d format
     * @param	<string> $dateString
     * @return DateTime|null
     */
    public static function convertDateStringToDateTimeObject($dateString)
    {
        if(is_null($dateString)){
            return null;
        }
        
        try{
            $dateTime = new DateTime($dateString);
            return $dateTime;
        }catch(Exception $e){
            return null;
        }
    }

    /**
     * convert dateTime object to dateIso
     * @param	<DateTime> $dateTime
     * @return string|null
     */
    public static function convertDateTimeObjectToDateIso($dateTime)
    {
        if($dateTime instanceof DateTime){
            return $dateTime->format(DateTime::ISO8601);
        }else{
            return null;
        }
    }
}