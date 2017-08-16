<?php

namespace app\controllers;

use app\models\DataUsers;
use yii\data\ActiveDataProvider;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;



class RestController extends \yii\rest\ActiveController
{
	public $modelClass = 'app\models\DataUsers';
	
	
	public function init()
	{
		parent::init();
		\Yii::$app->user->enableSession = false;
	}
	
	public function behaviors()
	{
		 $behaviors = parent::behaviors();
			$behaviors['authenticator'] = [
				'class' => CompositeAuth::className(),
				'authMethods' => [
					//HttpBasicAuth::className(),
					// HttpBearerAuth::className(),
					QueryParamAuth::className(),
				],
			];
			return $behaviors;
	}
	
	public function actions()
	{
		$actions = parent::actions();
		unset(
			$actions['view'],
			$actions['options'],
			$actions['update'],
			$actions['delete'],
			$actions['create']);
			
			$actions['index'] = [
                'class' => 'yii\rest\IndexAction',
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'prepareDataProvider' =>  function ($action) {
                   return new ActiveDataProvider([
                         'query' => DataUsers::find()
											->where([
												 'user_id' => \Yii::$app->user->identity->id
											])
                   ]);
                 }
            ];
		
		return $actions;
	}
	

	
	//Ресурс для вычисления сзначений 
	public function actionProfile()
    {
		
		$req = \Yii::$app->request;
		
		if($req ->get('arrint') and $req ->get('oneint'))
		{
			$model  =  SiteController::getDynModel();
			
		    $model ->arrint = preg_replace('![\s\[\]]!', ' ',$req ->get('arrint'));
		    $model ->oneint = $req ->get('oneint');
			
		    if($model->validate())
			{
				    $result = SiteController::parseData($model);
					
					if(!$result['flag'])
						return ['status' => 'Не удалось вычислить значения.', 'Ответ' => '-1'];
					
					return $result;
					
			}
			
			return ['Errors' => $model->getErrors()];
			
		}
			
		return [
				'Info' => 'Введиде массив целых чисел через пробел, и одно целое число N',
				'Info2' => 'Где числа массива X, и одно число N',
				'Required format' =>' /rest/profile?arrint=[X X X X X . . . ]&oneint=N'
		];
		
		
	}
	
	
}
