<?php

use yii\db\Migration;

/**
 * Class m210601_201822_zero
 */
class m210601_201822_zero extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Cria a tabela de usuário
        $this->createTable('usuario', [
            'id' => $this->primaryKey(),
            'email' => $this->string()->notNull()->unique(),
            'senha' => $this->string()->notNull(),
            'funcao' => $this->integer()->notNull(),
            'nome' => $this->string()->notNull()
        ]);

        // Cria a tabela de função
        $this->createTable('funcao', [
            'id' => $this->primaryKey(),
            'nome' => $this->string()->notNull()
        ]);

        // Adiciona uma chave estrangeira ao usuário com a funcao
        $this->addForeignKey('cargo_in_usuario',
        'usuario',
        'funcao',
        'funcao',
        'id',
        'NO ACTION',
        'NO ACTION');

        // Cria a tabela de estado de implantação
        $this->createTable('estado_implantacao', [
            'id' => $this->primaryKey(),
            'nome' => $this->string()->notNull(),
            'cor' => $this->string()
        ]);

        // Cria a tabela de horario disponivel
        $this->createTable('horario_disponivel', [
            'id' => $this->primaryKey(),
            'horario' => $this->string()->notNull()->unique(),
        ]);

        // Cria a tabela de horario indisponivel
        $this->createTable('horario_indisponivel', [
            'id' => $this->primaryKey(),
            'data_inicio' => $this->datetime()->notNull(),
            'data_fim' => $this->datetime()->notNull(),
            'motivo' => $this->string()->notNull()
        ]);

        // Cria a tabela de implantacao
        $this->createTable('implantacao', [
            'id' => $this->primaryKey(),
            'data' => $this->datetime()->notNull(),
            'responsavel' => $this->string()->notNull(),
            'telefone' => $this->string(11)->notNull(),
            'cadastrante_id' => $this->integer()->notNull(),
            'atendente_id' => $this->integer(),
            'email_responsavel' => $this->string(512)->notNull(),
            'celular' => $this->string(11),
            'razao_social' => $this->string(256)->notNull(),
            'cnpj' => $this->string(14),
            'comentario' => $this->text(),
            'estado_implantacao_id' => $this->integer()->notNull(),
            'cota_xml' => $this->integer()->notNull(),
            'cota_bipagem' => $this->integer(),
            'cota_ged' => $this->integer(),
        ]);

        // Adiciona uma chave estrangeira do cadastrante
        $this->addForeignKey('fk_agendamento_usuario',
        'implantacao',
        'cadastrante_id',
        'usuario',
        'id',
        'NO ACTION',
        'NO ACTION');

        // Adiciona uma chave estrangeira do atendente
        $this->addForeignKey('fk_agendamento_usuario1',
        'implantacao',
        'atendente_id',
        'usuario',
        'id',
        'NO ACTION',
        'NO ACTION');

        // Adiciona uma chave estrangeira do cadastrante
        $this->addForeignKey('fk_implantacao_estado_implantacao1',
        'implantacao',
        'estado_implantacao_id',
        'estado_implantacao',
        'id',
        'NO ACTION',
        'NO ACTION');

        // Cria a tabela de implantacao
        $this->createTable('implantacao_indisponivel', [
            'id' => $this->primaryKey(),
            'motivo' => $this->string(256)->notNull(),
            'data' => $this->datetime()->notNull(),
            'operadores' => $this->integer()->notNull()
        ]);


        // Cria as funções padrão
        $this->insert('funcao', [
            'id' => 1,
            'nome' => 'Administrador'
        ]);
        $this->insert('funcao', [
            'nome' => 'Agente de Suporte'
        ]);
        $this->insert('funcao', [
            'nome' => 'Vendedor'
        ]);

        // Cria um usuário administrador por padrão
        $this->insert('usuario', [
            'nome' => 'TEDD',
            'email' => 'admin@tedd.com.br',
            'senha' => Yii::$app->getSecurity()->generatePasswordHash('admin123'),
            'funcao' => 1
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210601_201822_zero cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210601_201822_zero cannot be reverted.\n";

        return false;
    }
    */
}
