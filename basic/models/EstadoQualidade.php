<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "estado_implantacao".
 *
 * @property int $id
 * @property string $nome
 * @property string|null $cor
 *
 * @property Qualidade[] $implantacoes
 */
class EstadoQualidade extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'estado_qualidade';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome'], 'required'],
            [['nome'], 'string', 'max' => 45],
            [['cor'], 'string', 'max' => 7],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'cor' => 'Cor',
        ];
    }

    /**
     * Gets query for [[Implantacoes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQualidades()
    {
        return $this->hasMany(Implantacao::className(), ['estado_qualidade_id' => 'id']);
    }

    /**
     * Gets all as map
     */
    public static function allAsMap()
    {
        $funcoes = EstadoQualidade::find()->all();
        return ArrayHelper::map($funcoes, 'id', 'nome');
    }
}
