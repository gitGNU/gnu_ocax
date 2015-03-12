/*
OCAX -- Citizen driven Observatory software
Copyright (C) 2014 OCAX Contributors. See AUTHORS.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

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
INSERT INTO config(parameter, value, required, description) VALUES ('vaultDefaultCapacity', '3', '1', 'Default vault capacity');

ALTER TABLE budget MODIFY csv_parent_id varchar(255);
ALTER TABLE budget MODIFY csv_id varchar(255);
ALTER TABLE budget ADD INDEX csv_id (csv_id);

ALTER TABLE budget_desc_state MODIFY csv_id varchar(255);
ALTER TABLE budget_desc_common MODIFY csv_id varchar(255);
ALTER TABLE budget_desc_local MODIFY csv_id varchar(255);

ALTER TABLE reply MODIFY COLUMN created DATE;

