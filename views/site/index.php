<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\web\JsExpression;
use yii\helpers\Html;
use kartik\grid\GridView;
$this->title = 'index page';
/*
	поиск транзакции (будущем перенести в модель в relations)
*/
$transactions = \app\models\Transactions::find()->where(['TN' => \Yii::$app->user->id ])->orderBy('ID DESC')->one();
?>
<!-- Static navbar -->
<nav class="navbar navbar-default navbar-static-top">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?= Url::to(['/']); ?>"><img src="/images/logo_fullsize.png" height="40"></a>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<!-- <li class="active"><a href="#">menu 1</a></li>
				<li><a href="#">menu 2</a></li>
				<li><a href="#">menu 3</a></li> -->
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li class="user-info">Вы авторизованны как: <?= \Yii::$app->user->identity->LOGIN; ?> (номер транзакции: <?= $transactions->ID;  ?>)</li>
				<li><a data-method="post" href="<?= Url::to(['site/logout']); ?>">Выйти</a></li>
			</ul>
		</div><!--/.nav-collapse -->
	</div>
</nav>

<div id="wrapper">

	<!-- Sidebar -->
	<div id="sidebar-wrapper">
		<ul class="sidebar-nav">
			<li>
				<button type="button" data-backdrop="static" class="btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#issue-modal">
				  	Выдать задание
				</button>
			</li>
		</ul>
	</div>
	<!-- /#sidebar-wrapper -->

	<!-- Page Content -->
	<div id="page-content-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">
					<?php
						echo GridView::widget([
						    'dataProvider' => $dataProvider,
						    'filterModel' => $searchModel,
						    'columns' => [
						        'DESIGNATION',
						        'DOCUMENTID',
						        'TASK_NUMBER',
						        'ORDERNUM',
						        'PEOORDERNUM',
						        'TASK_TEXT',
						        'DEADLINE',
						        'TRACT_ID'
						    ],
						]);
					?>
				</div>
			</div>
		</div>
	</div>
	<!-- /#page-content-wrapper -->

</div>
<!-- /#wrapper -->

<!-- Modal -->
<div class="modal fade" id="issue-modal" role="dialog" aria-labelledby="issue-modal-label">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="issue-modal-label">Выдача задания</h4>
			</div>
			<?php $form = ActiveForm::begin([
	                'id' => 'issue-form',
	                //'enableAjaxValidation' => true,
	                'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],
	        ]); ?>
			<div class="modal-body">
				
					
				    <?= $form->field($model, 'designation', [
				        'template' => "{label}<div class=\"col-sm-8\">{input}</div>\n{hint}\n<div class=\"col-sm-offset-4 col-lg-8\">{error}</div>", 
				        'labelOptions'=>['class'=>'col-sm-4 control-label'],
				        'inputOptions'=>['class'=>'form-control input-sm']
				    ])->widget(Select2::classname(), [
					    'initValueText' => '',
					    'options' => ['placeholder' => 'Поиcк основания ...'],
					    'pluginOptions' => [
					        'allowClear' => true,
					        'tags' => true,
					        'minimumInputLength' => 3,
					        'maximumInputLength' => 25,
					        'ajax' => [
					            'url' => Url::to(['site/designationsearch']),
					            'dataType' => 'json',
					            'data' => new JsExpression('function(params) { return {q:params.term}; }')
					        ],
					        'createSearchChoice' => new JsExpression('function (term) { return {id: term, text: term}; }'),
					        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
					        'templateResult' => new JsExpression('function(designation) { return designation.text; }'),
					        'templateSelection' => new JsExpression('function (designation) { return designation.text; }'),
					    ],
					    'pluginEvents' => [
					    	"select2:selecting" => "function(e) { 
					    		var selected_data = e.params.args.data; 
					    		if (typeof selected_data.ordernum != 'undefined') {
						    		$(\"#issueform-ordernum\").val(selected_data.ordernum);
						    		$(\"#issueform-peoordernum\").val(selected_data.peoordernum);
						    		//console.log(selected_data);
						    		$(\"#issueform-documentid\").val(selected_data.id);
						    	} else {
						    		//console.log('selected new value');
						    		$(\"#issueform-documentid\").val('');
						    		$(\"#issueform-ordernum\").val('');
						    		$(\"#issueform-peoordernum\").val('');
						    	}
					    	}",
					    ]
					]);
				    ?>

				    <?= $form->field($model, 'documentid', ['options' => ['class' => '']])->hiddenInput()->label(false) ?>

				    <div class="hr-line-dashed"></div>

				    <?= $form->field($model, 'task_number', [
				        'template' => "{label}<div class=\"col-sm-8\">{input}</div>\n{hint}\n<div class=\"col-sm-offset-4 col-lg-8\">{error}</div>", 
				        'labelOptions'=>['class'=>'col-sm-4 control-label'],
				        'inputOptions'=>['class'=>'form-control input-sm'],
				        'enableAjaxValidation' => true
				    ])->textInput() ?>
				    <div class="hr-line-dashed"></div>

				    <?= $form->field($model, 'podr_list', [
				        'template' => "{label}<div class=\"col-sm-6\">{input}</div><div class=\"col-sm-2\" style=\"text-align: right;\"><button type=\"button\" id=\"add-podr-button\" class=\"btn btn-default btn-sm\"><span class=\"glyphicon glyphicon-plus\"></span></button></div>\n{hint}\n<div class=\"col-sm-offset-4 col-lg-8\">{error}</div>", 
				        'labelOptions'=>['class'=>'col-sm-4 control-label'],
				        'inputOptions'=>['class'=>'form-control input-sm']
				    ])->textInput() ?>
				    <div class="hr-line-dashed"></div>

				    <?= $form->field($model, 'persons_list', [
				        'template' => "{label}<div class=\"col-sm-6\">{input}</div><div class=\"col-sm-2\" style=\"text-align: right;\"><button type=\"button\" id=\"add-persons-button\" class=\"btn btn-default btn-sm\"><span class=\"glyphicon glyphicon-plus\"></span></button></div>\n{hint}\n<div class=\"col-sm-offset-4 col-lg-8\">{error}</div>", 
				        'labelOptions'=>['class'=>'col-sm-4 control-label'],
				        'inputOptions'=>['class'=>'form-control input-sm']
				    ])->textInput() ?>
				    <div class="hr-line-dashed"></div>

				    <?= $form->field($model, 'ordernum', [
				        'template' => "{label}<div class=\"col-sm-8\">{input}</div>\n{hint}\n<div class=\"col-sm-offset-4 col-lg-8\">{error}</div>", 
				        'labelOptions'=>['class'=>'col-sm-4 control-label'],
				        'inputOptions'=>['class'=>'form-control input-sm']
				    ])->textInput() ?>
				    <div class="hr-line-dashed"></div>

				    <?= $form->field($model, 'peoordernum', [
				        'template' => "{label}<div class=\"col-sm-8\">{input}</div>\n{hint}\n<div class=\"col-sm-offset-4 col-lg-8\">{error}</div>", 
				        'labelOptions'=>['class'=>'col-sm-4 control-label'],
				        'inputOptions'=>['class'=>'form-control input-sm']
				    ])->textInput() ?>
				    <div class="hr-line-dashed"></div>

				    <?= $form->field($model, 'date', [
				        'template' => "{label}<div class=\"col-sm-8\">{input}</div>\n{hint}\n<div class=\"col-sm-offset-4 col-lg-8\">{error}</div>", 
				        'labelOptions'=>['class'=>'col-sm-4 control-label'],
				        'inputOptions'=>['class'=>'form-control input-sm']
				    ])->widget(DatePicker::classname(), [
				    	'pluginOptions' => [
					        'todayHighlight' => true,
					        'todayBtn' => true,
					        'format' => 'yyyy-mm-dd',
					        'autoclose' => true,
					    ]
				    ]);
				    ?>
				    <div class="hr-line-dashed"></div>


				    <?= $form->field($model, 'message', [
				        'template' => "{label}<div class=\"col-sm-8\">{input}</div>\n{hint}\n<div class=\"col-sm-offset-4 col-lg-8\">{error}</div>", 
				        'labelOptions'=>['class'=>'col-sm-4 control-label'],
				        'inputOptions'=>['class'=>'form-control input-sm']
				    ])->textArea() ?>
				    <div class="hr-line-dashed"></div>

				    
				    

				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" id="issue-cancel-button">Отмена</button>
				<?= Html::submitButton('Выдать', ['class' => 'btn btn-primary', 'id' => 'issue-submit-button']) ?>
			</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>

<div class="modal fade" id="podr-select-modal" role="dialog" aria-labelledby="podr-select-modal-label">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Выбор подразделений</h4>
			</div>
			<div class="modal-body" id="podr-check-list">
				<?= $podr_data; ?>
			</div>
			<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
				<button type="button" id="select-podr" class="btn btn-primary">Выбрать указанные подразделения</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="persons-select-modal" role="dialog" aria-labelledby="podr-select-modal-label">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Выбор исполнителей</h4>
			</div>
			<div class="modal-body" id="persons-check-list">
				<div class="alert alert-warning" role="alert">Пожалуйста, сначала укажите подразделения</div>
			</div>
			<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
				<button type="button" id="select-persons" class="btn btn-primary">Выбрать указанных исполнителей</button>
			</div>
		</div>
	</div>
</div>
