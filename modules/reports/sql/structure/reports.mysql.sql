DROP TABLE IF EXISTS usergroups_reports;
CREATE TABLE usergroups_reports (
  group_id varchar(32) collate utf8_unicode_ci NOT NULL,
  report_id varchar(50) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (group_id,report_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
