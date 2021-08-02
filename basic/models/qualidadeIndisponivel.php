<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "implantacao_indisponivel".
 *
 * @property int $id
 * @property string $motivo
 * @property string $data
 * @property int $operadores
 */
class ImplantacaoIndisponivel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'implantacao_indisponivel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['motivo', 'data', 'operadores'], 'required'],
            [['data'], 'safe'],
            [['operadores'], 'integer'],
            [['motivo'], 'string', 'max' => 256],
            [['data'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'motivo' => 'Motivo',
            'data' => 'Data',
            'operadores' => 'Operadores',
        ];
    }
}
