/*
	OCA(x) database schematic.
	chris@gatopelao.org
 */

CREATE TABLE user (
  id int(11) NOT NULL AUTO_INCREMENT,
  username varchar(32) NOT NULL,
  fullname VARCHAR(64) NOT NULL,
  password varchar(128) NOT NULL,
  salt varchar(128) NOT NULL,
  email varchar(128) NOT NULL,
  joined date NOT NULL,
  activationcode int(11) NOT NULL,
  activationstatus int(11) NOT NULL,

  is_socio TINYINT(1) DEFAULT 0,
  is_team_member TINYINT(1) DEFAULT 0,
  is_editor TINYINT(1) DEFAULT 0,
  is_manager TINYINT(1) DEFAULT 0,
  is_admin TINYINT(1) DEFAULT 0,
  PRIMARY KEY (id)
) ENGINE=INNODB DEFAULT CHARSET = utf8;

CREATE TABLE consulta (
  id int(11) NOT NULL AUTO_INCREMENT,
  user int(11) NOT NULL,
  team_member int(11),
  manager int(11),
  created date NOT NULL,
  assigned date,	/* date the manager assigned the consulta to a team_member */
  type TINYINT(1) DEFAULT 0, /* generica=0, pressupostaria=1 */
  capitulo int(11), /* capitol_pressupostario (vacio si és una consulta generica) */
  state int(11) DEFAULT 0,
/*
    0 Esperando respuesta de la OCAB
	1 OCAB reconoce la entrega (team_memeber assigned)
    2 Descartado por el OCAB
    3 Esperando respuesta de la Administración. Assignado a un team_member
    4 Respuesta con éxito
    5 Respuesta parcialmente con éxito ¿que significa esto?
    6 Descartado por la Administración
*/
  title varchar( 255 ) NOT NULL,
  body LONGTEXT,
  PRIMARY KEY (id),
  FOREIGN KEY (user) REFERENCES user(id),
  FOREIGN KEY (team_member) REFERENCES user(id),
  FOREIGN KEY (manager) REFERENCES user(id)
) ENGINE=INNODB DEFAULT CHARSET = utf8;

CREATE TABLE respuesta (
  id int(11) NOT NULL AUTO_INCREMENT,
  consulta int(11) NOT NULL,
  created DATETIME NOT NULL,
  team_member int(11) NOT NULL,
  body LONGTEXT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (consulta) REFERENCES consulta(id),
  FOREIGN KEY (team_member) REFERENCES user(id)
) ENGINE=INNODB DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS email (
	id int(11) NOT NULL AUTO_INCREMENT,
	created DATETIME NOT NULL,
	title varchar( 255 ) NOT NULL,
	sender int(11) NOT NULL,
	consulta int(11) NOT NULL,
	body LONGTEXT NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (sender) REFERENCES user(id),
	FOREIGN KEY (consulta) REFERENCES consulta(id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS cms_page (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	pagename varchar( 255 ) NOT NULL ,
	block int( 10 ) NOT NULL DEFAULT '0',
	published tinyint( 1 ) NOT NULL DEFAULT '0',
	heading varchar( 255 ) DEFAULT NULL ,
	body LONGTEXT,
	pageTitle varchar( 255 ) DEFAULT NULL ,
	weight int( 10 ) NOT NULL DEFAULT '0',
	metaTitle varchar( 255 ) DEFAULT NULL ,
	metaDescription varchar( 255 ) DEFAULT NULL ,
	metaKeywords varchar( 255 ) DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

INSERT INTO cms_page(pagename, block, body, pageTitle) VALUES ('qui-som', 0, '<p>hello world</p>', 'Qui Som?');
INSERT INTO cms_page(pagename, block, body, pageTitle) VALUES ('ajuntament', 1, '<p>hello world</p>', 'L\'Ajuntament');





