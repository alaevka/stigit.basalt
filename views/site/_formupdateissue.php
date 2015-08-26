<?php
	use yii\bootstrap\ActiveForm;
	use yii\helpers\Html;
	use yii\web\View;

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

					<?= $form->field($model, 'podr_list', [
				        'template' => "{label}<div class=\"col-sm-6\">{input}</div><div class=\"col-sm-2\" style=\"text-align: right;\"><button type=\"button\" id=\"add-podr-button-update\" class=\"btn btn-default btn-sm\"><span class=\"glyphicon glyphicon-plus\"></span></button></div>\n{hint}", 
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
	
		echo $this->registerJs(
			"
				function _selectPodr() {
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
			        		$(\"#persons-check-list-update\").html(data);
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

				$(\".checkbox-podr-link\").click(function(){
			    	var link_id = $(this).attr('data-id');
			    	$(\"#checkbox_\"+link_id).attr(\"checked\", true);
			    	_selectPodr();
			    	return false;
			    });

				$('#tasks-podr_list').tokenfield()
			    	.on('tokenfield:removetoken', function (e) {
			    		$('#tasks-persons_list').tokenfield('setTokens', []);
			    		$(\"#podr-check-list-update\").find('input:checkbox').removeAttr('checked');
			    		$(\"#persons-check-list-update\").html('<div class=\"alert alert-warning\" role=\"alert\">Пожалуйста, сначала укажите подразделения</div>');
			        	
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

<div class="modal fade" id="persons-select-modal" role="dialog" aria-labelledby="podr-select-modal-label">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel-pers">Выбор исполнителей</h4>
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