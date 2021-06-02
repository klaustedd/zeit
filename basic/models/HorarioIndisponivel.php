<?php

namespace app\models;

use DateTime;
use Yii;

/**
 * This is the model class for table "horario_indisponivel".
 *
 * @property int $id
 * @property string $data_inicio
 * @property string $data_fim
 * @property string $motivo
 */
class HorarioIndisponivel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'horario_indisponivel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['data_inicio', 'data_fim', 'motivo'], 'required'],
            [['data_inicio', 'data_fim'], 'safe'],
            [['data_fim'], 'dataPosterior'],
            [['motivo'], 'string', 'max' => 256],
        ];
    }

    /**
     * Valida as datas antes de persistir
     */
    public function dataPosterior($attribute_name, $params)
    {
        $dataInicioFormatada = DateTime::createFromFormat('d/m/Y H:i:s', $this->data_inicio);
        $dataFimFormatada = DateTime::createFromFormat('d/m/Y H:i:s', $this->data_fim);
        if ($dataFimFormatada <= $dataInicioFormatada) {
            $this->addError($attribute_name, 'Data de fim deve ser posterior à data de inicio.');
            return false;
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'data_inicio' => 'Data Inicio',
            'data_fim' => 'Data Fim',
            'motivo' => 'Motivo',
        ];
    }

    public function formatarDataParaPTBR()
    {
        $this->data_inicio = DateTime::createFromFormat('Y-m-d H:i:s', $this->data_inicio)->format('d/m/Y H:i:s');
        $this->data_fim = DateTime::createFromFormat('Y-m-d H:i:s', $this->data_fim)->format('d/m/Y H:i:s');
    }

    public function beforeSave($insert)
    {

        $this->data_inicio = DateTime::createFromFormat('d/m/Y H:i:s', $this->data_inicio)->format('Y-m-d H:i:s');
        $this->data_fim = DateTime::createFromFormat('d/m/Y H:i:s', $this->data_fim)->format('Y-m-d H:i:s');

        if (!parent::beforeSave($insert)) {
            return false;
        }

        return true;
    }

    public static function verificarSeHoraEstaIndisponivel($data)
    {
        return HorarioIndisponivel::findBySql("SELECT * FROM horario_indisponivel WHERE :data >= data_inicio AND :data <= data_fim", ['data' => $data])->count() > 0;
    }
}
