CREATE TABLE IF NOT EXISTS vault (
  id int(11) NOT NULL AUTO_INCREMENT,
  host varchar(255) NOT NULL,
  name varchar(255) NOT NULL, /* name of the directory where backups are kept */
  type TINYINT(1) NOT NULL,	/* 0 = copies are on LOCAL host, 1 = copies are on REMOTE host */
  schedule varchar(7) NOT NULL,	/* which day(s) to make the copy seven digit char, starts on Monday 0000000 */
  created DATETIME NOT NULL,
  count INT(11) NOT NULL DEFAULT 0,	/* number for backups made (historical stats) */
  capacity SMALLINT(2) NOT NULL DEFAULT 2,	/* number for backups per safe */
  state TINYINT(2) DEFAULT 0,
  PRIMARY KEY (id)
) ENGINE=INNODB DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS vault_schedule (
  id int(11) NOT NULL AUTO_INCREMENT,
  vault int(11) NOT NULL,
  day TINYINT(7) NOT NULL,	/* 0 - 6 */
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
  state TINYINT(1) NULL,	/* null=not_finished 0=failed 1=success */
  FOREIGN KEY (vault) REFERENCES vault(id),
  PRIMARY KEY (id)
) ENGINE=INNODB DEFAULT CHARSET = utf8;

ALTER TABLE budget MODIFY csv_parent_id varchar(255);
ALTER TABLE budget MODIFY csv_id varchar(255);
ALTER TABLE budget_desc_state MODIFY csv_id varchar(255);
ALTER TABLE budget_desc_common MODIFY csv_id varchar(255);
ALTER TABLE budget_desc_local MODIFY csv_id varchar(255);

