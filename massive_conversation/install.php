<?php


sql("DROP TABLE msg");

if (0===count(sql_get_tables()))
    sql("

CREATE TABLE `msg` (
	`msg_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`canal` DECIMAL(10,0) UNSIGNED NULL DEFAULT NULL,
	`fecha_creacion` DATETIME NULL DEFAULT NULL,
	`fecha_publicacion` DATETIME NULL DEFAULT NULL,
	`estado` VARCHAR(50) NULL DEFAULT NULL,
	`texto` VARCHAR(900) NULL DEFAULT NULL,
	`participante` VARCHAR(900) NULL DEFAULT NULL,
	`ip` VARCHAR(50) NULL DEFAULT NULL,
	`participantes_num` DECIMAL(10,0) UNSIGNED NULL DEFAULT NULL,
	`puntos` MEDIUMINT(9) NULL DEFAULT NULL,
	PRIMARY KEY (`msg_id`),
	INDEX `canal` (`canal`),
	INDEX `fecha_creacion` (`fecha_creacion`),
	INDEX `fecha_publicacion` (`fecha_publicacion`),
	INDEX `estado` (`estado`),
	INDEX `texto` (`texto`(255)),
	INDEX `participante` (`participante`(255)),
	INDEX `puntos` (`puntos`),
	INDEX `participantes_num` (`participantes_num`),
	INDEX `ip` (`ip`)
)
ENGINE=InnoDB
;


");

echo sql_error();

print_r(sql_get_tables());