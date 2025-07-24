-- Sauvegarde générée par PHP
-- Date: 2025-06-30 21:12:52


-- Structure de la table `action`
DROP TABLE IF EXISTS `action`;
CREATE TABLE `action` (
  `id_action` int NOT NULL AUTO_INCREMENT,
  `lib_action` varchar(120) NOT NULL,
  PRIMARY KEY (`id_action`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Données de la table `action`
INSERT INTO `action` VALUES ('3', 'Modifier');
INSERT INTO `action` VALUES ('6', 'Supprimer');
INSERT INTO `action` VALUES ('7', 'Consulter');


-- Structure de la table `affecter`
DROP TABLE IF EXISTS `affecter`;
CREATE TABLE `affecter` (
  `id_enseignant` int NOT NULL,
  `id_rapport` int NOT NULL,
  `id_jury` int NOT NULL,
  KEY `Key_affecter_enseignant` (`id_enseignant`),
  KEY `Key_affecter_rappetu` (`id_rapport`),
  KEY `Key_affecter_jury` (`id_jury`),
  CONSTRAINT `fk_affecter_enseignant` FOREIGN KEY (`id_enseignant`) REFERENCES `enseignants` (`id_enseignant`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_affecter_jury` FOREIGN KEY (`id_jury`) REFERENCES `statut_jury` (`id_jury`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_affecter_rapport` FOREIGN KEY (`id_rapport`) REFERENCES `rapport_etudiants` (`id_rapport`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_mysql500_ci;


-- Structure de la table `annee_academique`
DROP TABLE IF EXISTS `annee_academique`;
CREATE TABLE `annee_academique` (
  `id_annee_acad` int NOT NULL AUTO_INCREMENT,
  `date_deb` date NOT NULL,
  `date_fin` date NOT NULL,
  PRIMARY KEY (`id_annee_acad`)
) ENGINE=InnoDB AUTO_INCREMENT=29901 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_mysql500_ci;

-- Données de la table `annee_academique`
INSERT INTO `annee_academique` VALUES ('21413', '2013-09-08', '2014-06-25');
INSERT INTO `annee_academique` VALUES ('21514', '2014-09-02', '2015-06-21');
INSERT INTO `annee_academique` VALUES ('21615', '2015-09-03', '2016-06-24');
INSERT INTO `annee_academique` VALUES ('21716', '2016-09-05', '2017-06-20');
INSERT INTO `annee_academique` VALUES ('21817', '2017-09-01', '2018-06-25');
INSERT INTO `annee_academique` VALUES ('21918', '2018-09-04', '2019-06-23');
INSERT INTO `annee_academique` VALUES ('22019', '2019-09-08', '2020-06-24');
INSERT INTO `annee_academique` VALUES ('22120', '2020-09-01', '2021-06-27');
INSERT INTO `annee_academique` VALUES ('22221', '2021-09-08', '2022-07-20');
INSERT INTO `annee_academique` VALUES ('22322', '2022-09-10', '2023-07-31');
INSERT INTO `annee_academique` VALUES ('22423', '2023-09-11', '2024-07-17');
INSERT INTO `annee_academique` VALUES ('22524', '2024-09-10', '2025-07-30');


-- Structure de la table `approuver`
DROP TABLE IF EXISTS `approuver`;
CREATE TABLE `approuver` (
  `id_approb` int NOT NULL,
  `id_pers_admin` int NOT NULL,
  `id_rapport` int NOT NULL,
  `decision` enum('approuve','desapprouve') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  `date_approv` datetime NOT NULL,
  `commentaire_approv` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  PRIMARY KEY (`id_pers_admin`,`id_rapport`),
  KEY `Key_approver_enseignant` (`id_pers_admin`),
  KEY `Key_approver_rapport` (`id_rapport`),
  KEY `id_approb` (`id_approb`),
  CONSTRAINT `fk_approuver_approb` FOREIGN KEY (`id_approb`) REFERENCES `niveau_approbation` (`id_approb`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_approuver_pers_admin` FOREIGN KEY (`id_pers_admin`) REFERENCES `personnel_admin` (`id_pers_admin`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_approuver_rapport` FOREIGN KEY (`id_rapport`) REFERENCES `rapport_etudiants` (`id_rapport`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_mysql500_ci;

-- Données de la table `approuver`
INSERT INTO `approuver` VALUES ('3', '7', '5', 'approuve', '2025-06-29 19:21:24', 'Rapport bien rédiger et respectant les normes de mise en forme');


-- Structure de la table `avoir`
DROP TABLE IF EXISTS `avoir`;
CREATE TABLE `avoir` (
  `id_grade` int NOT NULL,
  `id_enseignant` int NOT NULL,
  `date_grade` date NOT NULL,
  KEY `Key_avoir_grade` (`id_grade`),
  KEY `Key_avoir_enseignant` (`id_enseignant`),
  CONSTRAINT `fk_avoir_enseignant` FOREIGN KEY (`id_enseignant`) REFERENCES `enseignants` (`id_enseignant`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_avoir_grade` FOREIGN KEY (`id_grade`) REFERENCES `grade` (`id_grade`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_mysql500_ci;

-- Données de la table `avoir`
INSERT INTO `avoir` VALUES ('10', '2', '2023-06-05');
INSERT INTO `avoir` VALUES ('12', '5', '2015-05-04');
INSERT INTO `avoir` VALUES ('7', '7', '2022-05-04');
INSERT INTO `avoir` VALUES ('7', '9', '2020-06-27');
INSERT INTO `avoir` VALUES ('7', '10', '2018-02-12');
INSERT INTO `avoir` VALUES ('6', '11', '2018-05-13');
INSERT INTO `avoir` VALUES ('15', '12', '2008-02-27');
INSERT INTO `avoir` VALUES ('14', '13', '2024-06-24');
INSERT INTO `avoir` VALUES ('7', '14', '2024-01-05');
INSERT INTO `avoir` VALUES ('7', '15', '2015-04-15');


-- Structure de la table `candidature_soutenance`
DROP TABLE IF EXISTS `candidature_soutenance`;
CREATE TABLE `candidature_soutenance` (
  `id_candidature` int NOT NULL AUTO_INCREMENT,
  `num_etu` int NOT NULL,
  `date_candidature` datetime NOT NULL,
  `statut_candidature` enum('En attente','Validée','Rejetée') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'En attente',
  `date_traitement` datetime DEFAULT NULL,
  `id_pers_admin` int DEFAULT NULL,
  `commentaire_admin` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  PRIMARY KEY (`id_candidature`),
  KEY `num_etu` (`num_etu`),
  KEY `id_pers_admin` (`id_pers_admin`),
  CONSTRAINT `candidature_soutenance_ibfk_1` FOREIGN KEY (`num_etu`) REFERENCES `etudiants` (`num_etu`),
  CONSTRAINT `candidature_soutenance_ibfk_2` FOREIGN KEY (`id_pers_admin`) REFERENCES `personnel_admin` (`id_pers_admin`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Données de la table `candidature_soutenance`
INSERT INTO `candidature_soutenance` VALUES ('6', '2003003', '2025-06-23 10:23:24', 'Rejetée', '2025-06-23 10:36:53', '6', 'Évaluation complète terminée');
INSERT INTO `candidature_soutenance` VALUES ('7', '2003003', '2025-06-23 11:55:25', 'Rejetée', '2025-06-25 13:31:57', '6', 'Évaluation complète terminée');
INSERT INTO `candidature_soutenance` VALUES ('9', '2004003', '2025-06-25 12:37:46', 'Validée', '2025-06-25 13:41:42', '6', 'Évaluation complète terminée');
INSERT INTO `candidature_soutenance` VALUES ('10', '2003011', '2025-06-29 20:49:57', 'En attente', NULL, NULL, NULL);


-- Structure de la table `compte_rendu`
DROP TABLE IF EXISTS `compte_rendu`;
CREATE TABLE `compte_rendu` (
  `id_CR` int NOT NULL AUTO_INCREMENT,
  `num_etu` int NOT NULL,
  `nom_CR` varchar(70) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  `date_CR` datetime NOT NULL,
  PRIMARY KEY (`id_CR`),
  KEY `fk_etudiant` (`num_etu`),
  CONSTRAINT `fk_etudiant` FOREIGN KEY (`num_etu`) REFERENCES `etudiants` (`num_etu`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_mysql500_ci;


-- Structure de la table `deposer`
DROP TABLE IF EXISTS `deposer`;
CREATE TABLE `deposer` (
  `num_etu` int NOT NULL,
  `id_rapport` int NOT NULL,
  `date_depot` datetime NOT NULL,
  PRIMARY KEY (`num_etu`,`id_rapport`),
  KEY `Key_deposer_etudiant` (`num_etu`),
  KEY `Key_deposer_rapport_etud` (`id_rapport`),
  CONSTRAINT `fk_deposer_etudiant` FOREIGN KEY (`num_etu`) REFERENCES `etudiants` (`num_etu`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_deposer_rapport` FOREIGN KEY (`id_rapport`) REFERENCES `rapport_etudiants` (`id_rapport`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_mysql500_ci;

-- Données de la table `deposer`
INSERT INTO `deposer` VALUES ('2004003', '5', '2025-06-27 20:09:26');


-- Structure de la table `dossier_academique`
DROP TABLE IF EXISTS `dossier_academique`;
CREATE TABLE `dossier_academique` (
  `id_dossier` int NOT NULL AUTO_INCREMENT,
  `num_etu` int NOT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_modification` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `adresse` varchar(255) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `nationalite` varchar(50) DEFAULT NULL,
  `situation_familiale` varchar(50) DEFAULT NULL,
  `dernier_diplome` varchar(100) DEFAULT NULL,
  `etablissement_origine` varchar(100) DEFAULT NULL,
  `annee_obtention_diplome` year DEFAULT NULL,
  `mention_diplome` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_dossier`),
  KEY `fk_dossier_etudiant` (`num_etu`),
  CONSTRAINT `fk_dossier_etudiant` FOREIGN KEY (`num_etu`) REFERENCES `etudiants` (`num_etu`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Données de la table `dossier_academique`
INSERT INTO `dossier_academique` VALUES ('1', '2004003', '2025-06-21 17:03:27', '2025-06-21 17:21:02', 'Cocody, riviera palmeraie, quartier bonoumin', '0759395841', 'Ivoirienne', 'Marié', 'Baccalauréat', 'CSM Niangon', '2019', 'Assez bien');


-- Structure de la table `echeances`
DROP TABLE IF EXISTS `echeances`;
CREATE TABLE `echeances` (
  `id_echeance` int NOT NULL AUTO_INCREMENT,
  `id_inscription` int DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `date_echeance` date DEFAULT NULL,
  `statut_echeance` enum('En attente','Payée','En retard') DEFAULT NULL,
  PRIMARY KEY (`id_echeance`),
  KEY `id_inscription` (`id_inscription`),
  CONSTRAINT `echeances_ibfk_1` FOREIGN KEY (`id_inscription`) REFERENCES `inscriptions` (`id_inscription`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Données de la table `echeances`
INSERT INTO `echeances` VALUES ('1', '1', '565000.00', '2025-09-01', 'En attente');
INSERT INTO `echeances` VALUES ('2', '2', '565000.00', '2025-09-01', 'En attente');
INSERT INTO `echeances` VALUES ('6', '4', '188333.33', '2025-09-01', 'En attente');
INSERT INTO `echeances` VALUES ('7', '4', '188333.33', '2025-12-01', 'En attente');
INSERT INTO `echeances` VALUES ('8', '4', '188333.33', '2026-03-01', 'En attente');
INSERT INTO `echeances` VALUES ('10', '16', '205000.00', '2025-09-03', 'En attente');
INSERT INTO `echeances` VALUES ('11', '16', '205000.00', '2025-12-03', 'En attente');
INSERT INTO `echeances` VALUES ('12', '17', '565000.00', '2025-09-03', 'En attente');
INSERT INTO `echeances` VALUES ('15', '19', '410000.00', '2025-09-03', 'En attente');
INSERT INTO `echeances` VALUES ('36', '18', '220000.00', '2025-09-03', 'En attente');
INSERT INTO `echeances` VALUES ('37', '18', '220000.00', '2025-12-03', 'En attente');
INSERT INTO `echeances` VALUES ('38', '20', '565000.00', '2025-09-04', 'En attente');
INSERT INTO `echeances` VALUES ('49', '25', '565000.00', '2025-09-04', 'En attente');
INSERT INTO `echeances` VALUES ('50', '26', '565000.00', '2025-09-04', 'En attente');


-- Structure de la table `ecue`
DROP TABLE IF EXISTS `ecue`;
CREATE TABLE `ecue` (
  `id_ecue` int NOT NULL AUTO_INCREMENT,
  `id_ue` int NOT NULL,
  `lib_ecue` varchar(70) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  `credit` int NOT NULL,
  `id_enseignant` int DEFAULT NULL,
  PRIMARY KEY (`id_ecue`),
  KEY `Key_ecue_ue` (`id_ue`),
  KEY `fk_enseignant_responsable` (`id_enseignant`),
  CONSTRAINT `fk_ecue_ue` FOREIGN KEY (`id_ue`) REFERENCES `ue` (`id_ue`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_enseignant_responsable` FOREIGN KEY (`id_enseignant`) REFERENCES `enseignants` (`id_enseignant`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_mysql500_ci;

-- Données de la table `ecue`
INSERT INTO `ecue` VALUES ('7', '8', 'Economie 1', '2', '10');
INSERT INTO `ecue` VALUES ('9', '8', 'Economie 2', '2', '10');
INSERT INTO `ecue` VALUES ('10', '13', 'Suites et fonctions', '3', '10');
INSERT INTO `ecue` VALUES ('11', '13', 'Calcul intégral', '2', '10');
INSERT INTO `ecue` VALUES ('12', '14', 'Elements de logique', '2', '10');
INSERT INTO `ecue` VALUES ('13', '14', 'Structures algébriques', '3', '10');
INSERT INTO `ecue` VALUES ('14', '17', 'Géométrie', '1', NULL);
INSERT INTO `ecue` VALUES ('15', '17', 'Calcul matriciel', '2', NULL);
INSERT INTO `ecue` VALUES ('16', '17', 'espaces vectoriels', '3', NULL);
INSERT INTO `ecue` VALUES ('17', '18', 'Probabilités 1', '2', NULL);
INSERT INTO `ecue` VALUES ('18', '18', 'Statistique 1', '2', NULL);
INSERT INTO `ecue` VALUES ('19', '18', 'Initiation au langage R', '1', NULL);
INSERT INTO `ecue` VALUES ('20', '82', 'Algorithmique', '3', NULL);
INSERT INTO `ecue` VALUES ('21', '82', 'programmation Java', '2', NULL);
INSERT INTO `ecue` VALUES ('22', '21', 'Methodologie de travail', '1', NULL);
INSERT INTO `ecue` VALUES ('23', '21', 'Technique d\'expression', '1', NULL);
INSERT INTO `ecue` VALUES ('24', '26', 'Fondamentaux de POO', '3', NULL);
INSERT INTO `ecue` VALUES ('25', '26', 'Programmation POO', '3', NULL);
INSERT INTO `ecue` VALUES ('26', '28', 'Analyse 2', '3', NULL);
INSERT INTO `ecue` VALUES ('27', '28', 'Algèbre', '3', NULL);
INSERT INTO `ecue` VALUES ('28', '29', 'Probabilités 2', '2', NULL);
INSERT INTO `ecue` VALUES ('29', '29', 'Statistique 2', '2', NULL);
INSERT INTO `ecue` VALUES ('30', '30', 'Modèle comptable', '2', NULL);
INSERT INTO `ecue` VALUES ('31', '30', 'Opérations comptables', '2', NULL);
INSERT INTO `ecue` VALUES ('32', '30', 'Opérations d\'inventaires', '2', NULL);
INSERT INTO `ecue` VALUES ('33', '32', 'Arithmétique', '2', NULL);
INSERT INTO `ecue` VALUES ('34', '33', 'Base de données relationnelles', '3', NULL);
INSERT INTO `ecue` VALUES ('35', '33', 'Données semi-structurées', '2', NULL);
INSERT INTO `ecue` VALUES ('36', '33', 'base de données et applications', '3', NULL);
INSERT INTO `ecue` VALUES ('37', '35', 'Initiation au Langage SCALA', '2', NULL);
INSERT INTO `ecue` VALUES ('38', '35', 'Atelier de Génie Logiciel', '4', NULL);
INSERT INTO `ecue` VALUES ('39', '36', 'Programmation VBA', '2', NULL);
INSERT INTO `ecue` VALUES ('40', '36', 'Programmation C#', '2', NULL);
INSERT INTO `ecue` VALUES ('41', '38', 'Application à la cryptographie', '2', NULL);
INSERT INTO `ecue` VALUES ('42', '40', 'Fondamentaux des systèmes d\'exploitation', '2', NULL);
INSERT INTO `ecue` VALUES ('43', '40', 'UNIX et langage C', '4', NULL);
INSERT INTO `ecue` VALUES ('44', '41', 'Algo avancé et Java', '5', NULL);
INSERT INTO `ecue` VALUES ('45', '46', 'Suivi des performances', '2', NULL);
INSERT INTO `ecue` VALUES ('46', '46', 'Coût complet et coût partiel', '2', NULL);
INSERT INTO `ecue` VALUES ('47', '9', 'Fondamentaux des réseaux', '3', NULL);
INSERT INTO `ecue` VALUES ('48', '9', 'Internet/Intranet', '2', NULL);
INSERT INTO `ecue` VALUES ('49', '56', 'ISI', '2', NULL);
INSERT INTO `ecue` VALUES ('50', '56', 'UML', '3', NULL);
INSERT INTO `ecue` VALUES ('51', '57', 'Files d\'attente et gestion de stock', '3', NULL);
INSERT INTO `ecue` VALUES ('52', '57', 'Regression linéaire', '1', NULL);


-- Structure de la table `enseignants`
DROP TABLE IF EXISTS `enseignants`;
CREATE TABLE `enseignants` (
  `id_enseignant` int NOT NULL AUTO_INCREMENT,
  `nom_enseignant` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  `prenom_enseignant` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  `mail_enseignant` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  `id_specialite` int NOT NULL,
  `type_enseignant` enum('Simple','Administratif') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  PRIMARY KEY (`id_enseignant`),
  KEY `Key_enseign_specialite` (`id_specialite`),
  CONSTRAINT `fk_enseignants_specialite` FOREIGN KEY (`id_specialite`) REFERENCES `specialite` (`id_specialite`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_mysql500_ci;

-- Données de la table `enseignants`
INSERT INTO `enseignants` VALUES ('2', 'Brou', 'Patrice', 'angeaxelgomez@gmail.com', '2', 'Simple');
INSERT INTO `enseignants` VALUES ('5', 'Kouakou', 'Mathias', 'axelangegomez@gmail.com', '5', 'Simple');
INSERT INTO `enseignants` VALUES ('7', 'Koua', 'Brou', 'oceanetl27@gmail.com', '2', 'Simple');
INSERT INTO `enseignants` VALUES ('9', 'N\'golo', 'Konaté', 'ngolokonate@yahoo.fr', '2', 'Simple');
INSERT INTO `enseignants` VALUES ('10', 'Nindjin', 'Malan', 'nindjinmalan@gmail.com', '2', 'Simple');
INSERT INTO `enseignants` VALUES ('11', 'Kouakou', 'Florent', 'kouakouflorent@gmail.com', '5', 'Simple');
INSERT INTO `enseignants` VALUES ('12', 'Wah', 'Médard', 'wahmedard@gmail.com', '2', 'Simple');
INSERT INTO `enseignants` VALUES ('13', 'Brou', 'IDA', 'brouida@gmail.com', '2', 'Administratif');
INSERT INTO `enseignants` VALUES ('14', 'Tembely', 'Salifou', 'tembelysalifou@gmail.com', '15', 'Simple');
INSERT INTO `enseignants` VALUES ('15', 'Kouabera', 'Tanoh', 'kouaberatanoh11@yahoo.com', '3', 'Simple');


-- Structure de la table `entreprises`
DROP TABLE IF EXISTS `entreprises`;
CREATE TABLE `entreprises` (
  `id_entreprise` int NOT NULL AUTO_INCREMENT,
  `lib_entreprise` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  PRIMARY KEY (`id_entreprise`),
  UNIQUE KEY `lib_entreprise` (`lib_entreprise`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_mysql500_ci;

-- Données de la table `entreprises`
INSERT INTO `entreprises` VALUES ('3', 'Deloitte Côte d\'Ivoire');
INSERT INTO `entreprises` VALUES ('5', 'Orange Côte d\'Ivoire');
INSERT INTO `entreprises` VALUES ('8', 'QuanTech Côte d\'Ivoire');
INSERT INTO `entreprises` VALUES ('7', 'Tuzzo Côte d\'Ivoire');


-- Structure de la table `etudiants`
DROP TABLE IF EXISTS `etudiants`;
CREATE TABLE `etudiants` (
  `num_etu` int NOT NULL AUTO_INCREMENT,
  `nom_etu` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  `prenom_etu` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  `email_etu` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  `date_naiss_etu` date NOT NULL,
  `genre_etu` enum('Homme','Femme','Neutre') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  `promotion_etu` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  PRIMARY KEY (`num_etu`)
) ENGINE=InnoDB AUTO_INCREMENT=20250003 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_mysql500_ci;

-- Données de la table `etudiants`
INSERT INTO `etudiants` VALUES ('2003001', 'Brou', 'Kouamé Wa Ambroise', 'kouame.brou@miage.edu', '1980-05-15', 'Homme', '2003-2004');
INSERT INTO `etudiants` VALUES ('2003002', 'Coulibaly', 'Pécory Ismaèl', 'pecory.coulibaly@miage.edu', '1981-07-22', 'Homme', '2003-2004');
INSERT INTO `etudiants` VALUES ('2003003', 'Diomandé', 'Gondo Patrick', 'gondo.diomande@miage.edu', '1980-11-30', 'Homme', '2003-2004');
INSERT INTO `etudiants` VALUES ('2003004', 'Ekponou', 'Georges', 'georges.ekponou@miage.edu', '1981-03-18', 'Homme', '2003-2004');
INSERT INTO `etudiants` VALUES ('2003005', 'Gnaman', 'Arthur Berenger', 'arthur.gnaman@miage.edu', '1980-09-12', 'Homme', '2003-2004');
INSERT INTO `etudiants` VALUES ('2003006', 'Guiégui', 'Arnaud Kévin Boris', 'arnaud.guiegui@miage.edu', '1981-01-25', 'Homme', '2003-2004');
INSERT INTO `etudiants` VALUES ('2003007', 'Kacou', 'Allou Yves-Roland', 'yves.kacou@miage.edu', '1980-08-07', 'Homme', '2003-2004');
INSERT INTO `etudiants` VALUES ('2003008', 'Kadio', 'Paule Elodie', 'elodie.kadio@miage.edu', '1981-04-14', 'Femme', '2003-2004');
INSERT INTO `etudiants` VALUES ('2003009', 'Kéi', 'Ninsémon Hervé', 'herve.kei@miage.edu', '1980-12-03', 'Homme', '2003-2004');
INSERT INTO `etudiants` VALUES ('2003010', 'Kinimo', 'Habia Elvire', 'elvire.kinimo@miage.edu', '1981-06-19', 'Femme', '2003-2004');
INSERT INTO `etudiants` VALUES ('2003011', 'Kouadio', 'Donald', 'donald.kouadio@miage.edu', '1980-02-28', 'Homme', '2003-2004');
INSERT INTO `etudiants` VALUES ('2003012', 'Kouadio', 'Sékédoua Jules', 'jules.kouadio@miage.edu', '1981-10-08', 'Homme', '2003-2004');
INSERT INTO `etudiants` VALUES ('2003013', 'Mambo', 'Katty Tatiana', 'tatiana.mambo@miage.edu', '1980-07-17', 'Femme', '2003-2004');
INSERT INTO `etudiants` VALUES ('2003014', 'Mukenge', 'Kalenga', 'kalenga.mukenge@miage.edu', '1981-09-05', 'Homme', '2003-2004');
INSERT INTO `etudiants` VALUES ('2003015', 'N\'guessan', 'Constant', 'constant.nguessan@miage.edu', '1980-04-21', 'Homme', '2003-2004');
INSERT INTO `etudiants` VALUES ('2003016', 'Niamien', 'Casimir', 'casimir.niamien@miage.edu', '1981-12-11', 'Homme', '2003-2004');
INSERT INTO `etudiants` VALUES ('2003017', 'Oula', 'Séblé Lucien', 'lucien.oula@miage.edu', '1980-03-09', 'Homme', '2003-2004');
INSERT INTO `etudiants` VALUES ('2003018', 'Sagnon', 'Boga Eric', 'eric.sagnon@miage.edu', '1981-05-30', 'Homme', '2003-2004');
INSERT INTO `etudiants` VALUES ('2003019', 'Tiémélé', 'Solange', 'solange.tieme@miage.edu', '1981-06-30', 'Femme', '2003-2004');
INSERT INTO `etudiants` VALUES ('2003020', 'Yao', 'Hermann Berenger', 'yao.hermann@miage.edu', '1982-03-27', 'Homme', '2003-2004');
INSERT INTO `etudiants` VALUES ('2003021', 'Yao', 'Michaelle Sylvie', 'yao.mickaelle@miage.edu', '1986-03-27', 'Femme', '2003-2004');
INSERT INTO `etudiants` VALUES ('2003022', 'Zakpa', 'Emmanuella', 'zakpa.emmanuella@miage.edu', '1982-08-25', 'Femme', '2003-2004');
INSERT INTO `etudiants` VALUES ('2004001', 'Agounkpeto', 'Jean Michel', 'jean.agounkpeto@miage.edu', '1981-06-22', 'Homme', '2004-2005');
INSERT INTO `etudiants` VALUES ('2004002', 'Aka', 'Ange Kévin', 'ange.aka@miage.edu', '1982-08-15', 'Homme', '2004-2005');
INSERT INTO `etudiants` VALUES ('2004003', 'Aka', 'Prince', 'prince.aka@miage.edu', '1981-12-03', 'Homme', '2004-2005');
INSERT INTO `etudiants` VALUES ('2004004', 'Akpa', 'Gnagne David Martial', 'david.akpa@miage.edu', '1982-04-18', 'Homme', '2004-2005');
INSERT INTO `etudiants` VALUES ('2004005', 'Barthe', 'Kobi Hugues Didier', 'hugues.barthe@miage.edu', '1981-10-29', 'Homme', '2004-2005');
INSERT INTO `etudiants` VALUES ('2004006', 'Djehéré', 'Claude', 'claude.djehere@miage.edu', '1982-02-14', 'Homme', '2004-2005');
INSERT INTO `etudiants` VALUES ('2004007', 'Doumun', 'Mékapeu Solange', 'solange.doumun@miage.edu', '1981-07-07', 'Femme', '2004-2005');
INSERT INTO `etudiants` VALUES ('2004008', 'Gogori', 'N\'guessan Etienne Hugues', 'etienne.gogori@miage.edu', '1982-05-21', 'Homme', '2004-2005');
INSERT INTO `etudiants` VALUES ('2004009', 'Gouzou', 'Zékou Mathurin', 'mathurin.gouzou@miage.edu', '1981-09-30', 'Homme', '2004-2005');
INSERT INTO `etudiants` VALUES ('2004010', 'Kacou', 'Akimba Carolle', 'carolle.kacou@miage.edu', '1982-01-12', 'Femme', '2004-2005');
INSERT INTO `etudiants` VALUES ('2004011', 'Koffi', 'Bi Tiessé Franck', 'franck.koffi@miage.edu', '1981-11-25', 'Homme', '2004-2005');
INSERT INTO `etudiants` VALUES ('2004012', 'Koné', 'Pétiéninpou Salifou', 'salifou.kone@miage.edu', '1982-03-08', 'Homme', '2004-2005');
INSERT INTO `etudiants` VALUES ('2004013', 'Kouadé', 'Ano Jean', 'jean.kouade@miage.edu', '1981-08-17', 'Homme', '2004-2005');
INSERT INTO `etudiants` VALUES ('2004014', 'Kouadio', 'Assi Donald Landry', 'donald.kouadio@miage.edu', '1982-06-19', 'Homme', '2004-2005');
INSERT INTO `etudiants` VALUES ('2004015', 'Kouamé', 'Marie Paule', 'marie.kouame@miage.edu', '1981-04-05', 'Femme', '2004-2005');
INSERT INTO `etudiants` VALUES ('2004016', 'Ossey', 'Tanguy', 'tanguy.ossey@miage.edu', '1982-10-27', 'Homme', '2004-2005');
INSERT INTO `etudiants` VALUES ('2004017', 'Touré', 'Badiénry Fabrice', 'fabrice.toure@miage.edu', '1981-12-22', 'Homme', '2004-2005');
INSERT INTO `etudiants` VALUES ('2004018', 'Yéré', 'Adou Vincent', 'vincent.yere@miage.edu', '1982-07-14', 'Homme', '2004-2005');
INSERT INTO `etudiants` VALUES ('2005001', 'Aby', 'Nanpé Olivier', 'olivier.aby@miage.edu', '1982-01-15', 'Homme', '2005-2006');
INSERT INTO `etudiants` VALUES ('2005002', 'Aliman', 'Prisca', 'prisca.aliman@miage.edu', '1983-03-28', 'Femme', '2005-2006');
INSERT INTO `etudiants` VALUES ('2005003', 'Bakayoko', 'Soumaila', 'soumaila.bakayoko@miage.edu', '1982-09-10', 'Homme', '2005-2006');
INSERT INTO `etudiants` VALUES ('2005004', 'Berthé', 'Issa', 'issa.berthe@miage.edu', '1983-05-22', 'Homme', '2005-2006');
INSERT INTO `etudiants` VALUES ('2005005', 'Dacoury', 'Armand', 'armand.dacoury@miage.edu', '1982-11-07', 'Homme', '2005-2006');
INSERT INTO `etudiants` VALUES ('2005006', 'Diallo', 'Marlène', 'marlene.diallo@miage.edu', '1983-07-19', 'Femme', '2005-2006');
INSERT INTO `etudiants` VALUES ('2005007', 'Dossou', 'Falome Flora', 'flora.dossou@miage.edu', '1982-04-03', 'Femme', '2005-2006');
INSERT INTO `etudiants` VALUES ('2005008', 'Fofana', 'Lazeni', 'lazeni.fofana@miage.edu', '1983-08-25', 'Homme', '2005-2006');
INSERT INTO `etudiants` VALUES ('2005009', 'Fongbé', 'Amadou', 'amadou.fongbe@miage.edu', '1982-12-30', 'Homme', '2005-2006');
INSERT INTO `etudiants` VALUES ('2005010', 'Gnamien', 'Badjo Carine', 'carine.gnamien@miage.edu', '1983-02-14', 'Femme', '2005-2006');
INSERT INTO `etudiants` VALUES ('2005011', 'Kalou', 'Bi Florent', 'florent.kalou@miage.edu', '1982-06-18', 'Homme', '2005-2006');
INSERT INTO `etudiants` VALUES ('2005012', 'Kané', 'Kader', 'kader.kane@miage.edu', '1983-10-09', 'Homme', '2005-2006');
INSERT INTO `etudiants` VALUES ('2005013', 'Konan', 'Hermann Michel', 'hermann.konan@miage.edu', '1982-07-22', 'Homme', '2005-2006');
INSERT INTO `etudiants` VALUES ('2005014', 'Koné', 'Djébilou', 'djebilou.kone@miage.edu', '1983-01-05', 'Homme', '2005-2006');
INSERT INTO `etudiants` VALUES ('2005015', 'Koné', 'Moussa', 'moussa.kone@miage.edu', '1982-05-17', 'Homme', '2005-2006');
INSERT INTO `etudiants` VALUES ('2005016', 'Kouyaté', 'Bangali', 'bangali.kouyate@miage.edu', '1983-09-28', 'Homme', '2005-2006');
INSERT INTO `etudiants` VALUES ('2005017', 'Latte', 'Pierre André', 'pierre.latte@miage.edu', '1982-03-11', 'Homme', '2005-2006');
INSERT INTO `etudiants` VALUES ('2005018', 'Méango', 'Jean Marie', 'jean.meango@miage.edu', '1983-04-24', 'Homme', '2005-2006');
INSERT INTO `etudiants` VALUES ('2005019', 'Mian', 'Koffi Jules Césare', 'jules.mian@miage.edu', '1982-08-07', 'Homme', '2005-2006');
INSERT INTO `etudiants` VALUES ('2005020', 'Monsan', 'Chimène', 'chimene.monsan@miage.edu', '1983-12-19', 'Femme', '2005-2006');
INSERT INTO `etudiants` VALUES ('2005021', 'Mouhamed', 'Moubarak', 'moubarak.mouhamed@miage.edu', '1982-10-02', 'Homme', '2005-2006');
INSERT INTO `etudiants` VALUES ('2005022', 'N\'goran', 'Yao Dénis', 'denis.ngoran@miage.edu', '1983-06-15', 'Homme', '2005-2006');
INSERT INTO `etudiants` VALUES ('2005023', 'N\'guessan', 'Jacques', 'jacques.nguessan@miage.edu', '1982-02-28', 'Homme', '2005-2006');
INSERT INTO `etudiants` VALUES ('2005024', 'Ossey', 'Sabrina', 'sabrina.ossey@miage.edu', '1983-11-11', 'Femme', '2005-2006');
INSERT INTO `etudiants` VALUES ('2005025', 'Ouattara', 'Ecaré Myriam', 'myriam.ouattara@miage.edu', '1982-01-23', 'Femme', '2005-2006');
INSERT INTO `etudiants` VALUES ('2005026', 'Ouffoué', 'Yawyha Attinouanfier J.', 'yawyha.ouffoue@miage.edu', '1983-07-06', 'Homme', '2005-2006');
INSERT INTO `etudiants` VALUES ('2005027', 'Sassou', 'Mensah Boris', 'boris.sassou@miage.edu', '1982-04-19', 'Homme', '2005-2006');
INSERT INTO `etudiants` VALUES ('2005028', 'Soumahoro', 'Badra Ali', 'ali.soumahoro@miage.edu', '1983-08-31', 'Homme', '2005-2006');
INSERT INTO `etudiants` VALUES ('2005029', 'Tanoh', 'Kouassi Pacome', 'pacome.tanoh@miage.edu', '1982-12-14', 'Homme', '2005-2006');
INSERT INTO `etudiants` VALUES ('2005030', 'Yaméogo', 'Emmanuel', 'emmanuel.yameogo@miage.edu', '1983-05-27', 'Homme', '2005-2006');
INSERT INTO `etudiants` VALUES ('2006001', 'Akinola', 'Oyéniyi Alexis Laurent S.', 'alexis.akinola@miage.edu', '1983-02-15', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006002', 'Attisou', 'Jean-François', 'jean-francois.attisou@miage.edu', '1984-04-28', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006003', 'Badouon', 'Ange Rodrigue', 'rodrigue.badouon@miage.edu', '1983-10-10', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006004', 'Bédy', 'Nathanael Durand', 'nathanael.bedy@miage.edu', '1984-06-22', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006005', 'Blé', 'Aka Jean-Jacques Ferdinand', 'jean-jacques.ble@miage.edu', '1983-12-07', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006006', 'Bodjé', 'Hippolyte', 'hippolyte.bodje@miage.edu', '1984-08-19', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006007', 'Bodjé', 'N\'kauh Nathan Regis', 'nathan.bodje@miage.edu', '1983-05-03', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006008', 'Bouraïman', 'Farwaz', 'farwaz.bouraiman@miage.edu', '1984-09-25', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006009', 'Brou', 'Kouakou Ange', 'ange.brou@miage.edu', '1983-01-30', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006010', 'Cissé', 'Souleymane Désiré Cédric', 'souleymane.cisse@miage.edu', '1984-03-14', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006011', 'Diallo', 'Mamadou', 'mamadou.diallo@miage.edu', '1983-07-18', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006012', 'Dja', 'Blé Robert Martial', 'robert.dja@miage.edu', '1984-11-09', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006013', 'Dobé', 'Anicet Landry G.', 'landry.dobe@miage.edu', '1983-08-22', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006014', 'Doh', 'Alain Hyppolyte', 'alain.doh@miage.edu', '1984-02-05', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006015', 'Fanoudh-Siefer', 'Jocelyne', 'jocelyne.fanoudh@miage.edu', '1983-06-17', 'Femme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006016', 'Fioklou', 'Mawuena Linda Sandrine', 'linda.fioklou@miage.edu', '1984-10-28', 'Femme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006017', 'Gami', 'Tizié Bi Eric', 'eric.gami@miage.edu', '1983-04-11', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006018', 'Gnanagbé', 'Gilles Gohou', 'gilles.gnanagbe@miage.edu', '1984-07-24', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006019', 'Gouley', 'Vincent de Paul', 'vincent.gouley@miage.edu', '1983-09-07', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006020', 'Koffi', 'Kouassi Michel', 'michel.koffi@miage.edu', '1984-01-19', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006021', 'Koné', 'Moussa', 'moussa.kone2@miage.edu', '1983-05-02', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006022', 'Kouamé', 'Kouamenan Jean Baptiste', 'jean.kouame@miage.edu', '1984-12-15', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006023', 'Kouman', 'Kobenan Constant', 'constant.kouman@miage.edu', '1983-03-28', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006024', 'Maïga', 'Jean-Luc Hervé Morel', 'jean-luc.maiga@miage.edu', '1984-06-10', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006025', 'N\'gadjingar', 'Arnold', 'arnold.ngadjingar@miage.edu', '1983-10-23', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006026', 'N\'guessan', 'Medri suzanne Sandrine', 'suzanne.nguessan@miage.edu', '1984-04-05', 'Femme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006027', 'Nindjin', 'Malan Alain', 'alain.nindjin@miage.edu', '1983-08-18', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006028', 'Oussou', 'Gbogboly Romaric Anselme', 'romaric.oussou@miage.edu', '1984-02-28', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006029', 'Rabet', 'Stéphane', 'stephane.rabet@miage.edu', '1983-07-11', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006030', 'Sékongo', 'Kafalo David', 'david.sekongo@miage.edu', '1984-11-24', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006031', 'Sékongo', 'Kafalo Siméon', 'simeon.sekongo@miage.edu', '1983-01-06', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006032', 'Sékongo', 'Sionta Débora', 'debora.sekongo@miage.edu', '1984-05-19', 'Femme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006033', 'Senin', 'N\'guetta Patrick Yoann', 'patrick.senin@miage.edu', '1983-09-01', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006034', 'Tanoh-Niangoin', 'Arnaud Joël', 'arnaud.tanoh@miage.edu', '1984-12-14', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006035', 'Tchétché', 'Lazare', 'lazare.tchetche@miage.edu', '1983-04-27', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006036', 'Tchicaillat', 'Anelvie', 'anelvie.tchicaillat@miage.edu', '1984-08-09', 'Femme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006037', 'Tiémélé', 'Amandi Jean-Michel', 'jean-michel.tieme@miage.edu', '1983-02-21', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006038', 'Traoré', 'Kigninlman François-Michaël', 'francois.traore@miage.edu', '1984-07-04', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006039', 'Traoré', 'Mamadou Ben', 'mamadou.traore@miage.edu', '1983-11-17', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006040', 'Yaméogo', 'Emmanuel', 'emmanuel.yameogo2@miage.edu', '1984-03-30', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006041', 'Yoboué', 'Kouamé Françis', 'francis.yoboue@miage.edu', '1983-10-13', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2006042', 'Zokou', 'Gbalé Simion', 'simion.zokou@miage.edu', '1984-05-26', 'Homme', '2006-2007');
INSERT INTO `etudiants` VALUES ('2007001', 'Abroh', 'Alokré Samuel Eliézer', 'samuel.abroh@miage.edu', '1984-01-15', 'Homme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007002', 'Abudrahman', 'Bako Rouhiya', 'rouhiya.abudrahman@miage.edu', '1985-03-28', 'Femme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007003', 'Acho', 'Dessi Stéphane Ivan', 'stephane.acho@miage.edu', '1984-09-10', 'Homme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007004', 'Adja', 'Willy Junior', 'willy.adja@miage.edu', '1985-05-22', 'Homme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007005', 'Aka', 'Manouan Angora Jean-Yves', 'jean-yves.aka@miage.edu', '1984-11-07', 'Homme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007006', 'Allou', 'Niamké Jean-Marc', 'jean-marc.allou@miage.edu', '1985-07-19', 'Homme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007007', 'Assy', 'Yves Landry', 'yves.assy@miage.edu', '1984-04-03', 'Homme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007008', 'Bouah', 'Martin Benjamin', 'martin.bouah@miage.edu', '1985-08-25', 'Homme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007009', 'Cissé', 'Ladji', 'ladji.cisse@miage.edu', '1984-12-30', 'Homme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007010', 'Dagbo', 'Ouraga Hervé', 'herve.dagbo@miage.edu', '1985-02-14', 'Homme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007011', 'Dagnogo', 'Chigata', 'chigata.dagnogo@miage.edu', '1984-06-18', 'Homme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007012', 'Degni', 'N\'drin Marie-Corine Jordane', 'marie.degni@miage.edu', '1985-10-09', 'Femme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007013', 'Djeah', 'Eric', 'eric.djeah@miage.edu', '1984-07-22', 'Homme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007014', 'Fagla', 'Armel Jean Yves', 'jean.fagla@miage.edu', '1985-01-05', 'Homme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007015', 'Gnayoro', 'Dano Hugues Florent', 'hugues.gnayoro@miage.edu', '1984-05-17', 'Homme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007016', 'Gohourou', 'Djédjé Didier', 'didier.gohourou@miage.edu', '1985-09-28', 'Homme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007017', 'Houi', 'Sosthène', 'sosthene.houi@miage.edu', '1984-03-11', 'Homme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007018', 'Houssou', 'Ipou Marie-Ange Colette', 'marie-ange.houssou@miage.edu', '1985-04-24', 'Femme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007019', 'Kabran', 'N\'guessan Jules-César', 'jules.kabran@miage.edu', '1984-08-07', 'Homme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007020', 'Kacou', 'N\'da Geneviève', 'genevieve.kacou@miage.edu', '1985-12-19', 'Femme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007021', 'Kanaté', 'Adama', 'adama.kanate@miage.edu', '1984-10-02', 'Homme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007022', 'Konan', 'Attocoly Aristide Christian', 'aristide.konan@miage.edu', '1985-06-15', 'Homme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007023', 'Kotei', 'Nikoi Samuel', 'samuel.kotei@miage.edu', '1984-02-28', 'Homme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007024', 'Koua', 'Konin N\'goran Marc Benjamin', 'marc.koua@miage.edu', '1985-11-11', 'Homme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007025', 'Kouacou', 'Adjoua Jessica Noelle', 'jessica.kouacou@miage.edu', '1984-01-23', 'Femme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007026', 'Kouamé', 'Ayoua Alain Blédoumou', 'alain.kouame@miage.edu', '1985-07-06', 'Homme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007027', 'Kouamé', 'Bi Gohoré Stéphane-Marcel', 'stephane.kouame@miage.edu', '1984-04-19', 'Homme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007028', 'Kouao', 'Akoissy Amoan Lynda Flore', 'lynda.kouao@miage.edu', '1985-08-31', 'Femme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007029', 'Kouodé', 'Nioulé Steve', 'steve.kouode@miage.edu', '1984-12-14', 'Homme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007030', 'Loba', 'Badjo Caroline Vinciane', 'caroline.loba@miage.edu', '1985-05-27', 'Femme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007031', 'Mariko', 'Eba Raïssa', 'raissa.mariko@miage.edu', '1984-09-09', 'Femme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007032', 'Moukounzi', 'Bakala Axel', 'axel.moukounzi@miage.edu', '1985-01-22', 'Homme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007033', 'N\'diaye', 'M\'baye', 'mbaye.ndiaye@miage.edu', '1984-06-05', 'Homme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007034', 'Niamké', 'Ehuia Marie-Eve', 'marie-eve.niamke@miage.edu', '1985-10-18', 'Femme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2007035', 'Oué', 'Simon', 'simon.oue@miage.edu', '1984-03-01', 'Homme', '2007-2008');
INSERT INTO `etudiants` VALUES ('2008001', 'Akanza', 'Kouassi Ronald', 'ronald.akanza@miage.edu', '1985-02-15', 'Homme', '2008-2009');
INSERT INTO `etudiants` VALUES ('2008002', 'Ané', 'Antoine Ahoua', 'antoine.ane@miage.edu', '1986-04-28', 'Homme', '2008-2009');
INSERT INTO `etudiants` VALUES ('2008003', 'Ango', 'Charles Erwan Brou', 'charles.ango@miage.edu', '1985-10-10', 'Homme', '2008-2009');
INSERT INTO `etudiants` VALUES ('2008004', 'Anon', 'Noelly', 'noelly.anon@miage.edu', '1986-06-22', 'Femme', '2008-2009');
INSERT INTO `etudiants` VALUES ('2008005', 'Boni', 'Jean-Philipe', 'jean-philipe.boni@miage.edu', '1985-12-07', 'Homme', '2008-2009');
INSERT INTO `etudiants` VALUES ('2008006', 'Boua', 'Stéphane Guesso', 'stephane.boua@miage.edu', '1986-08-19', 'Homme', '2008-2009');
INSERT INTO `etudiants` VALUES ('2008007', 'Coulibaly', 'Sékoumar Ayaké', 'sekoumar.coulibaly@miage.edu', '1985-05-03', 'Homme', '2008-2009');
INSERT INTO `etudiants` VALUES ('2008008', 'Diallo', 'Ismaël', 'ismael.diallo@miage.edu', '1986-09-25', 'Homme', '2008-2009');
INSERT INTO `etudiants` VALUES ('2008009', 'Diawara', 'Khady', 'khady.diawara@miage.edu', '1985-01-30', 'Femme', '2008-2009');
INSERT INTO `etudiants` VALUES ('2008010', 'Djédjé', 'Manoko Arthur-Ange', 'arthur.djedje@miage.edu', '1986-03-14', 'Homme', '2008-2009');
INSERT INTO `etudiants` VALUES ('2008011', 'Dongo', 'Kouamé Yannick', 'yannick.dongo@miage.edu', '1985-07-18', 'Homme', '2008-2009');
INSERT INTO `etudiants` VALUES ('2008012', 'Doou', 'Serge Baulais', 'serge.doou@miage.edu', '1986-11-09', 'Homme', '2008-2009');
INSERT INTO `etudiants` VALUES ('2008013', 'Douampo', 'Berthe', 'berthe.douampo@miage.edu', '1985-08-22', 'Femme', '2008-2009');
INSERT INTO `etudiants` VALUES ('2008014', 'Goeh-Akue', 'Adoté Fabrice', 'fabrice.goeh@miage.edu', '1986-02-05', 'Homme', '2008-2009');
INSERT INTO `etudiants` VALUES ('2008015', 'Kanga', 'Didier Franck', 'didier.kanga@miage.edu', '1985-06-17', 'Homme', '2008-2009');
INSERT INTO `etudiants` VALUES ('2008016', 'Kanté', 'Néné', 'nene.kante@miage.edu', '1986-10-28', 'Femme', '2008-2009');
INSERT INTO `etudiants` VALUES ('2008017', 'Kéïta', 'Abdul Pierre Emmanuel', 'pierre.keita@miage.edu', '1985-04-11', 'Homme', '2008-2009');
INSERT INTO `etudiants` VALUES ('2008018', 'Kouadio', 'Ange Aristide', 'aristide.kouadio@miage.edu', '1986-07-24', 'Homme', '2008-2009');
INSERT INTO `etudiants` VALUES ('2008019', 'Kouadio', 'Loukou Arnaud', 'arnaud.kouadio@miage.edu', '1985-09-07', 'Homme', '2008-2009');
INSERT INTO `etudiants` VALUES ('2008020', 'Kouahouri', 'Okou Joel', 'joel.kouahouri@miage.edu', '1986-01-19', 'Homme', '2008-2009');
INSERT INTO `etudiants` VALUES ('2008021', 'Kouamé', 'N\'woley Kévin', 'kevin.kouame@miage.edu', '1985-05-02', 'Homme', '2008-2009');
INSERT INTO `etudiants` VALUES ('2008022', 'Kouassi', 'Kamelan Herman', 'herman.kouassi@miage.edu', '1986-12-15', 'Homme', '2008-2009');
INSERT INTO `etudiants` VALUES ('2008023', 'Lobé', 'Ogonnin Gédéon', 'gedeon.lobe@miage.edu', '1985-03-28', 'Homme', '2008-2009');
INSERT INTO `etudiants` VALUES ('2008024', 'M\'bra', 'Koffi Serges Pacôme', 'serges.mbra@miage.edu', '1986-06-10', 'Homme', '2008-2009');
INSERT INTO `etudiants` VALUES ('2008025', 'Namongo', 'Soro Christian Etienne', 'christian.namongo@miage.edu', '1985-10-23', 'Homme', '2008-2009');
INSERT INTO `etudiants` VALUES ('2008026', 'N\'da-Ezoa', 'Melanwa Issac', 'issac.ndaezoa@miage.edu', '1986-04-05', 'Homme', '2008-2009');
INSERT INTO `etudiants` VALUES ('2008027', 'N\'guessan', 'Ahoko Lazare', 'lazare.nguessan@miage.edu', '1985-08-18', 'Homme', '2008-2009');
INSERT INTO `etudiants` VALUES ('2008028', 'N\'zazi', 'Yannick', 'yannick.nzazi@miage.edu', '1986-02-28', 'Homme', '2008-2009');
INSERT INTO `etudiants` VALUES ('2008029', 'Ouattara', 'Nambé Adama', 'adama.ouattara@miage.edu', '1985-07-11', 'Homme', '2008-2009');
INSERT INTO `etudiants` VALUES ('2008030', 'Sawadogo', 'Moussa', 'moussa.sawadogo@miage.edu', '1986-11-24', 'Homme', '2008-2009');
INSERT INTO `etudiants` VALUES ('2009001', 'Akini', 'Marie Danielle', 'marie.akini@miage.edu', '1986-01-15', 'Femme', '2009-2010');
INSERT INTO `etudiants` VALUES ('2009002', 'Arra', 'Jean Jonathan', 'jean.arra@miage.edu', '1987-03-28', 'Homme', '2009-2010');
INSERT INTO `etudiants` VALUES ('2009003', 'Attro', 'Elvis Donald', 'elvis.attro@miage.edu', '1986-09-10', 'Homme', '2009-2010');
INSERT INTO `etudiants` VALUES ('2009004', 'Bailly', 'G. Arnaud', 'arnaud.bailly@miage.edu', '1987-05-22', 'Homme', '2009-2010');
INSERT INTO `etudiants` VALUES ('2009005', 'Coulibaly', 'Hector Emile', 'hector.coulibaly@miage.edu', '1986-11-07', 'Homme', '2009-2010');
INSERT INTO `etudiants` VALUES ('2009006', 'Diaw', 'Oumar Passidi', 'oumar.diaw@miage.edu', '1987-07-19', 'Homme', '2009-2010');
INSERT INTO `etudiants` VALUES ('2009007', 'Diawara', 'Khady', 'khady.diawara2@miage.edu', '1986-04-03', 'Femme', '2009-2010');
INSERT INTO `etudiants` VALUES ('2009008', 'Doe', 'Kuoassi Ezékiel', 'ezekiel.doe@miage.edu', '1987-08-25', 'Homme', '2009-2010');
INSERT INTO `etudiants` VALUES ('2009009', 'Facondé', 'Rudy Ariel', 'rudy.faconde@miage.edu', '1986-12-30', 'Homme', '2009-2010');
INSERT INTO `etudiants` VALUES ('2009010', 'Fofana', 'N\'valy', 'nvaly.fofana@miage.edu', '1987-02-14', 'Homme', '2009-2010');
INSERT INTO `etudiants` VALUES ('2009011', 'Gossan', 'Akon Boris', 'boris.gossan@miage.edu', '1986-06-18', 'Homme', '2009-2010');
INSERT INTO `etudiants` VALUES ('2009012', 'Koffi', 'Guetta J.B. Carmel', 'carmel.koffi@miage.edu', '1987-10-09', 'Homme', '2009-2010');
INSERT INTO `etudiants` VALUES ('2009013', 'Koffi', 'Stéphane Placide', 'stephane.koffi@miage.edu', '1986-07-22', 'Homme', '2009-2010');
INSERT INTO `etudiants` VALUES ('2009014', 'Kouadio', 'Stéphane Kpangban', 'stephane.kouadio@miage.edu', '1987-01-05', 'Homme', '2009-2010');
INSERT INTO `etudiants` VALUES ('2009015', 'Kouahouri', 'Okou Joel', 'joel.kouahouri2@miage.edu', '1986-05-17', 'Homme', '2009-2010');
INSERT INTO `etudiants` VALUES ('2009016', 'Kouamé', 'Christian Koffi', 'christian.kouame@miage.edu', '1987-09-28', 'Homme', '2009-2010');
INSERT INTO `etudiants` VALUES ('2009017', 'Kouamé', 'Kodé Guy Roland', 'guy.kouame@miage.edu', '1986-03-11', 'Homme', '2009-2010');
INSERT INTO `etudiants` VALUES ('2009018', 'Kouassi', 'N\'dri Yves', 'yves.kouassi@miage.edu', '1987-04-24', 'Homme', '2009-2010');
INSERT INTO `etudiants` VALUES ('2009019', 'Kourouma', 'Fanta', 'fanta.kourouma@miage.edu', '1986-08-07', 'Femme', '2009-2010');
INSERT INTO `etudiants` VALUES ('2009020', 'Kouyo', 'Jonathan Ivan Lesson', 'jonathan.kouyo@miage.edu', '1987-12-19', 'Homme', '2009-2010');
INSERT INTO `etudiants` VALUES ('2009021', 'N\'guessan', 'Tecky Huber N\'da', 'huber.nguessan@miage.edu', '1986-10-02', 'Homme', '2009-2010');
INSERT INTO `etudiants` VALUES ('2009022', 'Ossein', 'Franck', 'franck.ossein@miage.edu', '1987-06-15', 'Homme', '2009-2010');
INSERT INTO `etudiants` VALUES ('2009023', 'Ouattara', 'Perbin Parfait', 'parfait.ouattara@miage.edu', '1986-02-28', 'Homme', '2009-2010');
INSERT INTO `etudiants` VALUES ('2009024', 'Tra bi', 'Tra Tizié Cyrille Modeste', 'cyrille.tra@miage.edu', '1987-11-11', 'Homme', '2009-2010');
INSERT INTO `etudiants` VALUES ('2010001', 'Ahouana', 'Akichi Roche Wilfried', 'wilfried.ahouana@miage.edu', '1987-01-15', 'Homme', '2010-2011');
INSERT INTO `etudiants` VALUES ('2010002', 'Aka', 'Itchi Maxime', 'maxime.aka@miage.edu', '1988-03-28', 'Homme', '2010-2011');
INSERT INTO `etudiants` VALUES ('2010003', 'Amisia', 'Molay Jean-marie', 'jean-marie.amisia@miage.edu', '1987-09-10', 'Homme', '2010-2011');
INSERT INTO `etudiants` VALUES ('2010004', 'Amoikon', 'Kangah Christophe', 'christophe.amoikon@miage.edu', '1988-05-22', 'Homme', '2010-2011');
INSERT INTO `etudiants` VALUES ('2010005', 'Attiembone', 'Christelle', 'christelle.attiembone@miage.edu', '1987-11-07', 'Femme', '2010-2011');
INSERT INTO `etudiants` VALUES ('2010006', 'Beugré', 'Wahon Marie-claude Esther', 'marie-claude.beugre@miage.edu', '1988-07-19', 'Femme', '2010-2011');
INSERT INTO `etudiants` VALUES ('2010007', 'Cherif', 'Idriss Ibrahim', 'ibrahim.cherif@miage.edu', '1987-04-03', 'Homme', '2010-2011');
INSERT INTO `etudiants` VALUES ('2010008', 'Diarrassouba', 'Habib Ismael', 'habib.diarrassouba@miage.edu', '1988-08-25', 'Homme', '2010-2011');
INSERT INTO `etudiants` VALUES ('2010009', 'Diawara', 'daoud ben Ahmed', 'daoud.diawara@miage.edu', '1987-12-30', 'Homme', '2010-2011');
INSERT INTO `etudiants` VALUES ('2010010', 'Flan', 'Zédé delphin', 'delphin.flan@miage.edu', '1988-02-14', 'Homme', '2010-2011');
INSERT INTO `etudiants` VALUES ('2010011', 'Gbakatchétché', 'Gilles-loïc', 'gilles.gbakatch@miage.edu', '1987-06-18', 'Homme', '2010-2011');
INSERT INTO `etudiants` VALUES ('2010012', 'Gnangne', 'Jean Jacques', 'jean.gnangne@miage.edu', '1988-10-09', 'Homme', '2010-2011');
INSERT INTO `etudiants` VALUES ('2010013', 'Koloubla', 'Dakouri Auguste Trésor', 'auguste.koloubla@miage.edu', '1987-07-22', 'Homme', '2010-2011');
INSERT INTO `etudiants` VALUES ('2010014', 'Konan', 'Konan Jean françois regis', 'jean.konan@miage.edu', '1988-01-05', 'Homme', '2010-2011');
INSERT INTO `etudiants` VALUES ('2010015', 'Kouakou', 'Enode de Laure', 'laure.kouakou@miage.edu', '1987-05-17', 'Femme', '2010-2011');
INSERT INTO `etudiants` VALUES ('2010016', 'Kouakou', 'kouamé Adjaphin Noël Désiré', 'noel.kouakou@miage.edu', '1988-09-28', 'Homme', '2010-2011');
INSERT INTO `etudiants` VALUES ('2010017', 'Kouakou', 'N\'guetta Marie-laure Cynthia', 'marie-laure.kouakou@miage.edu', '1987-03-11', 'Femme', '2010-2011');
INSERT INTO `etudiants` VALUES ('2010018', 'Kouassi', 'Laetitia Aimée tomoly', 'laetitia.kouassi@miage.edu', '1988-04-24', 'Femme', '2010-2011');
INSERT INTO `etudiants` VALUES ('2010019', 'Krama', 'Abdel-Kader', 'abdel.krama@miage.edu', '1987-08-07', 'Homme', '2010-2011');
INSERT INTO `etudiants` VALUES ('2010020', 'Menzan', 'Bini Kouamé Christian', 'christian.menzan@miage.edu', '1988-12-19', 'Homme', '2010-2011');
INSERT INTO `etudiants` VALUES ('2010021', 'Nguessan', 'kalou bi Dieudonné', 'dieudonne.nguessan@miage.edu', '1987-10-02', 'Homme', '2010-2011');
INSERT INTO `etudiants` VALUES ('2010022', 'N\'guessan', 'kouakou Fulgence', 'fulgence.nguessan@miage.edu', '1988-06-15', 'Homme', '2010-2011');
INSERT INTO `etudiants` VALUES ('2010023', 'Tanoh', 'Adjoua Marie', 'marie.tanoh@miage.edu', '1987-02-28', 'Femme', '2010-2011');
INSERT INTO `etudiants` VALUES ('2010024', 'Tuo', 'Kolotioloma Augustin', 'augustin.tuo@miage.edu', '1988-11-11', 'Homme', '2010-2011');
INSERT INTO `etudiants` VALUES ('2010025', 'Ya', 'Sandrine Anne-elodie', 'sandrine.ya@miage.edu', '1987-01-23', 'Femme', '2010-2011');
INSERT INTO `etudiants` VALUES ('2010026', 'Yéyé', 'Schadrachs Guy-roland', 'guy.yeye@miage.edu', '1988-07-06', 'Homme', '2010-2011');
INSERT INTO `etudiants` VALUES ('20190001', 'Emmanuel', 'Malan', 'emmanuelmalan@gmail.com', '1998-01-05', 'Homme', '2019-2020');


-- Structure de la table `evaluations_rapports`
DROP TABLE IF EXISTS `evaluations_rapports`;
CREATE TABLE `evaluations_rapports` (
  `id_evaluation` int NOT NULL,
  `id_rapport` int NOT NULL,
  `id_evaluateur` int NOT NULL,
  `note` decimal(4,2) DEFAULT NULL,
  `commentaire` text,
  `statut_evaluation` enum('en_attente','en_cours','terminee') NOT NULL DEFAULT 'en_attente',
  `date_evaluation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modification` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- Structure de la table `fonction`
DROP TABLE IF EXISTS `fonction`;
CREATE TABLE `fonction` (
  `id_fonction` int NOT NULL AUTO_INCREMENT,
  `lib_fonction` varchar(70) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  PRIMARY KEY (`id_fonction`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_mysql500_ci;

-- Données de la table `fonction`
INSERT INTO `fonction` VALUES ('2', 'Doyen de faculté');
INSERT INTO `fonction` VALUES ('3', 'Directeur de recherche');
INSERT INTO `fonction` VALUES ('5', 'Directeur pédagogique');
INSERT INTO `fonction` VALUES ('7', 'Professeur titulaire');
INSERT INTO `fonction` VALUES ('8', 'Maître de conférences');
INSERT INTO `fonction` VALUES ('9', 'Chargé de cours');
INSERT INTO `fonction` VALUES ('10', 'Assistant d’enseignement');
INSERT INTO `fonction` VALUES ('11', 'Chef de département');
INSERT INTO `fonction` VALUES ('12', 'Responsable de programme');
INSERT INTO `fonction` VALUES ('13', 'Coordonnateur pédagogique');
INSERT INTO `fonction` VALUES ('14', 'Directeur de laboratoire');
INSERT INTO `fonction` VALUES ('15', 'Encadreur de mémoire');
INSERT INTO `fonction` VALUES ('16', 'Enseignant vacataire');
INSERT INTO `fonction` VALUES ('17', 'Expert externe');
INSERT INTO `fonction` VALUES ('18', 'Secrétaire scientifique');
INSERT INTO `fonction` VALUES ('19', 'Président de jury');
INSERT INTO `fonction` VALUES ('20', 'Conseiller pédagogique');


-- Structure de la table `grade`
DROP TABLE IF EXISTS `grade`;
CREATE TABLE `grade` (
  `id_grade` int NOT NULL AUTO_INCREMENT,
  `lib_grade` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  PRIMARY KEY (`id_grade`),
  UNIQUE KEY `lib_grade` (`lib_grade`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_mysql500_ci;

-- Données de la table `grade`
INSERT INTO `grade` VALUES ('7', 'A1');
INSERT INTO `grade` VALUES ('6', 'A2');
INSERT INTO `grade` VALUES ('14', 'A3');
INSERT INTO `grade` VALUES ('10', 'B1');
INSERT INTO `grade` VALUES ('12', 'B2');
INSERT INTO `grade` VALUES ('15', 'D1');
INSERT INTO `grade` VALUES ('16', 'E2');
INSERT INTO `grade` VALUES ('13', 'F4');


-- Structure de la table `groupe_utilisateur`
DROP TABLE IF EXISTS `groupe_utilisateur`;
CREATE TABLE `groupe_utilisateur` (
  `id_GU` int NOT NULL AUTO_INCREMENT,
  `lib_GU` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  PRIMARY KEY (`id_GU`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_mysql500_ci;

-- Données de la table `groupe_utilisateur`
INSERT INTO `groupe_utilisateur` VALUES ('5', 'Administrateur');
INSERT INTO `groupe_utilisateur` VALUES ('6', 'Secretaire');
INSERT INTO `groupe_utilisateur` VALUES ('7', 'Chargée de communication');
INSERT INTO `groupe_utilisateur` VALUES ('8', 'Responsable scolarité');
INSERT INTO `groupe_utilisateur` VALUES ('9', 'Responsable Filière');
INSERT INTO `groupe_utilisateur` VALUES ('10', 'Responsable niveau');
INSERT INTO `groupe_utilisateur` VALUES ('11', 'commission de validation');
INSERT INTO `groupe_utilisateur` VALUES ('12', 'Enseignant sans responsabilité administrative');
INSERT INTO `groupe_utilisateur` VALUES ('13', 'Etudiant');


-- Structure de la table `informations_stage`
DROP TABLE IF EXISTS `informations_stage`;
CREATE TABLE `informations_stage` (
  `id_info_stage` int NOT NULL AUTO_INCREMENT,
  `num_etu` int NOT NULL,
  `id_entreprise` int NOT NULL,
  `date_debut_stage` date NOT NULL,
  `date_fin_stage` date NOT NULL,
  `sujet_stage` text NOT NULL,
  `description_stage` text NOT NULL,
  `encadrant_entreprise` varchar(100) NOT NULL,
  `email_encadrant` varchar(100) NOT NULL,
  `telephone_encadrant` varchar(20) NOT NULL,
  PRIMARY KEY (`id_info_stage`),
  KEY `num_etu` (`num_etu`),
  KEY `id_entreprise` (`id_entreprise`),
  CONSTRAINT `informations_stage_ibfk_1` FOREIGN KEY (`num_etu`) REFERENCES `etudiants` (`num_etu`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `informations_stage_ibfk_2` FOREIGN KEY (`id_entreprise`) REFERENCES `entreprises` (`id_entreprise`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Données de la table `informations_stage`
INSERT INTO `informations_stage` VALUES ('1', '2004003', '7', '2024-05-04', '2024-11-05', 'Développement d\'application mobile', 'ce stage a été effectué dans le but de d\'améliorer mes compétences en UI/UX Design', 'Tra Bi Hervé', 'fabriceherve@gmail.com', '+255 0705925841');
INSERT INTO `informations_stage` VALUES ('5', '2003003', '8', '2025-02-28', '2025-06-15', 'Développement d\'application mobile', 'Ce stage avait pour but d\'améliorer mes compétences en développement d\'application mobile avec flutter.', 'Yao Ferdinand', 'yaoferdinand@gmail.com', '+2250711489473');
INSERT INTO `informations_stage` VALUES ('6', '2003011', '8', '2024-08-12', '2025-01-13', 'Apprentissage des techniques d\'animation 3D', 'Amélioration des techniques d\'amélioration 3D', 'ASSI VIRGINIE ASSOMA', 'tiamaoui@yahoo.fr', '+2250708289628');


-- Structure de la table `inscriptions`
DROP TABLE IF EXISTS `inscriptions`;
CREATE TABLE `inscriptions` (
  `id_inscription` int NOT NULL AUTO_INCREMENT,
  `id_etudiant` int DEFAULT NULL,
  `id_niveau` int DEFAULT NULL,
  `id_annee_acad` int NOT NULL,
  `date_inscription` datetime DEFAULT NULL,
  `statut_inscription` enum('En cours','Validée','Annulée') DEFAULT NULL,
  `nombre_tranche` int NOT NULL,
  `reste_a_payer` decimal(10,2) NOT NULL,
  `montant_paye` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_inscription`),
  KEY `id_etudiant` (`id_etudiant`),
  KEY `id_niveau` (`id_niveau`),
  KEY `id_annee_acad` (`id_annee_acad`),
  CONSTRAINT `inscriptions_ibfk_1` FOREIGN KEY (`id_etudiant`) REFERENCES `etudiants` (`num_etu`),
  CONSTRAINT `inscriptions_ibfk_2` FOREIGN KEY (`id_niveau`) REFERENCES `niveau_etude` (`id_niv_etude`),
  CONSTRAINT `inscriptions_ibfk_3` FOREIGN KEY (`id_annee_acad`) REFERENCES `annee_academique` (`id_annee_acad`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Données de la table `inscriptions`
INSERT INTO `inscriptions` VALUES ('1', '2003001', '9', '22423', '2025-06-01 21:07:42', 'En cours', '2', '565000.00', '670000.00');
INSERT INTO `inscriptions` VALUES ('2', '2003002', '9', '22524', '2025-06-01 21:22:01', 'En cours', '2', '565000.00', '670000.00');
INSERT INTO `inscriptions` VALUES ('4', '2003003', '9', '22221', '2025-06-01 23:10:25', 'En cours', '4', '565000.00', '670000.00');
INSERT INTO `inscriptions` VALUES ('15', '2003004', '9', '22221', '2025-06-03 00:53:09', 'En cours', '1', '565000.00', '670000.00');
INSERT INTO `inscriptions` VALUES ('16', '2003005', '8', '22322', '2025-06-03 00:53:33', 'En cours', '3', '410000.00', '500000.00');
INSERT INTO `inscriptions` VALUES ('17', '2003006', '9', '22221', '2025-06-03 00:55:05', 'En cours', '2', '565000.00', '670000.00');
INSERT INTO `inscriptions` VALUES ('18', '2003007', '7', '22221', '2025-06-03 00:57:34', 'En cours', '3', '40000.00', '850000.00');
INSERT INTO `inscriptions` VALUES ('19', '2003008', '8', '22322', '2025-06-03 00:59:02', 'En cours', '2', '0.00', '910000.00');
INSERT INTO `inscriptions` VALUES ('20', '2003009', '9', '22221', '2025-06-04 13:55:43', 'En cours', '2', '565000.00', '670000.00');
INSERT INTO `inscriptions` VALUES ('25', '2003010', '9', '22221', '2025-06-04 19:09:06', 'En cours', '2', '565000.00', '670000.00');
INSERT INTO `inscriptions` VALUES ('26', '2003011', '9', '22221', '2025-06-04 19:10:40', 'En cours', '2', '0.00', '1235000.00');
INSERT INTO `inscriptions` VALUES ('30', '2004003', '9', '21817', '2025-06-16 00:48:39', 'En cours', '1', '0.00', '1235000.00');


-- Structure de la table `messages`
DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id_message` int NOT NULL AUTO_INCREMENT,
  `contenu_message` text NOT NULL,
  `lib_message` varchar(60) NOT NULL,
  `type_message` varchar(60) NOT NULL,
  PRIMARY KEY (`id_message`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Données de la table `messages`
INSERT INTO `messages` VALUES ('3', 'Bienvenue sur Soutenance Manager', 'message_bienvenue', 'info');
INSERT INTO `messages` VALUES ('4', 'Erreur lors du traitement du fichier', 'messageErreur', 'error');


-- Structure de la table `niveau_acces_donnees`
DROP TABLE IF EXISTS `niveau_acces_donnees`;
CREATE TABLE `niveau_acces_donnees` (
  `id_niveau_acces_donnees` int NOT NULL AUTO_INCREMENT,
  `lib_niveau_acces_donnees` varchar(70) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  PRIMARY KEY (`id_niveau_acces_donnees`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_mysql500_ci;

-- Données de la table `niveau_acces_donnees`
INSERT INTO `niveau_acces_donnees` VALUES ('4', 'Lecture seule');
INSERT INTO `niveau_acces_donnees` VALUES ('5', 'Écriture');


-- Structure de la table `niveau_approbation`
DROP TABLE IF EXISTS `niveau_approbation`;
CREATE TABLE `niveau_approbation` (
  `id_approb` int NOT NULL AUTO_INCREMENT,
  `lib_approb` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  PRIMARY KEY (`id_approb`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_mysql500_ci;

-- Données de la table `niveau_approbation`
INSERT INTO `niveau_approbation` VALUES ('3', 'Niveau 1');
INSERT INTO `niveau_approbation` VALUES ('4', 'Niveau 2');
INSERT INTO `niveau_approbation` VALUES ('6', 'Niveau 3');


-- Structure de la table `niveau_etude`
DROP TABLE IF EXISTS `niveau_etude`;
CREATE TABLE `niveau_etude` (
  `id_niv_etude` int NOT NULL AUTO_INCREMENT,
  `lib_niv_etude` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  `id_enseignant` int DEFAULT NULL,
  `montant_scolarite` decimal(10,2) DEFAULT NULL,
  `montant_inscription` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_niv_etude`),
  KEY `id_enseignant` (`id_enseignant`),
  CONSTRAINT `fk_niveau_enseignant` FOREIGN KEY (`id_enseignant`) REFERENCES `enseignants` (`id_enseignant`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_mysql500_ci;

-- Données de la table `niveau_etude`
INSERT INTO `niveau_etude` VALUES ('6', 'Licence 1', '5', '870000.00', '450000.00');
INSERT INTO `niveau_etude` VALUES ('7', 'Licence 2', '2', '890000.00', '450000.00');
INSERT INTO `niveau_etude` VALUES ('8', 'Licence 3', '13', '910000.00', '500000.00');
INSERT INTO `niveau_etude` VALUES ('9', 'Master 2', '12', '1235000.00', '670000.00');
INSERT INTO `niveau_etude` VALUES ('10', 'Master 1', '7', '980000.00', '560000.00');


-- Structure de la table `notes`
DROP TABLE IF EXISTS `notes`;
CREATE TABLE `notes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `num_etu` int NOT NULL,
  `id_ue` int DEFAULT NULL,
  `id_ecue` int DEFAULT NULL,
  `moyenne` decimal(4,2) NOT NULL,
  `commentaire` text,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_modification` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `notes_ibfk_1` (`num_etu`),
  KEY `notes_ibfk_2` (`id_ue`),
  KEY `notes_ibfk_3` (`id_ecue`),
  CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`num_etu`) REFERENCES `etudiants` (`num_etu`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `notes_ibfk_2` FOREIGN KEY (`id_ue`) REFERENCES `ue` (`id_ue`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `notes_ibfk_3` FOREIGN KEY (`id_ecue`) REFERENCES `ecue` (`id_ecue`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Données de la table `notes`
INSERT INTO `notes` VALUES ('5', '2004003', '73', NULL, '14.65', 'Bien', '2025-06-25 15:08:12', '2025-06-25 15:17:59');
INSERT INTO `notes` VALUES ('6', '2004003', '81', NULL, '12.00', 'Assez bien', '2025-06-25 15:08:12', '2025-06-25 15:08:12');
INSERT INTO `notes` VALUES ('7', '2004003', '76', NULL, '17.00', 'Très bien', '2025-06-25 15:08:12', '2025-06-25 15:08:12');
INSERT INTO `notes` VALUES ('8', '2004003', '77', NULL, '12.00', 'Assez bien', '2025-06-25 15:08:12', '2025-06-25 15:08:12');
INSERT INTO `notes` VALUES ('9', '2004003', '74', NULL, '13.00', 'Bien', '2025-06-25 15:08:12', '2025-06-25 15:08:12');
INSERT INTO `notes` VALUES ('10', '2004003', '79', NULL, '12.00', 'Assez bien', '2025-06-25 15:08:12', '2025-06-25 15:08:12');
INSERT INTO `notes` VALUES ('11', '2004003', '75', NULL, '7.00', 'Insuffisant', '2025-06-25 15:08:12', '2025-06-25 15:08:12');
INSERT INTO `notes` VALUES ('12', '2004003', '80', NULL, '8.65', 'Insuffisant', '2025-06-25 15:08:13', '2025-06-25 15:17:59');
INSERT INTO `notes` VALUES ('13', '2004003', '78', NULL, '12.00', 'Assez bien', '2025-06-25 15:08:13', '2025-06-25 15:08:13');
INSERT INTO `notes` VALUES ('14', '2003005', '51', '46', '12.60', 'Assez bien', '2025-06-25 17:24:51', '2025-06-25 17:24:51');
INSERT INTO `notes` VALUES ('15', '2003005', '51', '45', '18.00', 'Excellent', '2025-06-25 17:24:51', '2025-06-25 17:24:51');


-- Structure de la table `occuper`
DROP TABLE IF EXISTS `occuper`;
CREATE TABLE `occuper` (
  `id_fonction` int NOT NULL,
  `id_enseignant` int NOT NULL,
  `date_occupation` date NOT NULL,
  KEY `Key_occuper_enseignant` (`id_enseignant`),
  KEY `Key_occuper_fonction` (`id_fonction`),
  CONSTRAINT `fk_occuper_enseignant` FOREIGN KEY (`id_enseignant`) REFERENCES `enseignants` (`id_enseignant`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_occuper_fonction` FOREIGN KEY (`id_fonction`) REFERENCES `fonction` (`id_fonction`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_mysql500_ci;

-- Données de la table `occuper`
INSERT INTO `occuper` VALUES ('5', '2', '2011-02-02');
INSERT INTO `occuper` VALUES ('3', '5', '1999-01-02');
INSERT INTO `occuper` VALUES ('5', '7', '2016-05-02');
INSERT INTO `occuper` VALUES ('5', '9', '2018-05-01');
INSERT INTO `occuper` VALUES ('2', '10', '2018-02-12');
INSERT INTO `occuper` VALUES ('3', '11', '2016-04-25');
INSERT INTO `occuper` VALUES ('5', '12', '1998-04-12');
INSERT INTO `occuper` VALUES ('9', '13', '2023-09-25');
INSERT INTO `occuper` VALUES ('9', '14', '2013-04-23');
INSERT INTO `occuper` VALUES ('9', '15', '2019-02-23');


-- Structure de la table `personnel_admin`
DROP TABLE IF EXISTS `personnel_admin`;
CREATE TABLE `personnel_admin` (
  `id_pers_admin` int NOT NULL AUTO_INCREMENT,
  `nom_pers_admin` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  `prenom_pers_admin` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  `email_pers_admin` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  `tel_pers_admin` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  `poste` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  `date_embauche` date NOT NULL,
  PRIMARY KEY (`id_pers_admin`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_mysql500_ci;

-- Données de la table `personnel_admin`
INSERT INTO `personnel_admin` VALUES ('2', 'Yah', 'Christine', 'yahchristine@gmail.com', '0748703738', 'Secrétaire de la filière MIAGE', '2013-02-05');
INSERT INTO `personnel_admin` VALUES ('6', 'N\'goran', 'Durand', 'ngorandurand@gmail.com', '0759395841', 'Responsable scolarité', '2012-05-15');
INSERT INTO `personnel_admin` VALUES ('7', 'Seri', 'Marie Christine', 'noemietra27@gmail.com', '0711489473', 'Chargé de communication', '2013-02-12');
INSERT INTO `personnel_admin` VALUES ('8', 'Yao', 'Bertrand', 'yaobertrand@gmail.com', '0748703738', 'Chef du service informatique', '2006-02-05');


-- Structure de la table `pister`
DROP TABLE IF EXISTS `pister`;
CREATE TABLE `pister` (
  `id_utilisateur` int NOT NULL,
  `id_traitement` int NOT NULL,
  `date_acces` date NOT NULL,
  `heure_acces` time NOT NULL,
  `acceder` tinyint(1) NOT NULL DEFAULT '0',
  KEY `Key_pister_traitement` (`id_traitement`),
  KEY `Key_pister_utilisateur` (`id_utilisateur`),
  CONSTRAINT `fk_pister_traitement` FOREIGN KEY (`id_traitement`) REFERENCES `traitement` (`id_traitement`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pister_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_mysql500_ci;


-- Structure de la table `rapport_etudiants`
DROP TABLE IF EXISTS `rapport_etudiants`;
CREATE TABLE `rapport_etudiants` (
  `id_rapport` int NOT NULL AUTO_INCREMENT,
  `num_etu` int NOT NULL,
  `nom_rapport` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  `date_rapport` datetime NOT NULL,
  `theme_rapport` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  `chemin_fichier` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci DEFAULT NULL COMMENT 'Chemin vers le fichier de contenu',
  `statut_rapport` enum('en_cours','valider','rejeter','en_attente') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL DEFAULT 'en_attente',
  `date_modification` datetime DEFAULT NULL,
  `taille_fichier` int DEFAULT NULL COMMENT 'Taille du fichier en octets',
  `version` int NOT NULL DEFAULT '1' COMMENT 'Version du rapport',
  PRIMARY KEY (`id_rapport`),
  KEY `num_etu` (`num_etu`),
  CONSTRAINT `ibfk_etudiant` FOREIGN KEY (`num_etu`) REFERENCES `etudiants` (`num_etu`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_mysql500_ci;

-- Données de la table `rapport_etudiants`
INSERT INTO `rapport_etudiants` VALUES ('5', '2004003', 'Génération d\'une base de connaissance pour l\'IA', '2025-06-27 02:24:49', 'Intégration d\'une base intelligente', 'rapport_5.html', 'en_cours', '2025-06-27 02:24:49', '11780', '1');
INSERT INTO `rapport_etudiants` VALUES ('8', '2004003', 'Développement Web', '2025-06-27 20:09:48', 'Intégration d\'un système CRM', 'rapport_8.html', 'en_attente', '2025-06-27 20:09:48', '11771', '1');
INSERT INTO `rapport_etudiants` VALUES ('10', '2004003', 'Analyse de données', '2025-06-28 20:10:04', 'Régression multiple', 'rapport_10.html', 'en_attente', '2025-06-28 20:10:04', '11771', '1');


-- Structure de la table `rattacher`
DROP TABLE IF EXISTS `rattacher`;
CREATE TABLE `rattacher` (
  `id_GU` int NOT NULL,
  `id_traitement` int NOT NULL,
  KEY `Key_rattacher_GU` (`id_GU`),
  KEY `Key_rattacher_traitement` (`id_traitement`),
  CONSTRAINT `fk_rattacher_gu` FOREIGN KEY (`id_GU`) REFERENCES `groupe_utilisateur` (`id_GU`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_rattacher_traitement` FOREIGN KEY (`id_traitement`) REFERENCES `traitement` (`id_traitement`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_mysql500_ci;

-- Données de la table `rattacher`
INSERT INTO `rattacher` VALUES ('13', '12');
INSERT INTO `rattacher` VALUES ('13', '13');
INSERT INTO `rattacher` VALUES ('13', '20');
INSERT INTO `rattacher` VALUES ('13', '16');
INSERT INTO `rattacher` VALUES ('13', '15');
INSERT INTO `rattacher` VALUES ('13', '19');
INSERT INTO `rattacher` VALUES ('5', '5');
INSERT INTO `rattacher` VALUES ('5', '8');
INSERT INTO `rattacher` VALUES ('5', '7');
INSERT INTO `rattacher` VALUES ('5', '16');
INSERT INTO `rattacher` VALUES ('5', '11');
INSERT INTO `rattacher` VALUES ('5', '9');
INSERT INTO `rattacher` VALUES ('5', '19');
INSERT INTO `rattacher` VALUES ('5', '10');
INSERT INTO `rattacher` VALUES ('12', '27');
INSERT INTO `rattacher` VALUES ('12', '28');
INSERT INTO `rattacher` VALUES ('12', '16');
INSERT INTO `rattacher` VALUES ('12', '19');
INSERT INTO `rattacher` VALUES ('6', '33');
INSERT INTO `rattacher` VALUES ('6', '34');
INSERT INTO `rattacher` VALUES ('6', '29');
INSERT INTO `rattacher` VALUES ('6', '16');
INSERT INTO `rattacher` VALUES ('6', '19');
INSERT INTO `rattacher` VALUES ('7', '32');
INSERT INTO `rattacher` VALUES ('7', '29');
INSERT INTO `rattacher` VALUES ('7', '16');
INSERT INTO `rattacher` VALUES ('7', '19');
INSERT INTO `rattacher` VALUES ('7', '31');
INSERT INTO `rattacher` VALUES ('8', '23');
INSERT INTO `rattacher` VALUES ('8', '25');
INSERT INTO `rattacher` VALUES ('8', '6');
INSERT INTO `rattacher` VALUES ('8', '26');
INSERT INTO `rattacher` VALUES ('8', '41');
INSERT INTO `rattacher` VALUES ('8', '24');
INSERT INTO `rattacher` VALUES ('8', '16');
INSERT INTO `rattacher` VALUES ('8', '19');
INSERT INTO `rattacher` VALUES ('10', '27');
INSERT INTO `rattacher` VALUES ('10', '30');
INSERT INTO `rattacher` VALUES ('10', '28');
INSERT INTO `rattacher` VALUES ('10', '16');
INSERT INTO `rattacher` VALUES ('10', '19');
INSERT INTO `rattacher` VALUES ('9', '27');
INSERT INTO `rattacher` VALUES ('9', '29');
INSERT INTO `rattacher` VALUES ('9', '28');
INSERT INTO `rattacher` VALUES ('9', '16');
INSERT INTO `rattacher` VALUES ('9', '19');
INSERT INTO `rattacher` VALUES ('11', '35');
INSERT INTO `rattacher` VALUES ('11', '36');
INSERT INTO `rattacher` VALUES ('11', '38');
INSERT INTO `rattacher` VALUES ('11', '39');
INSERT INTO `rattacher` VALUES ('11', '16');
INSERT INTO `rattacher` VALUES ('11', '19');
INSERT INTO `rattacher` VALUES ('11', '40');
INSERT INTO `rattacher` VALUES ('11', '42');


-- Structure de la table `reclamations`
DROP TABLE IF EXISTS `reclamations`;
CREATE TABLE `reclamations` (
  `id_reclamation` int NOT NULL AUTO_INCREMENT,
  `num_etu` int DEFAULT NULL,
  `titre_reclamation` varchar(255) NOT NULL,
  `description_reclamation` text NOT NULL,
  `type_reclamation` enum('Académique','Administrative','Technique','Financière','Autre') NOT NULL,
  `priorite_reclamation` enum('Faible','Moyenne','Élevée','Urgente') NOT NULL DEFAULT 'Moyenne',
  `statut_reclamation` enum('En attente','Résolue','Rejetée') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'En attente',
  `date_creation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_mise_a_jour` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_pers_admin` int DEFAULT NULL,
  PRIMARY KEY (`id_reclamation`),
  KEY `idx_num_etu` (`num_etu`),
  KEY `idx_statut` (`statut_reclamation`),
  KEY `idx_type` (`type_reclamation`),
  KEY `idx_date_creation` (`date_creation`),
  KEY `fk_admin_assigne` (`id_pers_admin`),
  CONSTRAINT `fk_admin_assigne` FOREIGN KEY (`id_pers_admin`) REFERENCES `personnel_admin` (`id_pers_admin`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Données de la table `reclamations`
INSERT INTO `reclamations` VALUES ('1', '2006002', 'Mes notes on été echanger', 'les chiennns la mes notes ahi vous avez quelle probleme bande de cafards', 'Académique', 'Moyenne', 'En attente', '2025-06-16 13:13:39', '2025-06-16 13:13:39', NULL);
INSERT INTO `reclamations` VALUES ('2', '2004003', 'Problème au niveau de mes notes', 'il y\' a une erreur au niveau de mes notes d\'anglais et d\'analyse de données', 'Académique', 'Moyenne', 'En attente', '2025-06-25 18:30:54', '2025-06-25 18:30:54', NULL);
INSERT INTO `reclamations` VALUES ('3', '2004003', 'oggggggggggggggggg', 'epppppppppppppppppppppppppppppppppppppppppppppppppppppppppppppp', 'Académique', 'Moyenne', 'En attente', '2025-06-27 21:52:57', '2025-06-27 21:52:57', NULL);
INSERT INTO `reclamations` VALUES ('4', '2004003', 'rogggggggggggggggggggggggggg', 'zptmffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff', 'Académique', 'Moyenne', 'Rejetée', '2025-06-27 21:54:42', '2025-06-29 21:41:19', NULL);


-- Structure de la table `rendre`
DROP TABLE IF EXISTS `rendre`;
CREATE TABLE `rendre` (
  `id_CR` int NOT NULL,
  `id_enseignant` int NOT NULL,
  `date_env` datetime NOT NULL,
  KEY `Key_rendre_CR` (`id_CR`),
  KEY `Key_rendre_enseignant` (`id_enseignant`),
  CONSTRAINT `fk_rendre_cr` FOREIGN KEY (`id_CR`) REFERENCES `compte_rendu` (`id_CR`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_rendre_enseignant` FOREIGN KEY (`id_enseignant`) REFERENCES `enseignants` (`id_enseignant`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_mysql500_ci;


-- Structure de la table `resume_candidature`
DROP TABLE IF EXISTS `resume_candidature`;
CREATE TABLE `resume_candidature` (
  `id` int NOT NULL AUTO_INCREMENT,
  `num_etu` int NOT NULL,
  `id_candidature` int NOT NULL,
  `resume_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `decision` varchar(20) NOT NULL,
  `date_enregistrement` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `num_etu` (`num_etu`),
  KEY `fk_candidature` (`id_candidature`),
  CONSTRAINT `resume_ibfk_1` FOREIGN KEY (`num_etu`) REFERENCES `etudiants` (`num_etu`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `resume_ibfk_2` FOREIGN KEY (`id_candidature`) REFERENCES `candidature_soutenance` (`id_candidature`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Données de la table `resume_candidature`
INSERT INTO `resume_candidature` VALUES ('1', '2003003', '6', '{\"scolarite\":{\"statut\":\"En retard\",\"montant_total\":\"1 905 000 FCFA\",\"montant_paye\":\"670 000 FCFA\",\"dernier_paiement\":\"01\\/06\\/2025\",\"validation\":\"rejet\\u00e9\"},\"stage\":{\"entreprise\":\"QuanTech C\\u00f4te d\'Ivoire\",\"sujet\":\"D\\u00e9veloppement d\'application mobile\",\"periode\":\"28\\/02\\/2025 - 15\\/06\\/2025\",\"encadrant\":\"Yao Ferdinand\",\"validation\":\"rejet\\u00e9\"},\"semestre\":{\"semestre\":\"Non renseign\\u00e9\",\"moyenne\":\"0.00\\/20\",\"unites\":\"0\\/30 cr\\u00e9dits du Master 2 valid\\u00e9s\",\"validation\":\"rejet\\u00e9\"}}', 'Rejetée', '2025-06-23 10:36:53');
INSERT INTO `resume_candidature` VALUES ('4', '2003003', '7', '{\"scolarite\":{\"statut\":\"En retard\",\"montant_total\":\"1 235 000 FCFA\",\"montant_paye\":\"670 000 FCFA\",\"dernier_paiement\":\"01\\/06\\/2025\",\"validation\":\"rejet\\u00e9\"},\"stage\":{\"entreprise\":\"QuanTech C\\u00f4te d\'Ivoire\",\"sujet\":\"D\\u00e9veloppement d\'application mobile\",\"periode\":\"28\\/02\\/2025 - 15\\/06\\/2025\",\"encadrant\":\"Yao Ferdinand\",\"validation\":\"rejet\\u00e9\"},\"semestre\":{\"semestre\":\"Non renseign\\u00e9\",\"moyenne\":\"0.00\\/20\",\"unites\":\"0\\/30 cr\\u00e9dits du Master 2 valid\\u00e9s\",\"validation\":\"rejet\\u00e9\"}}', 'Rejetée', '2025-06-25 13:31:57');
INSERT INTO `resume_candidature` VALUES ('5', '2004003', '9', '{\"scolarite\":{\"statut\":\"\\u00c0 jour\",\"montant_total\":\"1 235 000 FCFA\",\"montant_paye\":\"1 235 000 FCFA\",\"dernier_paiement\":\"25\\/06\\/2025\",\"validation\":\"valid\\u00e9\"},\"stage\":{\"entreprise\":\"Tuzzo C\\u00f4te d\'Ivoire\",\"sujet\":\"D\\u00e9veloppement d\'application mobile\",\"periode\":\"04\\/05\\/2024 - 05\\/11\\/2024\",\"encadrant\":\"Tra Bi Herv\\u00e9\",\"validation\":\"valid\\u00e9\"},\"semestre\":{\"semestre\":\"Non renseign\\u00e9\",\"moyenne\":\"0.00\\/20\",\"unites\":\"0\\/30 cr\\u00e9dits du Master 2 valid\\u00e9s\",\"validation\":\"valid\\u00e9\"}}', 'Validée', '2025-06-25 13:41:42');


-- Structure de la table `semestre`
DROP TABLE IF EXISTS `semestre`;
CREATE TABLE `semestre` (
  `id_semestre` int NOT NULL AUTO_INCREMENT,
  `lib_semestre` varchar(100) NOT NULL,
  `id_niv_etude` int NOT NULL,
  PRIMARY KEY (`id_semestre`),
  KEY `id_niv_etude` (`id_niv_etude`),
  CONSTRAINT `fk_niveau_etude` FOREIGN KEY (`id_niv_etude`) REFERENCES `niveau_etude` (`id_niv_etude`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Données de la table `semestre`
INSERT INTO `semestre` VALUES ('12', 'Semestre 1', '6');
INSERT INTO `semestre` VALUES ('15', 'Semestre 2', '6');
INSERT INTO `semestre` VALUES ('16', 'Semestre 3', '7');
INSERT INTO `semestre` VALUES ('17', 'Semestre 4', '7');
INSERT INTO `semestre` VALUES ('18', 'Semestre 5', '8');
INSERT INTO `semestre` VALUES ('19', 'Semestre 6', '8');
INSERT INTO `semestre` VALUES ('20', 'Semestre 7', '10');
INSERT INTO `semestre` VALUES ('21', 'Semestre 8', '10');
INSERT INTO `semestre` VALUES ('22', 'Semestre 9', '9');


-- Structure de la table `specialite`
DROP TABLE IF EXISTS `specialite`;
CREATE TABLE `specialite` (
  `id_specialite` int NOT NULL AUTO_INCREMENT,
  `lib_specialite` varchar(70) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  PRIMARY KEY (`id_specialite`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_mysql500_ci;

-- Données de la table `specialite`
INSERT INTO `specialite` VALUES ('2', 'Informatique');
INSERT INTO `specialite` VALUES ('3', 'Comptabilité');
INSERT INTO `specialite` VALUES ('5', 'Mathématique');
INSERT INTO `specialite` VALUES ('6', 'Réseaux');
INSERT INTO `specialite` VALUES ('7', 'Médecine');
INSERT INTO `specialite` VALUES ('8', 'Géoscience');
INSERT INTO `specialite` VALUES ('9', 'Physique');
INSERT INTO `specialite` VALUES ('10', 'Génie Électrique et Électronique');
INSERT INTO `specialite` VALUES ('11', 'Biologie');
INSERT INTO `specialite` VALUES ('12', 'Droit Public');
INSERT INTO `specialite` VALUES ('13', 'Langues Étrangères');
INSERT INTO `specialite` VALUES ('14', 'Management');
INSERT INTO `specialite` VALUES ('15', 'Finance');
INSERT INTO `specialite` VALUES ('16', 'Marketing');


-- Structure de la table `statut_jury`
DROP TABLE IF EXISTS `statut_jury`;
CREATE TABLE `statut_jury` (
  `id_jury` int NOT NULL AUTO_INCREMENT,
  `lib_jury` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  PRIMARY KEY (`id_jury`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_mysql500_ci;

-- Données de la table `statut_jury`
INSERT INTO `statut_jury` VALUES ('6', 'accepter');
INSERT INTO `statut_jury` VALUES ('7', 'refuser');


-- Structure de la table `traitement`
DROP TABLE IF EXISTS `traitement`;
CREATE TABLE `traitement` (
  `id_traitement` int NOT NULL AUTO_INCREMENT,
  `lib_traitement` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  `label_traitement` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  `icone_traitement` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  `ordre_traitement` int NOT NULL,
  PRIMARY KEY (`id_traitement`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_mysql500_ci;

-- Données de la table `traitement`
INSERT INTO `traitement` VALUES ('5', 'dashboard', 'Tableau de bord', 'fa-home', '1');
INSERT INTO `traitement` VALUES ('6', 'gestion_etudiants', 'Gestion des étudiants', 'fa-book', '2');
INSERT INTO `traitement` VALUES ('7', 'gestion_utilisateurs', 'Gestion des utilisateurs', 'fa-user', '3');
INSERT INTO `traitement` VALUES ('8', 'gestion_rh', 'Gestion des ressources humaines', 'fa-users', '2');
INSERT INTO `traitement` VALUES ('9', 'piste_audit', 'Gestion de la piste', 'fa-history', '4');
INSERT INTO `traitement` VALUES ('10', 'sauvegarde_restauration', 'Sauvegarde et restauration des données', 'fa-save', '5');
INSERT INTO `traitement` VALUES ('11', 'parametres_generaux', 'Paramètres généraux', 'fa-gears', '6');
INSERT INTO `traitement` VALUES ('12', 'candidature_soutenance', 'Candidater à la soutenance', 'fa-graduation-cap', '1');
INSERT INTO `traitement` VALUES ('13', 'gestion_rapports', 'Gestion des rapports', 'fa-file', '2');
INSERT INTO `traitement` VALUES ('15', 'notes_resultats', 'Notes & résultats', 'fa-note-sticky', '4');
INSERT INTO `traitement` VALUES ('16', 'messagerie', 'Messagerie', 'fa-envelope', '5');
INSERT INTO `traitement` VALUES ('17', 'profil_etudiant', 'Profil étudiant', 'fa-user', '6');
INSERT INTO `traitement` VALUES ('19', 'profil', 'Profil', 'fa-user', '6');
INSERT INTO `traitement` VALUES ('20', 'gestion_reclamations', 'Gestion des réclamations', 'fa-exclamation', '3');
INSERT INTO `traitement` VALUES ('23', 'dashboard_scolarite', 'Tableau de bord scolarité', 'fa-home', '1');
INSERT INTO `traitement` VALUES ('24', 'gestion_scolarite', 'Gestion de la scolarité', 'fa-money-bill', '3');
INSERT INTO `traitement` VALUES ('25', 'gestion_candidatures_soutenance', 'Gestion des candidatures de soutenance', 'fa-folder', '4');
INSERT INTO `traitement` VALUES ('26', 'gestion_notes_evaluations', 'Gestions des notes et évaluations', 'fa-note-sticky', '5');
INSERT INTO `traitement` VALUES ('27', 'dashboard_enseignant', 'Tableau de bord enseignant', 'fa-home', '1');
INSERT INTO `traitement` VALUES ('28', 'liste_etudiants_ens_simple', 'Liste des étudiants évalués', 'fa-users', '2');
INSERT INTO `traitement` VALUES ('29', 'liste_etudiants_resp_filiere', 'Liste des étudiants MIAGE', 'fa-users', '2');
INSERT INTO `traitement` VALUES ('30', 'liste_etudiants_resp_niveau', 'Liste des étudiants de mon niveau', 'fa-users', '2');
INSERT INTO `traitement` VALUES ('31', 'verification_candidatures_soutenance', 'Vérification des candidatures de soutenance', 'fa-certificate', '1');
INSERT INTO `traitement` VALUES ('32', 'gestion_dossiers_candidatures', 'Gestion des dossiers de candidature', 'fa-folder', '2');
INSERT INTO `traitement` VALUES ('33', 'dashboard_secretaire', 'Tableau de bord secrétariat', 'fa-home', '1');
INSERT INTO `traitement` VALUES ('34', 'dossiers_academiques', 'Dossiers académiques', 'fa-folder-open', '3');
INSERT INTO `traitement` VALUES ('35', 'dashboard_commission', 'Tableau de bord de la commission', 'fa-home', '1');
INSERT INTO `traitement` VALUES ('36', 'evaluations_dossiers_soutenance', 'Évaluation des dossiers de soutenance', 'fa-file-contract', '2');
INSERT INTO `traitement` VALUES ('38', 'processus_validation', 'Processus de validation des dossiers', 'fa-list-check', '3');
INSERT INTO `traitement` VALUES ('39', 'archives_dossiers_soutenance', 'Archives des dossiers de soutenance', 'fa-inbox', '5');
INSERT INTO `traitement` VALUES ('40', 'planification_reunion', 'Planification des réunions', 'fa-calendar-days', '6');
INSERT INTO `traitement` VALUES ('41', 'gestion_reclamations_scolarite', 'Gestion des réclamations étudiantes ', 'fa-file', '4');
INSERT INTO `traitement` VALUES ('42', 'redaction_compte_rendu', 'Rédaction du compte rendu', 'fa-file', '6');


-- Structure de la table `type_utilisateur`
DROP TABLE IF EXISTS `type_utilisateur`;
CREATE TABLE `type_utilisateur` (
  `id_type_utilisateur` int NOT NULL AUTO_INCREMENT,
  `lib_type_utilisateur` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  PRIMARY KEY (`id_type_utilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_mysql500_ci;

-- Données de la table `type_utilisateur`
INSERT INTO `type_utilisateur` VALUES ('4', 'Personnel administratif');
INSERT INTO `type_utilisateur` VALUES ('5', 'Enseignant administratif');
INSERT INTO `type_utilisateur` VALUES ('6', 'Enseignant simple');
INSERT INTO `type_utilisateur` VALUES ('7', 'Etudiant');


-- Structure de la table `ue`
DROP TABLE IF EXISTS `ue`;
CREATE TABLE `ue` (
  `id_ue` int NOT NULL AUTO_INCREMENT,
  `lib_ue` varchar(70) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  `id_niveau_etude` int NOT NULL,
  `id_semestre` int NOT NULL,
  `id_annee_academique` int NOT NULL,
  `credit` int NOT NULL,
  `id_enseignant` int DEFAULT NULL,
  PRIMARY KEY (`id_ue`),
  KEY `id_annee_academique` (`id_annee_academique`),
  KEY `id_niveau_etude` (`id_niveau_etude`),
  KEY `id_semestre` (`id_semestre`),
  KEY `fk_enseignant_responsable` (`id_enseignant`),
  CONSTRAINT `ue_ibfk_1` FOREIGN KEY (`id_annee_academique`) REFERENCES `annee_academique` (`id_annee_acad`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ue_ibfk_2` FOREIGN KEY (`id_niveau_etude`) REFERENCES `niveau_etude` (`id_niv_etude`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ue_ibfk_3` FOREIGN KEY (`id_semestre`) REFERENCES `semestre` (`id_semestre`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ue_ibfk_4` FOREIGN KEY (`id_enseignant`) REFERENCES `enseignants` (`id_enseignant`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_mysql500_ci;

-- Données de la table `ue`
INSERT INTO `ue` VALUES ('8', 'Economie', '6', '12', '22524', '5', NULL);
INSERT INTO `ue` VALUES ('9', 'Réseaux informatiques', '8', '19', '22423', '5', NULL);
INSERT INTO `ue` VALUES ('10', 'Initiation à l\'informatique', '6', '12', '22524', '4', NULL);
INSERT INTO `ue` VALUES ('11', 'Initiation à l\'algorithmique', '6', '12', '22524', '3', NULL);
INSERT INTO `ue` VALUES ('12', 'Outils bureautiques 1', '6', '12', '22524', '2', '10');
INSERT INTO `ue` VALUES ('13', 'Mathématiques 1', '6', '12', '22524', '5', NULL);
INSERT INTO `ue` VALUES ('14', 'Mathématiques 2', '6', '12', '22524', '5', NULL);
INSERT INTO `ue` VALUES ('15', 'Organisation des entreprises', '6', '12', '22524', '3', NULL);
INSERT INTO `ue` VALUES ('16', 'Électronique', '6', '12', '22524', '3', NULL);
INSERT INTO `ue` VALUES ('17', 'Mathématiques 3', '6', '15', '22524', '6', NULL);
INSERT INTO `ue` VALUES ('18', 'Probabilité et Statistique 1', '6', '15', '22524', '5', NULL);
INSERT INTO `ue` VALUES ('19', 'Outils bureautiques 2', '6', '15', '22524', '2', '10');
INSERT INTO `ue` VALUES ('20', 'Atelier de maintenance', '6', '15', '22524', '1', NULL);
INSERT INTO `ue` VALUES ('21', 'Technique d\'expression et méthodologie de travail', '6', '15', '22524', '2', NULL);
INSERT INTO `ue` VALUES ('22', 'Intelligence économique', '6', '15', '22524', '2', NULL);
INSERT INTO `ue` VALUES ('23', 'Gestion des Ressources Humaines', '6', '15', '22524', '2', NULL);
INSERT INTO `ue` VALUES ('24', 'Logiciel de traitement d\'images ou de montage vidéo', '6', '15', '22524', '2', NULL);
INSERT INTO `ue` VALUES ('25', 'Anglais', '6', '15', '22524', '3', NULL);
INSERT INTO `ue` VALUES ('26', 'Programmation orientée objet', '7', '16', '22524', '6', NULL);
INSERT INTO `ue` VALUES ('27', 'Outils formels pour l\'informatique', '7', '16', '22524', '2', '9');
INSERT INTO `ue` VALUES ('28', 'Mathématiques 4', '7', '16', '22524', '6', NULL);
INSERT INTO `ue` VALUES ('29', 'Probabilité et Statistique 2', '7', '16', '22524', '4', NULL);
INSERT INTO `ue` VALUES ('30', 'Comptabilité generale', '7', '16', '22524', '6', NULL);
INSERT INTO `ue` VALUES ('31', 'Anglais', '7', '16', '22524', '3', NULL);
INSERT INTO `ue` VALUES ('32', 'Mathématiques 5', '7', '17', '22524', '2', NULL);
INSERT INTO `ue` VALUES ('33', 'Données semi-structurées et bases de données', '7', '17', '22524', '8', NULL);
INSERT INTO `ue` VALUES ('34', 'Programmation web', '7', '17', '22524', '3', NULL);
INSERT INTO `ue` VALUES ('35', 'Génie logiciel', '7', '17', '22524', '6', NULL);
INSERT INTO `ue` VALUES ('36', 'Programmation sous windows', '7', '17', '22524', '4', NULL);
INSERT INTO `ue` VALUES ('37', 'Contrôle budgétaire', '7', '17', '22524', '3', NULL);
INSERT INTO `ue` VALUES ('38', 'Initiation Python', '7', '17', '22524', '2', NULL);
INSERT INTO `ue` VALUES ('39', 'Projet', '7', '17', '22524', '2', NULL);
INSERT INTO `ue` VALUES ('40', 'Systèmes informatiques', '8', '18', '22524', '6', NULL);
INSERT INTO `ue` VALUES ('41', 'Programmation', '8', '18', '22524', '5', NULL);
INSERT INTO `ue` VALUES ('42', 'Base de données avancées', '8', '18', '22524', '3', NULL);
INSERT INTO `ue` VALUES ('43', 'Programmation client web', '8', '18', '22524', '3', NULL);
INSERT INTO `ue` VALUES ('44', 'Algorithmique des graphes', '8', '18', '22524', '3', NULL);
INSERT INTO `ue` VALUES ('45', 'Programmation linéaire', '8', '18', '22524', '3', NULL);
INSERT INTO `ue` VALUES ('46', 'Comptabilité de gestion', '8', '18', '22524', '4', NULL);
INSERT INTO `ue` VALUES ('47', 'Files d\'attente et gestion de stock', '8', '19', '22524', '3', NULL);
INSERT INTO `ue` VALUES ('48', 'Analyse de données', '8', '19', '22524', '3', '13');
INSERT INTO `ue` VALUES ('49', 'Programmation d\'application', '8', '19', '22524', '3', NULL);
INSERT INTO `ue` VALUES ('51', 'Théorie des langages', '8', '19', '22524', '3', NULL);
INSERT INTO `ue` VALUES ('53', 'Anglais', '8', '19', '22524', '3', NULL);
INSERT INTO `ue` VALUES ('54', 'Projet', '8', '19', '22524', '4', NULL);
INSERT INTO `ue` VALUES ('55', 'Environnement juridique', '8', '19', '22524', '3', NULL);
INSERT INTO `ue` VALUES ('56', 'Modélisation système d\'information', '10', '20', '22524', '5', NULL);
INSERT INTO `ue` VALUES ('57', 'Compléments de mathématiques', '10', '20', '22423', '4', NULL);
INSERT INTO `ue` VALUES ('58', 'Intelligence Artificielle', '10', '20', '22423', '2', NULL);
INSERT INTO `ue` VALUES ('59', 'Base de données avancées', '10', '20', '22524', '4', NULL);
INSERT INTO `ue` VALUES ('60', 'Programmation avancée Java', '10', '20', '22524', '4', NULL);
INSERT INTO `ue` VALUES ('61', 'Progiciel de comptabilité (SAGE)', '10', '20', '22423', '2', NULL);
INSERT INTO `ue` VALUES ('62', 'Management des entreprises', '10', '20', '22423', '3', NULL);
INSERT INTO `ue` VALUES ('63', 'Concurrence et coopération dans les systèmes et les réseaux', '10', '20', '22423', '4', NULL);
INSERT INTO `ue` VALUES ('64', 'Internet/Intranet', '10', '20', '22423', '2', NULL);
INSERT INTO `ue` VALUES ('65', 'Base de données décisionnelles ', '10', '21', '22524', '3', NULL);
INSERT INTO `ue` VALUES ('66', 'Programmation impérative et developpement d\'IHM ', '10', '21', '22524', '4', NULL);
INSERT INTO `ue` VALUES ('67', 'Système d\'information repartis', '10', '21', '22524', '5', NULL);
INSERT INTO `ue` VALUES ('68', 'Contrôle de gestion', '10', '21', '22524', '3', NULL);
INSERT INTO `ue` VALUES ('69', 'Comptabilité analytique', '10', '21', '22524', '4', NULL);
INSERT INTO `ue` VALUES ('70', 'Marketing', '10', '21', '22524', '3', NULL);
INSERT INTO `ue` VALUES ('71', 'Projet de developpement logiciel', '10', '21', '22524', '5', NULL);
INSERT INTO `ue` VALUES ('72', 'Anglais', '10', '21', '22524', '3', NULL);
INSERT INTO `ue` VALUES ('73', 'Analyse et conception à objet', '9', '22', '22524', '5', '2');
INSERT INTO `ue` VALUES ('74', 'Gestion financière', '9', '22', '22524', '3', '14');
INSERT INTO `ue` VALUES ('75', 'Management de projet et intégration d\'application', '9', '22', '22524', '6', NULL);
INSERT INTO `ue` VALUES ('76', 'Audit informatique', '9', '22', '22524', '3', '13');
INSERT INTO `ue` VALUES ('77', 'Entrepreunariat', '9', '22', '22524', '2', '12');
INSERT INTO `ue` VALUES ('78', 'Multimedia mobile', '9', '22', '22524', '3', NULL);
INSERT INTO `ue` VALUES ('79', 'Ingenierie des exigences et veille technologique', '9', '22', '22524', '3', '10');
INSERT INTO `ue` VALUES ('80', 'Mathématiques financières', '9', '22', '22524', '3', NULL);
INSERT INTO `ue` VALUES ('81', 'Anglais', '9', '22', '22524', '2', '11');
INSERT INTO `ue` VALUES ('82', 'Algorithmique et Programmation', '6', '15', '22524', '5', NULL);
INSERT INTO `ue` VALUES ('83', 'Gestion financière', '8', '19', '22524', '3', '14');
INSERT INTO `ue` VALUES ('84', 'Analyse de données', '7', '16', '22524', '3', NULL);
INSERT INTO `ue` VALUES ('85', 'BMO', '8', '18', '22524', '3', NULL);


-- Structure de la table `utilisateur`
DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE `utilisateur` (
  `id_utilisateur` int NOT NULL AUTO_INCREMENT,
  `nom_utilisateur` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  `id_type_utilisateur` int NOT NULL,
  `id_GU` int NOT NULL,
  `id_niv_acces_donnee` int NOT NULL,
  `statut_utilisateur` enum('Actif','Inactif') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  `login_utilisateur` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  `mdp_utilisateur` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  PRIMARY KEY (`id_utilisateur`),
  UNIQUE KEY `login_utilisateur` (`login_utilisateur`),
  KEY `id_groupe_utilisateur` (`id_GU`),
  KEY `id_niv_acces_donnee` (`id_niv_acces_donnee`),
  KEY `id_type_utilisateur` (`id_type_utilisateur`),
  CONSTRAINT `utilisateur_ibfk_2` FOREIGN KEY (`id_GU`) REFERENCES `groupe_utilisateur` (`id_GU`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `utilisateur_ibfk_3` FOREIGN KEY (`id_niv_acces_donnee`) REFERENCES `niveau_acces_donnees` (`id_niveau_acces_donnees`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `utilisateur_ibfk_4` FOREIGN KEY (`id_type_utilisateur`) REFERENCES `type_utilisateur` (`id_type_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_mysql500_ci;

-- Données de la table `utilisateur`
INSERT INTO `utilisateur` VALUES ('5', 'Koua Brou', '5', '5', '5', 'Actif', 'oceanetl27@gmail.com', '$2y$10$5xegW5cpfo9paDNWYZHnsup7Qngf8JpejSPRxwVpmxCaxAGP.w4im');
INSERT INTO `utilisateur` VALUES ('27', 'Kouakou Mathias', '6', '12', '5', 'Actif', 'axelangegomez@gmail.com', '$2y$10$QXdyHw8Tky94eHKJY.Bw/OoQ/t9h1cNn/itHTZa7wgRHJxtKb9URC');
INSERT INTO `utilisateur` VALUES ('34', 'Yah Christine', '4', '6', '4', 'Actif', 'yahchristine@gmail.com', '$2y$10$StdfqOOpnOBUf1kSZnhHeu6TqUiXVWtfqI73AKv4v7Cv2NjjKqNna');
INSERT INTO `utilisateur` VALUES ('35', 'N\'goran Durand', '4', '8', '5', 'Actif', 'ngorandurand@gmail.com', '$2y$10$9ZHd/WKtdrB2jpeaH2cewe/hdoUfUOxaDH7P4S/YUTI5uqu23hOoe');
INSERT INTO `utilisateur` VALUES ('36', 'Brou Patrice', '5', '11', '5', 'Actif', 'angeaxelgomez@gmail.com', '$2y$10$QtMW8goQLrH.om8q1mlgo.tJwA1AGFc./uZpOLhoo4g2z7p46OCrm');
INSERT INTO `utilisateur` VALUES ('39', 'N\'golo Konaté', '6', '12', '5', 'Actif', 'ngolokonate@yahoo.fr', '$2y$10$o7fxEokbRfMLFoGPOS1TpOFuxjFQ3TFzMMV4mukNEWLs9aNxGjr/u');
INSERT INTO `utilisateur` VALUES ('40', 'Nindjin Malan', '6', '12', '5', 'Actif', 'nindjinmalan@gmail.com', '$2y$10$f0Nq70iGpanY5FrbCZI2G.fJ.aj8oDu05Y2Gi5y1LjsrcDc5M3TXa');
INSERT INTO `utilisateur` VALUES ('59', 'Aka Prince', '7', '13', '5', 'Actif', 'prince.aka@miage.edu', '$2y$10$4Ch3UNErI7enw4qTEum.He/je80LyawhwafWzSd1XFpPslQresEdy');
INSERT INTO `utilisateur` VALUES ('79', 'Seri Marie Christine', '4', '7', '5', 'Actif', 'noemietra27@gmail.com', '$2y$10$MU/.mUpi4WcU.YeihVgip.WdJ7EMmZ1vO3NKnH5IclNzcp.zaWUYy');
INSERT INTO `utilisateur` VALUES ('81', 'Brou Kouamé Wa Ambroise', '7', '13', '5', 'Actif', 'kouame.brou@miage.edu', '$2y$10$keRv.zYilWhkPEcFa8zF/uf4fu4mTbHkfF9orQS9zThS3BDU0b/He');
INSERT INTO `utilisateur` VALUES ('82', 'Coulibaly Pécory Ismaèl', '7', '13', '5', 'Actif', 'pecory.coulibaly@miage.edu', '$2y$10$HliFB0N/ZfI2qWPnclw01O8lBMcJjjhhxyqlLqGWjTRRMfnj.WWGC');
INSERT INTO `utilisateur` VALUES ('83', 'Diomandé Gondo Patrick', '7', '13', '5', 'Actif', 'gondo.diomande@miage.edu', '$2y$10$zYnzDrTSquYQmaIYOFkq7eKzL9kqYS/OHO99IEv0PijUvkO.uCVK6');
INSERT INTO `utilisateur` VALUES ('84', 'Ekponou Georges', '7', '13', '5', 'Actif', 'georges.ekponou@miage.edu', '$2y$10$X8VsR9CHU/VwYBaHCrf6MuDhJ9JkUv0Ncz7t1RyGnsEn2om0IMDw2');
INSERT INTO `utilisateur` VALUES ('85', 'Guiégui Arnaud Kévin Boris', '7', '13', '5', 'Actif', 'arnaud.guiegui@miage.edu', '$2y$10$MDqlwgj2HvdJ5NZ0zSF59eyL1ZTkRNa9xidM8guQpVZUlzgziqJjG');
INSERT INTO `utilisateur` VALUES ('86', 'Kéi Ninsémon Hervé', '7', '13', '5', 'Actif', 'herve.kei@miage.edu', '$2y$10$O1mce8rBqd8FMPOEEOzz8u72lU1fYYACaNRl64vs0IygVbKRcPHkK');
INSERT INTO `utilisateur` VALUES ('87', 'Kinimo Habia Elvire', '7', '13', '5', 'Actif', 'elvire.kinimo@miage.edu', '$2y$10$Eb5ji/2./UjVCJFz2kKXuuiCsjgJooZAJYE5woBs6H9BiESPZpiqu');
INSERT INTO `utilisateur` VALUES ('88', 'Kouadio Donald', '7', '13', '5', 'Actif', 'donald.kouadio@miage.edu', '$2y$10$2.Eg0s563GbxmHKklA7SjuPNj.2AgWk7KIInerEOKzOX7VEd3UP.W');
INSERT INTO `utilisateur` VALUES ('89', 'Brou IDA', '5', '10', '4', 'Actif', 'brouida@gmail.com', '$2y$10$osV4q118B2wZAydw0YFmzuqU4WNQbyBd5sat8XxhaW9/96qmqaLy.');


-- Structure de la table `valider`
DROP TABLE IF EXISTS `valider`;
CREATE TABLE `valider` (
  `id_enseignant` int NOT NULL,
  `id_rapport` int NOT NULL,
  `date_validation` datetime NOT NULL,
  `commentaire_validation` varchar(1000) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_mysql500_ci NOT NULL,
  KEY `Key_valider_enseignant` (`id_enseignant`),
  KEY `Key_valider_rapport` (`id_rapport`),
  CONSTRAINT `fk_valider_enseignant` FOREIGN KEY (`id_enseignant`) REFERENCES `enseignants` (`id_enseignant`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_valider_rapport` FOREIGN KEY (`id_rapport`) REFERENCES `rapport_etudiants` (`id_rapport`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_mysql500_ci;


-- Structure de la table `versements`
DROP TABLE IF EXISTS `versements`;
CREATE TABLE `versements` (
  `id_versement` int NOT NULL AUTO_INCREMENT,
  `id_inscription` int DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `date_versement` datetime DEFAULT CURRENT_TIMESTAMP,
  `type_versement` enum('Premier versement','Tranche') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `methode_paiement` enum('Espèce','Carte bancaire','Virement','Chèque') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id_versement`),
  KEY `id_inscription` (`id_inscription`),
  CONSTRAINT `versements_ibfk_1` FOREIGN KEY (`id_inscription`) REFERENCES `inscriptions` (`id_inscription`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Données de la table `versements`
INSERT INTO `versements` VALUES ('1', '1', '670000.00', '2025-06-01 21:07:42', 'Premier versement', 'Espèce');
INSERT INTO `versements` VALUES ('2', '2', '670000.00', '2025-06-01 21:22:01', 'Premier versement', 'Espèce');
INSERT INTO `versements` VALUES ('4', '4', '670000.00', '2025-06-01 23:10:25', 'Premier versement', 'Espèce');
INSERT INTO `versements` VALUES ('19', '15', '670000.00', '2025-06-03 00:53:09', 'Premier versement', 'Espèce');
INSERT INTO `versements` VALUES ('20', '16', '500000.00', '2025-06-03 00:53:33', 'Premier versement', 'Carte bancaire');
INSERT INTO `versements` VALUES ('21', '17', '670000.00', '2025-06-03 00:55:05', 'Premier versement', 'Carte bancaire');
INSERT INTO `versements` VALUES ('22', '18', '450000.00', '2025-06-03 00:57:34', 'Premier versement', 'Espèce');
INSERT INTO `versements` VALUES ('23', '19', '500000.00', '2025-06-03 00:59:02', 'Premier versement', 'Carte bancaire');
INSERT INTO `versements` VALUES ('24', '20', '670000.00', '2025-06-04 13:55:43', 'Premier versement', 'Carte bancaire');
INSERT INTO `versements` VALUES ('29', '25', '670000.00', '2025-06-04 19:09:06', 'Premier versement', 'Virement');
INSERT INTO `versements` VALUES ('30', '26', '670000.00', '2025-06-04 19:10:40', 'Premier versement', 'Espèce');
INSERT INTO `versements` VALUES ('45', '26', '165000.00', '2025-06-15 23:37:22', 'Tranche', 'Carte bancaire');
INSERT INTO `versements` VALUES ('46', '19', '410000.00', '2025-06-15 23:44:56', 'Tranche', 'Chèque');
INSERT INTO `versements` VALUES ('51', '30', '670000.00', '2025-06-16 00:48:39', 'Premier versement', 'Virement');
INSERT INTO `versements` VALUES ('54', '26', '400000.00', '2025-06-16 01:33:56', 'Tranche', 'Espèce');
INSERT INTO `versements` VALUES ('55', '18', '400000.00', '2025-06-16 01:36:18', 'Tranche', 'Chèque');
INSERT INTO `versements` VALUES ('56', '30', '565000.00', '2025-06-25 12:13:45', 'Tranche', 'Carte bancaire');

