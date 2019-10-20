<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Rule */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rule-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uid')->textInput() ?>

    <?= $form->field($model, 'device_id')->textInput() ?>

    <?= $form->field($model, 'rule_key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rule_condition')->textInput() ?>

    <?= $form->field($model, 'rule_value')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'notice')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
