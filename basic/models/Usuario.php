<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "usuario".
 *
 * @property int $id
 * @property string $email
 * @property string $senha
 * @property int $funcao
 * @property string $nome
 *
 * @property Implantacao[] $agendamentos
 * @property Implantacao[] $implantacoes
 * @property Funcao $funcaoModel
 */
class Usuario extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'senha', 'funcao', 'nome'], 'required'],
            [['funcao'], 'integer'],
            [['email'], 'string', 'max' => 128],
            [['senha'], 'string', 'max' => 64],
            [['nome'], 'string', 'max' => 512],
            ['email','email'],
            [['email'], 'unique'],
            [['funcao'], 'exist', 'skipOnError' => true, 'targetClass' => Funcao::className(), 'targetAttribute' => ['funcao' => 'id']]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'senha' => 'Senha',
            'funcao' => 'Função',
            'nome' => 'Nome',
        ];
    }

     /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return Usuario::find()->where(['id' => $id])->one();
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach ([] as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return Usuario::find()->where(['email' => $email])->one();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return hash('sha256',$this->email.$this->senha);
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return  Yii::$app->getSecurity()->validatePassword($password, $this->senha);
    }
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->senha = Yii::$app->getSecurity()->generatePasswordHash($this->senha);
            return true;
        } else {
            return false;
        }
    }

      /**
     * Gets query for [[Agendamentos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAgendamentos()
    {
        return $this->hasMany(Implantacao::className(), ['cadastrante_id' => 'id']);
    }

    /**
     * Gets query for [[Implantacoes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getImplantacoes()
    {
        return $this->hasMany(Implantacao::className(), ['atendente_id' => 'id']);
    }

    /**
     * Gets query for [[Funcao0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFuncaoModel()
    {
        return $this->hasOne(Funcao::className(), ['id' => 'funcao']);
    }

    /**
     * Verifica o role do usuário
     */
    public static function isRole($roles, $usuario) {
        
        if ($usuario == null) {
            return false;
        }

        foreach ($roles as $role) {
            return $usuario->funcaoModel->nome == $role;
        }

        return false;
    }

       /**
     * Gets all as map
     */
    public static function allAsMap() {
        $funcoes = Usuario::find()->all();
        return ArrayHelper::map($funcoes,'id','nome');
    }

    public static function getAllSuporteAsMap() {
        $usuarios = Usuario::find()->where(['funcao' => Funcao::find()->where(['nome' => 'Agente de Suporte'])->one()->id])->all();
        return ArrayHelper::map($usuarios,'id','nome');
    }
}
