# Sistema para gerenciamento de escola de música

## O que tem o sistema?
- Todo desenvolvido com TDD
- Cadastro de alunos
- Cadastro de cedente
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
- configure o arquivo .env

### Proposta
Implementar o sistema de template AdminLTE, pois o sistema para o qual foi desenvolvido foi removido para poder disponibilizar o código fonte para não ter problemas legais.
