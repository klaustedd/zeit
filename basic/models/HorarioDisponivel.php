<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "horario_disponivel".
 *
 * @property int $id
 * @property string $horario
 */
class HorarioDisponivel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'horario_disponivel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['horario'], 'required'],
            [['horario'], 'unique'],
            [['horario'], 'string', 'max' => 8],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'horario' => 'Horário',
        ];
    }
}
