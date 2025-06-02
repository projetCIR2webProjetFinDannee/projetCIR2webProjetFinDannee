#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------


#------------------------------------------------------------
# Table: Pays
#------------------------------------------------------------

CREATE TABLE Pays(
        id  Int  Auto_increment  NOT NULL ,
        nom Varchar (70) NOT NULL
	,CONSTRAINT Pays_PK PRIMARY KEY (id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Region
#------------------------------------------------------------

CREATE TABLE Region(
        code Char (2) NOT NULL ,
        nom  Varchar (50) NOT NULL ,
        id   Int NOT NULL
	,CONSTRAINT Region_PK PRIMARY KEY (code)

	,CONSTRAINT Region_Pays_FK FOREIGN KEY (id) REFERENCES Pays(id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Departement
#------------------------------------------------------------

CREATE TABLE Departement(
        code        Char (3) NOT NULL ,
        nom         Varchar (50) NOT NULL ,
        code_Region Char (2) NOT NULL
	,CONSTRAINT Departement_PK PRIMARY KEY (code)

	,CONSTRAINT Departement_Region_FK FOREIGN KEY (code_Region) REFERENCES Region(code)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Commune
#------------------------------------------------------------

CREATE TABLE Commune(
        code_insee  Char (5) NOT NULL ,
        nom         Varchar (50) NOT NULL ,
        population  Int NOT NULL ,
        code_postal Char (5) NOT NULL ,
        code        Char (3) NOT NULL
	,CONSTRAINT Commune_PK PRIMARY KEY (code_insee)

	,CONSTRAINT Commune_Departement_FK FOREIGN KEY (code) REFERENCES Departement(code)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Panneau Marque
#------------------------------------------------------------

CREATE TABLE Panneau_Marque(
        id  Int  Auto_increment  NOT NULL ,
        nom Varchar (10) NOT NULL
	,CONSTRAINT Panneau_Marque_PK PRIMARY KEY (id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Panneau Modele
#------------------------------------------------------------

CREATE TABLE Panneau_Modele(
        id  Int  Auto_increment  NOT NULL ,
        nom Varchar (10) NOT NULL
	,CONSTRAINT Panneau_Modele_PK PRIMARY KEY (id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Panneau
#------------------------------------------------------------

CREATE TABLE Panneau(
        id                Int  Auto_increment  NOT NULL ,
        id_Panneau_Marque Int NOT NULL ,
        id_Panneau_Modele Int NOT NULL
	,CONSTRAINT Panneau_PK PRIMARY KEY (id)

	,CONSTRAINT Panneau_Panneau_Marque_FK FOREIGN KEY (id_Panneau_Marque) REFERENCES Panneau_Marque(id)
	,CONSTRAINT Panneau_Panneau_Modele0_FK FOREIGN KEY (id_Panneau_Modele) REFERENCES Panneau_Modele(id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Ondulateur Marque
#------------------------------------------------------------

CREATE TABLE Ondulateur_Marque(
        id  Int  Auto_increment  NOT NULL ,
        nom Varchar (50) NOT NULL
	,CONSTRAINT Ondulateur_Marque_PK PRIMARY KEY (id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Ondulateur Modele
#------------------------------------------------------------

CREATE TABLE Ondulateur_Modele(
        id  Int  Auto_increment  NOT NULL ,
        nom Varchar (50) NOT NULL
	,CONSTRAINT Ondulateur_Modele_PK PRIMARY KEY (id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Ondulateur
#------------------------------------------------------------

CREATE TABLE Ondulateur(
        id                   Int  Auto_increment  NOT NULL ,
        id_Ondulateur_Modele Int NOT NULL ,
        id_Ondulateur_Marque Int NOT NULL
	,CONSTRAINT Ondulateur_PK PRIMARY KEY (id)

	,CONSTRAINT Ondulateur_Ondulateur_Modele_FK FOREIGN KEY (id_Ondulateur_Modele) REFERENCES Ondulateur_Modele(id)
	,CONSTRAINT Ondulateur_Ondulateur_Marque0_FK FOREIGN KEY (id_Ondulateur_Marque) REFERENCES Ondulateur_Marque(id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Documentation
#------------------------------------------------------------

CREATE TABLE Documentation(
        id                  Int NOT NULL ,
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
        code_insee          Char (5) NOT NULL ,
        id_Panneau          Int NOT NULL ,
        id_Ondulateur       Int NOT NULL
	,CONSTRAINT Documentation_PK PRIMARY KEY (id)

	,CONSTRAINT Documentation_Commune_FK FOREIGN KEY (code_insee) REFERENCES Commune(code_insee)
	,CONSTRAINT Documentation_Panneau0_FK FOREIGN KEY (id_Panneau) REFERENCES Panneau(id)
	,CONSTRAINT Documentation_Ondulateur1_FK FOREIGN KEY (id_Ondulateur) REFERENCES Ondulateur(id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Installeur
#------------------------------------------------------------

CREATE TABLE Installeur(
        id               Int  Auto_increment  NOT NULL ,
        nom              Varchar (100) NOT NULL ,
        id_Documentation Int NOT NULL
	,CONSTRAINT Installeur_PK PRIMARY KEY (id)

	,CONSTRAINT Installeur_Documentation_FK FOREIGN KEY (id_Documentation) REFERENCES Documentation(id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: Installation
#------------------------------------------------------------

CREATE TABLE Installation(
        id               Int NOT NULL ,
        id_Documentation Int NOT NULL
	,CONSTRAINT Installation_PK PRIMARY KEY (id)

	,CONSTRAINT Installation_Documentation_FK FOREIGN KEY (id_Documentation) REFERENCES Documentation(id)
)ENGINE=InnoDB;