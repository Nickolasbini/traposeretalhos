<?php

/**
 * 
 */
class Midleware
{
	public static function checkLogin()
	{
		$request = $_SERVER['REQUEST_URI'];
		$request = str_replace('/'.URL['urlDomain'], '', $request);
		$routesToVerify = ['/post/save'];
		foreach($routesToVerify as $routeName){
			if($request == $routeName){
				if(!isset($_SESSION['personId']) || is_null($_SESSION['personId'])){
					$_SESSION['errorMessage'] = ucfirst(translate('please log in first'));
		            return false;
		      	}
			}
		}
      	return true;
	}

	// saves the routes accessed by user
	public static function saveLastRoute()
	{
		// verify here what to use, maybe put just the main routes to be allowed to be saved on the history
		$recordOnlyThesesRoutes = ['/traposeretalhos/', '/traposeretalhos/news', '/traposeretalhos/search', '/traposeretalhos/map', '/traposeretalhos/tips', '/traposeretalhos/courses', '/traposeretalhos/posts'];
		$routes = isset($_SESSION['history']) ? json_decode($_SESSION['history'], true) : [];
		$currentRoute = $_SERVER['REQUEST_URI'];
		if(!in_array($currentRoute, $recordOnlyThesesRoutes))
			return;
		$routes[] = $currentRoute;
		if(count($routes) == 3){
			unset($routes[0]);
			$routes = array_values($routes);
		}
		$_SESSION['history'] = json_encode($routes);
	}
}
