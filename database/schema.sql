-- Schéma PostgreSQL pour la gestion des réservations de salles

CREATE TABLE IF NOT EXISTS salles (
    id BIGSERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    batiment VARCHAR(100) NOT NULL,
    capacite INT NOT NULL,
    type VARCHAR(50) NOT NULL CHECK (type IN ('cours', 'informatique', 'laboratoire', 'amphitheatre', 'reunion')),
    active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_salles_capacite CHECK (capacite BETWEEN 1 AND 1000)
);

CREATE TABLE IF NOT EXISTS reservations (
    id BIGSERIAL PRIMARY KEY,
    salle_id BIGINT NOT NULL REFERENCES salles(id) ON DELETE CASCADE,
    responsable VARCHAR(120) NOT NULL,
    email VARCHAR(255) NOT NULL,
    motif VARCHAR(255) NOT NULL,
    date_debut TIMESTAMP NOT NULL,
    date_fin TIMESTAMP NOT NULL,
    statut VARCHAR(20) NOT NULL DEFAULT 'confirmée',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_reservations_salle_dates ON reservations (salle_id, date_debut, date_fin);
CREATE INDEX IF NOT EXISTS idx_reservations_statut ON reservations (statut);

CREATE TABLE IF NOT EXISTS responsables (
    id BIGSERIAL PRIMARY KEY,
    nom VARCHAR(120) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
);
