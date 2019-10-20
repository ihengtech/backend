<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\FileManage */

$this->title = Yii::t('app', 'Create File Manage');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'File Manages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="file-manage-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
