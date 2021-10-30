<?php

namespace Source\Controllers;

use Source\Models\Country;
use Source\Models\State;
use Source\Models\City;
use Source\Models\Translation;
use Source\Helpers\FunctionsClass;
use DOMDocument;

/**
 * 
 */
class CountryController{

	/**
     * Synchronize countries by creating a new country if necessary
     * @author NAB
     * @version 1.0 - 20201202
     * @return <array> 'newCountries' the number of created | 'countriesFound' the number of countries already on BD 
     */
    public function syncCountriesFromXML()
    {
        ini_set('max_execution_time', '3600');
        $xmlPath = 'Source\Files\countries.xml';
        $xmlString = file_get_contents($xmlPath);
        $xml = simplexml_load_string($xmlString);
        $json = json_encode($xml);
        $xml = json_decode($json,TRUE);
        $xml = array_values($xml);
        $reports = [
            'newCountries'   => 0,
            'countriesFound' => 0,
            'total'          => 0
        ];
        foreach($xml[0] as $xmlCountryData){
        	$reports['total']++;
            $country = $xmlCountryData['@attributes'];
            $name       = strtolower($country['name']);
            $alphaCode2 = $country['alpha-2'];
            $alphaCode3 = $country['alpha-3'];
            $region     = strtolower($country['region']);

            $name = utf8_decode(utf8_encode($name));
            $countryWk = new Country();
            $countryObj = $countryWk->getCountryByAlphaCode($alphaCode2);
            if(!$countryObj){
                $countryWk->setName($name);
                $countryWk->setAlphaCode2($alphaCode2);
                $countryWk->setAlphaCode3($alphaCode3);
                $countryWk->setRegion($region);
                $result = $countryWk->save();
                if(!$result){
                    // send email to admin
                    echo 'an error occured';
                    continue;
                }
                $reports['newCountries']++;
            }else{
                $reports['countriesFound']++;
            }
        }

        return json_encode($reports);
    }

    /**
     * Synchronize states and cities of all Countries in DB
     * @author  NAB - 20210405
     * @version 1 - initial release
     * @return <array> keys: <int>    newCountryState
     *                         <array> newCountryStateIds
     *                         <int>   newCountryCity
     *                         <array> newCountryCityIds
     */
    public function syncWithUnece()
    {
        ini_set('max_execution_time', '3600');
        $baseRequestURL = "https://unece.org/trade/cefact/unlocode-code-list-country-and-territory";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseRequestURL);
        curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $answer = curl_exec($ch);
        if(curl_errno($ch))
            exit('error');
        $pageResults = new DOMDocument();
        libxml_use_internal_errors(true);
        $pageResults->loadHTML($answer);
        $table = $pageResults->getElementsByTagName('table')->item(0);
        $as = $table->getElementsByTagName('a');
        $countryObj = new Country();
        $report = [
            'newCountryState'    => 0,
            'newCountryCity'     => 0,
        ];
        $newCityIds  = [];
        $newStateIds = [];
        foreach($as as $a){
            $href = $a->getAttribute('href');
            $parentTd = $a->parentNode->parentNode;
            $countryISO = $parentTd->childNodes->item(0)->nodeValue;
            if(is_null($href) || is_null($countryISO))
                continue;
            curl_setopt($ch, CURLOPT_URL, $href);
            $countryData = curl_exec($ch);
            if(curl_errno($ch))
                continue;
            $pageResults->loadHTML($countryData);
            $countryTable = $pageResults->getElementsByTagName('table');
            $trs = $countryTable->item(2)->getElementsByTagName('tr');
            $elements = [];
            $header = true;
            // Getting object
            $country = $countryObj->getCountryByAlphaCode($countryISO);
            if(!$country)
                continue;
            $countryId = $country->getId();
            // Row through the table with data
            foreach($trs as $tr){
                if($header){
                    $header = false;
                    continue;
                }
                $countryData = $this->extractUneceRowData($tr, $countryISO);
                $cityObj = new City();
                $countryStatesAndCitiesArray = $cityObj->getAllStatesAndCitiesOfCountry($countryId);
                $states = $countryStatesAndCitiesArray['states'];
                $cities = $countryStatesAndCitiesArray['cities'];
                $stateISOCodes = FunctionsClass::parseObjArrayToArray($states, 'isoCode');
                $citiesISOCodes = FunctionsClass::parseObjArrayToArray($cities, 'isoCode');
                // Create state of country
                $stateObj = new State();
                if(!in_array($countryData['stateISO'], $stateISOCodes['isoCode'])){
                    $stateObj->setCountry($countryId);
                    $stateObj->setIsoCode($countryData['stateISO']);
                    $stateResult = $stateObj->save();
                    if($stateResult){
                        $report['newCountryState']++;
                        $newStateIds[] = $stateObj->data->id;
                    }
                }else{
                    $stateObj = $stateObj->getStateByIsoCode($countryData['stateISO']);
                }
                // Create city of country
                if(!in_array($countryData['isoCode'], $citiesISOCodes['isoCode'])){
                    $cityObj->setCountry($countryId);
                    $cityObj->setIsoCode($countryData['isoCode']);
                    $cityObj->setState($stateObj->getId());
                    $cityObj->setName(mb_strtolower($countryData['baseName']));
                    $cityObj->setRegionalName(mb_strtolower($countryData['regionalName']));
                    $coordinates = $cityObj->formateDegreeCoordinatesToMapCoordinates($countryData['coordinates']);
                    $cityObj->setCoordinates($coordinates);
                    $cityResult = $cityObj->save();
                    if($cityResult){
                        $report['newCountryCity']++;
                        $newCityIds[] = $cityObj->data->id;
                    }
                }
            }
        }
        $report['newCountryStateIds'] = $newStateIds;
        $report['newCountryCityIds']  = $newCityIds;
        return json_encode($report);
    }
    
    /**
     * Extract needed data from Unece table of the Country
     * @author  NAB - 20210405
     * @version 1   - initial release
     * @return  <Array> keys: <string> 'isoCode'
     *                          <string> 'regionalName'
     *                          <string> 'baseName'
     *                          <string> 'stateISO'
     *                          <string> 'coordinates'
     */
    public function extractUneceRowData($tableRow, $countryIso)
    {
        $tds = $tableRow->getElementsByTagName('td');
        $cityISO = $tds->item(1)->nodeValue;
        $cityISO = str_replace($countryIso, '', $cityISO);
        $cityISO = preg_replace('/^\p{Z}+|\p{Z}+$/u', '', $cityISO);
        $countryData = [
            'isoCode'      => preg_replace('/^\p{Z}+|\p{Z}+$/u', '', $cityISO),
            'regionalName' => preg_replace('/^\p{Z}+|\p{Z}+$/u', '', $tds->item(2)->nodeValue),
            'baseName'     => preg_replace('/^\p{Z}+|\p{Z}+$/u', '', $tds->item(3)->nodeValue),
            'stateISO'     => preg_replace('/^\p{Z}+|\p{Z}+$/u', '', $tds->item(4)->nodeValue),
            'coordinates'  => preg_replace('/^\p{Z}+|\p{Z}+$/u', '', $tds->item(9)->nodeValue)
        ];
        return $countryData;
    }

    /**
     * Synchronize Brazilian states name
     * @author  NAB - 20210626
     * @version 1 - initial release
     * @return <int> the total of updates
     */
    public function feedBrazilStatesName()
    {
        $stateObj = new State();
        $file = file_get_contents('Source/Files/brazil-states.txt');
        $file = preg_replace('/[\n\r]/', '@', $file);
        $fileArray = explode('@@', $file);
        $updates = 0;
        foreach($fileArray as $dataArray){
            $stateData = explode(',', $dataArray);
            $stateISO = trim($stateData[2]);
            $state = $stateObj->getStateByIsoCode($stateISO, 'BR');
            if(is_null($state))
                continue;
            $name = mb_strtolower($stateData[1]);
            if(!is_null($state->getName()))
                continue;
            $state->setName($name);
            $result = $state->save();
            if($result)
                $updates++;
        }
        return $updates;
    }

    // returns all countries on BD
    public function fetchAllContries($asArray = false)
    {
        $countryObj = new Country();
        $countries = $countryObj->find()->fetch(true);
        $elements = [];
        if(count($countries) > 0){
            foreach($countries as $country){
                $elements[] = $country->getFullData();
            }
        }
        return $asArray ? $elements : json_encode($elements);
    }

    // translate countries translations by online file
    public function feedCountryTranslations()
    {
        $countryObj = new Country();

        $countryTranslations = [];

        $portugueseFilePath  = TMPPATH['files'] . 'countriesInPortuguese.json';
        $portugueseFileArray = json_decode(file_get_contents($portugueseFilePath), true);
        foreach($portugueseFileArray as $ptData){
            $iSOCode = strtoupper($ptData['sigla2']);
            $pt      = $ptData['nome'];
            $position = strpos($pt, '(');
            if(is_numeric($position) && $position > 0){
                $pt = trim(substr($pt, 0, $position));
            }
            $countryTranslations[$iSOCode]['pt'] = $pt;
        }
        $spanishFilePath = TMPPATH['files'] . 'countriesInSpanish.txt';
        $file = fopen($spanishFilePath, "r");
        while(! feof($file)) {
          $line = fgets($file);
          $lineData = preg_replace('/\t/', '@@@@', $line);
          $lineData = explode('@@@@', $lineData);
          $iSOCode = strtoupper(trim($lineData[0]));
          $es      = trim($lineData[1]);
          $countryTranslations[$iSOCode]['es'] = $es;
        }
        fclose($file);
        $report = [
            'updated'    => 0,
            'notUpdated' => 0
        ];
        $translationObj = new Translation();
        foreach($countryTranslations as $countryISO => $translations){
            $hasTranslationObj = null;
            $ptTranslation = array_key_exists('pt', $translations) ? $translations['pt'] : null;
            $esTranslation = array_key_exists('es', $translations) ? $translations['es'] : null;
            $country = $countryObj->getCountryByAlphaCode($countryISO);
            if(!$country)
                continue;
            $enTranslation = $country->getName();
            if(!$country->getTranslation()){
                // create a new one
                $translationObj = new Translation();
                $translationObj->setCategory(Translation::CATEGORY_COUNTRY);
                $translationObj->setBaseWord($enTranslation);
                $result = $translationObj->save();
                // gather translation object
                $hasTranslationObj = $translationObj->getByBaseWordAndCategory($enTranslation, Translation::CATEGORY_COUNTRY);
                if(!$hasTranslationObj)
                    continue;
                $country->setTranslation($hasTranslationObj[0]->getId());
                $country->save();
            }
            if(!$hasTranslationObj){
                $translationObj = $country->getTranslation(true);
            }else{
                $translationObj = $hasTranslationObj;
            }
            $translationObj = is_array($translationObj) ? $translationObj[0] : $translationObj;
            // update translations
            $position = strpos($translationObj->getEn(), '(');
            if(is_numeric($position) && $position > 0){
                $enTranslation = trim(substr($translationObj->getEn(), 0, $position));
                $translationObj->setBaseWord(mb_strtolower($enTranslation));
            }
            $translationObj->setEn(mb_strtolower($enTranslation));
            $translationObj->setPt(mb_strtolower($ptTranslation));
            $translationObj->setEs(mb_strtolower($esTranslation));
            $result = $translationObj->save();
            if(!$result){
                $report['notUpdated'];
            }
            $report['updated'];
        }
        return json_encode([
            'success' => true,
            'report'  => $report
        ]);
    }
}