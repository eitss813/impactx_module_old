
INSERT IGNORE INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES ('siteapi', 'SEAO - Native APP', 'SocialEngine REST API Plugin', '5.3.2', 1, 'extra');

-- @Todao
-- DROP TABLE IF EXISTS `engine4_siteapi_oauth_nonce`;
-- CREATE TABLE IF NOT EXISTS `engine4_siteapi_oauth_nonce` (
--   `nonce` varchar(32) NOT NULL,
--   `timestamp` int(10) unsigned NOT NULL,
--   UNIQUE KEY `nonce` (`nonce`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- Apple Signin Table

DROP TABLE IF EXISTS `engine4_user_apple`;
CREATE TABLE IF NOT EXISTS `engine4_user_apple` (
  `user_id` int(10) unsigned NOT NULL,
  `apple_id` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
   PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
