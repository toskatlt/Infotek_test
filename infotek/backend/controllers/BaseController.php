<?php

namespace app\controllers;

use yii\web\Controller;
use yii\web\Response;
use Yii;

class BaseController extends Controller
{
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    protected function jsonResponse(array $data, int $status = 200): Response
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->statusCode = $status;

        return $this->asJson($data);
    }
}