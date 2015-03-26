CREATE TABLE IF NOT EXISTS vault (
  id int(11) NOT NULL AUTO_INCREMENT,
  host varchar(255) NOT NULL,
  name varchar(255) NOT NULL,
  type TINYINT(1) NOT NULL,
  schedule varchar(7) NOT NULL,
  created DATETIME NOT NULL,
  count INT(11) NOT NULL DEFAULT 0,
  capacity SMALLINT(2) NOT NULL DEFAULT 2,
  state TINYINT(2) DEFAULT 0,
  PRIMARY KEY (id)
) ENGINE=INNODB DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS vault_schedule (
  id int(11) NOT NULL AUTO_INCREMENT,
  vault int(11) NOT NULL,
  day TINYINT(7) NOT NULL,
  FOREIGN KEY (vault) REFERENCES vault(id),
  PRIMARY KEY (id)
) ENGINE=INNODB DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS backup (
  id int(11) NOT NULL AUTO_INCREMENT,
  vault int(11) NOT NULL,
  filename varchar(255) NULL,
  created DATETIME NOT NULL,
  initiated DATETIME NULL,
  completed DATETIME NULL,
  filesize varchar(255) NULL,
  state TINYINT(1) NULL,
  FOREIGN KEY (vault) REFERENCES vault(id),
  PRIMARY KEY (id)
) ENGINE=INNODB DEFAULT CHARSET = utf8;
INSERT INTO config(parameter, value, required, description) VALUES ('vaultDefaultCapacity', '3', '1', 'Default vault capacity');

ALTER TABLE budget MODIFY csv_parent_id varchar(255);
ALTER TABLE budget MODIFY csv_id varchar(255);
ALTER TABLE budget ADD INDEX csv_id (csv_id);

ALTER TABLE budget_desc_state MODIFY csv_id varchar(255);
ALTER TABLE budget_desc_common MODIFY csv_id varchar(255);
ALTER TABLE budget_desc_local MODIFY csv_id varchar(255);

ALTER TABLE reply MODIFY COLUMN created DATE;

