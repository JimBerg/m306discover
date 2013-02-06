CREATE SCHEMA IF NOT EXISTS `discover` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `discover` ;

-- -----------------------------------------------------
-- Table `discover`.`location`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `discover`.`location` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NULL DEFAULT NULL ,
  `lat` DOUBLE NULL DEFAULT NULL ,
  `lng` DOUBLE NULL DEFAULT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  `poi` TINYINT(1) NULL DEFAULT NULL ,
  `type` INT(11) NULL DEFAULT NULL ,
  `task` TEXT NULL DEFAULT NULL ,
  `solution` TEXT NULL DEFAULT NULL ,
  `lat_north` DOUBLE NULL DEFAULT NULL ,
  `lat_south` DOUBLE NULL DEFAULT NULL ,
  `lng_east` DOUBLE NULL DEFAULT NULL ,
  `lng_west` DOUBLE NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 18
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;


-- -----------------------------------------------------
-- Table `discover`.`user`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `discover`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `fbid` VARCHAR(45) NULL ,
  `email` VARCHAR(120) NULL ,
  `password` VARCHAR(255) NULL ,
  `firstname` VARCHAR(45) NULL ,
  `lastname` VARCHAR(45) NULL ,
  `rank` VARCHAR(45) NULL ,
  `avatar` VARCHAR(255) NULL ,
  `is_registered` TINYINT(1) NULL DEFAULT 0 ,
  `created_at` TIMESTAMP NULL ,
  `location_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_user_location` (`location_id` ASC) ,
  CONSTRAINT `fk_user_location`
    FOREIGN KEY (`location_id` )
    REFERENCES `discover`.`location` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `discover`.`visits`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `discover`.`visits` (
  `id` INT NOT NULL ,
  `location_id` INT NOT NULL ,
  `user_id` INT NOT NULL ,
  `visitdate` INT NULL ,
  `taskcomplete` TINYINT(1) NULL ,
  `points` INT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_visits_location1` (`location_id` ASC) ,
  INDEX `fk_visits_user1` (`user_id` ASC) ,
  CONSTRAINT `fk_visits_location1`
    FOREIGN KEY (`location_id` )
    REFERENCES `discover`.`location` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_visits_user1`
    FOREIGN KEY (`user_id` )
    REFERENCES `discover`.`user` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
