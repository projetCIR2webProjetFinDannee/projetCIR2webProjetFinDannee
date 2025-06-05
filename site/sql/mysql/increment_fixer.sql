-- Fix serials. Only compatible with mysql
ALTER TABLE Pays AUTO_INCREMENT = (SELECT MAX(id) + 1 FROM Pays);
ALTER TABLE Panneau_Marque AUTO_INCREMENT = (SELECT MAX(id) + 1 FROM Panneau_Marque);
ALTER TABLE Panneau_Modele AUTO_INCREMENT = (SELECT MAX(id) + 1 FROM Panneau_Modele);
ALTER TABLE Panneau AUTO_INCREMENT = (SELECT MAX(id) + 1 FROM Panneau);
ALTER TABLE Ondulateur_Marque AUTO_INCREMENT = (SELECT MAX(id) + 1 FROM Ondulateur_Marque);
ALTER TABLE Ondulateur_Modele AUTO_INCREMENT = (SELECT MAX(id) + 1 FROM Ondulateur_Modele);
ALTER TABLE Ondulateur AUTO_INCREMENT = (SELECT MAX(id) + 1 FROM Ondulateur);
ALTER TABLE Installeur AUTO_INCREMENT = (SELECT MAX(id) + 1 FROM Installeur);
ALTER TABLE Documentation AUTO_INCREMENT = (SELECT MAX(id) + 1 FROM Documentation);
ALTER TABLE Installation AUTO_INCREMENT = (SELECT MAX(id) + 1 FROM Installation);