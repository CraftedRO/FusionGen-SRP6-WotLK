ALTER TABLE `menu` ADD COLUMN `direct_link` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Is it a direct link or not? is needed for the ajax.' AFTER `order`;
