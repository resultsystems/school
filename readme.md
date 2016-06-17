
# Sistema para gerenciamento de escola

[Vídeo com template](https://www.youtube.com/watch?v=Z11WPs8AJvY)

[Vídeo explicando a ideia do sistema](https://www.youtube.com/watch?v=kd0y0e_2dR4)

## O que tem o sistema?
- Todo desenvolvido com TDD
- Cadastro de alunos
- Cadastro de cedente (Emitente da cobrança)
- Cadastro de funcionários
- Cadastro de horários
- Cadastro de matérias
- Cadastro de lições
- Cadastro de professores
- Cadastro de turmas
- Cadastro de usuários (Aluno, Funcionário e Professor)
- Geração de boleto automático (caixa e. federal, banco do brasil, banco itau, hsbc, santander)

## Requisitos do servidor

- PHP >= 5.5.9
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
- NPM

## No terminal execute

- composer create-project --prefer-dist resultsystems/school school
- cd school
- npm install
- gulp
- configure o arquivo .env
- php artisan migrate

### Opcionalmente gere dados falsos
- php artisan db:seed --class=Fakers 

### If you get this error: *No supported encrypter found*

run:
```
php artisan key:generate
``` 

### Virtual Host

**http.conf**
```xml
<Directory /path/to/school/>
        Options Indexes FollowSymLinks
        AllowOverride All
        Order allow,deny
        Require all granted
</Directory>

<VirtualHost school.dev:80> 
     ServerAdmin your@email.dev     
     ServerName school.dev
     ServerAlias school.dev
     DocumentRoot /path/to/school/public
     ErrorLog /path/to/school/storage/logs/mysite.error.log 
     CustomLog /path/to/school/storage/logs/mysite.access.log combined
</VirtualHost>
```

**hosts**
```
127.0.0.1	school.dev
```

## Roadmap

Implementar o sistema utilizando Vue-js e Materialize.
