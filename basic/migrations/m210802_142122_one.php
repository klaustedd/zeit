<?php

use yii\db\Migration;

/**
 * Class m210802_142122_one
 */
class m210802_142122_one extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        // Cria a tabela de qualidade
        $this->createTable('qualidade', [
            'id' => $this->primaryKey(),
            'data' => $this->datetime()->notNull(),
            'responsavel' => $this->string()->notNull(),
            'telefone' => $this->string(11)->notNull(),
            'cadastrante_id' => $this->integer()->notNull(),
            'atendente_id' => $this->integer(),
            'nome' => $this->string(),
            'email_responsavel' => $this->string(512)->notNull(),
            'celular' => $this->string(11),
            'razao_social' => $this->string(256)->notNull(),
            'cnpj' => $this->string(14),
            'comentario' => $this->text(),
            'estado_implantacao_id' => $this->integer()->notNull(),
            'cota_xml' => $this->integer()->notNull(),
            'cota_bipagem' => $this->integer(),
            'cota_ged' => $this->integer(),
            'vez' => $this->integer()
        ]);

        // Adiciona uma chave estrangeira do cadastrante
        $this->addForeignKey(
            'fk_agendamento_usuario2',
            'qualidade',
            'cadastrante_id',
            'usuario',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        // Adiciona uma chave estrangeira do atendente
        $this->addForeignKey(
            'fk_agendamento_usuario3',
            'qualidade',
            'atendente_id',
            'usuario',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        // Adiciona uma chave estrangeira do cadastrante
        $this->addForeignKey(
            'fk_qualidade_estado_qualidade1',
            'qualidade',
            'estado_implantacao_id',
            'estado_implantacao',
            'id',
            'NO ACTION',
            'NO ACTION'
        );


        // Cria a tabela de implantacao
        $this->createTable('qualidade_indisponivel', [
            'id' => $this->primaryKey(),
            'motivo' => $this->string(256)->notNull(),
            'data' => $this->datetime()->notNull(),
            'operadores' => $this->integer()->notNull()
        ]);

        /*
        // Cria um usuário administrador por padrão
        $this->insert('estado_implantacao', [
            'nome' => 'Pendente',
            'cor' => '#0000ff',
        ]);

        // Cria um usuário administrador por padrão
        $this->insert('estado_implantacao', [
            'nome' => 'Realizada',
            'cor' => '#00ff00',
        ]);
        */

        $this->insert('funcao', [
            'nome' => 'Agente de Qualidade'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210802_142122_one cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210802_142122_one cannot be reverted.\n";

        return false;
    }
    */
}
