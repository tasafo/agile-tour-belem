DROP TABLE IF EXISTS tipo_inscricao ;

CREATE  TABLE IF NOT EXISTS tipo_inscricao (
  id INT NOT NULL AUTO_INCREMENT ,
  descricao VARCHAR(45) NOT NULL ,
  valor DOUBLE NOT NULL ,
  status CHAR(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (id) ,
  UNIQUE INDEX descricao_UNIQUE (descricao ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 1;


DROP TABLE IF EXISTS inscricao ;

CREATE  TABLE IF NOT EXISTS inscricao (
  id INT NOT NULL AUTO_INCREMENT ,
  data_registro DATETIME NOT NULL ,
  data_pagamento DATETIME NULL ,
  tipo_pagamento VARCHAR(50) NULL ,
  status_transacao VARCHAR(50) NULL ,
  transacao_id VARCHAR(100) NULL ,
  id_tipo_inscricao INT NOT NULL ,
  id_empresa INT NULL DEFAULT 0,
  PRIMARY KEY (id) ,
  INDEX fk_inscricao_tipo_inscricao1 (id_tipo_inscricao ASC) ,
  CONSTRAINT fk_inscricao_tipo_inscricao1
    FOREIGN KEY (id_tipo_inscricao )
    REFERENCES tipo_inscricao (id )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1
PACK_KEYS = DEFAULT;


DROP TABLE IF EXISTS endereco ;

CREATE  TABLE IF NOT EXISTS endereco (
  id INT NOT NULL AUTO_INCREMENT ,
  endereco VARCHAR(60) NOT NULL ,
  numero INT NOT NULL ,
  complemento VARCHAR(60) NULL ,
  bairro VARCHAR(45) NOT NULL ,
  cep VARCHAR(8) NOT NULL ,
  cidade VARCHAR(45) NOT NULL ,
  uf VARCHAR(2) NOT NULL ,
  PRIMARY KEY (id) )
ENGINE = InnoDB
AUTO_INCREMENT = 1;


DROP TABLE IF EXISTS empresa ;

CREATE  TABLE IF NOT EXISTS empresa (
  id INT NOT NULL AUTO_INCREMENT ,
  cnpj VARCHAR(14) NOT NULL ,
  razao_social VARCHAR(100) NULL ,
  nome_fantasia VARCHAR(100) NOT NULL ,
  nome_responsavel VARCHAR(60) NOT NULL ,
  telefone VARCHAR(10) NOT NULL ,
  email VARCHAR(45) NOT NULL ,
  senha VARCHAR(45) NOT NULL ,
  id_endereco INT NOT NULL ,
  ddd INT NOT NULL ,
  PRIMARY KEY (id) ,
  UNIQUE INDEX razao_social_UNIQUE (razao_social ASC) ,
  UNIQUE INDEX cnpj_UNIQUE (cnpj ASC) ,
  UNIQUE INDEX email_UNIQUE (email ASC) ,
  INDEX fk_empresa_endereco1 (id_endereco ASC) ,
  CONSTRAINT fk_empresa_endereco1
    FOREIGN KEY (id_endereco )
    REFERENCES endereco (id )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1;


DROP TABLE IF EXISTS individual ;

CREATE  TABLE IF NOT EXISTS individual (
  id INT NOT NULL AUTO_INCREMENT ,
  nome VARCHAR(60) NOT NULL ,
  cpf VARCHAR(11) NOT NULL ,
  email VARCHAR(45) NOT NULL ,
  nome_cracha VARCHAR(60) NOT NULL ,
  senha VARCHAR(20) NOT NULL ,
  empresa VARCHAR(100) NOT NULL ,
  ddd INT NOT NULL ,
  telefone VARCHAR(10) NOT NULL ,
  sexo CHAR(1) NOT NULL DEFAULT 'M',
  situacao CHAR(1) NOT NULL DEFAULT 'A',
  id_inscricao INT NOT NULL ,
  id_endereco INT NOT NULL ,
  UNIQUE INDEX cpf_UNIQUE (cpf ASC) ,
  UNIQUE INDEX email_UNIQUE (email ASC) ,
  PRIMARY KEY (id) ,
  INDEX fk_individual_inscricao1 (id_inscricao ASC) ,
  INDEX fk_individual_endereco1 (id_endereco ASC) ,
  CONSTRAINT fk_individual_inscricao1
    FOREIGN KEY (id_inscricao )
    REFERENCES inscricao (id )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_individual_endereco1
    FOREIGN KEY (id_endereco )
    REFERENCES endereco (id )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 1;