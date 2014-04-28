/*
	OCA(x) database schematic.
	http://ocax.net
	chris@gatopelao.org
 */

CREATE TABLE IF NOT EXISTS user (
  id int(11) NOT NULL AUTO_INCREMENT,
  username varchar(32) NOT NULL,
  fullname VARCHAR(64) NOT NULL,
  password varchar(128) NOT NULL,
  salt varchar(128) NOT NULL,
  email varchar(128) NOT NULL,
  language char(2) NULL,
  joined date NOT NULL,
  activationcode varchar(45) NOT NULL,
  is_active TINYINT(1) DEFAULT 0,
  is_disabled TINYINT(1) DEFAULT 0,
  is_socio TINYINT(1) DEFAULT 0,
  is_team_member TINYINT(1) DEFAULT 0,
  is_editor TINYINT(1) DEFAULT 0,
  is_manager TINYINT(1) DEFAULT 0,
  is_admin TINYINT(1) DEFAULT 0,
  PRIMARY KEY (id)
) ENGINE=INNODB DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS file (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) NULL,
  path varchar(255) NULL,
  model varchar(32) NOT NULL,
  model_id int(11) NULL,
  PRIMARY KEY (id)
) ENGINE=INNODB DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS budget (
  id int(11) NOT NULL AUTO_INCREMENT,
  parent int(11) NULL,
  year SMALLINT(2) NOT NULL,
  csv_id varchar(100) NULL,
  csv_parent_id varchar(100) NULL,
  code varchar(20) NULL,
  label varchar(255) NULL,
  concept varchar( 255 ) NOT NULL,
  initial_provision decimal(14, 2) NOT NULL,
  actual_provision decimal(14, 2) NOT NULL,
  trimester_1 decimal(14, 2) NOT NULL,	/* 	1st trimester */
  trimester_2 decimal(14, 2) NOT NULL,	/* 	2nd trimester */
  trimester_3 decimal(14, 2) NOT NULL,	/* 	3rd trimester */
  trimester_4 decimal(14, 2) NOT NULL,	/* 	4th trimester */
  featured TINYINT(1) DEFAULT 0,
  weight int(10) DEFAULT 0,				/* order for display */
  PRIMARY KEY (id),
  FOREIGN KEY (parent) REFERENCES budget(id)
) ENGINE=INNODB DEFAULT CHARSET = utf8;
INSERT INTO budget(year, code, concept, initial_provision, actual_provision) VALUES ('2014', 0, 'root budget', 10000, 0);

CREATE TABLE IF NOT EXISTS budget_desc_common (
  id int(11) NOT NULL AUTO_INCREMENT,
  csv_id varchar(100) NOT NULL,
  language char(2) NOT NULL,
  code varchar(32) NULL,
  label varchar(32) NULL,
  concept varchar( 255 ) NOT NULL,
  description MEDIUMTEXT,
  text MEDIUMTEXT,	/* description without tags */
  modified DATETIME NULL,
  FULLTEXT (concept, text),
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS budget_desc_local (
  id int(11) NOT NULL AUTO_INCREMENT,
  csv_id varchar(100) NOT NULL,
  language char(2) NOT NULL,
  code varchar(32) NULL,
  label varchar(32) NULL,
  concept varchar( 255 ) NOT NULL,
  description MEDIUMTEXT,
  text MEDIUMTEXT,	/* description without tags */
  modified DATETIME NULL,
  FULLTEXT (concept, text),
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS enquiry (
  id int(11) NOT NULL AUTO_INCREMENT,
  related_to int(11) NULL,	/* related to this another enquiry */
  user int(11) NOT NULL,
  team_member int(11),
  manager int(11),
  created date NOT NULL,
  modified DATETIME NOT NULL,
  assigned date,	/* date the manager assigned the enquiry to a team_member */
  submitted date,	/* date the team_member submitted the enquiry to the administration */
  registry_number varchar( 32 ),	/* number assigned by the council to the enquiry when submitted */
  documentation int(11),	/* the doc. scanned with stamp from the council on submission */
  type TINYINT(1) DEFAULT 0, /* generic=0, budgetary=1 */
  addressed_to TINYINT(1) DEFAULT 0 NOT NULL,	/* addressed to the administration=0, addressed to the Observatory=1 */
  budget int(11), /* budget (null is a generic enquiry) */
  state int(11) DEFAULT 1,
  title varchar( 255 ) NOT NULL,
  body MEDIUMTEXT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (related_to) REFERENCES enquiry(id),
  FOREIGN KEY (user) REFERENCES user(id),
  FOREIGN KEY (team_member) REFERENCES user(id),
  FOREIGN KEY (manager) REFERENCES user(id),
  FOREIGN KEY (documentation) REFERENCES file(id),
  FOREIGN KEY (budget) REFERENCES budget(id)
) ENGINE=INNODB DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS enquiry_text (
  /* id int(11) NOT NULL AUTO_INCREMENT, */
  enquiry int(11) NOT NULL,
  title varchar( 255 ) NOT NULL,
  body MEDIUMTEXT NOT NULL,
  FULLTEXT (title, body),
  PRIMARY KEY (enquiry)
) ENGINE=MyISAM DEFAULT CHARSET = utf8;

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
  body MEDIUMTEXT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (enquiry) REFERENCES enquiry(id),
  FOREIGN KEY (team_member) REFERENCES user(id)
) ENGINE=INNODB DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS comment (
  id int(11) NOT NULL AUTO_INCREMENT,
  model VARCHAR(50) DEFAULT NULL,
  model_id int(11) DEFAULT NULL,
  thread_position int(11) DEFAULT NULL,
  created DATETIME NOT NULL,
  user int(11) NOT NULL,
  body TEXT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (user) REFERENCES user(id)
) ENGINE=INNODB DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS comment_count (
  id int(11) NOT NULL AUTO_INCREMENT,
  model VARCHAR(50) DEFAULT NULL,
  model_id int(11) DEFAULT NULL,
  thread_count int(11) DEFAULT 1,
  PRIMARY KEY (id)
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
	body MEDIUMTEXT NOT NULL,
	PRIMARY KEY (state)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

INSERT INTO emailtext(state, body) VALUES (1, '<p>Hola %name%,</p><p>ENQUIRY_PENDING_VALIDATION</p>');
INSERT INTO emailtext(state, body) VALUES (2, '<p>Hola team_member,</p><p>This is an internal email.<br />ENQUIRY_ASSIGNED</p><p>%link%</p>');
INSERT INTO emailtext(state, body) VALUES (3, '<p>Hola,</p><p>ENQUIRY_REJECTED</p>');
INSERT INTO emailtext(state, body) VALUES (4, '<p>Hola,</p><p>ENQUIRY_ACCEPTED</p><p>Link<br />%link%</p><p>Cordiales Saludos,</p>');
INSERT INTO emailtext(state, body) VALUES (5, '<p>Hola,</p><p>ENQUIRY_AWAITING_REPLY</p><p>Link<br />%link%</p><p>Cordiales Saludos,</p>');
INSERT INTO emailtext(state, body) VALUES (6, '<p>Hola,</p><p>ENQUIRY_REPLY_PENDING_ASSESSMENT</p><p>Link<br />%link%</p><p>Cordiales Saludos,</p>');
INSERT INTO emailtext(state, body) VALUES (7, '<p>Hola,</p><p>ENQUIRY_REPLY_SATISFACTORY</p><p>Link<br />%link%</p><p>Cordiales Saludos,</p>');
INSERT INTO emailtext(state, body) VALUES (8, '<p>Hola,</p><p>ENQUIRY_REPLY_INSATISFACTORY</p><p>Link<br />%link%</p><p>Cordiales Saludos,</p>');

CREATE TABLE IF NOT EXISTS email (
	id int(11) NOT NULL AUTO_INCREMENT,
	type TINYINT(1) DEFAULT 0 NOT NULL,	/* email generated by 0 = workflow, 1 = user */
	created DATETIME NOT NULL,
	sent TINYINT(1) DEFAULT 0 NOT NULL,
	title varchar( 255 ) NOT NULL,
	sender int(11) NULL,
  	sent_as varchar(128) NOT NULL,
	recipients TEXT NOT NULL,
	enquiry int(11) NOT NULL,
	body TEXT NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (sender) REFERENCES user(id),
	FOREIGN KEY (enquiry) REFERENCES enquiry(id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS block_user (
  id int(11) NOT NULL AUTO_INCREMENT,
  user int(11) NOT NULL,
  blocked_user int(11) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (user) REFERENCES user(id),
  FOREIGN KEY (blocked_user) REFERENCES user(id)
) ENGINE=INNODB DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS newsletter (
	id int(11) NOT NULL AUTO_INCREMENT,
	created DATETIME NOT NULL,
	published DATETIME NULL,
	sent TINYINT(1) DEFAULT 0 NOT NULL,	/* 0 = draft, 1 = failed, 2 = sent */
	sender int(11) NOT NULL,
  	sent_as varchar(128) NOT NULL,
	recipients TEXT NOT NULL,
	subject varchar(255) NOT NULL,
	body TEXT NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (sender) REFERENCES user(id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS intro_page (
	id int(11) NOT NULL AUTO_INCREMENT,
	weight TINYINT(2) NOT NULL,
	toppos INT(3) NOT NULL DEFAULT '50',
	leftpos INT(3) NOT NULL DEFAULT '50',
	color varchar(6) NOT NULL DEFAULT '222222',
	bgcolor varchar(6) NOT NULL DEFAULT 'FFFFFF',
	opacity TINYINT(1) NOT NULL DEFAULT '8',
	width INT(4) NOT NULL DEFAULT '600',
	published tinyint(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS intro_page_content (
	id int(11) NOT NULL AUTO_INCREMENT,
	page int(11) NOT NULL,
	language char(2) NOT NULL,
	title varchar( 255 ) NOT NULL,
	subtitle varchar( 255 ),
	body TEXT NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (page) REFERENCES intro_page(id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS cms_page (
	id int(11) NOT NULL AUTO_INCREMENT,
	block int( 10 ) NOT NULL,
	weight int( 10 ) NOT NULL,
	published tinyint( 1 ) NOT NULL DEFAULT '0',
	PRIMARY KEY (id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS cms_page_content (
	id int(11) NOT NULL AUTO_INCREMENT,
	page int(11) NOT NULL,
	language char(2) NOT NULL,
	pageURL varchar( 255 ) NOT NULL ,
	pageTitle varchar( 255 ) DEFAULT NULL ,
	body MEDIUMTEXT,
	heading varchar( 255 ) DEFAULT NULL ,
	metaTitle varchar( 255 ) DEFAULT NULL ,
	metaDescription varchar( 255 ) DEFAULT NULL ,
	metaKeywords varchar( 255 ) DEFAULT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (page) REFERENCES cms_page(id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS archive (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  path varchar(255) NOT NULL,
  extension varchar(5) NULL,
  author int(11) NOT NULL,
  description TEXT NOT NULL,
  created date NOT NULL,
  FOREIGN KEY (author) REFERENCES user(id),
  PRIMARY KEY (id)
) ENGINE=INNODB DEFAULT CHARSET = utf8;


CREATE TABLE IF NOT EXISTS reset_password (
  id int(11) NOT NULL AUTO_INCREMENT,
  user int(11) NOT NULL,
  code varchar(45) NOT NULL,
  created DATETIME NOT NULL,
  used TINYINT(1) DEFAULT 0 NOT NULL,
  FOREIGN KEY (user) REFERENCES user(id),
  PRIMARY KEY (id)
) ENGINE=INNODB DEFAULT CHARSET = utf8;


CREATE TABLE IF NOT EXISTS config (
  parameter VARCHAR(64) PRIMARY KEY,
  value varchar(255) NOT NULL ,
  required TINYINT(1) DEFAULT 1,
  description varchar(255) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

INSERT INTO config(parameter, value, required, description) VALUES ('councilName', 'Ayuntamiento de Espanistan', '1', 'Name of the council');
INSERT INTO config(parameter, value, required, description) VALUES ('currencySymbol', 'n €', '1', 'Currency symbol and collocation');
INSERT INTO config(parameter, value, required, description) VALUES ('databaseDumpMethod', 'native', '1', 'Method for database backups: native or alternative');
INSERT INTO config(parameter, value, required, description) VALUES ('emailContactAddress', 'info@ocax.net', '1', 'Contact email address');
INSERT INTO config(parameter, value, required, description) VALUES ('emailNoReply', 'no-reply@ocax.es', '1', 'no-reply email address');
INSERT INTO config(parameter, value, required, description) VALUES ('languages', 'es,ca,en', '1', 'Available languages on this site');
INSERT INTO config(parameter, value, required, description) VALUES ('membership', '1', '1', 'Does your Observatory encourage membership? (0 or 1)');
INSERT INTO config(parameter, value, required, description) VALUES ('observatoryBlog', '', '0', 'Observatory blog');
INSERT INTO config(parameter, value, required, description) VALUES ('observatoryName1', 'Observatorio Ciutadano Municipal#del %s', '1', 'Observatory name part 1');
INSERT INTO config(parameter, value, required, description) VALUES ('observatoryName2', 'My town', '0', 'Observatory name part 2');
INSERT INTO config(parameter, value, required, description) VALUES ('siglas', 'OCA(x)', '1', "Observatory's initials");
INSERT INTO config(parameter, value, required, description) VALUES ('smtpMethod', '0', '1', 'SMTP Method ( 0=SMTP, 1=Sendmail )');
INSERT INTO config(parameter, value, required, description) VALUES ('smtpAuth', '1', '1', 'SMTP Auth (0 or 1)');
INSERT INTO config(parameter, required, description) VALUES ('smtpHost', '0', 'SMTP Server');
INSERT INTO config(parameter, required, description) VALUES ('smtpPassword', '0', 'SMTP Password');
INSERT INTO config(parameter, required, description) VALUES ('smtpPort', '0', 'SMTP Port');
INSERT INTO config(parameter, required, description) VALUES ('smtpSecure', '0', 'SMTP Secure');
INSERT INTO config(parameter, required, description) VALUES ('smtpUsername', '0', 'SMTP Username');
INSERT INTO config(parameter, required, description) VALUES ('socialFacebookURL', '0', "Observatory's facebook URL");
INSERT INTO config(parameter, required, description) VALUES ('socialTwitterURL', '0', "Observatory's twitter URL");
INSERT INTO config(parameter, required, description) VALUES ('socialTwitterUsername', '0', "Observatory's twitter username");
INSERT INTO config(parameter, required, description) VALUES ('telephone', '0', 'Contact telephone');
INSERT INTO config(parameter, value, required, description) VALUES ('year', '2014', '1', 'Default Year (this year)');

