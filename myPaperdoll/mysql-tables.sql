-- MySQL dump 9.10
--
-- Host: localhost    Database: MyRunUO
-- ------------------------------------------------------
-- Server version	4.0.20a-nt

--
-- Table structure for table `myrunuo_characters`
--

CREATE TABLE myrunuo_characters (
  char_id int(12) unsigned default NULL,
  char_name varchar(150) default NULL,
  char_str int(3) unsigned default NULL,
  char_dex int(3) unsigned default NULL,
  char_int int(3) unsigned default NULL,
  char_female int(2) unsigned default NULL,
  char_counts int(3) unsigned default NULL,
  char_guild varchar(4) default NULL,
  char_guildtitle varchar(150) default NULL,
  char_nototitle varchar(150) default NULL,
  char_bodyhue int(3) unsigned default NULL,
  char_public int(1) unsigned default NULL,
  KEY char_id (char_id),
  KEY char_guild (char_guild)
) TYPE=MyISAM;

--
-- Table structure for table `myrunuo_characters_layers`
--

CREATE TABLE myrunuo_characters_layers (
  char_id int(12) unsigned default NULL,
  layer_id int(3) unsigned default NULL,
  item_id int(12) unsigned default NULL,
  item_hue int(3) unsigned default NULL,
  KEY charid (char_id)
) TYPE=MyISAM;

--
-- Table structure for table `myrunuo_characters_skills`
--

CREATE TABLE myrunuo_characters_skills (
  char_id int(12) unsigned default NULL,
  skill_id int(3) unsigned default NULL,
  skill_value int(3) unsigned default NULL,
  KEY charid (char_id),
  KEY skillid (skill_id)
) TYPE=MyISAM;

--
-- Table structure for table `myrunuo_guilds`
--

CREATE TABLE myrunuo_guilds (
  guild_id varchar(4) default NULL,
  guild_name varchar(150) default NULL,
  guild_abbreviation varchar(4) default NULL,
  guild_website varchar(150) default NULL,
  guild_charter varchar(250) default NULL,
  guild_type varchar(8) default NULL,
  guild_wars int(3) unsigned default NULL,
  guild_members int(3) unsigned default NULL,
  guild_master int(12) unsigned default NULL,
  KEY guild_id (guild_id)
) TYPE=MyISAM;

--
-- Table structure for table `myrunuo_guilds_wars`
--

CREATE TABLE myrunuo_guilds_wars (
  guild_1 varchar(4) default NULL,
  guild_2 varchar(4) default NULL,
  KEY guild1 (guild_1),
  KEY guild2 (guild_2)
) TYPE=MyISAM;

--
-- Table structure for table `myrunuo_status`
--

CREATE TABLE myrunuo_status (
  char_id int(12) unsigned default NULL,
  char_location varchar(14) default NULL,
  char_map varchar(8) default NULL,
  char_karma int(6) default NULL,
  char_fame int(6) default NULL,
  KEY charid (char_id)
) TYPE=MyISAM;

--
-- Table structure for table `myrunuo_timestamps`
--

CREATE TABLE myrunuo_timestamps (
  time_datetime varchar(22) default NULL,
  time_type varchar(6) default NULL
) TYPE=MyISAM;

