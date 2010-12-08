SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `ftpsearch` DEFAULT CHARACTER SET utf8 ;
USE `ftpsearch` ;

-- -----------------------------------------------------
-- Table `ftpsearch`.`ftpinfo`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ftpsearch`.`ftpinfo` (
  `id` INT NOT NULL ,
  `site` VARCHAR(15) NULL ,
  `port` INT NULL DEFAULT 21 ,
  `user` VARCHAR(45) NULL DEFAULT 'anonymous' ,
  `pw` VARCHAR(45) NULL ,
  `acc` TINYINT(1)  NULL ,
  `indb` TINYINT(1)  NULL ,
  `info` VARCHAR(100) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = '记录ftp站点相关信息';


-- -----------------------------------------------------
-- Table `ftpsearch`.`cat`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ftpsearch`.`cat` (
  `id` INT NOT NULL ,
  `cat` VARCHAR(50) NULL ,
  `pid` INT NULL ,
  `ipid` INT NULL ,
  `acctime` MEDIUMTEXT  NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_cat_ftpinfo` (`ipid` ASC) ,
  INDEX `fk_cat_cat1` (`pid` ASC) ,
  CONSTRAINT `fk_cat_ftpinfo`
    FOREIGN KEY (`ipid` )
    REFERENCES `ftpsearch`.`ftpinfo` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cat_cat1`
    FOREIGN KEY (`pid` )
    REFERENCES `ftpsearch`.`cat` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ftpsearch`.`files`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ftpsearch`.`files` (
  `id` INT NOT NULL ,
  `file` VARCHAR(50) NULL ,
  `postfix` VARCHAR(10) NULL ,
  `pid` INT NULL ,
  `ipid` INT NULL ,
  `acctime` MEDIUMTEXT  NULL ,
  `type` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_files_ftpinfo1` (`ipid` ASC) ,
  INDEX `fk_files_cat1` (`pid` ASC) ,
  CONSTRAINT `fk_files_ftpinfo1`
    FOREIGN KEY (`ipid` )
    REFERENCES `ftpsearch`.`ftpinfo` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_files_cat1`
    FOREIGN KEY (`pid` )
    REFERENCES `ftpsearch`.`cat` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
