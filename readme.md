
# Sistema para gerenciamento de escola

[Vídeo com template](https://www.youtube.com/watch?v=Z11WPs8AJvY)

[Vídeo explicando o sistema a ideia](https://www.youtube.com/watch?v=kd0y0e_2dR4)

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
Opcionalmente gere dados falsos
- php artisan db:seed --class=Fakers 

### Proposta
Implementar o sistema de template AdminLTE, pois o sistema para o qual foi desenvolvido foi removido para poder disponibilizar o código fonte para não ter problemas legais.
