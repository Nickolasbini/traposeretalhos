<?php

define("DATA_LAYER_CONFIG", [
    "driver" => "mysql",
    "host" => "localhost",
    "port" => "3306",
    "dbname" => "traposeretalhos",
    "username" => "root",
    "passwd" => "",
    "options" => [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ]
]);

define("MAIL_CONFIGURATION", [
    "host"=>"smtp.gmail.com",
    "port"=>"587",
    "user"=>"nciwebsolution@gmail.com",
    "passwd"=>"vin@!gard",
    "fromName"=>"NCI_WebSolutions",
    "fromMail"=>"nciwebolution@gmail.com",
    "AltBody"=>"Nci webSolutions"
]);

define("TEMPLATES_PATH", [
	"userTemplate" =>"Source/Resourses/Views/UserViews",
	"adminTemplate"=>"Source/Resourses/Views/AdminViews"
]);

define("URL",[
    'urlDomain' => getBaseURLPath(),
    'webPath'   => getBaseWebPath(),
    'iconsPath' => 'Source/Resourses/External/icons/'
]);

define('TMPPATH', [
    'tmp'              => __DIR__.'/Files/tmp/',
    'files'            => __DIR__.'/Files/',
    'images'           => __DIR__.'/Files/img/',
    'imagesSystemPath' => 'Source/Files/img/'
]);

define('APP', [
    'appName' => getAppNameByCountry()
]);

define('COUNTRIESFORMATS', [
    'en' => 'Y-m-d',
    'pt' => 'd-m-Y',
    'es' => 'd-m-Y'
]);

/**
* Brazil location and default time zone
*/
define('DEFAULTOPTIONS', [
    'mapLocation' => '-14.094 -55.020',
    'timeZone'    => 'America/Sao_Paulo'
]);

// Do me in order to check by session or by url or something like it
function getAppNameByCountry()
{
    return 'Trapos e Retalhos';
}

/**
* Returns the Domain name
*/
function getBaseURLPath()
{
    $url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $baseURL = explode('/', $url);
    $basePath = '';
    if(is_array($baseURL) && count($baseURL) > 1){
        foreach($baseURL as $url){
            if($url == '')
                continue;
            $basePath = $url;
            break;   
        }
    }
    return $basePath;
}

/**
* Returns the full WebPath
*/
function getBaseWebPath(){
    $fullLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $domainName = getBaseURLPath().'/';
    $position = strpos($fullLink, $domainName);
    $webPath = substr($fullLink, 0, $position + strlen($domainName));
    return $webPath;
}

// Make it get translation by Session and save to cache or something like it
function translate($phrase)
{
    $userLanguage = isset($_SESSION['userLanguage']) ? $_SESSION['userLanguage'] : 'pt';
    $userLanguage = strtolower($userLanguage);
    $currentTranslations = file_get_contents(TMPPATH['files'].'systemTranslation.txt');
    $currentTranslations = json_decode($currentTranslations, true);
    // create a new systemTranslation placeholder for this message
    if(!array_key_exists($phrase, $currentTranslations)){
        // this is a Development place
        $currentTranslations[$phrase] = [
            'en' => $phrase,
            'es' => '',
            'pt' => ''
        ];
        file_put_contents(TMPPATH['files'].'systemTranslation.txt', json_encode($currentTranslations));
        return $phrase;
    }
    $phrase = !empty($currentTranslations[$phrase][$userLanguage]) 
                    ? $currentTranslations[$phrase][$userLanguage] 
                    : $currentTranslations[$phrase]['en'];
    return $phrase;
}