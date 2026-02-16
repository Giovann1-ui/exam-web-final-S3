<?php

namespace app\controllers;

use Flight;
use app\models\RecapModel;

class RecapController
{
    public function recap()
    {
        Flight::render('recap.php');
    }

    public function getRecapJSON()
    {
        $db = Flight::db();
        $recapModel = new RecapModel($db);
        $data = $recapModel->getRecapData();
        Flight::json($data);
    }
}