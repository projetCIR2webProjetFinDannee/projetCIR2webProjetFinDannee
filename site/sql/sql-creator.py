import csv

PATH = "C:/Users/alexi/OneDrive/Transfert PC/Etudes sup/2ème année prépa/projetCIR2/"

table_countries = {1: (1, "France")}
table_reg = {}
table_dep = {}
table_city = {}
table_pann_marque = {}
table_pann_modele = {}
table_pann = {}
table_ondul_marque = {}
table_ondul_modele = {}
table_ondul = {}
table_docu = {}
table_installeur = {}
table_installation = {}

cache_city = {}

all_tables = (
    ("Pays", table_countries, "(id, nom)"),
    ("Region", table_reg, "(code, nom, id_pays)"),
    ("Departement", table_dep, "(code, nom, code_Region)"),
    ("Commune", table_city, "(code_insee, nom, population, code_postal, code_dep)"),
    ("Panneau_Marque", table_pann_marque, "(id, nom)"),
    ("Panneau_Modele", table_pann_modele, "(id, nom)"),
    ("Panneau", table_pann, "(id, id_Panneau_Marque, id_Panneau_Modele)"),
    ("Ondulateur_Marque", table_ondul_marque, "(id, nom)"),
    ("Ondulateur_Modele", table_ondul_modele, "(id, nom)"),
    ("Ondulateur", table_ondul, "(id, id_Ondulateur_Modele, id_Ondulateur_marque)"),
    ("Installeur", table_installeur, "(id, nom)"),
    ("Documentation", table_docu, "(id, date, latitude, longitude, nb_panneaux, nb_ondul, puiss_crete, surface, pente, pente_optimum, orientation, orientation_optimum, production_pvgis, code_insee, id_Panneau, id_Ondulateur, id_Installeur)"),
    ("Installation", table_installation, "(id, iddoc)")
)


def insert_reg(code, nom, id_pays):
    if not code in table_reg:
        table_reg[code] = (code, nom, id_pays)

def insert_dep(code, nom, code_reg):
    if not code in table_dep:
        table_dep[code] = (code, nom, code_reg)

def insert_city(code_insee, nom, population, code_postal, code_dep):
    if not code_insee in table_city and code_insee != '':
        table_city[code_insee] = (code_insee, nom, population, code_postal, code_dep)
        cache_city[nom] = code_insee

next_pann_marque_id = 1
def insert_pann_marque(nom):
    global next_pann_marque_id
    if not nom in table_pann_marque:
        table_pann_marque[nom] = (next_pann_marque_id, nom)
        next_pann_marque_id += 1
    return table_pann_marque[nom][0]
    
next_pann_modele_id = 1
def insert_pann_modele(nom):
    global next_pann_modele_id
    if not nom in table_pann_modele:
        table_pann_modele[nom] = (next_pann_modele_id, nom)
        next_pann_modele_id += 1
    return table_pann_modele[nom][0]

next_ondul_marque_id = 1
def insert_ondul_marque(nom):
    global next_ondul_marque_id
    if not nom in table_ondul_marque:
        table_ondul_marque[nom] = (next_ondul_marque_id, nom)
        next_ondul_marque_id += 1
    return table_ondul_marque[nom][0]

next_ondul_modele_id = 1
def insert_ondul_modele(nom):
    global next_ondul_modele_id
    if not nom in table_ondul_modele:
        table_ondul_modele[nom] = (next_ondul_modele_id, nom)
        next_ondul_modele_id += 1
    return table_ondul_modele[nom][0]

next_pann_id = 1
def insert_pann(id_marque, id_modele):
    global next_pann_id
    if not (id_marque, id_modele) in table_pann:
        table_pann[(id_marque, id_modele)] = (next_pann_id, id_marque, id_modele)
        next_pann_id += 1
    return table_pann[(id_marque, id_modele)][0]

next_ondul_id = 1
def insert_ondul(id_marque, id_modele):
    global next_ondul_id
    if not (id_modele, id_marque) in table_ondul:
        table_ondul[(id_modele, id_marque)] = (next_ondul_id, id_modele, id_marque)
        next_ondul_id += 1
    return table_ondul[(id_modele, id_marque)][0]

def insert_docu(iddoc, date, lat, long, nb_pann, nb_ondul, puiss_crete, surface, prod_pvgis, pente, pente_optimum, orientation, orientation_optimum, code_insee, id_pann, id_ondul, id_installer):
    if not iddoc in table_docu:
        if pente_optimum == '':
            pente_optimum = 0
        if orientation_optimum == '':
            orientation_optimum = 0
        if id_installer == None:
            print("wtf")
        table_docu[iddoc] = (iddoc, date, float(lat), float(long), int(nb_pann), int(nb_ondul), int(puiss_crete), int(surface), int(pente), int(pente_optimum), orientation, orientation_optimum, int(prod_pvgis), code_insee, id_pann, id_ondul, id_installer)

next_installer_id = 1
def insert_installer(nom):
    global next_installer_id
    if not nom in table_installeur:
        table_installeur[nom] = (next_installer_id, nom)
        next_installer_id += 1
    return table_installeur[nom][0]

def insert_installation(id, iddoc):
    if not id in table_installation:
        table_installation[id] = (id, iddoc)

def get_insee_code(city_name):
    if city_name in cache_city:
        return cache_city[city_name]
    else:
        return None     # delete line
    
def to_sql_row(row):
    row_str = list(str(row))
    is_in_str = False
    for i in range(len(row_str)):
        if row_str[i] == '"':
            row_str[i] = "'"
            is_in_str = not is_in_str
        elif row_str[i] == "'":
            if is_in_str:
                row_str[i] = '’'
        elif row_str[i] == 'N':
            if row_str[i:i+4] == 'None':
                row_str[i+1] = 'U'
                row_str[i+2] = 'L'
                row_str[i+3] = 'L'
    return "".join(row_str)




def open_locations(csv_filename):
    with open(csv_filename, 'r') as file:
        csv_reader = csv.reader(file)
        headers = next(csv_reader)

        for row in csv_reader:
            row_data = row[0].split(";")
            insert_reg(row_data[2], row_data[3], 1)
            insert_dep(row_data[4], row_data[5], row_data[2])
            insert_city(row_data[0], row_data[1], row_data[7], row_data[6], row_data[4])


def open_installations(csv_filename):
    with open(csv_filename, 'r') as file:
        csv_reader = csv.reader(file)
        headers = next(csv_reader)

        for row in csv_reader:
            data = row[0].split(";")
            date = data[3] + "-" + data[2] + "-01"
            code_insee = get_insee_code(data[24])
            if code_insee != None:
                id_pann_marque = insert_pann_marque(data[5])
                id_pann_modele = insert_pann_modele(data[6])
                id_ondul_marque = insert_ondul_marque(data[8])
                id_ondul_modele = insert_ondul_modele(data[9])
                id_pann = insert_pann(id_pann_marque, id_pann_modele)
                id_ondul = insert_ondul(id_ondul_marque, id_ondul_modele)
                id_installer = insert_installer(data[16])
                insert_docu(data[1], date, data[18], data[19], data[7], data[4], data[10], data[11], data[17], data[12], data[13], data[14], data[15], code_insee, id_pann, id_ondul, id_installer)
                insert_installation(data[0], data[1])


def export_sql(sql_filename):
    with open(sql_filename, 'w') as file:
        for table_name, table, inserted_cols in all_tables:
            file.write(f"-- Insertion in table {table_name}\n")
            file.write(f"INSERT INTO {table_name} {inserted_cols} VALUES \n")
            for i_row, row in enumerate(table.values()):
                file.write(f"{to_sql_row(row)}")
                if i_row != len(table.values()) -1:
                    file.write(",\n")
            file.write(";\n\n")

open_locations(PATH + "communes_france.csv")
open_installations(PATH + "data.csv")
export_sql(PATH + "data.sql")