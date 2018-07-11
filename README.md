### Pré-requisitos

**A instalação padrão do GIT via repositório do Ubuntu/Debian consiste em um simples comando 'apt-get'**
```
$ sudo apt-get update
$ sudo apt-get install git-core
```
**A instalação padrão do GIT via repositório do CentOS consiste em um simples comando 'yum'**
```
$ sudo apt-get install git -y
```

**Para testar a versão instalada digite o comando `git --version` e tera algo como a saída abaixo (Qualquer Distribuição)**
```
git version 1.8.3.1
```
## Sistema Operacional 
**Para descobrir a versão do sistema operacional digite o comando**
```
# lsb_release -irc
```
ou 
```
# lsb_release -a
```
**O retorno será algo como o trecho abaixo:**
```
Distributor ID: Ubuntu
Release:        18.04
Codename:       bionic
```
**Caso não funcione, tente este outro comando**
```
# cat /etc/*-release | grep PRETTY
```
**O retorno será algo como o trecho abaixo:**
```
PRETTY_NAME="CentOS Linux 7 (Core)"
```

### Conteúdo
- [Sobre o Testlink](#sobre-o-testlink)
- [Sobre este projeto](#sobre-este-projeto)
- [Customizações no Testlink](#customizações-no-testlink)
- [Iniciando/parando os containers (serviços)](#iniciandoparando-os-containers-serviços)
- [Configuração do ambiente dos serviços](#configuração-do-ambiente-dos-serviços)

### Sobre o Testlink
> Testlink é uma ferramenta web de gerenciamento e sistema de execução de testes. Possibilita que os times de garantia de qualidade criem e gerenciem casos de testes, além de organizá-los em planos de testes. Esses planos permitem que os membros dos times executem casos de testes e rastreiem os resultados de forma dinâmica.

[Repositório GitHub - Testlink](https://github.com/TestLinkOpenSourceTRMS/testlink-code)

### Sobre este projeto
Este projeto permite executar uma instalação limpa e original do Testlink através de containers Docker. São utilizadas duas imagens para gerar os containers: **mariadb** e uma própria baseada na **webdevops/php-nginx:alpine-php7** (descrita pelo arquivo _[/testlink_web.dockerfile](https://github.com/alyssontkd/testlink-docker/blob/master/testlink_web.dockerfile)_). Ambas podem ser encontradas no [Docker Hub](http://hub.docker.com).

### Customizações no Testlink
O Testlink deste projeto possui configurações personalizadas de e-mail e autenticação (via LDAP), definidas no arquivo [context/web/app/custom_config.inc.php](https://github.com/alyssontkd/testlink-docker/blob/master/context/web/app/custom_config.inc.php).

Além disso, como o Testlink não permite o upload de imagens em várias das telas, foi incluído um gerenciador simples de arquivos com autenticação simples (usuário e senhas definidos (chaves `TL_FILES_*`) no arquivo `testlink_web.env`). Foram feitas alterações para limitar a funcionalidade do mesmo para permitir o upload de somente imagens e operações simples de arquivos e pastas.

Para acessar o gerenciador de arquivos, utilize a URL: `http://<servidor-testlink>/file-manager.php`.

**Dica:** ao enviar a imagem, é possível se referir a ela através de URL absoluta, sendo necessário indicar somente o prefixo da pasta (normalmente, `files`): `/files/imagem_enviada.jpg`.

### Iniciando/parando os containers (serviços)
Para facilitar a execução dos containers, é recomendado o uso do `docker-compose`. As configurações dos containers (**serviços** no contexto do Docker Compose) encontram-se no descritor _[/docker-compose.yml](https://github.com/alyssontkd/testlink-docker/blob/master/docker-compose.yml)_.

Os serviços que compoem o _stack_ são:
- **web:** PHP e Nginx, expondo a porta 80;
- **db:** MariaDB, expondo a porta 3306.

#### a) Iniciando os serviços:
Basta executar o comando abaixo no diretório raíz da cópia local deste repositório:
```bash
# Primeira execução (constrói e inicia os serviços):
$ docker-compose up -d --build

# Inicia os serviços listados:
$ docker-compose start testlink_web testlink_db
```
Os containers irão iniciar em background (`-d`) depois que as imagens forem construídas (`--build`).

#### b) Parando os serviços:
Também no diretório raíz da cópia local:
```bash
# Para os serviços listados:
$ docker-compose stop testlink_web testlink_db

# Para todos os serviços e destrói os containers, mantendo os volumes:
$ docker-compose down
```
### Configuração do ambiente dos serviços
Os serviços utilizam volumes gerenciados pelo Docker (**não** bind mounted), então não dependem de diretórios específicos no servidor. A única configuração externa que deve ser feita é liberar, no servidor, as portas definidas no descritor do Docker Compose, para permitir o acesso.

Toda a configuração é baseada em variáveis de ambientes passadas para o Docker nos momentos da construção das imagens e execução dos serviços, através de arquivos **.env**, cujos nomes estão especificados no descritor do Docker Compose. Seguem templates para os mesmos:

**testlink_web.env**: utilizado pelo serviço `web`:
```
# TESTLINK
TL_ADMIN=testlink
TL_ADMIN_EMAIL=central.millenium@gmail.com

# FILE MANAGER
TL_FILES_ADMIN=testlink
TL_FILES_ADMIN_PSWD=********

# EMAIL
TL_SMTP_HOST=smtp.gmail.com
TL_SMTP_USER=central.millenium@gmail.com
TL_SMTP_PASSWORD=********
TL_SMTP_PORT=587
# Connection mode - can be '', 'ssl','tls'
TL_SMTP_CONN_MODE=

# LDAP
TL_LDAP_SERVER=ldap.acthosti.com.br
TL_LDAP_PORT=389
TL_LDAP_ROOT_DN=DC=ACTHOS,DC=COM,DC=BR
TL_LDAP_BIND_DN=user_ldap@acthosti.com.br
TL_LDAP_BIND_PASSWORD=********
TL_LDAP_USE_TLS=false
TL_LDAP_UID_FIELD=sAMAccountName
TL_LDAP_EMAIL_FIELD=mail
TL_LDAP_FIRSTNAME_FIELD=givenname
TL_LDAP_SURNAME_FIELD=sn
```

**testlink.env**: utilizado pelo serviço `web` e `db`:
```properties
MYSQL_HOST=db
MYSQL_DATABASE=testlink
MYSQL_USER=user_testlink
MYSQL_PASSWORD=***************
MYSQL_ROOT_PASSWORD=***************
```
**Obs:** a variável `MYSQL_HOST` deve apontar para o nome do serviço do banco de dados, definido no descritor do Docker Compose.
**Obs2:** Para habilitar a autenticação via LDAP, descomente a linha `#$tlCfg->authentication['method'] = 'LDAP';` no arquivo`context/web/app/custom_config.inc.php` antes de compilar a imagem.

***Dados de Acesso:** usuário `testlink` e senha `12345678`.
