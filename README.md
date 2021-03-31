# Transfer App

Essa API possui um endpoint que gerencia a transferência monetária entre dois usuários.
Abaixo estão algumas dicas para instalação e configuração do projeto.

## Download do Projeto

### Clonando o repositório

    git clone https://github.com/leogonp/transfer_app.git

### Instalando as bibliotecas

    cd transfer_app/src
    composer install

## Configurações do ambiente

Crie um .env, utilizando o .env.example como referência.
Preencha todas as informações de acordo com o ambiente de onde será rodada a aplicação.  

### Criando as tabelas

	php artisan migrate
 
 Caso queira também seedar as tabelas com dados de teste, rode com o parametro --seed

	php artisan migrate --seed

### Rodando os testes

    ./run-tests.sh

## Autenticação
Para utilização da api, é necessário enviar um token via header. 
É possível obter o token via o seguinte endpoint:

`POST /login`

### Body

    {
        "login" : "EMAILDELOGIN",
        "password" : "SENHA"
    }

O retorno do endpoint será um token que poderá ser usado no endpoint de transferência.

## Transferência
Abaixo está um exemplo de uso do endpoint.

`POST /transaction`

### Header

    api-token:TOKENGERADONOLOGIN

### Body

    {
    	"value" : 3.50,
    	"payer" : 2,
    	"payee" : 15
	}

### Response

    {
    	"message": "Transação realizada com sucesso.",
    	"transactionId": 9
	}


## Documentação
Para mais detalhes sobre a aplicação, acesse o endpoint:

	/public/swagger