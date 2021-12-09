<?php

use Source\Controllers\PersonActivityController;

$app->post('/personactivity/updateandcheckpersonactivities', function(){
	$personActivityCt = new PersonActivityController();
	$result = $personActivityCt->updateAndCheckPersonActivities();
	return $result;
});