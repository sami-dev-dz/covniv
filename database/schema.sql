-- 1. Create users table
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    num_carte INT UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- 2. Create infos table
CREATE TABLE infos (
    user_info_id INT PRIMARY KEY AUTO_INCREMENT, 
    num_carte INT UNIQUE NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    nom VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telephone VARCHAR(20),
    sexe ENUM('Homme', 'Femme') DEFAULT 'Homme',
    password VARCHAR(255) NOT NULL,
    photo_profil VARCHAR(255),
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP,
    est_actif BOOLEAN DEFAULT TRUE
);



-- 5. Create voitures table (required for trajets)
CREATE TABLE voitures (
    voiture_id INT PRIMARY KEY AUTO_INCREMENT,
    proprietaire_id INT NOT NULL,
    marque VARCHAR(50) NOT NULL,
    modele VARCHAR(50) NOT NULL,
    annee INT,
    plaque_immatriculation VARCHAR(20) UNIQUE NOT NULL,
    couleur VARCHAR(30),
    nombre_places INT NOT NULL CHECK (nombre_places > 0),
    date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP, 
    FOREIGN KEY (proprietaire_id) REFERENCES users(user_id)
);

-- 6. Create trajets table
CREATE TABLE trajets (
    trajet_id INT PRIMARY KEY AUTO_INCREMENT,
    conducteur_id INT NOT NULL,
    voiture_id INT NOT NULL,
    lieu_depart VARCHAR(255) NOT NULL,
    lieu_arrivee VARCHAR(255) NOT NULL,
    adresse_depart VARCHAR(255) NOT NULL,
    adresse_arrivee VARCHAR(255) NOT NULL,
    date_depart DATE NOT NULL,
    heure_depart TIME NOT NULL,
    places_disponibles INT NOT NULL CHECK (places_disponibles >= 0),
    prix DECIMAL(5, 2) NOT NULL CHECK (prix >= 0),
    statut_trajet ENUM('planifie', 'en_cours', 'termine', 'annule') DEFAULT 'planifie',
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    notes TEXT,
    FOREIGN KEY (conducteur_id) REFERENCES users(user_id),
    FOREIGN KEY (voiture_id) REFERENCES voitures(voiture_id)
);

-- 7. Create points_arret table (required for reservations)

-- 8. Create reservations table
CREATE TABLE reservations (
    reservation_id INT PRIMARY KEY AUTO_INCREMENT,
    trajet_id INT NOT NULL,
    passager_id INT NOT NULL,
    places_reservees INT NOT NULL DEFAULT 1,
    statut_reservation ENUM('en_attente', 'confirmee', 'annulee', 'terminee') DEFAULT 'en_attente',
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (trajet_id) REFERENCES trajets(trajet_id),
    FOREIGN KEY (passager_id) REFERENCES users(user_id)
);

-- 9. Create messages table
CREATE TABLE messages (
    message_id INT PRIMARY KEY AUTO_INCREMENT,
    expediteur_id INT NOT NULL,
    destinataire_id INT NOT NULL,
    trajet_id INT,
    contenu_message TEXT NOT NULL,
    date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP,
    est_lu BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (expediteur_id) REFERENCES users(user_id),
    FOREIGN KEY (destinataire_id) REFERENCES users(user_id),
    FOREIGN KEY (trajet_id) REFERENCES trajets(trajet_id)
);

