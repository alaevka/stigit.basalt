<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\web\View;
use yii\helpers\Url;
$transactions = \app\models\Transactions::find()->where(['TN' => \Yii::$app->user->id ])->orderBy('ID DESC')->one();

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
<!-- Static navbar -->
<nav class="navbar navbar-default navbar-fixed-top navbar-inverse">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?= Url::to(['site/index']); ?>"><img src="/images/logo_fullsize.png" height="40"></a>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav ">
				<li style="padding-top: 12px;" <?php if(\Yii::$app->controller->id == 'permissions') { ?>class="active"<?php } ?>><a id="permissions-link" href="<?= Url::to(['permissions/index']); ?>">Права доступа</a></li>
				<li style="padding-top: 12px;"><a id="states-change-link" href="#">Смена состояний</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li class="user-info">Вы авторизованны как: <?= \Yii::$app->user->identity->LOGIN; ?> (номер транзакции: <?= $transactions->ID;  ?>)</li>
				<li style="margin-top: 10px;"><a data-method="post" href="<?= Url::to(['site/logout']); ?>">Выйти</a></li>
			</ul>
		</div><!--/.nav-collapse -->
	</div>

</nav>
<div id="issue-view-preloader" style="z-index: 999;width: 90px; height: 90px; position: fixed; left: 45%; top: 30%; display: none;"><img src="/images/preloader.gif" /></div>
<div id="wrapper" style="padding-top: 53px;">

	<!-- Sidebar -->
	<div id="sidebar-wrapper">
		<?php if(\Yii::$app->controller->id != 'permissions') { ?>
		<ul class="sidebar-nav">
			<li>
				<button type="button" data-backdrop="static" class="btn btn-primary btn-block" data-toggle="modal" data-target="#issue-modal">
				  	Выдать задание
				</button>
			</li>
		</ul>
		<?php } ?>
	</div>
	<!-- /#sidebar-wrapper -->
    <?= $content ?>
</div>
<div class="modal fade" id="states-change-modal" role="dialog" aria-labelledby="states-change-modal-label">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel-states-change">Смена состояний</h4>
			</div>
			<div class="modal-body" id="states-change-modal-body">
				<div class="row">
					<div class="col-md-12">
						
						<div class="panel-group" id="accordion-states" role="tablist" aria-multiselectable="true">
							<?php
								$states_list = \app\models\States::find()->all();
								if($states_list) {
									foreach($states_list as $state) {
							?>
							<div class="panel panel-default">
								<div class="panel-heading" role="tab" id="state-list-item-<?= $state->ID ?>">
									<h4 class="panel-title">
										<a class="collapsed" role="button" data-toggle="collapse" href="#collapse-state-item-<?= $state->ID ?>" aria-expanded="false" aria-controls="collapse-state-item-<?= $state->ID ?>">
											<?= $state->getState_name_state_colour_without_text().' '.$state->STATE_NAME; ?>
										</a>
									</h4>
								</div>
								<div id="collapse-state-item-<?= $state->ID ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="state-list-item-<?= $state->ID ?>">
									<div class="panel-body">
										<?php
											$inner_states = \app\models\States::find()->where(['!=','ID', $state->ID])->all();
											foreach($inner_states as $inner_state) {
												$state_next = \app\models\StatesNext::find()->where(['STATE_ID' => $state->ID, 'NEXT_STATE_ID'=>$inner_state->ID, 'DEL_TRACT_ID' => 0])->one();
												//if($state_next) echo $state_next->NEXT_STATE_ID;
										?>
											<div class="checkbox" style="font-size: 11px;"><label><input type="checkbox" <?php if($state_next) { ?>checked<?php } ?> class="states-change-checkbox" data-parent="<?= $state->ID; ?>" name="States[<?= $state->ID; ?>][]" value="<?= $inner_state->ID; ?>"> <?= $inner_state->STATE_NAME ?></label></div>
										<?php
											}
										?>
									</div>
								</div>
							</div>
							<?php 
									}
								}
							?>
						</div>


					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
			</div>
		</div>
	</div>
</div>
<!-- /#wrapper -->
<?php $this->endBody() ?>
<?php if (Yii::$app->getSession()->hasFlash('flash_message_success')): ?>
	<?=
		$this->registerJs(
			"
				$.jGrowl('".Yii::$app->getSession()->getFlash('flash_message_success')."', {themeState: 'success-jg'});
			", 
			View::POS_END, 
			'flash_message'
		);
	?>
<?php endif; ?>
<?php if (Yii::$app->getSession()->hasFlash('flash_message_error')): ?>
	<?=
		$this->registerJs(
			"
				$.jGrowl('".Yii::$app->getSession()->getFlash('flash_message_error')."', {themeState: 'error-jg'});
			", 
			View::POS_END, 
			'flash_message'
		);
	?>
<?php endif; ?>
</body>
</html>
<?php $this->endPage() ?>
