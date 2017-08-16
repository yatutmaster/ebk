<?php

namespace app\controllers;

use Yii;
use app\models\Users;
use app\models\UsersSearch;
use app\models\DataUsers;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Url;
use yii\filters\AccessControl;

use yii\base\DynamicModel;




class SiteController extends Controller
{
	
	private $_is_admin = 0;
	
    /**
     * @inheritdoc
     */
   
	 public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout','user','admin'],
                'rules' => [
                    [
                        'actions' => ['logout','user','admin'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
	
	/**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


	public function beforeAction($action) {
		
		$this->_is_admin = (!Yii::$app->user->isGuest and Yii::$app->user->identity->role)?1:0;
		$this->view->params['is_admin'] = $this->_is_admin;
			
		return parent::beforeAction($action);
	}
	
	   
    public function actionIndex()
    {
        return $this->render('index');
    }

	
   //profile page
    public function actionUser()
    {
		
		$model  =  self::getDynModel();
		
		if($model->load(Yii::$app->request->post()))
		{
			
	        //чистим от излишних пробелов
			$model->arrint = preg_replace('!\s+!', ' ',$model->arrint);
			
			//алгоритм деления массива
		    $result =  self::parseData($model);
			
			 //Для опопвещения
             Yii::$app->session->setFlash('hasNewRecord', $result);
			 
			 return $this->refresh();

		}	
			
		//История запросов пользователя	
		$dataProvider   = DataUsers::find()->where([
											'user_id' => Yii::$app->user->identity->id
											]);

		return $this->render('profile_page' ,[
            'model' => $model,
			'dataProvider' => $dataProvider 
        ]);
    }

	
   //admin panel
    public function actionAdmin()
    {
		
		if(!$this->_is_admin)
			return $this->goHome();
		
		$model = new Users();
		
		//Add user
		if(Yii::$app->request->isPost){
			
			$model->scenario = Users::SCENARIO_REGIST;
			
			if ($model->load(Yii::$app->request->post()) and $model->saveUser()) 
			{
				 Yii::$app->session->setFlash('hasNewRecord', $model->username);
				
				 return $this->refresh();
			}	
			
		}
		
		$searchModel = new UsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('admin_page',[
            'model' => $model,
			'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	///модель валидациия массива целых чисел, и одного целого числа
    public static function getDynModel ()
	{
		
		
		return  ( new DynamicModel(['oneint' , 'arrint']))
									->addRule(['oneint', 'arrint'], 'required',['message' => 'Введите целое число'])
									->addRule(['oneint', 'arrint'], 'trim')
									->addRule('oneint', 'match', ['pattern' => '/^\s*(\-?[1-9]{1}[0-9]{0,6}|[0-9]{1})\s*$/',
																				'message' => 'Введите одно целое число'])
									->addRule('arrint',  'match', ['pattern' => '/^\s*(\-?[1-9]{1}[0-9]{0,6}\s+|[0-9]{1}\s+){1,100}(\-?[1-9]{1}[0-9]{0,6}|[0-9]{1})\s*$/',
																				'message' => 'Массив чисел, должен содержать, как минимум 2 целых числа.']);
												
		
	}
	//Алгоритм деления массива 
	public static function parseData($model)
	{
		//разбиваем строку на массив
		$arr_int = array_map('trim', explode(" ", $model->arrint));
		

		$count_n = array_count_values($arr_int);
		
		 //результат
		 //флаг, укажет на удачное деление массива
		$result['flag'] = null;
		 
		//если есть число N, в массиве чисел
		 if(isset($count_n[$model->oneint]))
		 {
				//берем количество чисел N
				$count_n  = $count_n[$model->oneint];

				//берем количество чисел 
				$count_arr = count($arr_int);
				
				//делим пополам сумму количества чисел, втрорая часть на 1 число больше
				$half_arr = ($count_arr % 2)?($count_arr+1) / 2:$count_arr / 2;
				
				//перебераем массив в обратном порядке, пока число N != 0 
				//или пока не дойдем до середины массива
				for($i = ($count_arr - 1); ($count_n > 0 and $half_arr > 0);$i--)
				{	     

					$result['index'] = $i;
					
					 if($arr_int[$i] != $model->oneint)
							  $result['flag'] = true;
						  
					$half_arr--; 
					$count_n--;
					
				}	
				//ставим метку в строке
				$arr_int[($result['index'] - 1)] .= ' |';
				
				$result['arr_int'] = implode('  ',$arr_int);

		 }
		 
		 //сохраняем запросы  пользователя
		$DataUsers = new DataUsers();
		$DataUsers->user_id = Yii::$app->user->identity->id;
		$DataUsers->req = "Число N {$model->oneint}. Массив чисел {$model->arrint}. ";
		
		if($result['flag'])
			$DataUsers->res = "Ответ = {$result['index']} ,   {$result['arr_int']}";
		else
			$DataUsers->res = 	"Не удалось, поделить массив( Ответ: -1 )";
		$DataUsers->save();
		 
		 return $result;
		
	}
	
	public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new Users();
		$model->scenario = Users::SCENARIO_LOGIN;
		
        if ($model->load(Yii::$app->request->post()) and $model->login()) {
			
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }	
		

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    // /**
     // * Deletes an existing History model.
     // * If deletion is successful, the browser will be redirected to the 'index' page.
     // * @param integer $id
     // * @return mixed
     // */
    // public function actionDelete($id = 0)
    // {
       // throw new NotFoundHttpException('The requested page does not exist.');
    // }

   
}
