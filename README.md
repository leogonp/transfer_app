# Transfer App

Essa API possui um endpoint que gerencia a transferência monetária entre dois usuários.
Abaixo estão algumas dicas para instalação e configuração do projeto.

## Download do Projeto

### Clonando o repositório

    git clone https://github.com/leogonp/transfer_app.git

## Configurações do ambiente

Crie um .env dentro da pasta src, utilizando o .env.example como referência.

### Instalando as bibliotecas e subindo o container
Dentro da pasta `transfer_app`, execute o seguinte comando para poder subir a o container da aplicação:
    
    make run

A aplicação rodará na porta 30001.

### Rodando os testes

Para rodar os testes unitários, basta rodar o comando abaixo:

    make test
IMPORTANTE: O teste executa operações de checagens de persistência no banco. Ele não pode ser rodado em banco de produção.

## Autenticação
Para utilização da api, é necessário enviar um token via header. 
É possível obter o token via o seguinte endpoint:

`POST /login`

### Body

    {
        "login" : "EMAILDELOGIN",
        "password" : "SENHA"
    }

### CURL
Exemplo de cURL:

        curl --location --request POST 'http://localhost:30001/login' \
        --header 'Content-Type: application/json' \
        --data-raw '{
            "login" : "admin@admin.com",
            "password" : "secretPass"
        }'
    

O retorno do endpoint será um token que poderá ser usado no endpoint de transferência.

## Transferência
Abaixo está um exemplo de uso do endpoint.

`POST /transaction`

### Header
O token obtido no login deve ser passado como api-token no header:

    api-token:TOKENGERADONOLOGIN

### Body
O body deve ter a seguinte forma:

    {
    	"value" : 3.50,
    	"payer" : 2,
    	"payee" : 15
	}
Onde, `value` será o valor a ser pago, `payer` o id do pagador  e `payee` o id do recebedor.

###cURL

    curl --location --request POST 'http://localhost:30001/transaction' \
            --header 'api-token: nBqDzt0XIHzaOZ0LXNYjm7xyjt30H42DNdiE9PWbGx0nVs8SkGoHlSbG7FKx7erztCrlw3wrcvsarUN5' \
            --header 'Content-Type: application/json' \
            --data-raw '{
            "value" : 2.50,
            "payer" : 1,
            "payee" : 15
            }'

### Response
Se tudo ocorrer com sucesso, a resposta terá com código http 200 e uma mensagem no seguinte formato:

    {
    	"message": "Transação realizada com sucesso.",
    	"transactionId": 9
	}


## Documentação
Para mais detalhes sobre a aplicação, acesse o endpoint:

	/swagger/index.html