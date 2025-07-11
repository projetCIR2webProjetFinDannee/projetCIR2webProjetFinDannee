-- Fix serials. Only compatible with pgsql
SELECT setval(pg_get_serial_sequence('Pays', 'id'), COALESCE(MAX(id), 1)) FROM Pays;
SELECT setval(pg_get_serial_sequence('Panneau_Marque', 'id'), COALESCE(MAX(id), 1)) FROM Panneau_Marque;
SELECT setval(pg_get_serial_sequence('Panneau_Modele', 'id'), COALESCE(MAX(id), 1)) FROM Panneau_Modele;
SELECT setval(pg_get_serial_sequence('Panneau', 'id'), COALESCE(MAX(id), 1)) FROM Panneau;
SELECT setval(pg_get_serial_sequence('Ondulateur_Marque', 'id'), COALESCE(MAX(id), 1)) FROM Ondulateur_Marque;
SELECT setval(pg_get_serial_sequence('Ondulateur_Modele', 'id'), COALESCE(MAX(id), 1)) FROM Ondulateur_Modele;
SELECT setval(pg_get_serial_sequence('Ondulateur', 'id'), COALESCE(MAX(id), 1)) FROM Ondulateur;
SELECT setval(pg_get_serial_sequence('Installeur', 'id'), COALESCE(MAX(id), 1)) FROM Installeur;
SELECT setval(pg_get_serial_sequence('Documentation', 'id'), COALESCE(MAX(id), 1)) FROM Documentation;
SELECT setval(pg_get_serial_sequence('Installation', 'id'), COALESCE(MAX(id), 1)) FROM Installation;