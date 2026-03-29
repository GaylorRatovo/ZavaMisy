-- ========================================
-- Base de données : Site d'infos guerre Iran
-- ========================================

-- Table des catégories
CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL
);

-- Table des articles
CREATE TABLE articles (
    id SERIAL PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    contenu TEXT NOT NULL,
    extrait VARCHAR(500),
    image VARCHAR(255),
    image_alt VARCHAR(255),
    meta_titre VARCHAR(70),
    meta_description VARCHAR(160),
    categorie_id INTEGER REFERENCES categories(id) ON DELETE SET NULL,
    date_publication TIMESTAMP,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des images (liées aux articles)
CREATE TABLE images (
    id SERIAL PRIMARY KEY,
    fichier VARCHAR(255) NOT NULL,
    alt VARCHAR(255) NOT NULL,
    article_id INTEGER REFERENCES articles(id) ON DELETE CASCADE,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Index
CREATE INDEX idx_articles_slug ON articles(slug);
CREATE INDEX idx_categories_slug ON categories(slug);
CREATE INDEX idx_images_article ON images(article_id);

-- Catégories initiales
INSERT INTO categories (nom, slug) VALUES
('Politique', 'politique'),
('Militaire', 'militaire'),
('Humanitaire', 'humanitaire'),
('Economie', 'economie');
