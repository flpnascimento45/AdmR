## AdmR
Clique [aqui](http://admr.flpnascimento45.tk/) para acessar ambiente de teste.
Projeto usando POO (Programação Orientada a Objetos), usando estrutura MVC.

## 💻 Projeto
Sistema para fechamento diário de ponto de funcionário.
Possui controle de ponto, despesas de funcionários e contas a pagar.


## :hammer_and_wrench: Features 

-   [ ] Sistema de segurança com login
-   [ ] Controle de acessos dos usuários
-   [ ] Definição dos acessos por usuário, permitindo selecionar quais telas ele pode acessar
-   [ ] Cadastro de funcionários, fornecedores e prestadores de serviço
-   [ ] Cadastro das despesas dos funcionários, como VR, VA, pagamento, etc
-   [ ] Informar no funcionário quais despesas ele tem
-   [ ] Cadastro diários dos pontos, contabilizando a entrada e despesas dos funcionários
-   [ ] Fechamento dos pontos cadastrados, para geração do contas a pagar
-   [ ] Cadastro manual de contas a pagar, em caso de fornecedores ou prestadores de serviço


## ✨ Tecnologias

-   [ ] PHP
-   [ ] PDO
-   [ ] Javascript
-   [ ] Bootstrap
-   [ ] Jquery
-   [ ] CSS
-   [ ] Ajax
-   [ ] Mysql

## Executando o projeto

Necessário possuir ambiente servidor funcionando (Apache, IIS, etc), com PHP 7+.

Utilizado banco de dados MySQL - 10.3.7 - MariaDB.

Banco está na pasta "tmp" no formato DUMP, basta restaurá-lo.

Lembre-se de renomear o arquivo conexaoDefine_Modelo.php para conexaDefine.php, e definir os acessos ao banco de dados instalado. Formato do arquivo:
 
 ```cl

<?php

define('HOST', '##HOST##');
define('DBNAME', '##DBNAME##');
define('CHARSET', 'utf8');
define('USER', '##USER##');
define('PASSWORD', '##PASSWORD##');

```


## 📄 Licença

Esse projeto está sob a licença MIT.

<br />

<div align="center">

  <a href="https://www.linkedin.com/in/felipe-nascimento-970667214/">
  
  [![Linkedin Badge](https://img.shields.io/badge/-Felipe%20Nascimento%20Alves-6633cc?style=flat-square&logo=Linkedin&logoColor=white&link=https://www.linkedin.com/in/felipe-nascimento-970667214/)](https://www.linkedin.com/in/felipe-nascimento-970667214/) 
  
  </a>
  
</div>