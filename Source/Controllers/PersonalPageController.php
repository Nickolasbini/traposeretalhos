<?php

namespace Source\Controllers;

use Source\Helpers\FunctionsClass;
use Source\Models\PersonalPage;
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
                    'title'   => $work['description'],
                    'webPath' => $document->getWebPath()
               ];
          }
          return json_encode([
               'success'  => true,
               'message'  => ucfirst(translate('works gathered')),
               'hasWorks' => $elements
          ]);
     }
}