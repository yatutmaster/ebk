<?php


use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;

?>  

<div class="page-header">
		<h1>Панель администратора</h1>
</div>

<hr>

<p class="lead">Добавить пользователя.</p>

<div >
	 <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'inline',
        'fieldConfig' => [
		    'template' => "<div style=\"padding-right: 8px;\" >{label}\n{input}\n{error}</div>",
        ],
    ]); ?>
	
	     <?= $form->errorSummary($model, ['header' => "Не удалось добавить пользователя!"]) ?>
		 
		 <?php if( ($username = \Yii::$app->session->getFlash('hasNewRecord') ) ) { ?>
			 
			 <div class="alert alert-success alert-dismissable">
			  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			  Пользователь <?=$username?>, успешно добавлен!
			 </div>
		 
		 <?php } ?>
	
        <?= $form->field($model, 'fio')->textInput(['placeholder' => 'ФИО']) ?>
		
        <?= $form->field($model, 'username')->textInput(['placeholder' => 'Логин']) ?>

        <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Пароль']) ?>
		
        <?= $form->field($model, 'role')->checkbox(['title' => 'Дать права админа']) ?>
		
       
		
		
        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary', 'name' => 'add user']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
   
</div>

<hr>	

<p class="lead">Список пользователей.</p>

<div class="">

	  <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
              'id',
              'fio',
			  'username',
			 [
					'attribute'=>'role',
					'format'=>'raw',
					 'value'=>function ($data) {
                         return $data->role?'Admin':'User';
					},
            ],
	
			
        ],
    ]); ?>
	
   
</div>
