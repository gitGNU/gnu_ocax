
CREATE TABLE IF NOT EXISTS pox (
  parameter VARCHAR(64) PRIMARY KEY,
  value varchar(255) NOT NULL ,
  required TINYINT(1) DEFAULT 1,
  description varchar(255) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

