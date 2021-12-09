<?php

namespace Source\Controllers;

use Source\Helpers\FunctionsClass;
use Source\Models\PersonalPage;
use Source\Models\PersonRole;
use Source\Models\Document;

/**
 * 
 */
class PersonalPageController
{
	public function getMyWorks()
     {
          $personalPageId = isset($_POST['personalPageId']) ? $_POST['personalPageId'] : null;
          if(is_null($personalPageId)){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('required parameters missing'))
               ]);
          }
          $personalPageObj = new PersonalPage();
          $personalPage = $personalPageObj->findById($personalPageId);
          if(!$personalPage){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('invalid id'))
               ]);    
          }
          $myWorks = $personalPage->getMyWorks(true);
          if(is_null($myWorks)){
               return json_encode([
                    'success'  => true,
                    'message'  => ucfirst(translate('works gathered')),
                    'hasWorks' => false
               ]); 
          }
          $documentObj = new Document();
          $elements = [];
          foreach($myWorks as $work){
               $document = $documentObj->findById($work['documentId']);
               if(!$document)
                    continue;
               $elements[] = [
                    'documentId' => $work['documentId'],
                    'title'      => $work['description'],
                    'webPath'    => $document->getWebPath()
               ];
          }
          return json_encode([
               'success'  => true,
               'message'  => ucfirst(translate('works gathered')),
               'hasWorks' => $elements
          ]);
     }

     // adds a work to this personal page
     public function addWork()
     {
          $description = isset($_POST['description']) ? $_POST['description'] : null;
          $photo       = isset($_POST['photo']) ? $_POST['photo'] : null;
          $personId    = isset($_SESSION['personId']) ? $_SESSION['personId'] : null; 
          if(!$personId){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('log in'))
               ]);
          }
          if(!$photo || !is_numeric(strpos($photo, 'data:image'))){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('required parameters missing'))
               ]);
          }
          $personRoleObj = new PersonRole();
          $personRole = $personRoleObj->getPersonRoleByPerson($personId);
          if(!$personRole){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('no personal page found'))
               ]);
          }
          $personalPage = $personRole->getPersonalPage(true);
          $myWorks = $personalPage->getMyWorks();
          $myWorks = $myWorks ? json_decode($myWorks, true) : null;
          if(!is_array($myWorks)){
               $myWorks = [];
          }

          $documentObj = new Document();
          $result = $documentObj->saveDocuments($photo, 'personalPage');
          $resultId = $result && is_array($result) ? $result[0] : null;
          if(!$resultId){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('invalid photo'))
               ]);
          }
          $myWorks[] = [
               'documentId'  => $resultId,
               'description' => $description 
          ];
          $personalPage->setMyWorks(json_encode($myWorks));
          $result = $personalPage->save();
          if(!$result){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('an error occured'))
               ]);
          }
          return json_encode([
               'success' => true,
               'message' => ucfirst(translate('new photo added to personal page'))
          ]);
     }

     // removes a work from a personal page
     public function removeWork()
     {
          $personId       = isset($_SESSION['personId'])    ? $_SESSION['personId']    : null;
          $personalPageId = isset($_POST['personalPageId']) ? $_POST['personalPageId'] : null;
          $documentId     = isset($_POST['documentId'])     ? $_POST['documentId']     : null;
          if(!$personalPageId || !$documentId || !$personId){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('required parameters missing'))
               ]);
          }
          $personRoleObj = new PersonRole();
          $personRole = $personRoleObj->getPersonByPersonalPage($personalPageId);
          if(!$personRole || ($personRole->getPerson() != $personId)){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('you do not have permission to do this'))
               ]);
          }
          $personalPage = $personRole->getPersonalPage(true);
          $myWorks = $personalPage->getMyWorks();
          $myWorks = $myWorks ? json_decode($myWorks, true) : null;
          if(!$myWorks){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('nothing to remove'))
               ]);
          }
          $position = 0;
          foreach($myWorks as $workData){
               if($workData['documentId'] == $documentId){
                    $documentObj = new Document();
                    $document = $documentObj->findById($documentId);
                    if($document){
                         $document->removeDocument();
                    }
                    break;
               }
               $position++;
          }
          if($position > count($myWorks) - 1){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('an error occured'))
               ]);
          }
          unset($myWorks[$position]);
          $myWorks = array_values($myWorks);
          $personalPage->setMyWorks(json_encode($myWorks));
          $result = $personalPage->save();
          if(!$result){
               return json_encode([
                    'success' => false,
                    'message' => ucfirst(translate('an error occured'))
               ]);
          }
          return json_encode([
               'success' => true,
               'message' => ucfirst(translate('removed with success')),
               'content' => $myWorks
          ]);
     }
}