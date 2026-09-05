CREATE TABLE IF NOT EXISTS salles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    batiment VARCHAR(100) NOT NULL,
    capacite INT UNSIGNED NOT NULL,
    type ENUM('cours', 'informatique', 'laboratoire', 'amphitheatre', 'reunion') NOT NULL,
    active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT chk_salles_capacite CHECK (capacite BETWEEN 1 AND 1000)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS reservations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    salle_id BIGINT UNSIGNED NOT NULL,
    responsable VARCHAR(120) NOT NULL,
    email VARCHAR(255) NOT NULL,
    motif VARCHAR(255) NOT NULL,
    date_debut DATETIME NOT NULL,
    date_fin DATETIME NOT NULL,
    statut ENUM('confirmée', 'annulée') NOT NULL DEFAULT 'confirmée',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_reservations_salle
        FOREIGN KEY (salle_id) REFERENCES salles (id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    INDEX idx_reservations_salle_dates (salle_id, date_debut, date_fin),
    INDEX idx_reservations_statut (statut)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
