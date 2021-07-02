/*
SQLyog Community v12.09 (32 bit)
MySQL - 10.3.7-MariaDB : Database - admromanza
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `aplicacoes` */

DROP TABLE IF EXISTS `aplicacoes`;

CREATE TABLE `aplicacoes` (
  `apl_cod` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `apl_ace_ini` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `apl_descricao` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`apl_cod`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `aplicacoes` */

insert  into `aplicacoes`(`apl_cod`,`apl_ace_ini`,`apl_descricao`) values ('ContasPagar','N','Contas a Pagar'),('Despesa','N','Despesas'),('FechtoFunc','N','Fechamento do Funcionário'),('Fornecedor','N','Fornecedores'),('Funcionario','N','Funcionários'),('Ponto','N','Registro de Ponto'),('PrestServ','N','Prestador de Serviço'),('Usuario','N','Usuário');

/*Table structure for table `contas_pagar` */

DROP TABLE IF EXISTS `contas_pagar`;

CREATE TABLE `contas_pagar` (
  `pagar_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pessoa_id` bigint(20) NOT NULL DEFAULT 0,
  `desp_id` bigint(20) NOT NULL,
  `pagar_situacao` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'A',
  `pagar_data_vencto` date NOT NULL,
  `pagar_data_pagto` date DEFAULT NULL,
  `pagar_data_docto` date NOT NULL,
  `pagar_valor` decimal(15,2) NOT NULL,
  `pagar_valor_pago` decimal(15,2) NOT NULL DEFAULT 0.00,
  `pagar_num_doc` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pagar_sequencia` int(11) NOT NULL DEFAULT 1,
  `pagar_obs` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data_inclusao` datetime DEFAULT current_timestamp(),
  `data_alteracao` datetime DEFAULT NULL,
  `usr_inclusao` bigint(20) DEFAULT NULL,
  `usr_alteracao` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`pagar_id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `contas_pagar` */

/*Table structure for table `despesa` */

DROP TABLE IF EXISTS `despesa`;

CREATE TABLE `despesa` (
  `desp_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `desp_nome` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `desp_valor` decimal(15,2) NOT NULL,
  `desp_situacao` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'A',
  `desp_tipo` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'FUN' COMMENT 'FUN - Funcionario / PON - Ponto do funcionario / OUT - Outros',
  `usr_inclusao` bigint(20) DEFAULT NULL,
  `usr_alteracao` bigint(20) DEFAULT NULL,
  `data_inclusao` datetime DEFAULT current_timestamp(),
  `data_alteracao` datetime DEFAULT NULL,
  PRIMARY KEY (`desp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `despesa` */

insert  into `despesa`(`desp_id`,`desp_nome`,`desp_valor`,`desp_situacao`,`desp_tipo`,`usr_inclusao`,`usr_alteracao`,`data_inclusao`,`data_alteracao`) values (1,'VR','50.00','A','FUN',1,1,'2020-02-23 15:12:28','2020-03-01 12:13:43'),(2,'VA','10.00','A','FUN',2,1,'2020-02-23 15:27:57','2020-03-01 12:13:56'),(3,'Pagamento','10.00','A','PON',1,1,'2020-02-23 15:33:02','2020-02-23 18:21:04');

/*Table structure for table `fechamento` */

DROP TABLE IF EXISTS `fechamento`;

CREATE TABLE `fechamento` (
  `fechto_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fechto_descricao` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `fechto_data` date NOT NULL,
  `fechto_situacao` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'A' COMMENT 'A - Ativo / F - Finalizado / X - Cancelado',
  `data_inclusao` datetime DEFAULT current_timestamp(),
  `data_alteracao` datetime DEFAULT NULL,
  `usr_inclusao` bigint(20) DEFAULT NULL,
  `usr_alteracao` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`fechto_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `fechamento` */

/*Table structure for table `fechamento_pessoa` */

DROP TABLE IF EXISTS `fechamento_pessoa`;

CREATE TABLE `fechamento_pessoa` (
  `fecdesp_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fechto_id` bigint(20) NOT NULL,
  `pessoa_id` bigint(20) NOT NULL,
  `fecdesp_val_con_cfu` decimal(15,2) NOT NULL,
  `fecdesp_val_final` decimal(15,2) NOT NULL,
  `fecdesp_val_pago` decimal(15,2) NOT NULL,
  `fecdesp_val_ger_cfu` decimal(15,2) NOT NULL,
  `data_inclusao` datetime DEFAULT current_timestamp(),
  `data_alteracao` datetime DEFAULT NULL,
  `usr_inclusao` bigint(20) DEFAULT NULL,
  `usr_alteracao` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`fecdesp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `fechamento_pessoa` */

/*Table structure for table `fechamento_pessoa_despesa` */

DROP TABLE IF EXISTS `fechamento_pessoa_despesa`;

CREATE TABLE `fechamento_pessoa_despesa` (
  `fpd_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fecdesp_id` bigint(20) NOT NULL,
  `desp_id` bigint(20) NOT NULL,
  `fpd_valor_orig` decimal(15,2) NOT NULL,
  `fpd_valor` decimal(15,2) NOT NULL,
  `fpd_sit` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'A',
  `pagar_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`fpd_id`)
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `fechamento_pessoa_despesa` */

/*Table structure for table `pessoa` */

DROP TABLE IF EXISTS `pessoa`;

CREATE TABLE `pessoa` (
  `pessoa_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pessoa_fantasia` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `pessoa_razao` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `pessoa_telefone` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pessoa_celular` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pessoa_cpf_cnpj` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pessoa_situacao` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'A',
  `pessoa_tipo_for` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `pessoa_tipo_fun` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `pessoa_tipo_psv` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `pessoa_obs` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pessoa_val_cred` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Valor credito (negativo -> credito empresa)',
  `usr_inclusao` bigint(20) DEFAULT NULL,
  `usr_alteracao` bigint(20) DEFAULT NULL,
  `data_inclusao` datetime DEFAULT current_timestamp(),
  `data_alteracao` datetime DEFAULT NULL,
  PRIMARY KEY (`pessoa_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `pessoa` */

/*Table structure for table `pessoa_despesa` */

DROP TABLE IF EXISTS `pessoa_despesa`;

CREATE TABLE `pessoa_despesa` (
  `pesdes_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pessoa_id` bigint(20) NOT NULL,
  `desp_id` bigint(20) NOT NULL,
  `pesdes_val` decimal(15,2) NOT NULL,
  `pesdes_sit` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'A',
  `usr_inclusao` bigint(20) DEFAULT NULL,
  `usr_alteracao` bigint(20) DEFAULT NULL,
  `data_inclusao` datetime DEFAULT current_timestamp(),
  `data_alteracao` datetime DEFAULT NULL,
  PRIMARY KEY (`pesdes_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `pessoa_despesa` */

/*Table structure for table `ponto` */

DROP TABLE IF EXISTS `ponto`;

CREATE TABLE `ponto` (
  `ponto_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ponto_data` date NOT NULL,
  `ponto_situacao` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'A',
  `data_inclusao` datetime DEFAULT current_timestamp(),
  `data_alteracao` datetime DEFAULT NULL,
  `usr_inclusao` bigint(20) DEFAULT NULL,
  `usr_alteracao` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`ponto_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `ponto` */

/*Table structure for table `ponto_pessoa` */

DROP TABLE IF EXISTS `ponto_pessoa`;

CREATE TABLE `ponto_pessoa` (
  `ptopes_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pessoa_id` bigint(20) NOT NULL,
  `ponto_id` bigint(20) NOT NULL,
  `ptopes_presente` tinyint(1) NOT NULL COMMENT 'pessoa presente',
  `data_inclusao` datetime DEFAULT current_timestamp(),
  `data_alteracao` datetime DEFAULT NULL,
  `usr_inclusao` bigint(20) DEFAULT NULL,
  `usr_alteracao` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`ptopes_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `ponto_pessoa` */

/*Table structure for table `ponto_pessoa_despesa` */

DROP TABLE IF EXISTS `ponto_pessoa_despesa`;

CREATE TABLE `ponto_pessoa_despesa` (
  `ppd_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ptopes_id` bigint(20) NOT NULL,
  `pesdes_id` bigint(20) NOT NULL,
  `ppd_sit` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'A',
  `desp_id` bigint(20) NOT NULL,
  `pessoa_id` bigint(20) NOT NULL,
  `data_inclusao` datetime DEFAULT current_timestamp(),
  `data_alteracao` datetime DEFAULT NULL,
  `usr_inclusao` bigint(20) DEFAULT NULL,
  `usr_alteracao` bigint(20) DEFAULT NULL,
  `fpd_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`ppd_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `ponto_pessoa_despesa` */

/*Table structure for table `usuario` */

DROP TABLE IF EXISTS `usuario`;

CREATE TABLE `usuario` (
  `usr_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `usr_login` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `usr_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `usr_senha` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `usr_situacao` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data_inclusao` datetime DEFAULT current_timestamp(),
  `data_alteracao` datetime DEFAULT NULL,
  `usr_inclusao` bigint(20) DEFAULT NULL,
  `usr_alteracao` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`usr_id`),
  UNIQUE KEY `uniq_usuario_usr_login` (`usr_login`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `usuario` */

insert  into `usuario`(`usr_id`,`usr_login`,`usr_email`,`usr_senha`,`usr_situacao`,`data_inclusao`,`data_alteracao`,`usr_inclusao`,`usr_alteracao`) values (1,'adm','adm@adm.com','81dc9bdb52d04dc20036dbd8313ed055','A','2020-02-18 13:53:06','2020-02-23 14:42:24',NULL,2);

/*Table structure for table `usuario_acesso` */

DROP TABLE IF EXISTS `usuario_acesso`;

CREATE TABLE `usuario_acesso` (
  `usuace_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `usr_id` bigint(20) NOT NULL,
  `usuace_codigo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `usuace_acesso` char(1) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`usuace_id`)
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `usuario_acesso` */

insert  into `usuario_acesso`(`usuace_id`,`usr_id`,`usuace_codigo`,`usuace_acesso`) values (7,1,'Usuario','S'),(8,1,'Funcionario','S'),(9,1,'Fornecedor','S'),(10,1,'PrestServ','S'),(11,1,'Ponto','S'),(12,1,'FechtoFunc','S'),(13,1,'ContasPagar','S'),(98,1,'Despesa','S');

/* Trigger structure for table `aplicacoes` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `aplicacoes_aft_ins` */$$

/*!50003 CREATE */ /*!50003 TRIGGER `aplicacoes_aft_ins` AFTER INSERT ON `aplicacoes` FOR EACH ROW BEGIN
	
	INSERT INTO usuario_acesso (usr_id,usuace_codigo,usuace_acesso)
	SELECT usr_id, NEW.apl_cod, NEW.apl_ace_ini
	FROM usuario;
		
END */$$


DELIMITER ;

/* Trigger structure for table `fechamento` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `fechamento_bef_upd` */$$

/*!50003 CREATE */ /*!50003 TRIGGER `fechamento_bef_upd` BEFORE UPDATE ON `fechamento` FOR EACH ROW BEGIN
	
	if (OLD.fechto_situacao <> NEW.fechto_situacao and NEW.fechto_situacao = 'X') then
	
		delete from fechamento_pessoa where fechto_id = NEW.fechto_id;
	
	end if;
		
END */$$


DELIMITER ;

/* Trigger structure for table `fechamento_pessoa` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `fechamento_pessoa_bef_del` */$$

/*!50003 CREATE */ /*!50003 TRIGGER `fechamento_pessoa_bef_del` BEFORE DELETE ON `fechamento_pessoa` FOR EACH ROW BEGIN
	
	delete from fechamento_pessoa_despesa where fecdesp_id = OLD.fecdesp_id;
		
END */$$


DELIMITER ;

/* Trigger structure for table `fechamento_pessoa_despesa` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `fechamento_pessoa_despesa_aft_ins` */$$

/*!50003 CREATE */ /*!50003 TRIGGER `fechamento_pessoa_despesa_aft_ins` AFTER INSERT ON `fechamento_pessoa_despesa` FOR EACH ROW BEGIN
	
	IF ((select desp_tipo from despesa where desp_id = NEW.desp_id) = 'PON') THEN
	
		update ponto_pessoa_despesa
		set ppd_sit = 'P',
		    fpd_id = NEW.fpd_id
		where desp_id = NEW.desp_id
		  and ppd_sit = 'A'
		  and pessoa_id = (
			select pessoa_id
			from fechamento_pessoa
			where fecdesp_id = NEW.fecdesp_id
		  );
		  
	END IF;
		
END */$$


DELIMITER ;

/* Trigger structure for table `fechamento_pessoa_despesa` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `fechamento_pessoa_despesa_bef_del` */$$

/*!50003 CREATE */ /*!50003 TRIGGER `fechamento_pessoa_despesa_bef_del` BEFORE DELETE ON `fechamento_pessoa_despesa` FOR EACH ROW BEGIN
	
	IF ((SELECT desp_tipo FROM despesa WHERE desp_id = OLD.desp_id) = 'PON') THEN
	
		UPDATE ponto_pessoa_despesa
		SET ppd_sit = 'A',
		    fpd_id = 0
		WHERE fpd_id = OLD.fpd_id;
		  
	END IF;
		
END */$$


DELIMITER ;

/* Trigger structure for table `ponto_pessoa` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `ponto_pessoa_aft_ins` */$$

/*!50003 CREATE */ /*!50003 TRIGGER `ponto_pessoa_aft_ins` AFTER INSERT ON `ponto_pessoa` FOR EACH ROW BEGIN
	
	if (NEW.ptopes_presente = 1) then
	
		INSERT INTO ponto_pessoa_despesa (ptopes_id, pessoa_id, desp_id, pesdes_id, ppd_sit)
		SELECT NEW.ptopes_id, NEW.pessoa_id, p.desp_id, p.pesdes_id, 'A'
		FROM pessoa_despesa p inner join despesa d on (p.desp_id = d.desp_id)
		where p.pessoa_id  = NEW.pessoa_id
		  AND p.pesdes_sit = 'A'
		  AND d.desp_tipo = 'PON';
		  
	end if;
		
END */$$


DELIMITER ;

/* Trigger structure for table `usuario` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `usuario_aft_ins` */$$

/*!50003 CREATE */ /*!50003 TRIGGER `usuario_aft_ins` AFTER INSERT ON `usuario` FOR EACH ROW BEGIN
	
	INSERT INTO usuario_acesso (usr_id,usuace_codigo,usuace_acesso)
	SELECT NEW.usr_id, apl_cod, apl_ace_ini
	FROM aplicacoes;
		
END */$$


DELIMITER ;

/*Table structure for table `v_contas_pagar` */

DROP TABLE IF EXISTS `v_contas_pagar`;

/*!50001 DROP VIEW IF EXISTS `v_contas_pagar` */;
/*!50001 DROP TABLE IF EXISTS `v_contas_pagar` */;

/*!50001 CREATE TABLE  `v_contas_pagar`(
 `pagar_id` bigint(20) ,
 `pessoa_id` bigint(20) ,
 `desp_id` bigint(20) ,
 `pagar_situacao` char(1) ,
 `pagar_data_vencto` date ,
 `pagar_data_pagto` date ,
 `pagar_data_docto` date ,
 `pagar_valor` decimal(15,2) ,
 `pagar_valor_pago` decimal(15,2) ,
 `pagar_num_doc` varchar(50) ,
 `pagar_sequencia` int(11) ,
 `pagar_obs` varchar(100) ,
 `data_inclusao` datetime ,
 `data_alteracao` datetime ,
 `usr_inclusao` bigint(20) ,
 `usr_alteracao` bigint(20) ,
 `pessoa_fantasia` varchar(100) ,
 `pessoa_razao` varchar(100) ,
 `pessoa_cpf_cnpj` varchar(15) ,
 `desp_nome` varchar(50) 
)*/;

/*View structure for view v_contas_pagar */

/*!50001 DROP TABLE IF EXISTS `v_contas_pagar` */;
/*!50001 DROP VIEW IF EXISTS `v_contas_pagar` */;

/*!50001 CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_contas_pagar` AS select `p`.`pagar_id` AS `pagar_id`,`p`.`pessoa_id` AS `pessoa_id`,`p`.`desp_id` AS `desp_id`,`p`.`pagar_situacao` AS `pagar_situacao`,`p`.`pagar_data_vencto` AS `pagar_data_vencto`,`p`.`pagar_data_pagto` AS `pagar_data_pagto`,`p`.`pagar_data_docto` AS `pagar_data_docto`,`p`.`pagar_valor` AS `pagar_valor`,`p`.`pagar_valor_pago` AS `pagar_valor_pago`,`p`.`pagar_num_doc` AS `pagar_num_doc`,`p`.`pagar_sequencia` AS `pagar_sequencia`,`p`.`pagar_obs` AS `pagar_obs`,`p`.`data_inclusao` AS `data_inclusao`,`p`.`data_alteracao` AS `data_alteracao`,`p`.`usr_inclusao` AS `usr_inclusao`,`p`.`usr_alteracao` AS `usr_alteracao`,`pes`.`pessoa_fantasia` AS `pessoa_fantasia`,`pes`.`pessoa_razao` AS `pessoa_razao`,`pes`.`pessoa_cpf_cnpj` AS `pessoa_cpf_cnpj`,`d`.`desp_nome` AS `desp_nome` from ((`contas_pagar` `p` left join `pessoa` `pes` on(`pes`.`pessoa_id` = `p`.`pessoa_id`)) left join `despesa` `d` on(`d`.`desp_id` = `p`.`desp_id`)) */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
