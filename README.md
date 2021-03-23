# RPG Medieval

Jogo de RPG que roda por API. Recebe comandos via GET e retorna um JSON com o resultado de cada jogada. 

# Framework utilizado
- Laravel Framework 8.33.1
- PHP 7.4

# Instalação
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

# Passo a passo do desenvolvimento e as Rotas
- *JogoController*
    - Eu agrupei o registro de jogador e batalha neste controller, isso facilitou pois é uma requisição a menos antes de iniciar o jogo: 
    Ao escolher o nickname e classe de herói, um registro da batalha é gerado com um oponente defino. Dispensando ter que cadastrar um jogador para depois escolher a classe. 

    ```
    GET /api
    ```
    Retorna em JSON uma introdução, instruções para inicio de jogo e lista dos heróis com os atributos


    
