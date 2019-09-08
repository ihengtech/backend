<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%file_manage}}".
 *
 * @property int $id ID
 * @property int $device_id 设备 ID
 * @property string $raw_name 图像原始名称
 * @property string $unique_name 图像原始名称
 * @property string $detail DEVICE CONFIG
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class FileManage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%file_manage}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['device_id'], 'integer'],
            [['raw_name', 'unique_name'], 'required'],
            [['detail'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['raw_name', 'unique_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'device_id' => Yii::t('app', '设备 ID'),
            'raw_name' => Yii::t('app', '图像原始名称'),
            'unique_name' => Yii::t('app', '图像原始名称'),
            'detail' => Yii::t('app', 'DEVICE CONFIG'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
        ];
    }

    public static function getUploadDir()
    {
        return Yii::getAlias('@frontend') . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
    }

    public static function getUrlPath($file = null)
    {
        return 'upload/images/' . $file;
    }

    /**
     * {@inheritdoc}
     * @return FileManageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FileManageQuery(get_called_class());
    }
}
