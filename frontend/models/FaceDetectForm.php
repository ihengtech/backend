<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FaceDetectForm extends Model
{
    public $filename;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['filename'], 'required'],
            [['filename'], 'file'],
        ];
    }
}
