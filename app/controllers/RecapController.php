<?php

namespace app\controllers;

use Flight;
use app\models\RecapModel;

class RecapController
{
    public function recap()
    {
        $base_url = Flight::get('flight.base_url');
        Flight::render('recap.php', [
            'base_url' => $base_url
        ]);
    }

    public function getRecapJSON()
    {
        $db = Flight::db();
        $recapModel = new RecapModel($db);
        $data = $recapModel->getRecapData();
        Flight::json($data);
    }
}