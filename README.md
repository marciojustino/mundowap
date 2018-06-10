# Colocando o projeto em funcionamento
* Instalar o [docker](https://www.docker.com/) no sistema.
* No diretório raiz do projeto executar o seguinte comando:
```bash
<project-folder>/docker-compose up -d
```
Esse comando irá subir 2 containers para o serviço da aplicação: Nginx e MySQL

## Base de dados
Após os containers subirem, será necessário executar o script inicial da base de dados no MySQL.
Localização do arquivo de script:
```
<project-folder>/server/database/script.sql
```

Para se conectar ao MySQL utilizar as seguintes credenciais:
```
host: 127.0.0.1
username: homestead
password: secret
port: 3306
```

O serviço da aplicação estará escutando no seguinte endereço:
```
http://localhost:8080/
```

## Cliente
Após os containers subirem a aplicação web estará executando no seguinte endereço:
```
http://localhost:4200/
```

# Testando a aplicação
No script do banco de dados o seguinte usuário é incluído por padrão para os testes:
```
username: marciojustino
password: 120681
```