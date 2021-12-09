<?php

namespace Source\Models;

use Source\Helpers\FunctionsClass;

use CoffeeCode\DataLayer\DataLayer;
use DateTime;

/**
 * 
 */
class Document extends DataLayer
{
	
	function __construct()
	{
		parent::__construct('documents', ['directory','path', 'name'], 'id', false);
	}

	// SETTERS
	public function setDirectory($directory){
		$this->directory = $directory;
	}
	public function setPath($path){
		$this->path = $path;
	}
	public function setName($name){
		$this->name = $name;
	}
	public function setWebPath($webPath){
		$this->webPath = $webPath;
	}

	// GETTERS
	public function getId(){
		return $this->id;
	}
	public function getDirectory(){
		return $this->directory;
	}
	public function getPath(){
		return $this->path;
	}
	public function getName(){
		return $this->name;
	}
	public function getWebPath(){
		return $this->webPath;
	}

	// saves all sent photos and retunrs the path for it
	public function saveFiles($photo = null, $directoryName = null)
	{
		if($photo){
			$response = [];
			$data = explode(',', $photo);
			if(!is_array($data) || count($data) == 1){
				return [];
			}
			$info = $data[0];
			$extension = str_replace(['data:image/', ';base64'], '', $info);
			$photo = $data[1];

			$imageData = base64_decode($photo);
			$source = imagecreatefromstring($imageData);
			
			$currentDateTime = strtotime((new DateTime())->format('Y-m-dh:i:s'));
			// generating a random file name
			$fileName = FunctionsClass::generateRandomValue().'-'.$currentDateTime.'.jpeg';
			$dir = $directoryName ? $directoryName.'/' : '';
			if($dir && !file_exists(TMPPATH['images'].$dir)){
				mkdir(TMPPATH['images'].$dir);
			}
			$filePath = TMPPATH['images'].$dir.$fileName;
			$done = imagejpeg($source, $filePath);
			if($done){
				$response[] = [
					'directory' => $dir,
					'path'		=> $filePath,
					'webPath'   => URL['webPath'].TMPPATH['imagesSystemPath'].$dir.$fileName,
					'name'		=> $fileName
				];
			}
			imagedestroy($source);
			return $response;
		}

		$photos = $_FILES;
		$allowedFormats = ['png' , 'jpeg', 'web', 'gif'];
		$personId = isset($_SESSION['personId']) ? $_SESSION['personId'] : '';
		if(!is_dir(TMPPATH['images']))
			mkdir(TMPPATH['images']);
		$response = [];
		foreach($photos as $photo){
			if(is_null($photo))
				continue;
			// getting former size
			list($oldWidth, $oldHeight) = getimagesize($photo['tmp_name']);
			$extension = $photo['type'];
			$extension = substr($extension, strpos($extension, '/') + 1);
			if(!in_array($extension, $allowedFormats))
				continue;
			// Creating new thumb image
			
			// cheking by the extension
			switch ($extension) {
				case 'png':
					$oldImage = imagecreatefrompng($photo['tmp_name']);
					break;
				case 'jpeg':
					$oldImage = imagecreatefromjpeg($photo['tmp_name']);
					break;
				case 'web':
					$oldImage = imagecreatefromwbmp($photo['tmp_name']);
					break;
				case 'gif':
					$oldImage = imagecreatefromgif($photo['tmp_name']);
					break;
				default:
					$oldImage = imagecreatefromstring($photo['tmp_name']);
					break;
			}
			imagecopyresampled($newImage, $oldImage, 0, 0, 0, 0, 256, 256, $oldWidth, $oldHeight);
			
			// generating a random file name
			$fileName = FunctionsClass::generateRandomValue().'-'.$personId.'.jpeg';
			$filePath = TMPPATH['images'].$fileName;
			$done = imagejpeg($newImage, $filePath);
			if($done){
				$response[] = [
					'directory' => 'img',
					'path'		=> TMPPATH['images'],
					'webPath'   => URL['webPath'].TMPPATH['imagesSystemPath'].$dir.$fileName,
					'name'		=> $fileName
				];
			}
			imagedestroy($oldImage);
			imagedestroy($newImage);
		}
		return $response;
	}

	// save documents from return of saveFiles method, which saves the file
	// photo sent must be of type base64_encoded with the html tag on it: data:image/;base64
	// and return its path
	public function saveDocuments($photoToSave = null, $directoryName = null)
	{
		$results = $this->saveFiles($photoToSave, $directoryName);
		$documentsIds = [];
		foreach($results as $data){
			$documentObj = new Document();
			$documentObj->setDirectory($data['directory']);
			$documentObj->setPath($data['path']);
			$documentObj->setName($data['name']);
			$documentObj->setWebPath($data['webPath']);
			$saveResult = $documentObj->save();
			if($saveResult)
				$documentsIds[] = $documentObj->data->id;			
		}
		return $documentsIds;
	}

	// retunr full path
	public function getPhotoFullPath()
	{
		$path = $this->getPath();
		$photoName = $this->getName();
		return $path.$photoName;
	}

	public function getPhotoWebPath()
	{
		return $this->getWebPath();
	}

	public function getFullData()
	{
		$elements = [
			'id' 		=> $this->getId(),
			'directory' => $this->getDirectory(),
			'path' 		=> $this->getPath(),
			'name' 		=> $this->getName(),
			'webPath' 	=> $this->getWebPath(),
		];
		return $elements;
	}

	public function fetchDefaultPhotoElement()
	{
		$documentObj = $this->find("name = :name", "name=defaultPersonalPageBackgroundPhotoOfSystem")->limit(1)->fetch(true);
		if(!$documentObj){
			return $this->createDefaultPersonalPageBackgroundPhoto();
		}else{
			return $documentObj[0]->getId();
		}
	}

	// create the default personal page background photo
	public function createDefaultPersonalPageBackgroundPhoto()
	{
		$documentObj = new Document();
		$documentObj->setDirectory('img/default');
		$documentObj->setPath(TMPPATH['images']);
		$documentObj->setName('default-background.webp');
		$documentObj->setWebPath(URL['webPath'].TMPPATH['imagesSystemPath'].'default/default-background.webp');
		$saveResult = $documentObj->save();
		if(!$saveResult){
			$id = null;
		}else{
			$id = $documentObj->data->id;
		}
		return $id;
	}

	// remove the document obj and the physical file
	public function removeDocument()
	{
		$pathToFile = $this->getPath() . $this->getName();
		if(file_exists($pathToFile)){
			unlink($pathToFile);
		}
		return $this->destroy();
	}
}