<?php
	use yii\bootstrap\ActiveForm;
	use yii\helpers\Html;
	use yii\web\View;
	use yii\helpers\Url;
	use yii\web\JsExpression;

	$this->params['issue_title'] = $model->TASK_NUMBER;
	$this->title = 'Редактирование задания '. $model->TASK_NUMBER;
	
?>
<div class="modal-header">
	<?php 
	if(!$not_ajax) {
	?>
	<button type="button" class="close" id="close-label-issue-update" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<?php } ?>
	<h4 class="modal-title" id="myModalLabel-issue-title">Редактирование задания <?= $model->TASK_NUMBER; ?></h4>
</div>
<div class="modal-body" id="issue-update-table">
<?php $form = ActiveForm::begin([
	'id' => 'issue-form',
	//'enableAjaxValidation' => true,
	'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],
]); ?>
					<?= $form->errorSummary($model); ?>

					<?= $form->field($model, 'podr_list', [
				        'template' => "{label}<div class=\"col-sm-6\">{input}</div><div class=\"col-sm-2\" style=\"text-align: right;\"><button type=\"button\" id=\"add-podr-button-update\" class=\"btn btn-default btn-sm\"><span class=\"glyphicon glyphicon-plus\"></span></button></div>\n{hint}", 
				        'labelOptions'=>['class'=>'col-sm-4 control-label'],
				        'inputOptions'=>['class'=>'form-control input-sm']
				    ])->textInput() ?>
				    <div class="hr-line-dashed"></div>

				    <?= $form->field($model, 'persons_list', [
				        'template' => "{label}<div class=\"col-sm-6\">{input}</div><div class=\"col-sm-2\" style=\"text-align: right;\"><button type=\"button\" id=\"add-persons-button-update\" class=\"btn btn-default btn-sm\"><span class=\"glyphicon glyphicon-plus\"></span></button></div>\n{hint}", 
				        'labelOptions'=>['class'=>'col-sm-4 control-label'],
				        'inputOptions'=>['class'=>'form-control input-sm']
				    ])->textInput() ?>
				    <div class="hr-line-dashed"></div>

				    <?= $form->field($model, 'TASK_NUMBER', [
				        'template' => "{label}<div class=\"col-sm-8\">{input}</div>\n{hint}", 
				        'labelOptions'=>['class'=>'col-sm-4 control-label'],
				        'inputOptions'=>['class'=>'form-control input-sm'],
				        'enableAjaxValidation' => true
				    ])->textInput() ?>
				    <div class="hr-line-dashed"></div>

				    <?= $form->field($model, 'SOURCENUM', [
				        'template' => "{label}<div class=\"col-sm-8\">{input}</div>\n{hint}", 
				        'labelOptions'=>['class'=>'col-sm-4 control-label'],
				        'inputOptions'=>['class'=>'form-control input-sm'],
				        'enableAjaxValidation' => true
				    ])->textInput() ?>
				    <div class="hr-line-dashed"></div>

				    <?= $form->field($model, 'ORDERNUM', [
				        'template' => "{label}<div class=\"col-sm-8\">{input}</div>\n{hint}", 
				        'labelOptions'=>['class'=>'col-sm-4 control-label'],
				        'inputOptions'=>['class'=>'form-control input-sm']
				    ])->widget(\kartik\select2\Select2::classname(), [
				    	//'theme' => 'todc',
					    //'initValueText' => ,
					    'options' => ['placeholder' => ''],
					    'pluginOptions' => [
					        'allowClear' => true,
					        'tags' => true,
					        'minimumInputLength' => 3,
					        'maximumInputLength' => 25,
					        'ajax' => [
					            'url' => Url::to(['site/ordernumsearch']),
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
					    		
					    	}",
					    ]
					]);
				    ?>

				    <?= $form->field($model, 'PEOORDERNUM', [
				        'template' => "{label}<div class=\"col-sm-8\">{input}</div>\n{hint}", 
				        'labelOptions'=>['class'=>'col-sm-4 control-label'],
				        'inputOptions'=>['class'=>'form-control input-sm']
				    ])->widget(\kartik\select2\Select2::classname(), [
				    	//'theme' => 'todc',
					    //'initValueText' => ,
					    'options' => ['placeholder' => ''],
					    'pluginOptions' => [
					        'allowClear' => true,
					        'tags' => true,
					        'minimumInputLength' => 3,
					        'maximumInputLength' => 25,
					        'ajax' => [
					            'url' => Url::to(['site/peoordernumsearch']),
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
					    		
					    	}",
					    ]
					]);
				    ?>

				    <?= $form->field($model, 'DEADLINE', [
				        'template' => "{label}<div class=\"col-sm-8\">{input}</div>\n{hint}", 
				        'labelOptions'=>['class'=>'col-sm-4 control-label'],
				        'inputOptions'=>['class'=>'form-control input-sm']
				    ])->widget(\kartik\date\DatePicker::classname(), [
				    	'type' => \kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
				    	'pluginOptions' => [
					        'todayHighlight' => true,
					        'todayBtn' => true,
					        'format' => 'dd-mm-yyyy',
					        'autoclose' => true,
					    ]
				    ]);
				    ?>
				    <div class="hr-line-dashed"></div>

				    <?= $form->field($model, 'TASK_TEXT', [
				        'template' => "{label}<div class=\"col-sm-8\">{input}</div>\n{hint}", 
				        'labelOptions'=>['class'=>'col-sm-4 control-label'],
				        'inputOptions'=>['class'=>'form-control input-sm']
				    ])->textArea() ?>
				    <div class="hr-line-dashed"></div>

				    <?= $form->field($model, 'ADDITIONAL_TEXT', [
				        'template' => "{label}<div class=\"col-sm-8\">{input}</div>\n{hint}", 
				        'labelOptions'=>['class'=>'col-sm-4 control-label'],
				        'inputOptions'=>['class'=>'form-control input-sm']
				    ])->textArea() ?>
				    <div class="hr-line-dashed"></div>

				    <?= $form->field($model, 'task_type_date_3', [
				        'template' => "{label}<div class=\"col-sm-8\">{input}</div>\n{hint}", 
				        'labelOptions'=>['class'=>'col-sm-4 control-label'],
				        'inputOptions'=>['class'=>'form-control input-sm']
				    ])->widget(\kartik\date\DatePicker::classname(), [
				    	'type' => \kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
				    	'pluginOptions' => [
					        'todayHighlight' => true,
					        'todayBtn' => true,
					        'format' => 'dd-mm-yyyy',
					        'autoclose' => true,
					    ]
				    ]);
				    ?>
				    <div class="hr-line-dashed"></div>

				    <?= $form->field($model, 'transactions_tract_datetime', [
				        'template' => "{label}<div class=\"col-sm-8\">{input}</div>\n{hint}", 
				        'labelOptions'=>['class'=>'col-sm-4 control-label'],
				        'inputOptions'=>['class'=>'form-control input-sm']
				    ])->widget(\kartik\date\DatePicker::classname(), [
				    	'type' => \kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
				    	'disabled' => true,
				    	'pluginOptions' => [
					        'todayHighlight' => true,
					        'todayBtn' => true,
					        'format' => 'dd-mm-yyyy',
					        'autoclose' => true,
					    ]
				    ]);
				    ?>
				    <div class="hr-line-dashed"></div>

				    <?= $form->field($model, 'task_type_date_1', [
				        'template' => "{label}<div class=\"col-sm-8\">{input}</div>\n{hint}", 
				        'labelOptions'=>['class'=>'col-sm-4 control-label'],
				        'inputOptions'=>['class'=>'form-control input-sm']
				    ])->widget(\kartik\date\DatePicker::classname(), [
				    	'type' => \kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
				    	'pluginOptions' => [
					        'todayHighlight' => true,
					        'todayBtn' => true,
					        'format' => 'dd-mm-yyyy',
					        'autoclose' => true,
					    ]
				    ]);
				    ?>
				    <div class="hr-line-dashed"></div>

				    <?= $form->field($model, 'task_type_date_4', [
				        'template' => "{label}<div class=\"col-sm-8\">{input}</div>\n{hint}", 
				        'labelOptions'=>['class'=>'col-sm-4 control-label'],
				        'inputOptions'=>['class'=>'form-control input-sm']
				    ])->widget(\kartik\date\DatePicker::classname(), [
				    	'type' => \kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
				    	'pluginOptions' => [
					        'todayHighlight' => true,
					        'todayBtn' => true,
					        'format' => 'dd-mm-yyyy',
					        'autoclose' => true,
					    ]
				    ]);
				    ?>
				    <div class="hr-line-dashed"></div>

				    <?= $form->field($model, 'documentation', [
				        'template' => "{label}<div class=\"col-sm-8\">{input}</div>\n{hint}", 
				        'labelOptions'=>['class'=>'col-sm-4 control-label'],
				        'inputOptions'=>['class'=>'form-control input-sm'],
				        'enableAjaxValidation' => true
				    ])->textInput() ?>
				    <div class="hr-line-dashed"></div>

				    <?= $form->field($model, 'REPORT_TEXT', [
				        'template' => "{label}<div class=\"col-sm-8\">{input}</div>\n{hint}", 
				        'labelOptions'=>['class'=>'col-sm-4 control-label'],
				        'inputOptions'=>['class'=>'form-control input-sm']
				    ])->textArea() ?>
				    <div class="hr-line-dashed"></div>

				    <?= $form->field($model, 'agreed_podr_list', [
				        'template' => "{label}<div class=\"col-sm-6\">{input}</div><div class=\"col-sm-2\" style=\"text-align: right;\"><button type=\"button\" id=\"add-agreed-podr-button-update\" class=\"btn btn-default btn-sm\"><span class=\"glyphicon glyphicon-plus\"></span></button></div>\n{hint}", 
				        'labelOptions'=>['class'=>'col-sm-4 control-label'],
				        'inputOptions'=>['class'=>'form-control input-sm']
				    ])->textInput() ?>
				    <div class="hr-line-dashed"></div>

				    <?= $form->field($model, 'transmitted_podr_list', [
				        'template' => "{label}<div class=\"col-sm-6\">{input}</div><div class=\"col-sm-2\" style=\"text-align: right;\"><button type=\"button\" id=\"add-transmitted-podr-button-update\" class=\"btn btn-default btn-sm\"><span class=\"glyphicon glyphicon-plus\"></span></button></div>\n{hint}", 
				        'labelOptions'=>['class'=>'col-sm-4 control-label'],
				        'inputOptions'=>['class'=>'form-control input-sm']
				    ])->textInput() ?>
				    <div class="hr-line-dashed"></div>

				    

</div>
<div class="modal-footer">
	<?php 
	if(!$not_ajax) {
	?>
	<button type="button" class="btn btn-default" id="issue-cancel-update-button">Отмена</button>
	<?php } ?>
	<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'id' => 'issue-submit-update-button']) ?>
</div>
<?php ActiveForm::end(); ?>
<?php
		if($podr_tasks) {
			$podr_tasks_list = '';
			$chk_podr_list = '';
			foreach($podr_tasks as $podr) {
				$query = new \yii\db\Query;
		        $query->select('NAIMPODR AS name, KODPODR AS id, KODRODIT as parent, KODZIFR as code')
		                ->from('STIGIT.V_F_PODR')
		                ->where('KODZIFR = \''.trim($podr->KODZIFR).'\'');
		        $command = $query->createCommand();
		        $data = $command->queryOne();
		        if($data) {
					$podr_tasks_list .= '{value: '.$data['code'].', label: \''.$data['name'].'\'},';
					$chk_podr_list .= '$("#podr-check-list-update").find("#checkbox_'.$data['code'].'").prop("checked", true);';
		        }
			}
		}
		if($pers_tasks) {
			$pers_tasks_list = '';
			$chk_pers_list = '';
			foreach($pers_tasks as $pers) {
				$query = new \yii\db\Query;
		        $query->select('*')
		                ->from('STIGIT.V_F_PERS')
		                ->where('TN = \''.trim($pers->TN).'\'');
		        $command = $query->createCommand();
		        $data = $command->queryOne();
		        if($data) {
					$pers_tasks_list .= '{value: '.$data['TN'].', label: \''.$data['FIO'].'\'},';
					$chk_pers_list .= '$("#persons-check-list-update").find("#checkbox_'.$data['TN'].'").prop("checked", true);';
		        }
			}
		} else {
			$pers_tasks_list = '';
			$chk_pers_list = '';
		}
		echo $this->registerJs(
			"
				function _selectPodrUpdate() {
					var selected = [];
					var selected_values = {};
					$('#podr-check-list-update input:checked').each(function() {
					    selected.push({value: $(this).attr('value'), label:$(this).attr('data-title')});
					    selected_values[$(this).attr('value')] = $(this).attr('data-title');
					});
					
					$('#tasks-podr_list').tokenfield('setTokens', selected);
			        $('#podr-select-modal-update').modal('hide');

			        //create persons tree
			        $.ajax({
			        	type: \"POST\",
			        	dataType: 'json',
			        	url: \"index.php?r=site/getpersons\",
			        	data: \"selected_podr=\"+JSON.stringify(selected_values),
			        	success: function(data,status){
			        		$('#persons-check-list-update').html(data);
			        		$('#persons-check-list-update').tree({checkbox: false});
			        	}
			        });
			    }


				$('#issue-cancel-update-button, #close-label-issue-update').click(function () {
				   	bootbox.confirm({size: 'small', message: \"Вы уверены, что хотите закрыть форму редактирования задания?\", callback: function(result) {
					  	if(result == true) {
					  		$('#issue-update-modal').modal('hide');
					  	}	
					}}); 
				});

				$('#add-podr-button-update').click(function(){
					$(\"#podr-select-modal-update\").modal();
				});		
				$('#podr-check-list-update').tree({checkbox: false});

				$(\"#podr-check-list-update\").find(\".checkbox-podr-link\").click(function(){
			    	var link_id = $(this).attr('data-id');
			    	$(\"#checkbox_\"+link_id).prop(\"checked\", true);
			    	_selectPodrUpdate();
			    	return false;
			    });
				
				$('#tasks-podr_list').tokenfield();
				$('#tasks-podr_list').tokenfield('setTokens', [".substr_replace($podr_tasks_list ,"",-1)."]);
				//set checked in modal podr window
				".$chk_podr_list."

		    	$('#tasks-podr_list').on('tokenfield:removetoken', function (e) {
		    		$('#tasks-persons_list').tokenfield('setTokens', []);
		    		$(\"#persons-check-list-update\").html('<div class=\"alert alert-warning\" role=\"alert\">Пожалуйста, сначала укажите подразделения</div>');
		        	
		    	});
				$('#tasks-podr_list').on('tokenfield:removedtoken', function (e) {
					$(\"#podr-check-list-update\").find('#checkbox_'+e.attrs.value).removeAttr('checked');
					_selectPodrUpdate();
				});
				

				
				$(\"#add-persons-button-update\").click(function(){
					$(\"#persons-select-modal-update\").modal();
				});

				$('#tasks-persons_list').tokenfield();
				$('#tasks-persons_list').tokenfield('setTokens', [".substr_replace($pers_tasks_list ,"",-1)."]);

				$('#tasks-persons_list').on('tokenfield:removedtoken', function (e) {
					$(\"#persons-check-list-update\").find('#checkbox_'+e.attrs.value).removeAttr('checked');
					
				});
				
				$('#podr-check-list-update').tree({checkbox: false});
				var selected_values = {};
				$('#podr-check-list-update input:checked').each(function() {
				    selected_values[$(this).attr('value')] = $(this).attr('data-title');
				});
				var csrfToken = $('meta[name=\"csrf-token\"]').attr(\"content\");
			    $.ajax({
		        	type: \"POST\",
		        	dataType: 'json',
		        	url: \"index.php?r=site/getpersons\",
		        	data: \"selected_podr=\"+JSON.stringify(selected_values)+\"&_csrf=\"+csrfToken,
		        	success: function(data,status){
		        		$('#persons-check-list-update').html(data);
		        		$('#persons-check-list-update').tree({checkbox: false});
		        	},
		        	complete: function() {
		        		".$chk_pers_list."
		        	}
			    });

				$('#select-persons-update-btn').click(function(){
			    	var selected = [];
			    	$('#persons-check-list-update input:checked').each(function() {
					    selected.push({value: $(this).attr('value'), label:$(this).attr('data-title')});
					});
					$('#tasks-persons_list').tokenfield('setTokens', selected);
			        $('#persons-select-modal-update').modal('hide');
			    });
				

				//for agreed
				function _selectPodrUpdateAgreed() {
					var selected = [];
					var selected_values = {};
					$('#agreed-podr-check-list-update input:checked').each(function() {
					    selected.push({value: $(this).attr('value'), label:$(this).attr('data-title')});
					    selected_values[$(this).attr('value')] = $(this).attr('data-title');
					});
					
					$('#tasks-agreed_podr_list').tokenfield('setTokens', selected);
			        $('#agreed-podr-select-modal-update').modal('hide');
			    }

				$('#add-agreed-podr-button-update').click(function(){
					$(\"#agreed-podr-select-modal-update\").modal();
				});	
				$('#agreed-podr-check-list-update').tree({checkbox: false});

				$(\"#agreed-podr-check-list-update\").find(\".checkbox-podr-link-agreed\").click(function(){
			    	var link_id = $(this).attr('data-id');
			    	
			    	$(\"#agreed-podr-check-list-update\").find(\"#checkbox_\"+link_id).prop(\"checked\", true);
			    	_selectPodrUpdateAgreed();
			    	return false;
			    });
				$('#tasks-agreed_podr_list').tokenfield();
				//@todo see upper

				$('#tasks-agreed_podr_list').on('tokenfield:removedtoken', function (e) {
					$(\"#agreed-podr-check-list-update\").find('#checkbox_'+e.attrs.value).removeAttr('checked');
					_selectPodrUpdateAgreed();
				});

				//for transmitted
				function _selectPodrUpdateTransmitted() {
					var selected = [];
					var selected_values = {};
					$('#transmitted-podr-check-list-update input:checked').each(function() {
					    selected.push({value: $(this).attr('value'), label:$(this).attr('data-title')});
					    selected_values[$(this).attr('value')] = $(this).attr('data-title');
					});
					
					$('#tasks-transmitted_podr_list').tokenfield('setTokens', selected);
			        $('#transmitted-podr-select-modal-update').modal('hide');
			    }

				$('#add-transmitted-podr-button-update').click(function(){
					$(\"#transmitted-podr-select-modal-update\").modal();
				});	
				$('#transmitted-podr-check-list-update').tree({checkbox: false});

				$(\"#transmitted-podr-check-list-update\").find(\".checkbox-podr-link-transmitted\").click(function(){
			    	var link_id = $(this).attr('data-id');
			    	
			    	$(\"#transmitted-podr-check-list-update\").find(\"#checkbox_\"+link_id).prop(\"checked\", true);
			    	_selectPodrUpdateTransmitted();
			    	return false;
			    });
				$('#tasks-transmitted_podr_list').tokenfield();
				//@todo see upper

				$('#tasks-transmitted_podr_list').on('tokenfield:removedtoken', function (e) {
					$(\"#transmitted-podr-check-list-update\").find('#checkbox_'+e.attrs.value).removeAttr('checked');
					_selectPodrUpdateTransmitted();
				});
		

			", 
			View::POS_END, 
			'modal_js'
		);
	
?>

<div class="modal fade" id="podr-select-modal-update" role="dialog" aria-labelledby="podr-select-modal-label">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel-podr-update">Выбор подразделений</h4>
			</div>
			<div class="modal-body" id="podr-check-list-update">
				<?= $podr_data; ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="agreed-podr-select-modal-update" role="dialog" aria-labelledby="agreed-podr-select-modal-label">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel-agreed-podr-update">Выбор подразделений</h4>
			</div>
			<div class="modal-body" id="agreed-podr-check-list-update">
				<?= $agreed_podr_data; ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="transmitted-podr-select-modal-update" role="dialog" aria-labelledby="transmitted-podr-select-modal-label">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel-transmitted-podr-update">Выбор подразделений</h4>
			</div>
			<div class="modal-body" id="transmitted-podr-check-list-update">
				<?= $transmitted_podr_data; ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="persons-select-modal-update" role="dialog" aria-labelledby="podr-select-modal-label">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel-pers">Выбор исполнителей</h4>
			</div>
			<div class="modal-body" id="persons-check-list-update">
				<div class="alert alert-warning" role="alert">Пожалуйста, сначала укажите подразделения</div>
			</div>
			<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
				<button type="button" id="select-persons-update-btn" class="btn btn-primary">Выбрать указанных исполнителей</button>
			</div>
		</div>
	</div>
</div>	