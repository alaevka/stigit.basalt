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
			<ul class="nav navbar-nav">
				<?php
					$permissions_for_change_permissions = \app\models\Permissions::find()->where('SUBJECT_TYPE = :subject_type and SUBJECT_ID = :user_id and DEL_TRACT_ID = :del_tract and PERM_LEVEL = :perm_level and ACTION_ID = :action', ['action' => 1, 'subject_type' => 2, 'user_id' => \Yii::$app->user->id, 'del_tract' => 0, 'perm_level' => 2])->one();
					if($permissions_for_change_permissions) {
				?>
				<li style="padding-top: 12px;" <?php if(\Yii::$app->controller->id == 'permissions' && Yii::$app->controller->action->id == 'index') { ?>class="active"<?php } ?>><a id="permissions-link" href="<?= Url::to(['permissions/index']); ?>">Права доступа</a></li>
				<?php } ?>
				<?php
					$permissions_for_states_change = \app\models\Permissions::find()->where('SUBJECT_TYPE = :subject_type and SUBJECT_ID = :user_id and DEL_TRACT_ID = :del_tract and PERM_LEVEL = :perm_level and ACTION_ID = :action', ['action' => 2, 'subject_type' => 2, 'user_id' => \Yii::$app->user->id, 'del_tract' => 0, 'perm_level' => 2])->one();
					if($permissions_for_change_permissions) {
				?>
				<li style="padding-top: 12px;" <?php if(\Yii::$app->controller->id == 'permissions' && Yii::$app->controller->action->id == 'states') { ?>class="active"<?php } ?>><a id="states-change-link" href="<?= Url::to(['permissions/states']); ?>">Смена состояний</a></li>
				<?php } ?>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li class="user-info">
					Вы авторизованны как: <b><?= \Yii::$app->user->identity->LOGIN; ?></b> (номер транзакции: <?= $transactions->ID;  ?>)<br>
					<?php
						$query = new \yii\db\Query;
				        $query->select('*')
				                ->from('STIGIT.V_DOLG_PODR')
				                ->innerJoin('STIGIT.V_F_SHRAS', 'STIGIT.V_DOLG_PODR.IDDOLG = STIGIT.V_F_SHRAS.IDDOLG ')
				                ->where('TN = \'' . \Yii::$app->user->id .'\'');
				        $command = $query->createCommand();
				        $user_dolg_podr_data = $command->queryAll();
				        $user_dolg_podr_data_block = 'табельный номер: <b>'.\Yii::$app->user->id.'</b>';
				        if($user_dolg_podr_data) {
				        	$iddolg_array = [];
				        	$idpodr_array = [];
				        	foreach ($user_dolg_podr_data as $data_dolg_podr) {
				        		if(!empty($data_dolg_podr['NAIMDOLG'])) {
				        			if(!in_array($data_dolg_podr['IDDOLG'], $iddolg_array)) {
				        				$user_dolg_podr_data_block .= ', должность <b>'.$data_dolg_podr['NAIMDOLG'].'</b>';
				        				$iddolg_array[] = $data_dolg_podr['IDDOLG'];
				        			}
				        		}
				        		
				        		if(!empty($data_dolg_podr['KODPODR_M'])) {
				        			if(!in_array($data_dolg_podr['KODPODR_M'], $idpodr_array)) {
						        		//get podr
						        		$query_kodzifr = new \yii\db\Query;
						        		$query_kodzifr->select('NAIMPODR AS naimpodr')
							                ->from('STIGIT.V_F_PODR')
							                ->where('KODPODR = \'' . $data_dolg_podr['KODPODR_M'] .'\'');
							            $command_kodzifr = $query_kodzifr->createCommand();
						        		$naimpodr_data = $command_kodzifr->queryOne(); 
						        		if($naimpodr_data)
						        			$user_dolg_podr_data_block .= '<br>руководимое подразделение: <b>'.$naimpodr_data['naimpodr'].'</b>';
						        		$idpodr_array[] = $data_dolg_podr['KODPODR_M'];
						        	}
					        	}

				        	}
					    }
					    echo $user_dolg_podr_data_block;
					?>
				</li>
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
			<hr>

			<li class="submenu-li"><a href="<?= Url::to(['site/index']); ?>">Все задания</a> <?php if(!isset(Yii::$app->request->getQueryParams()['own_issues']) && !isset(Yii::$app->request->getQueryParams()['podr_issues']) && !isset(Yii::$app->request->getQueryParams()['tasks_my'])) { ?><i class="pull-right glyphicon glyphicon-ok"></i><?php } ?></li>
			<li class="submenu-li"><a href="<?= Url::to(['/site/index', 'own_issues' => 1]); ?>">Задания мне</a> <?php if(isset(Yii::$app->request->getQueryParams()['own_issues']) && Yii::$app->request->getQueryParams()['own_issues'] == 1) { ?><i class="pull-right glyphicon glyphicon-ok"></i><?php } ?></li>
			<?php
				$permissions_podr_tasks_my = \app\models\Permissions::find()->where('SUBJECT_TYPE = :subject_type and SUBJECT_ID = :user_id and DEL_TRACT_ID = :del_tract and PERM_LEVEL != :perm_level and ACTION_ID = :action', ['action' => 21, 'subject_type' => 2, 'user_id' => \Yii::$app->user->id, 'del_tract' => 0, 'perm_level' => 0])->one();
				if($permissions_podr_tasks_my) {
					if($permissions_podr_tasks_my->PERM_LEVEL == 1 || $permissions_podr_tasks_my->PERM_LEVEL == 2) {
			?>
			<li class="submenu-li"><a href="<?= Url::to(['/site/index', 'podr_issues' => 1]); ?>">Задания моему подразделению</a> <?php if(isset(Yii::$app->request->getQueryParams()['podr_issues']) && Yii::$app->request->getQueryParams()['podr_issues'] == 1) { ?><i class="pull-right glyphicon glyphicon-ok"></i><?php } ?></li>
			<?php
					}
				}
			?>
			<?php
				$permissions_podr_tasks_my = \app\models\Permissions::find()->where('SUBJECT_TYPE = :subject_type and SUBJECT_ID = :user_id and DEL_TRACT_ID = :del_tract and PERM_LEVEL != :perm_level and ACTION_ID = :action', ['action' => 23, 'subject_type' => 2, 'user_id' => \Yii::$app->user->id, 'del_tract' => 0, 'perm_level' => 0])->one();
				if($permissions_podr_tasks_my) {
					if($permissions_podr_tasks_my->PERM_LEVEL == 1 || $permissions_podr_tasks_my->PERM_LEVEL == 2) {
			?>
			<li class="submenu-li"><a href="<?= Url::to(['/site/index', 'tasks_my' => 1]); ?>">Выданные мной</a> <?php if(isset(Yii::$app->request->getQueryParams()['tasks_my']) && Yii::$app->request->getQueryParams()['tasks_my'] == 1) { ?><i class="pull-right glyphicon glyphicon-ok"></i><?php } ?></li>
			<?php
					}
				}
			?>
		</ul>
		<?php } ?>
	</div>
	<!-- /#sidebar-wrapper -->
    <?= $content ?>
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
