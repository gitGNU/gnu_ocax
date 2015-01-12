
INSERT INTO config(parameter, value, required, description) VALUES ('siteAutoBackup', '0', '1', 'Automated site backup (experimental)');
INSERT INTO config(parameter, value, required, description) VALUES ('siteAutoBackupEmailAlert', '1', '1', 'Send email reports to admin(s)');
INSERT INTO config(parameter, value, required, description) VALUES ('htmlEditorUseCompressor', '1', '1', "Use HTML editor compressor");
INSERT INTO config(parameter, value, required, description) VALUES ('socialActivateNonFree', '0', '1', "Include Twitter and Facebook widgets");
INSERT INTO config(parameter, value, required, description) VALUES ('vaultDefaultCapacity', '3', '1', 'Default vault capacity');
INSERT INTO config(parameter, value, required, description) VALUES ('siteColor', 'a1a150', '1', "The colour of your site eg: #a1a150");
INSERT INTO config(parameter, value, required, description) VALUES ('siteConfigStatus', '0', '1', 'Site configuration complete');
INSERT INTO config(parameter, value, required, description) VALUES ('siteConfigStatusBudgetDescriptionsImport', '0', '1', 'Budget descriptions have been imported');
INSERT INTO config(parameter, value, required, description) VALUES ('siteConfigStatusEmail', '0', '1', 'Site email configuration status');
INSERT INTO config(parameter, value, required, description) VALUES ('siteConfigStatusEmailTemplates', '0', '1', 'Have all templates been personalized?');
INSERT INTO config(parameter, value, required, description) VALUES ('siteConfigStatusLanguage', '0', '1', 'Site language configuration status');
INSERT INTO config(parameter, value, required, description) VALUES ('siteConfigStatusInitials', '0', '1', 'Obseratory initials configuration status');
INSERT INTO config(parameter, value, required, description) VALUES ('siteConfigStatusObservatoryName', '0', '1', 'Obseratory name configuration status');
INSERT INTO config(parameter, value, required, description) VALUES ('siteConfigStatusAdministrationName', '0', '1', 'Administration name configuration status');
INSERT INTO config(parameter, value, required, description) VALUES ('siteConfigStatusZipFileUpdated', '1', '1', 'Zip file containing csv is up to date');
INSERT INTO config(parameter, value, required, description) VALUES ('siteConfigStatusUptodate', '0', '1', 'OCAx version is up to date');
INSERT INTO config(parameter, value, required, description) VALUES ('siteConfigStatusPostInstallChecked', '0', '1', 'Post installation checked');
INSERT INTO config(parameter, value, required, description) VALUES ('budgetAutoFeature', '1', '1', 'Auto create budget graphics after csv import');

ALTER TABLE emailtext ADD updated TINYINT(1) DEFAULT 0 NOT NULL;

UPDATE config SET description='Name of the Administration' WHERE parameter='administrationName';
UPDATE config SET description='Does your Observatory encourage membership?' WHERE parameter='membership';
UPDATE config SET description='Email server' WHERE parameter='smtpMethod';
UPDATE config SET description='SMTP Auth' WHERE parameter='smtpAuth';
UPDATE config SET description='Database backup method' WHERE parameter='databaseDumpMethod';

UPDATE config SET required=0 WHERE parameter='smtpHost';
UPDATE config SET required=0 WHERE parameter='smtpPassword';
UPDATE config SET required=0 WHERE parameter='smtpPort';
UPDATE config SET required=0 WHERE parameter='smtpSecure';
UPDATE config SET required=0 WHERE parameter='smtpUsername';

ALTER TABLE user ADD is_description_editor TINYINT(1) DEFAULT 0;

CREATE TABLE IF NOT EXISTS log (
  id int(11) NOT NULL AUTO_INCREMENT,
  user int(11) NULL,		/* NULL because some things might be system log */
  created datetime NOT NULL,
  prefix varchar(255) NOT NULL,	/* possibly is the name of a model */
  model_id int(11) NULL,
  message varchar(1024) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=INNODB DEFAULT CHARSET = utf8;

ALTER TABLE cms_page MODIFY weight int(10);
ALTER TABLE cms_page_content ADD previewBody MEDIUMTEXT;

ALTER TABLE enquiry DROP COLUMN addressed_to;

RENAME TABLE emailtext TO email_template;
RENAME TABLE cms_page TO site_page;
RENAME TABLE cms_page_content TO site_page_content;

ALTER TABLE site_page ADD advancedHTML TINYINT(1) DEFAULT 0;
ALTER TABLE site_page ADD showTitle TINYINT(1) DEFAULT 1;

