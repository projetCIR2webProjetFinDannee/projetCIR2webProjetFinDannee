-- Fix serials. Only compatible with mysql

-- Pays
SET @max_id = (SELECT MAX(id) FROM Pays);
SET @sql = CONCAT('ALTER TABLE Pays AUTO_INCREMENT = ', @max_id + 1);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Panneau_Marque
SET @max_id = (SELECT MAX(id) FROM Panneau_Marque);
SET @sql = CONCAT('ALTER TABLE Panneau_Marque AUTO_INCREMENT = ', @max_id + 1);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Panneau_Modele
SET @max_id = (SELECT MAX(id) FROM Panneau_Modele);
SET @sql = CONCAT('ALTER TABLE Panneau_Modele AUTO_INCREMENT = ', @max_id + 1);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Panneau
SET @max_id = (SELECT MAX(id) FROM Panneau);
SET @sql = CONCAT('ALTER TABLE Panneau AUTO_INCREMENT = ', @max_id + 1);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Ondulateur_Marque
SET @max_id = (SELECT MAX(id) FROM Ondulateur_Marque);
SET @sql = CONCAT('ALTER TABLE Ondulateur_Marque AUTO_INCREMENT = ', @max_id + 1);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Ondulateur_Modele
SET @max_id = (SELECT MAX(id) FROM Ondulateur_Modele);
SET @sql = CONCAT('ALTER TABLE Ondulateur_Modele AUTO_INCREMENT = ', @max_id + 1);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Ondulateur
SET @max_id = (SELECT MAX(id) FROM Ondulateur);
SET @sql = CONCAT('ALTER TABLE Ondulateur AUTO_INCREMENT = ', @max_id + 1);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Installeur
SET @max_id = (SELECT MAX(id) FROM Installeur);
SET @sql = CONCAT('ALTER TABLE Installeur AUTO_INCREMENT = ', @max_id + 1);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Documentation
SET @max_id = (SELECT MAX(id) FROM Documentation);
SET @sql = CONCAT('ALTER TABLE Documentation AUTO_INCREMENT = ', @max_id + 1);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Installation
SET @max_id = (SELECT MAX(id) FROM Installation);
SET @sql = CONCAT('ALTER TABLE Installation AUTO_INCREMENT = ', @max_id + 1);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;