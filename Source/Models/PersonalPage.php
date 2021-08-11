<?php

namespace Source\Models;

use CoffeeCode\DataLayer\DataLayer;

/**
 * 
 */
class PersonalPage extends DataLayer
{
	const BASE_URL = 'person/personalpage/identification';

	function __construct()
	{
		parent::__construct('personalpages', [], 'id', false);
	}

	// SETTERS
	public function setCompanyUnit($companyUnit){
		$this->companyUnit = $companyUnit;
	}
	public function setTemplatePersonalPage($templatePersonalPage){
		$this->templatePersonalPage = $templatePersonalPage;
	}
	public function setIsTemplate($isTemplate){
		$this->isTemplate = $isTemplate ;
	}
	public function setCustomCSS($customCSS){
		$this->customCSS = $customCSS;
	}
	public function setCustomHTML($customHTML){
		$this->customHTML = $customHTML;
	}
	public function setPageURL($pageURL){
		$this->pageURL = $pageURL;
	}
	public function setQrCode($qrCode){
		$this->qrCode = $qrCode;
	}

	// GETTERS
	public function getId(){
		return $this->id;
	}
	public function getCompanyUnit(){
		return $this->companyUnit;
	}
	public function getTemplatePersonalPage(){
		return $this->templatePersonalPage;
	}
	public function getIsTemplate(){
		return $this->isTemplate;
	}
	public function getCustomCSS(){
		return $this->customCSS;
	}
	public function getCustomHTML(){
		return $this->customHTML;
	}
	public function getPageURL(){
		return $this->pageURL;
	}
	public function getQrCode(){
		return $this->qrCode;
	}

	// Create PersonalPage with default configuration and return its id
	public function createURL($personRoleId = null)
	{
		$this->setpageURL(PersonalPage::BASE_URL.base64_encode($personRoleId));
		$this->setIsTemplate(false);
		$this->setTemplatePersonalPage(1);
		$result = $this->save();
		return !is_null($result) ? $this->data->id : null;
	}
}