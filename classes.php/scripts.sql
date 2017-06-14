create table usuario(
    id int not null AUTO_INCREMENT PRIMARY KEY ,
    nome VARCHAR(55) not null,
    login VARCHAR(25) not null,
    senha VARCHAR(18) not null,
    usuarioTipo VARCHAR(55) not null,
    ultimoAcesso DATETIME,
    logado BOOLEAN
);

create table equipe(
    id int not null AUTO_INCREMENT PRIMARY KEY ,
    nome varchar(55) not null,
    categoria varchar(55) not null
);

create table equipe_membros(
    id int not null AUTO_INCREMENT PRIMARY KEY,
    equipe_id int not null,
    usuario_id int not null,
    FOREIGN KEY (equipe_id) REFERENCES equipe(id),
    FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

create table projeto(
	  id int not null auto_increment primary key,
    nome varchar(55) not null,
    descricao text(255) not null,
    equipe int not null,
    status varchar(55) not null,
    dataInicio datetime,
    dataTermino datetime,
    FOREIGN KEY(equipe) REFERENCES equipe(id)
);

create table sprint(
    id int not null AUTO_INCREMENT PRIMARY KEY ,
    nome varchar(55) not null,
    descricao TEXT(255) not null,
    status VARCHAR(55) not null,
    projeto_id int not null,
    FOREIGN KEY (projeto_id) REFERENCES projeto(id)
);

create table sprint_responsaveis (
    id         INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    sprint_id  INT NOT NULL,
    usuario_id INT NOT NULL,
    FOREIGN KEY (sprint_id) REFERENCES sprint (id),
    FOREIGN KEY (usuario_id) REFERENCES usuario (id)
);

create table mensagem (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    remetente INT NOT NULL,
    destinatario INT NOT NULL,
    mensagem TEXT(255) NOT NULL,
    dataEnvio DATETIME NOT NULL,
    FOREIGN KEY (remetente) REFERENCES usuario(id),
    FOREIGN KEY (destinatario) REFERENCES usuario(id)
);
