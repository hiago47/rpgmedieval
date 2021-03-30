# RPG Medieval
Jogo de RPG que roda por API Rest recebendo os comandos via GET e retornando em JSON o resultado de cada jogada. 
**Este projeto foi um desafio para um processo seletivo de dev PHP.**

## Tecnologias
- Laravel 8.33.1
- PHP 7.4
- MySQL
- Para execução: Postman Desktop

## Instalação
- Certifique-se de ter o Composer instalado: https://getcomposer.org/
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
    - Crie o BD MySQL e configure o acesso no arquivo .env, exemplo:
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
### JogoController
- Eu agrupei o registro de jogador e batalha neste controller, isso facilitou pois é uma requisição a menos antes de iniciar o jogo: 
Ao escolher o nickname e classe de herói, um registro de batalha é gerado com a escolha do herói e um oponente definido aletoriamente, apenas aguardando o primeiro comando para iniciar a batalha. 
Com isso não é necessário cadastrar o jogador para depois escolher a classe.
- Criei a Trait **PersonagensTrait** para consultas comuns de **Herois** e **Monstros**, como a listagem de heróis ou a seleção aleatória do monstro.
- Rotas:

```
GET /api
```
Retorna em JSON uma introdução, instruções para inicio de jogo e lista dos heróis com os atributos.

<br/>

```
POST /api/registrar - nickname - heroi 
```
Recebe os campos 'nickname' e 'heroi'(ID do herói escolhido) via POST, os dois campos são obrigatórios e tem regras definidas de validação. Eu criei **RegistrarJogoRequest** para esta validação.  

**O nickname é único** e ao registrar é verificado se o jogador já existe e se ele tem uma batalha em andamento. Caso já tenha uma batalha em andamento o ID da batalha é recuperado e o jogador continua na batalha com a classe de herói escolhida no registro desta batalha. 
Só é possível jogar com outra classe em uma nova batalha. 

O retorno: JSON com o ID da batalha(nova ou em andamento) e com o status dos personagens(nomes, classe e pontos de vida atuais), exemplo de retorno:
```
{
    "status": "ok",
    "mensagem": "Olá novamente, hiago47!",
    "batalha": {
        "id": 21,
        "mensagem": "Você enfrentará um Orc. Boa sorte!",
        "status": {
            "personagens": {
                "heroi": {
                    "nome": "hiago47",
                    "classe": "Guerreiro",
                    "pdv": 12
                },
                "monstro": {
                    "nome": "Orc",
                    "pdv": 20
                }
            }
        }
    }
}
```
    

### BatalhaController
- Este controller trata apenas da batalha já registrada. 
- Também criei uma Trait **BatalhaTrait** para os métodos de lance de dados, definir inciativa, cálculos de ataque, defesa e dano.
- Rotas:

```
GET /api/batalha/{ID da batalha}/jogar
```
Inicia ou continua uma batalha: Calcula a iniciativa, realiza o ataque e retorna o resultado do turno. 
Caso zere os pontos de vida de um dos personagens a batalha é encerrada, o retorno vem com o nome do vitorioso e pontuação feita no caso do herói(o mesmo para as batalhas já terminadas antes). 
Em todos os casos é retornado o status atual dos personagens. Exemplo:
```
{
    "id": 33,
    "mensagem": "Você atacou o Kobold.",
    "ataque": {
        "ataque": 17,
        "defesa": 13,
        "dano": 15,
        "atacante": "heroi",
        "defensor": "monstro"
    },
    "status": {
        "personagens": {
            "heroi": {
                "nome": "hiago47",
                "classe": "Bárbaro",
                "pdv": 5
            },
            "monstro": {
                "nome": "Kobold",
                "pdv": 5
            }
        }
    }
}
```

<br/>

```
GET /api/batalha/{ID da batalha}
```
Em qualquer momento é possível consultar o status de qualquer batalha com esta rota. Incluindo os turnos em ordem cronológica. 
Retorno:
```
{
    "id": 37,
    "status": {
        "personagens": {
            "heroi": {
                "nome": "hiago47",
                "classe": "Paladino",
                "pdv": 15
            },
            "monstro": {
                "nome": "Morto-Vivo",
                "pdv": 12
            }
        },
        "turnos": [
            {
                "atacante": "heroi",
                "ataque": 11,
                "defensor": "monstro",
                "defesa": 4,
                "dano": 9
            },
            {
                "atacante": "heroi",
                "ataque": 11,
                "defensor": "monstro",
                "defesa": 7,
                "dano": 4
            }
        ]
    }
}
```

### JogadorController
- Este controller é usado apenas para a consulta de ranking dos jogadores
- Rota:
```
GET /api/ranking
```
Retorna os nicknames com as devidas pontuações por ordem de colocação:
```
{
    "ranking": [
        {
            "nickname": "hiago47",
            "pontos": "1059"
        },
        {
            "nickname": "FortySeven",
            "pontos": "187"
        }
    ]
}
```

### Mais detalhes
- Criei a Trait **ApiResponseTrait** com um método padrão de resposta JSON usado em todos os Controllers 
- Fiz alterações no **Handler.php** para padronizar o retorno das exceções em JSON 
