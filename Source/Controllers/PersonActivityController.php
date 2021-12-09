<?php

namespace Source\Controllers;

use Source\Helpers\FunctionsClass;
use Source\Models\Person;
use Source\Models\PersonActivity;
use Datetime;

/**
 * 
 */
class PersonActivityController
{
    // updates person activity
    public function updateAndCheckPersonActivities()
    {
        $personId     = isset($_POST['personId'])     ? $_POST['personId']     : null;
        $currentRoute = isset($_POST['currentRoute']) ? $_POST['currentRoute'] : null;
        if(!$personId || !$currentRoute){
            return json_encode([
                'success' => false,
                'message' => 'required parameters missing'
            ]);
        }
        $personObj = new Person();
        $person = $personObj->findById($personId);
        if(!$person){
            return json_encode([
                'success' => true,
                'message' => 'person is invalid'
            ]);
        }
        $personActivityObj = new PersonActivity();
        $result = $personActivityObj->updateActivity($personId, $currentRoute);
        $personActivityObj->verifyLimitOfActivitiesByPerson($personId);
        return json_encode([
            'success' => $result,
            'message' => $result ? 'activity updated' : 'activity not updated'
        ]);
    }
}