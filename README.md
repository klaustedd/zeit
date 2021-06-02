
# 📅 zeit

**O projeto open-source do TEDD de agendamento de implantações e suporte.** 
*Desenvolvido pelos devs do 🐻 TEDD para os 🌎 devs do mundo todo.*

A principal linguagem utilizada é *PHP* ~~(nos perdoem por isso devs)~~ com **Yii2 Framework** ~~(por isso também).~~

### Recomendamos fortemente

 - Utilização do Composer;
 - Instalação do PHP  no Path de variáveis;
 - NÃO MEXER NO CORE DO YII2 <3;

### Iniciando o desenvolvimento

 - **Crie um esquema** no banco de dados (MySql) chamado ***zeit***;
 - Configure o **db.php** na pasta **basic/config**;
 - Recomendamos colocar também uma ***cookieValidationKey*** no **web.php** na pasta ***basic/config***;

Supondo a utilização de *Git Bash no Windows*

    git clone https://github.com/klaustedd/zeit.git
    cd zeit/basic
    composer install
    ./yii migrate/up
    
Essa sequência faz o clone do repositório, instala as dependências e cria o esquema do banco de dados.

Será criado também um usuário administrador padrão: 
**admin@tedd.com.br
admin123**
