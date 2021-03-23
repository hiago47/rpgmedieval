# RPG Medieval

Jogo de RPG que roda por API. Recebe comandos via GET e retorna um JSON com o resultado de cada jogada. 

## Tecnologias
- Laravel 8.33.1
- PHP 7.4
- MySQL

## Instalação
- Após clonar esse repositório acesse a pasta onde baixou via terminal e execute:
    ```
    composer install
    ```
- Aguarde instalar todas as dependências do framework
- Ainda no terminal:
    - Gere o arquivo .env:
    ```
    cp .env.example .env
    ```
    - Gere a APP Key:
    ```
    php artisan key:generate
    ```

- Banco de Dados
    - Crie o banco MySQL e configure o acesso no arquivo .env, exemplo:
    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=gamerpg
    DB_USERNAME=root
    DB_PASSWORD=
    ``` 
    - Para criar as tabelas rode no terminal:
    ``` 
    php artisan migrate
    ```
    - É necessário alimentar as tabelas de personagens e atributos, para isso rode:
    ```
    php artisan db:seed
    ```

## Passo a passo do desenvolvimento e as Rotas
- **JogoController**
    - Eu agrupei o registro de jogador e batalha neste controller, isso facilitou pois é uma requisição a menos antes de iniciar o jogo: 
Ao escolher o nickname e classe de herói, um registro da batalha é gerado com um oponente defino. Dispensando ter que cadastrar um jogador para depois escolher a classe. 

<br/>

```
GET /api
```
Retorna em JSON uma introdução, instruções para inicio de jogo e lista dos heróis com os atributos

<br/>

```
POST /api/registrar - nickname - heroi 
```
Recebe os campos via POST 'nickname' e 'heroi' (ID do herói escolhido), os dois campos são obrigatórios e tem regras definidas de validação. 

**O nickname é único** e ao registrar é verificado se o jogador já existe e se ele tem uma batalha em andamento. Caso já tenha uma batalha em andamento o da ID da batalha é recuperado e o jogador continua com a classe escolhida no registro desta batalha. 
Só é possível jogar com outra classe de herói numa próxima batalha. 
    
