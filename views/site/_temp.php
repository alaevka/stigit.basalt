<!--<?= $form->field($model, 'podr', [
					        'template' => "{label}<div class=\"col-sm-8\">{input}</div>\n{hint}\n<div class=\"col-sm-offset-4 col-lg-8\">{error}</div>", 
					        'labelOptions'=>['class'=>'col-sm-4 control-label'],
					        'inputOptions'=>['class'=>'form-control input-sm']
					    ])->widget(Select2::classname(), [
					    'data' => $podr_data,
					    'options' => ['placeholder' => 'Укажите подразделения ...', 'multiple' => true],
					    'pluginOptions' => [
					        'allowClear' => true,
					        'tags' => true,
					        'templateResult' => new JsExpression('function(repo) { 
					        	//if (repo.loading) return repo.text;
					        	_getTree(1, 0, repo);
							    
					        }'),
					        //'templateSelection' => new JsExpression('function (designation) { return \'fdgfdg\'; }'),
					    ],
					]); ?>
				    <div class="hr-line-dashed"></div>-->