<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\FaceDetect */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="face-detect-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'device_id')->textInput() ?>

    <?= $form->field($model, 'file_manage_id')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'analysis_result')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
