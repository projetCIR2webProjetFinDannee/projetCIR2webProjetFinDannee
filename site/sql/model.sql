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

------------------------------------------------------------
 -- Table Pays
------------------------------------------------------------

CREATE TABLE Pays(
        id SERIAL PRIMARY KEY,
        nom Varchar (70) NOT NULL
);


------------------------------------------------------------
 -- Table Region
------------------------------------------------------------

CREATE TABLE Region(
        code Char (2) PRIMARY KEY,
        nom  Varchar (50) NOT NULL,
        id_pays INT REFERENCES Pays(id) NOT NULL
);


------------------------------------------------------------
 -- Table Departement
------------------------------------------------------------

CREATE TABLE Departement(
        code Char (3) PRIMARY KEY,
        nom Varchar (50) NOT NULL,
        code_Region Char (2) REFERENCES Region(code) NOT NULL
);


------------------------------------------------------------
 -- Table Commune
------------------------------------------------------------

CREATE TABLE Commune(
        code_insee Char (5) PRIMARY KEY,
        nom Varchar (50) NOT NULL,
        population  Int NOT NULL,
        code_postal Char (5) NOT NULL,
        code_dep Char (3) REFERENCES Departement(code) NOT NULL
);


------------------------------------------------------------
 -- Table Panneau Marque
------------------------------------------------------------

CREATE TABLE Panneau_Marque(
        id SERIAL PRIMARY KEY,
        nom Varchar (50) NOT NULL
);


------------------------------------------------------------
 -- Table Panneau Modele
------------------------------------------------------------

CREATE TABLE Panneau_Modele(
        id SERIAL PRIMARY KEY,
        nom Varchar (50) NOT NULL
);


------------------------------------------------------------
 -- Table Panneau
------------------------------------------------------------

CREATE TABLE Panneau(
        id SERIAL PRIMARY KEY,
        id_Panneau_Marque INT REFERENCES Panneau_Marque(id) NOT NULL,
        id_Panneau_Modele INT REFERENCES Panneau_Modele(id) NOT NULL
);


------------------------------------------------------------
 -- Table Ondulateur Marque
------------------------------------------------------------

CREATE TABLE Ondulateur_Marque(
        id SERIAL PRIMARY KEY,
        nom Varchar (50) NOT NULL
);


------------------------------------------------------------
 -- Table Ondulateur Modele
------------------------------------------------------------

CREATE TABLE Ondulateur_Modele(
        id SERIAL PRIMARY KEY,
        nom Varchar (50) NOT NULL
);


------------------------------------------------------------
 -- Table Ondulateur
------------------------------------------------------------

CREATE TABLE Ondulateur(
        id SERIAL PRIMARY KEY,
        id_Ondulateur_Modele INT REFERENCES Ondulateur_Modele(id) NOT NULL,
        id_Ondulateur_Marque INT REFERENCES Ondulateur_Marque(id) NOT NULL
);


------------------------------------------------------------
 -- Table Installeur
------------------------------------------------------------

CREATE TABLE Installeur(
        id               SERIAL PRIMARY KEY,
        nom              Varchar (100) NOT NULL
);


------------------------------------------------------------
 -- Table Documentation
------------------------------------------------------------

CREATE TABLE Documentation(
        id                  SERIAL PRIMARY KEY,
        date                Date NOT NULL ,
        lat                 Float NOT NULL ,
        long                Float NOT NULL ,
        nb_panneaux         Int NOT NULL ,
        nb_ondul            Int NOT NULL ,
        puiss_crete         Int NOT NULL ,
        surface             Int NOT NULL ,
        pente               Int NOT NULL ,
        pente_optimum       Int ,
        orientation         Varchar (10) NOT NULL ,
        orientation_optimum Varchar (10) ,
        production_pvgis    Int,
        code_insee          Char (5) REFERENCES Commune(code_insee) NOT NULL,
        id_Panneau          INT REFERENCES Panneau(id) NOT NULL,
        id_Ondulateur       INT REFERENCES Ondulateur(id) NOT NULL,
        id_Installeur       INT REFERENCES Installeur(id)
);

------------------------------------------------------------
 -- Table Installation
------------------------------------------------------------

CREATE TABLE Installation(
        id               INT PRIMARY KEY,
        iddoc INT REFERENCES Documentation(id) NOT NULL
);