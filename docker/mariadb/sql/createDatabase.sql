CREATE OR REPLACE TABLE MainTable (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `Name` varchar(255) default NULL,
  `Age` varchar(50) default NULL,
  `City` varchar(50) default NULL,
  PRIMARY KEY (`id`)
);