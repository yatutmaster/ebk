<?php


use yii\helpers\Html;
use yii\helpers\Url;


?>
<div>

    <?php if(!Yii::$app->user->isGuest) { ?>
	
	<div class="page-header">
			    <h1><?= Html::encode("Добро пожаловать!") ?></h1>
	</div>		
	    <h4>Информация:</h4>
	<div class="panel panel-default col-lg-10">
		<div class="panel-body">
	
			<p class="lead"> Rest API сылки:</p>
			<p>	
			   1. <b class="text-danger"><?=Url::toRoute('/rest')?></b>  
			
				Эта ссылка возвращает историю Ваших запросов
				и результатов, вычислений  целых чисел. Ссылка без параметров.
			</p>	
			
			<p>	
			   2. <b class="text-danger"><?=Url::toRoute('/rest/profile')?></b>   
				
			   С помошью этой ссылки, можно найти индекс числа перед которым ставится разделитель.
			   Параметры запроса следующие: 
				<b>arrint=[X X X X X X]</b>  массив целых чисел.
				<b>oneint=N</b>  одно целое число.
				<br>
				Пример запроса. <b>/rest/profile?arrint=[2 1 3 21 123 2 3 4 2]&oneint=4</b> 
			</p>	
			
				<p class="bg-danger">
				Все запросы, должны быть с параметром <b>access-token={token}.</b>
				access-token, обновляется, при авторизации.<br>
				Ваш действующий токен:  <strong><?=Yii::$app->user->identity->accessToken?></strong> 
				</p>
		
	
		</div>
		<div class="panel-body">
	
			<p class="lead">Админка</p>
			<p>	
			Админка доступна, только админам. Где, админ видит список всех пользователей (админы и юзеры),
			А так же, можно создавать новых пользователей (админов и юзеров),
			</p>	
			
		
		</div>

		<div class="panel-body">
	
			<p class="lead">Сервис вычислений, целых чисел.  Ссылка ( Профиль )</p>
			<p>	
		     Сервис доступен каждому авторизованному пользователю, в нем реализованы вычислени целых цисел, согласно ТЗ.
			 
			 А так же выводит историю, всех Ваших запросов.
			</p>	
			
		
		</div>

	
	<?php }else{ ?>
	
	           <h1><?= Html::encode("Пожалуйста авторизуйтесь.") ?></h1>
	<?php } ?>
	
   
</div>
