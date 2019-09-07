<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\FaceDetect */

$this->title = Yii::t('app', 'Create Face Detect');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Face Detects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="face-detect-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
