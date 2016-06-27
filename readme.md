
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

## Errors 

#### *No supported encrypter found*
run:
```
php artisan key:generate
``` 

#### Maximum function nesting level of '100' reached

Isso acontece porque estás utilizando xdebug. Localize o arquivo php.ini do seu apache e adicione/altere a seguinte configuração:

```
xdebug.max_nesting_level=500
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

## Quer contribuir?

- Fork o projeto
- Faça o clone
- No diretório criado, faça:
- composer install
- npm install
- copie o arquivo .env.example para .env e edite-o apontando para o banco de dados
- php artisan migrate
- php artisan db:seed --class=Fakers
- php artisan key:generate
- npm i -g gulp
- gulp      (para compilar todos os javascript)
- gulp watch     (para recompilar ao salvar os arquivos)
- Faça as alterações e faça Pull Request para o master


## Roadmap

Implementar o sistema utilizando Vue-js e Materialize.

- [x] Estrutura básica com (vue,vuex,router,resource)
- [x] Login consultando api
- [x] Incluir opções para login automático: Funcionario, Professor e Aluno 
- [ ] Melhorar a forma como exibir o erro de login
- [ ] Recuperar a senha
- [ ] Registrar no sistema
- [x] Menu responsivo e menu mobile
  - [ ] Bug ao logar, o jquery não inicializa os menus
  - [ ] Bug quando seleciona o item do menu mobile, a tela não volta para o estado atual
- [x] Router com menu carregando os forms
- [ ] Perfil
- [ ] Alunos
  - [ ] Listar Alunos
  - [ ] Cadastrar Aluno
  - [ ] Editar Aluno
- [ ] Funcionários
- [ ] Horários
- [ ] Lições
- [ ] Matérias
- [ ] Pagamentos
- [ ] Professores
- [ ] Turmas
