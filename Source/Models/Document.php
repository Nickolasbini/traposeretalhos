<?php

namespace Source\Models;

use Source\Helpers\FunctionsClass;

use CoffeeCode\DataLayer\DataLayer;

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
	public function saveFiles()
	{
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
			$newImage = imagecreatetruecolor(256, 256);
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
			$image = imagejpeg($newImage);
			// generating a random file name
			$fileName = FunctionsClass::generateRandomValue().'-'.$personId.'jpeg';
			$done = file_put_contents(TMPPATH['images'].$fileName, $image);
			if($done){
				$response[] = [
					'directory' => 'img',
					'path'		=> TMPPATH['images'],
					'webPath'   => URL['webPath'].TMPPATH['imagesSystemPath'].$name,
					'name'		=> $fileName
				];
			}
			imagedestroy($oldImage);
			imagedestroy($newImage);
		}
		return $response;
	}

	// save documents from return of saveFiles method, which saves the file
	// and return its path
	public function saveDocuments()
	{
		$results = $this->saveFiles();
		$documentsIds = [];
		foreach($results as $data){
			$documentObj = new Document();
			$documentObj->setDirectory($data['directory']);
			$documentObj->setPath($data['path']);
			$documentObj->setName($data['name']);
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
}