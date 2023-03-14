# Oficina Mecânica Online
>  Sistema realizará gerenciamento de uma oficina de automobilístico e sem a necessidade de instalar nenhum programa.

O mercado de oficinas mecânicas cresceu nos últimos anos no Brasil, mesmo com a crise o número de oficinas registradas continuou aumentando. De acordo com o relatório da frota circulante, elaborado pela entidade Sindipeças com dados até 2017, apontou um acréscimo em comparação ao ano passado de 1,2% na frota brasileira de autoveículos.

O software fornece cadastramento de clientes, funcionários, fornecedores, produtos para vender ou não e pedidos.
Além disso, cada entidade tem um filtro de pesquisa avançada, gerador de relatório com três tipos (PDF, CSV e XLS p/ Excel), 
controle de permissão para usuários, gestão de menu e criação de template de e-mail e configuração do projeto.

![Imagens sobre o sistema](/screenshot/main.gif)

## Instalando

### Pré-requisitos

- PHP >= 7.1.3
- BCMath PHP Extension
- Ctype PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- **Suporte ao banco de dados:** MySQL, SQL Serve, PostgreSQL e SQLite

### 1º PASSO: Faça o download do projeto

O github oferece duas opção de download, cabe você escolher:
1. **Tradicional pelo HTTP:** [baixar pelo arquivo zip](https://github.com/marcellosilverio/oficina-mecanica.git)
2. **Git Bash:** `git clone git@github.com:marcellosilverio/oficina-mecanica.git [nome do arquivo]`
3. **SSH:** `git@github.com:marcellosilverio/oficina-mecanica.git` 

Git do customizado: https://github.com/vagton/mechanical-workshop.git

### 2º PASSO: Cria o seu banco de dados

Neste tópico não podemos ajudar você, porque é algo particular e relativo para escolher o banco de dado, alguns preferem o MySQL, 
outros a regra de negócio obriga SQL Serve e outro opta pelo SQLite. Todavia, a sua escolhe deve ser somente compatível com nosso 
sistema este são tipos de SGBDs: **MySQL, SQLite, SQL Serve e PostgreSQL**.

### 3º PASSO: Configuração do ambiente

Localiza o arquivo `.env` onde  na se encontra na raiz do projeto que são as variáveis credenciais e **nunca** devem ser expostas para outro canal ou pessoa.
Define conforme sua necessidade do sofware:

```php
APP_NAME=Oficina-Mecanica
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_LOG_LEVEL=debug
APP_URL=http://localhost
```
#### Atenção: importante informar sobre os valores possíveis para declarar na variável `DB_CONNECTION=[tipo de banco de dado]`: 

- MySQL: `DB_CONNECTION=mysql`
- SQL Serve: `DB_CONNECTION=sqlsrv `
- SQLite: `DB_CONNECTION=sqlite `
- PostgreSQL: `DB_CONNECTION=pgsql `

```php
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=oficina-mecanica
DB_USERNAME=root
DB_PASSWORD=
```

### 4º PASSO: Comandos pelo Terminal / CMD

![Comando para instalar o software](/screenshot/conf/cmd.gif)

> Atenção: verifica se sua máquina está instalado o PHP e acessível no terminal / cmd.

```sh
php -v
```

#### 4.1 PASSO: Gerador da chave de sessão
Vamos criptografar a sessão, gerando uma chave pelo próprio sistema.

```sh
php artisan key:generate
```

#### 4.2 PASSO: Executar as classes Migrations
Para gerar as tabelas do banco de dados, vamos executar o seguinte comando:

```
php artisan migrate --seed
```

#### 4.2 PASSO: Tudo OK ! Executar o servidor PHP
Agora vamos rodar o sistema pelo comando:

```
php artisan serve
```

Somente digitar a URL informado pelo terminal no navegador `Laravel development server started: <URL para acessar o navegador>`.

### PASSO FINAL: Acessar o sistema
- **E-mail:** admin@admin.com
- **Senha:** admin@123

![Tela principal do sistema](/screenshot/principal.png)

## Vídeo sobre uso do sistema

- [Cadastrar o privilégio do usuário](/screenshot/tutorial/00.mp4?raw=true)
- [Cadastramento no sistema - Parte 01](/screenshot/tutorial/01.mp4?raw=true)
- [Cadastramento no sistema - Parte 02](/screenshot/tutorial/02.mp4?raw=true)
- [Cadastramento no sistema - Parte 03](/screenshot/tutorial/03.mp4?raw=true)

## Construído com
### Frameworks
- Laravel, CRUDBooster Laravel, Jquery, Jquery Mask, AdminLTE 2

### Linguagens
- PHP, AJAX, CSS, SCSS, JS, HTML

### Webservice de terceiros
- ViaCEP

## Crédito

- Marcello Silvério - [marcellosilverio](https://github.com/marcellosilverio)
- Plínio Mendonça
- Gabriel Souza
- Leonardo Magalhães

### Agradecimentos
Agradeço a FATEC Zona Sul por oferecer esta oportunidade em construir este software para a matéria Laboratório de Engenharia de Software.


### Adaptação, criação de novas funcionaldades e melhorias
- Vagton Alves Ferreira [vagton](https://github.com/vagton/mechanical-workshop.git)
- Rodrigo Azevedo

## Licença
A estrutura do Oficina Mecânica é um software de código aberto licenciado sob a [licença MIT](/LICENSE).
