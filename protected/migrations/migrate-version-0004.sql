ALTER TABLE archive MODIFY extension varchar(5) DEFAULT NULL;
ALTER TABLE archive ADD is_container TINYINT(1) DEFAULT 0;
ALTER TABLE archive ADD container int(11) DEFAULT NULL;
ALTER TABLE archive ADD FOREIGN KEY (container) REFERENCES archive(id);