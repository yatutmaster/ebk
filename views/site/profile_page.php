<?php


use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;

?>

<div class="page-header">
		<h1>Страница профиля</h1>
</div>




<blockquote>
  <p class="lead">Веб форма, для отправки целого числа N, и массива целых чисел. </p>
  <footer>Число N, должно содержать только одно число </footer>
  <footer>Числа массива, нужно вводить через пробел, в след. формате: X X X X X </footer>

</blockquote>





	 <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>
	
	     <?= $form->errorSummary($model, ['header' => "Неудалось вычислить значения."]) ?>
		 
		 <?php if( ($result = \Yii::$app->session->getFlash('hasNewRecord') ) ) { ?>
		 
		 
			 	 <?php if($result['flag']) {  ?>
					 <div class="alert alert-success alert-dismissable">
					  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					  Результат запроса  ( Ответ: <?=$result['index']?> ) . [ <?=$result['arr_int']?>  ]
					 </div>
				<?php }else{ ?>
					<div class="alert alert-danger alert-dismissable">
					  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					  Не удалось, поделить массив( Ответ: -1 ) !
					 </div>
				<?php } ?>
				
		<?php } ?>

		<div class="row">
        <?= $form->field($model, 'oneint', [
																	'options' => ['class' => ' col-lg-2'],
																	'template' => '{label} <div class="row"><div class="col-xs-8">{input}</div></div>{error}{hint}'
										])->textInput()->label('Число N')?>
	
        <?= $form->field($model, 'arrint', [ 'options' => ['class' => ' col-lg-6']])->textInput()->label('Массив чисел') ?>
         </div>
   
		
		
        <div class="form-group">
            <div class="col-lg-11">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'calc_int']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
   




<div>

	 
<p class="lead">Все Ваши запросы:</p>

	<div class="">

	  <?= GridView::widget([
        'dataProvider' => new ActiveDataProvider(['query' => $dataProvider]),
        'columns' => [
					[
					'class' => 'yii\grid\SerialColumn',
					// you may configure additional properties here
					],
					'req',
					'res',
        ],
    ]); ?>
	
   
</div>
   
</div>
