<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;

use Source\Models\Person;
use DateTime;
use Source\Models\MessageFile;
use Source\Models\Document;

class MessageFile extends Datalayer
{
    function __construct()
    {
        parent::__construct('messagefiles', ['message','document'], 'id', false);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id($id);
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message($message);
    }

    public function getDocument($asObject = false)
    {
        if($asObject){
            $documentObj = (new Document)->findById($this->document);
            return $documentObj->getFullData();
        }
        return $this->document;
    }

    public function setDocument($document)
    {
        $this->document($document);
    }

    // gets by message id
    public function getByMessageId($messageId)
    {
        $messageFileObj = '';
        return $messageFileObj;
    }
}