/*
	OCA(x) database schematic.
	chris@gatopelao.org
 */

CREATE TABLE IF NOT EXISTS user (
  id int(11) NOT NULL AUTO_INCREMENT,
  username varchar(32) NOT NULL,
  fullname VARCHAR(64) NOT NULL,
  password varchar(128) NOT NULL,
  salt varchar(128) NOT NULL,
  email varchar(128) NOT NULL,
  joined date NOT NULL,
  activationcode varchar(15) NOT NULL,
  is_active TINYINT(1) DEFAULT 0,
  is_socio TINYINT(1) DEFAULT 0,
  is_team_member TINYINT(1) DEFAULT 0,
  is_editor TINYINT(1) DEFAULT 0,
  is_manager TINYINT(1) DEFAULT 0,
  is_admin TINYINT(1) DEFAULT 0,
  PRIMARY KEY (id)
) ENGINE=INNODB DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS budget (
  id int(11) NOT NULL AUTO_INCREMENT,
  parent int(11) NULL,
  year SMALLINT(2) NOT NULL,
  csv_id varchar(20) NULL,
  csv_parent_id varchar(20) NULL,
  code varchar(20) NULL,
  label varchar(255) NULL,
  concept varchar( 255 ) NOT NULL,
  initial_provision decimal(14, 2) NOT NULL,
  actual_provision decimal(14, 2) NOT NULL,
  spent_t1 decimal(14, 2) NOT NULL,	/* 	1st trimester */
  spent_t2 decimal(14, 2) NOT NULL,	/* 	2nd trimester */
  spent_t3 decimal(14, 2) NOT NULL,	/* 	3rd trimester */
  spent_t4 decimal(14, 2) NOT NULL,	/* 	4th trimester */
  featured TINYINT(1) DEFAULT 0,
  weight int(10) DEFAULT 0,				/* order for display */
  PRIMARY KEY (id),
  FOREIGN KEY (parent) REFERENCES budget(id)
) ENGINE=INNODB DEFAULT CHARSET = utf8;

INSERT INTO budget(year, code, concept, initial_provision, actual_provision) VALUES ('2013', 0, 'root budget', 0, 0);

CREATE TABLE IF NOT EXISTS enquiry (
  id int(11) NOT NULL AUTO_INCREMENT,
  related_to int(11) NULL,	/* related to this another enquiry */
  user int(11) NOT NULL,
  team_member int(11),
  manager int(11),
  created date NOT NULL,
  assigned date,	/* date the manager assigned the consulta to a team_member */
  type TINYINT(1) DEFAULT 0, /* generic=0, budgetary=1 */
  budget int(11), /* budget pressupostario (null si és una consulta generica) */
  state int(11) DEFAULT 1,
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
  FOREIGN KEY (related_to) REFERENCES enquiry(id),
  FOREIGN KEY (user) REFERENCES user(id),
  FOREIGN KEY (team_member) REFERENCES user(id),
  FOREIGN KEY (manager) REFERENCES user(id),
  FOREIGN KEY (budget) REFERENCES budget(id)
) ENGINE=INNODB DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS block_user (
  id int(11) NOT NULL AUTO_INCREMENT,
  user int(11) NOT NULL,
  blocked_user int(11) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (user) REFERENCES user(id),
  FOREIGN KEY (blocked_user) REFERENCES user(id)
) ENGINE=INNODB DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS enquiry_subscribe (
  id int(11) NOT NULL AUTO_INCREMENT,
  enquiry int(11) NOT NULL,
  user int(11) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (user) REFERENCES user(id),
  FOREIGN KEY (enquiry) REFERENCES enquiry(id)
) ENGINE=INNODB DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS reply (
  id int(11) NOT NULL AUTO_INCREMENT,
  enquiry int(11) NOT NULL,
  created DATETIME NOT NULL,
  team_member int(11) NOT NULL,
  body LONGTEXT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (enquiry) REFERENCES enquiry(id),
  FOREIGN KEY (team_member) REFERENCES user(id)
) ENGINE=INNODB DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS comment (
  id int(11) NOT NULL AUTO_INCREMENT,
  enquiry int(11) NULL,
  reply int(11) NULL,
  created DATETIME NOT NULL,
  user int(11) NOT NULL,
  body LONGTEXT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (enquiry) REFERENCES enquiry(id),
  FOREIGN KEY (reply) REFERENCES reply(id),
  FOREIGN KEY (user) REFERENCES user(id)
) ENGINE=INNODB DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS vote (
  id int(11) NOT NULL AUTO_INCREMENT,
  reply int(11) NOT NULL,
  user int(11) NOT NULL,
  vote TINYINT(1) NOT NULL,	/* 0 = dislike, 1 = like */
  PRIMARY KEY (id),
  FOREIGN KEY (user) REFERENCES user(id),
  FOREIGN KEY (reply) REFERENCES reply(id)
) ENGINE=INNODB DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS emailtext (
	state int(11) NOT NULL,
	body LONGTEXT NOT NULL,
	PRIMARY KEY (state)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

INSERT INTO emailtext(state, body) VALUES (1, '<p>Hola %name%,</p><p>Este es un correo automático para informarte que se ha creado la consulta.<br />
												En breve se asignará a un miembro del equipo</p><p>Link<br />%link%</p><p>Cordiales Saludos,</p>');
INSERT INTO emailtext(state, body) VALUES (2, '<p>Hola,</p><p>Estamos en ello</p><p>Link<br />%link%</p><p>Cordiales Saludos,</p>');
INSERT INTO emailtext(state, body) VALUES (3, '<p>Hola,</p><p>Lo siento, desestimamos tu petición</p><p>Link<br />%link%</p><p>Cordiales Saludos,</p>');
INSERT INTO emailtext(state, body) VALUES (4, '<p>Hola,</p><p>Esperando respuesta de la Administración.</p><p>Link<br />%link%</p><p>Cordiales Saludos,</p>');
INSERT INTO emailtext(state, body) VALUES (5, '<p>Hola,</p><p>Respuesta con éxito</p><p>Link<br />%link%</p><p>Cordiales Saludos,</p>');
INSERT INTO emailtext(state, body) VALUES (6, '<p>Hola,</p><p>Respuesta parcialmente con éxito</p><p>Link<br />%link%</p><p>Cordiales Saludos,</p>');
INSERT INTO emailtext(state, body) VALUES (7, '<p>Hola,</p><p>Descartado por la Administración</p><p>Link<br />%link%</p><p>Cordiales Saludos,</p>');

CREATE TABLE IF NOT EXISTS email (
	id int(11) NOT NULL AUTO_INCREMENT,
	type TINYINT(1) DEFAULT 0 NOT NULL,	/* email generated by 0 = workflow, 1 = user */
	created DATETIME NOT NULL,
	sent TINYINT(1) DEFAULT 0 NOT NULL,
	title varchar( 255 ) NOT NULL,
	sender int(11) NULL,
  	sent_as varchar(128) NOT NULL,
	recipients LONGTEXT NOT NULL,
	enquiry int(11) NOT NULL,
	body LONGTEXT NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (sender) REFERENCES user(id),
	FOREIGN KEY (enquiry) REFERENCES enquiry(id)
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

INSERT INTO cms_page(pagename, block, body, pageTitle) VALUES ('about-us', 0, '<p>hello world</p>', 'About us');
INSERT INTO cms_page(pagename, block, body, pageTitle) VALUES ('council', 1, '<p>hello world</p>', 'The council');

CREATE TABLE IF NOT EXISTS file (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) NULL,
  uri varchar(255) NOT NULL,		/* file system location */
  webPath varchar(255) NULL,	/* 'http://site.com'.$webPath */
  model varchar(32) NOT NULL,
  model_id int(11) NULL,
  PRIMARY KEY (id)
) ENGINE=INNODB DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS config (
  parameter VARCHAR(64) PRIMARY KEY,
  value varchar(255) NOT NULL ,
  description varchar(255) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

INSERT INTO config(parameter, value, description) VALUES ('councilName', 'City of Kaipara Flats', 'Name of the council');
INSERT INTO config(parameter, value, description) VALUES ('year', '2013', 'Default Year (this year)');
INSERT INTO config(parameter, value, description) VALUES ('siglas', 'OCA(x)', 'Observatory\'s initials');
INSERT INTO config(parameter, value, description) VALUES ('observatoryName', 'Observatori ciutadà de l\'Ajuntament de XXXXXX', 'Observatory name');
INSERT INTO config(parameter, value, description) VALUES ('telephone', '666 666 666', 'Contact telephone');
INSERT INTO config(parameter, value, description) VALUES ('emailContactAddress', 'contact@ocax.es', 'Contact email address');
INSERT INTO config(parameter, value, description) VALUES ('emailNoReply', 'no-reply@ocax.es', 'no-reply email address');
INSERT INTO config(parameter, description) VALUES ('smtpHost', 'SMTP Server');
INSERT INTO config(parameter, description) VALUES ('smtpPort', 'SMTP Port');
INSERT INTO config(parameter, value, description) VALUES ('smtpAuth', '0', 'SMTP Auth (0 or 1)');
INSERT INTO config(parameter, description) VALUES ('smtpSecure', 'SMTP Secure');
INSERT INTO config(parameter, description) VALUES ('smtpUsername', 'SMTP Username');
INSERT INTO config(parameter, description) VALUES ('smtpPassword', 'SMTP Password');






