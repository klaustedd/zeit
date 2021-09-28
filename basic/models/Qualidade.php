<?php

namespace app\models;

use Yii;
use yii\web\ForbiddenHttpException;

/**
 * This is the model class for table "implantacao".
 *
 * @property int $id
 * @property string $data
 * @property string $responsavel
 * @property string $telefone
 * @property int $cadastrante_id
 * @property int|null $atendente_id
 * @property string $email_responsavel
 * @property string|null $celular
 * @property string $razao_social
 * @property string|null $cnpj
 * @property string|null $comentario
 * @property int $estado_implantacao_id
 * @property int $cota_xml
 * @property int $cota_bipagem
 * @property int $cota_ged
 *
 * @property Usuario $cadastrante
 * @property Usuario $atendente
 * @property EstadoImplantacao $estadoImplantacao
 */
class Qualidade extends \yii\db\ActiveRecord
{

    public $hora = "";

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'qualidade';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[/*'data',*/'responsavel', 'telefone', 'cadastrante_id', 'email_responsavel', 'razao_social', 'estado_implantacao_id', 'hora', 'cota_xml'], 'required'],
            [['data'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['data'], 'isNotWeekend'],
            [['cadastrante_id', 'atendente_id', 'vez', 'cota_bipagem', 'cota_ged', 'cota_xml'], 'integer'],
            [['comentario'], 'string'],
            [['responsavel', 'razao_social'], 'string', 'max' => 256],
            [['telefone', 'celular'], 'string', 'max' => 11],
            [['email_responsavel', 'nome'], 'string', 'max' => 256],
            //[['cnpj'], 'string', 'max' => 14],
            [['cnpj'], 'isCnpj'],
            [['cadastrante_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['cadastrante_id' => 'id']],
            [['atendente_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['atendente_id' => 'id']],
            [['estado_implantacao_id'], 'exist', 'skipOnError' => true, 'targetClass' => EstadoImplantacao::className(), 'targetAttribute' => ['estado_implantacao_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vez' => 'D+ x',
            'nome' => 'Qualidade',
            'data' => 'Data Agendada',
            'responsavel' => 'Responsável',
            'telefone' => 'Telefone',
            'cadastrante_id' => 'Cadastrante',
            'atendente_id' => 'Implantação',
            'email_responsavel' => 'Email Responsável',
            'celular' => 'Celular',
            'razao_social' => 'Razão Social',
            'cnpj' => 'CNPJ',
            'comentario' => 'Comentário',
            'estado_implantacao_id' => 'Estado de Implantação',
            'hora' => 'Horário',
            'cota_bipagem' => 'Cota Bipagem',
            'cota_ged' => 'Cota GED',
            'cota_xml' => 'Cota XML'
        ];
    }

    /**0
     * Gets query for [[Cadastrante]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCadastrante()
    {
        return $this->hasOne(Usuario::className(), ['id' => 'cadastrante_id']);
    }

    /**
     * Gets query for [[Atendente]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAtendente()
    {
        return $this->hasOne(Usuario::className(), ['id' => 'atendente_id']);
    }

    /**
     * Gets query for [[EstadoImplantacao]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEstadoImplantacao()
    {
        return $this->hasOne(EstadoImplantacao::className(), ['id' => 'estado_implantacao_id']);
    }

    /**
     * Obtém os horários disponíveis
     */
    public static function getHorasDisponiveis($data)
    {

        // Busca os horários possíveis
        $horariosPossiveis = HorarioDisponivel::find()->all();

        $horarios = [];

        foreach ($horariosPossiveis as $hora) {

            $horarios[$hora->horario] = $hora->horario;
        }

        return $horarios;
    }

    public function horarioDisponivel($attribute, $params)
    {
        if ($this->isNewRecord) {
            $totalAgentes = Usuario::find()->where(['funcao' => Funcao::find()->where(['nome' => 'Agente de Suporte'])->one()->id])->count();
            $count = Implantacao::findBySql("SELECT * FROM implantacao WHERE data = :data", ['data' => $this->data])->count();

            if ($count >= $totalAgentes) {
                $this->addError('hora', 'O horário estava indisponível! Selecionamos um posterior.');
            }
        }
    }

    public function isNotWeekend($attribute, $params)
    {
        if (date('N', strtotime($this->data)) >= 6) {
            $this->addError('data', 'Não é possível realizar implantações em finais de semana.');
        }
    }

    public function isCnpj($attribute, $params)
    {
        if (!preg_match("(\d{14})", $this->cnpj) && !preg_match("/^\d{2}\.\d{3}\.\d{3}\/\d{4}\-\d{2}$/", $this->cnpj)) {
            $this->addError('cnpj', 'Formato de CNPJ inválido.');
        }
    }

    public function beforeSave($insert)
    {
        $this->cnpj = preg_replace("(\D)", "", $this->cnpj);
        if (parent::beforeSave($insert)) {
            return true;
        }
        return false;
    }
}
