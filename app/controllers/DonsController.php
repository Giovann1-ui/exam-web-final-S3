<?php

namespace app\controllers;

use PDO;
use app\models\DonModels;
use Flight;

class DonsController
{
    public function getAllDons()
    {
        $donsModel = new DonModels(Flight::db());
        $dons = $donsModel->getAllDons();
        Flight::render('dons/index', ['dons' => $dons]);
    }
}