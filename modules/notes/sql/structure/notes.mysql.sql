CREATE TABLE notes
(
  id bigint( 8 ) NOT NULL AUTO_INCREMENT ,
  identifier bigint(8) NOT NULL,
  tablename varchar(50),
  user_id varchar(50) NOT NULL,
  date_note date NOT NULL,
  note_text text NOT NULL,
  coll_id varchar(50),
 PRIMARY KEY (id)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
