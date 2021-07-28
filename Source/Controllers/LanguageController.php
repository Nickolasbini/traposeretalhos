<?php

namespace Source\Controllers;

use Source\Models\Language;
use Source\Models\Country;
use Source\Models\CountryLanguage;
use DOMDocument;

/**
 * 
 */
class LanguageController
{

    /**
     * Synchronize countryLanguages by creating langauge and setting its id to the country
     * @author NAB
     * @version 1.0 - 20201202
     * @return <array> 
     */
    public function syncCountryLanguages()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.fincher.org/Utilities/CountryLanguageList.shtml');
        curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $answer = curl_exec($ch);
        if(curl_errno($ch))
            exit('error');
        $pageResults = new DOMDocument();
        libxml_use_internal_errors(true);
        $pageResults->loadHTML($answer);
        $rows = $pageResults->getElementsByTagName('tr');
        $report = [
            'newLanguage'              => 0,
            'countriesLanguageUpdated' => 0
        ];
        foreach($rows as $elements){
            $tds = $elements->childNodes;
            if($tds->item(0)->nodeName == 'th')
                continue;
            $countryName = strtolower($tds->item(0)->nodeValue);
            $language    = strtolower($tds->item(1)->nodeValue);
            $iSOCodes    = $tds->item(2)->nodeValue;
            $iSOCodes    = explode('-', $iSOCodes);
            $languageISO = strtoupper($iSOCodes[0]);
            $countryISO  = strtoupper($iSOCodes[1]);
            // verify if language already exist
            $parameters = [
                'languageName' => $language,
                'iSOCode'      => $languageISO
            ];
            $languageWk = new Language();
            // this method try to get language object by both 'languageName' and 'iSOCode'
            $languageObj = $languageWk->getLanguageByIsoCode($parameters['iSOCode']);
            if(!$languageObj){
                $languageWk->setName($language);
                $languageWk->setISOCode($languageISO);
                $result = $languageWk->save();
                if(!$result){
                    // send email to admin
                    echo 'an error occured';
                    continue;
                }
                $report['newLanguage']++;
                $languageId = $languageWk->data->id;
            }else{
                $languageId = $languageObj->getId();
            }

            // try to get country object
            $countryWk = new Country();
            $countryObj = $countryWk->getCountryByAlphaCode($countryISO);
            if(!$countryObj){
                $sendErrorEmail = false;
                continue;
            }else{
                $countryId = $countryObj->getId();
            }

            $countryLanguage = new CountryLanguage();
            $countryLanguage->setLanguage($languageId);
            $countryLanguage->setCountry($countryId);
            $result = $countryLanguage->isInUse();
            if(!$result){
                $result = $countryLanguage->save();
                if(!$result){
                    $sendErrorEmail = false;
                    continue;
                }
                $report['countriesLanguageUpdated']++;
            }
        }
        exit(json_encode($report));
    }

}