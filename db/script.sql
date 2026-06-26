create table tipos(
    id int(1) primary key auto_increment, 
    nome varchar(14) not null
);

insert into tipos(nome) values('Admin');

create table cargos(
    id int(3) primary key auto_increment,
    nome varchar(150) not null
);
insert into cargos(nome) values('Chefe RH');


create table departamentos(
    id int(3) primary key auto_increment,
    nome varchar(150) not null
);

insert into departamentos(nome) values('Recursos Humanos');


create table areas(
    id int(3) primary key auto_increment,
    nome varchar(150) not null,
    idDepartamento int(3) not null,
    foreign key (idDepartamento) references departamentos(id)
);

insert into areas(nome, idDepartamento) values('Recrutamento', '1');


create table funcionarios(
    id int(3) primary key auto_increment,
    nome varchar(150) not null,
    nif int(9) not null,
    dataNascimento DATE not null,
    sexo varchar(20) not null,
    iban varchar(25) not null,
    niss varchar(11) not null,
    telefone varchar(20) not null,
    nacionalidade varchar(50) not null,
    morada varchar(200) not null,
    distrito varchar(50) not null,
    concelho varchar(50) not null,
    freguesia varchar(50) not null,
    codigoPostal varchar(10) not null,
    contactoEmergenciaNome VARCHAR(150) null,
    contactoEmergenciaTelefone VARCHAR(30) null,
    contactoEmergenciaParentesco VARCHAR(50) null,
    titulares int(1) not null,
    dependentes int(2) not null,
    idCargo int(3) not null,
    idArea int(3) not null,
    foreign key (idCargo) references cargos(id),
    foreign key (idArea) references areas(id)
);

insert into funcionarios(nome, nif, dataNascimento, sexo, iban, niss, telefone, nacionalidade, morada, 
distrito, concelho, freguesia, codigoPostal, contactoEmergenciaNome, contactoEmergenciaTelefone, 
contactoEmergenciaParentesco, titulares, dependentes, idCargo, idArea) values('Rodrigo Diniz','123456789',
'2001-09-15','Masculino','PT00000000000000000000000','12345678901','+351987654321','Africana',
'Rua XPTO 5esq','Setúbal','Barreiro','Barreiro e Lavradio','2830123','Nelsinho','+27821234567','Primo',
'1','1','1','1');
insert into funcionarios(nome, nif, dataNascimento, sexo, iban, niss, telefone, nacionalidade, morada, 
distrito, concelho, freguesia, codigoPostal, contactoEmergenciaNome, contactoEmergenciaTelefone, 
contactoEmergenciaParentesco, titulares, dependentes, idCargo, idArea) values('Fernando Mendes','123456789',
'2001-01-1','Masculino','PT00000000000000000000000','12345678901','+351927654321','Marroquino',
'Rua XPTO 4esq','Setúbal','Moita','Alhos Vedros','2860421','Rodrigo','+27821234567','Primaco',
'1','1','3','13');

create table utilizadores(
    id int(3) primary key auto_increment,
    nColaborador varchar(4) not null,
    password varchar(255) not null,
    email varchar(150) not null,
    idTipo int(1) not null,
    idFuncionario int(3) not null,
    foreign key (idTipo) references tipos(id),
    foreign key (idFuncionario) references funcionarios(id)
);

insert into utilizadores(nColaborador, password, email, idTipo, idFuncionario) values('f001','r','r@r.r',
'1','1');
insert into utilizadores(nColaborador, password, email, idTipo, idFuncionario) values('f002','r','f@f.f',
'1','2');


CREATE TABLE faltas (
    idFaltas int(6) primary key auto_increment,
    idFuncionario int(3) not null,
    dataInicio DATETIME NOT NULL,
    dataFim DATETIME NOT NULL,
    tipoFalta enum('parcial','diaria') not null,
    motivoFalta varchar(200) not null,
    motivo varchar(150) null,
    comentarioRH TEXT null,
    comprovativo varchar(255) null,
    estado enum('pendente','aprovado','rejeitado') null default 'pendente',
    idResponsavelRH int(3) null,
    dataPedido DATETIME null DEFAULT CURRENT_TIMESTAMP,
    dataDecisao DATETIME null,
    FOREIGN KEY (idFuncionario) REFERENCES funcionarios(id),
    FOREIGN KEY (idResponsavelRH) REFERENCES funcionarios(id)
);


CREATE TABLE pedidosFerias (
    id int(6) primary key auto_increment,
    idFuncionario int(3) not null,
    dataInicio DATE not null,
    dataFim DATE not null,
    nota TEXT null,
    estado enum('pendente','aprovado','rejeitado') not null DEFAULT 'pendente',
    idResponsavel int(3) null,
    dataPedido DATETIME not null DEFAULT CURRENT_TIMESTAMP,
    dataDecisao DATETIME null,
    comentarioResponsavel TEXT null,
    FOREIGN KEY (idFuncionario) REFERENCES funcionarios(id),
    FOREIGN KEY (idResponsavel) REFERENCES funcionarios(id)
);

insert into pedidosferias (idFuncionario, dataInicio, dataFim, estado, dataPedido) values(1, '2026-07-01', '2026-07-15', 'aprovado', NOW());


CREATE TABLE pedidosDadosFiscais (
    id int(5) primary key auto_increment,
    idFuncionario int(3) not null,
    novoNome varchar(150) null,
    novoNif int(9) NULL,
    novaDataNascimento DATE null,
    novoSexo varchar(20) null,
    novoIban varchar(25) null,
    novoNiss varchar(11) null,
    novoTelefone varchar(20) null,
    novaNacionalidade varchar(50) null,
    novaMorada varchar(200) null,
    novoDistrito varchar(50) null,
    novoConcelho varchar(50) null,
    novaFreguesia varchar(50) null,
    novoCodigoPostal varchar(10) null,
    contactoEmergenciaNome VARCHAR(150) null,
    contactoEmergenciaTelefone VARCHAR(30) null,
    contactoEmergenciaParentesco VARCHAR(50) null,
    novoTitulares int(1) null,
    novoDependentes int(2) null,
    comprovativo varchar(255) not null,
    nota TEXT null,
    estado enum('pendente','aprovado','rejeitado') not null default 'pendente',
    idResponsavelRH int(3) null,
    dataPedido DATETIME not null DEFAULT CURRENT_TIMESTAMP,
    dataDecisao DATETIME null,
    comentarioRH TEXT NULL,
    foreign key (idFuncionario) references funcionarios(id),
    foreign key (idResponsavelRH) references funcionarios(id)
);
INSERT INTO pedidosdadosfiscais(idFuncionario, novoNome, novoNif, novaDataNascimento, novoSexo, novoIban, novoNiss, novoTelefone, novaNacionalidade, novaMorada, novoDistrito, novoConcelho, novaFreguesia, novoCodigoPostal, contactoEmergenciaNome, contactoEmergenciaTelefone, contactoEmergenciaParentesco, novoTitulares, novoDependentes, comprovativo, estado, dataPedido)
VALUES ('1','Antonio Joao','123456789','2001-01-01','Masculino','PT00000000000000000000000','12345678901','+351987654321','Marroquino','Rua Maio 4esq','Setubal','Moita','Alhos Vedros','2860123','Sergio','+29782464527','Tio','1','1','pendente', 'comprovativo.pdf','2026-06-08 00:00:00')


CREATE TABLE registos (
    id int primary key auto_increment,
    idFuncionario int(3) not null,
    dataEntrada DATETIME not null,
    dataSaida DATETIME null,
    foreign key (idFuncionario) references funcionarios(id)
);