-- Only compatible with mysql due to AUTO_INCREMENT

DROP TABLE IF EXISTS Installation;
DROP TABLE IF EXISTS Documentation;
DROP TABLE IF EXISTS Installeur;
DROP TABLE IF EXISTS Panneau;
DROP TABLE IF EXISTS Panneau_Modele;
DROP TABLE IF EXISTS Panneau_Marque;
DROP TABLE IF EXISTS Ondulateur;
DROP TABLE IF EXISTS Ondulateur_Marque;
DROP TABLE IF EXISTS Ondulateur_Modele;

DROP TABLE IF EXISTS Commune;
DROP TABLE IF EXISTS Departement;
DROP TABLE IF EXISTS Region;
DROP TABLE IF EXISTS Pays;

-- ----------------------------------------------------------
 -- Table Pays
-- ----------------------------------------------------------

CREATE TABLE Pays(
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR (70) NOT NULL
);


-- ----------------------------------------------------------
 -- Table Region
-- ----------------------------------------------------------

CREATE TABLE Region(
        code CHAR (2) PRIMARY KEY,
        nom  VARCHAR (50) NOT NULL,
        id_pays INT NOT NULL,
        FOREIGN KEY (id_pays) REFERENCES Pays(id)
);


-- ----------------------------------------------------------
 -- Table Departement
-- ----------------------------------------------------------

CREATE TABLE Departement(
        code CHAR (3) PRIMARY KEY,
        nom VARCHAR (50) NOT NULL,
        code_Region CHAR (2) NOT NULL,
        FOREIGN KEY (code_Region) REFERENCES Region(code)
);


-- ----------------------------------------------------------
 -- Table Commune
-- ----------------------------------------------------------

CREATE TABLE Commune(
        code_insee CHAR (5) PRIMARY KEY,
        nom VARCHAR (50) NOT NULL,
        population  INT NOT NULL,
        code_postal CHAR (5) NOT NULL,
        code_dep CHAR (3) NOT NULL,
        FOREIGN KEY (code_dep) REFERENCES Departement(code)
);


-- ----------------------------------------------------------
 -- Table Panneau Marque
-- ----------------------------------------------------------

CREATE TABLE Panneau_Marque(
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR (50) NOT NULL
);


-- ----------------------------------------------------------
 -- Table Panneau Modele
-- ----------------------------------------------------------

CREATE TABLE Panneau_Modele(
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR (50) NOT NULL
);


-- ----------------------------------------------------------
 -- Table Panneau
-- ----------------------------------------------------------

CREATE TABLE Panneau(
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_Panneau_Marque INT NOT NULL,
        id_Panneau_Modele INT NOT NULL,
        FOREIGN KEY (id_Panneau_Marque) REFERENCES Panneau_Marque(id),
        FOREIGN KEY (id_Panneau_Modele) REFERENCES Panneau_Modele(id)
);


-- ----------------------------------------------------------
 -- Table Ondulateur Marque
-- ----------------------------------------------------------

CREATE TABLE Ondulateur_Marque(
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR (50) NOT NULL
);


-- ----------------------------------------------------------
 -- Table Ondulateur Modele
-- ----------------------------------------------------------

CREATE TABLE Ondulateur_Modele(
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR (50) NOT NULL
);


-- ----------------------------------------------------------
 -- Table Ondulateur
-- ----------------------------------------------------------

CREATE TABLE Ondulateur(
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_Ondulateur_Modele INT NOT NULL,
        id_Ondulateur_Marque INT NOT NULL,
        FOREIGN KEY (id_Ondulateur_Modele) REFERENCES Ondulateur_Modele(id),
        FOREIGN KEY (id_Ondulateur_Marque) REFERENCES Ondulateur_Marque(id)
);


-- ----------------------------------------------------------
 -- Table Installeur
-- ----------------------------------------------------------

CREATE TABLE Installeur(
        id               INT AUTO_INCREMENT PRIMARY KEY,
        nom              VARCHAR (100) NOT NULL
);


-- ----------------------------------------------------------
 -- Table Documentation
-- ----------------------------------------------------------

CREATE TABLE Documentation(
        id                  INT AUTO_INCREMENT PRIMARY KEY,
        date                Date NOT NULL ,
        latitude            FLOAT NOT NULL ,
        longitude           FLOAT NOT NULL ,
        nb_panneaux         INT NOT NULL ,
        nb_ondul            INT NOT NULL ,
        puiss_crete         INT NOT NULL ,
        surface             INT NOT NULL ,
        pente               INT NOT NULL ,
        pente_optimum       INT ,
        orientation         VARCHAR (10) NOT NULL ,
        orientation_optimum VARCHAR (10) ,
        production_pvgis    INT,
        code_insee          CHAR (5) NOT NULL,
        id_Panneau          INT NOT NULL,
        id_Ondulateur       INT NOT NULL,
        id_Installeur       INT,
        FOREIGN KEY (code_insee) REFERENCES Commune(code_insee),
        FOREIGN KEY (id_Panneau) REFERENCES Panneau(id),
        FOREIGN KEY (id_Ondulateur) REFERENCES Ondulateur(id),
        FOREIGN KEY (id_Installeur) REFERENCES Installeur(id)
);

-- ----------------------------------------------------------
 -- Table Installation
-- ----------------------------------------------------------

CREATE TABLE Installation(
        id               INT AUTO_INCREMENT PRIMARY KEY,
        iddoc INT NOT NULL,
        FOREIGN KEY (iddoc) REFERENCES Documentation(id)
);