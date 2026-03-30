-- ========================================
-- Données de test pour ZavaMisy
-- ========================================

-- Catégories initiales
INSERT INTO categories (nom, slug) VALUES
('Politique', 'politique'),
('Militaire', 'militaire'),
('Humanitaire', 'humanitaire'),
('Economie', 'economie');

-- Administrateur par défaut
INSERT INTO admins (username, email, password) VALUES
('admin', 'admin@zavamisy.mg', 'admin');

-- Articles de test
INSERT INTO articles (titre, slug, contenu, extrait, image, image_alt, meta_titre, meta_description, categorie_id, date_publication) VALUES
(
    'Nouvelles négociations diplomatiques entre Téhéran et Washington',
    'negociations-diplomatiques-teheran-washington',
    '<p>Les représentants iraniens et américains se sont rencontrés à Genève ce week-end pour discuter d''une éventuelle reprise des pourparlers sur le programme nucléaire iranien.</p><p><img src="/uploads/gi-2.jpg" alt="Réunion diplomatique" /></p><p>Selon des sources proches du dossier, les discussions auraient porté sur un allègement progressif des sanctions économiques en échange de garanties internationales sur les activités d''enrichissement. Le ministre des Affaires étrangères iranien a qualifié ces échanges de "constructifs mais prudents".</p><p>Les analystes restent divisés sur les chances de succès de cette nouvelle initiative diplomatique, certains rappelant les échecs des négociations précédentes.</p>',
    'Les représentants iraniens et américains se sont rencontrés à Genève pour discuter de la reprise des pourparlers nucléaires.',
    'gi-1.jpg',
    'Drapeaux iranien et américain lors des négociations',
    'Négociations Iran-USA à Genève',
    'Reprise des pourparlers diplomatiques entre l''Iran et les États-Unis concernant le programme nucléaire iranien.',
    1,
    NOW() - INTERVAL '2 days'
),
(
    'Renforcement des défenses aériennes dans le détroit d''Ormuz',
    'renforcement-defenses-aeriennes-ormuz',
    '<p>L''armée iranienne a annoncé le déploiement de nouveaux systèmes de défense anti-aériens le long du détroit d''Ormuz, zone stratégique par laquelle transite une grande partie du pétrole mondial.</p><p><img src="/uploads/gi-1.jpg" alt="Équipement militaire" /></p><p>Le général Hossein Salami, commandant des Gardiens de la révolution, a déclaré que ces installations visaient à "protéger la souveraineté nationale face aux menaces extérieures". Ces nouveaux équipements seraient capables de détecter et d''intercepter des aéronefs à haute altitude.</p><p><img src="/uploads/gi-3.jpg" alt="Zone du détroit" /></p><p>Cette annonce intervient dans un contexte de tensions maritimes accrues dans la région du Golfe persique.</p>',
    'L''Iran déploie de nouveaux systèmes de défense anti-aériens dans la zone stratégique du détroit d''Ormuz.',
    'gi-2.jpg',
    'Système de défense anti-aérien iranien',
    'Défenses aériennes iraniennes à Ormuz',
    'Déploiement de nouveaux systèmes de défense anti-aériens iraniens dans le détroit d''Ormuz.',
    2,
    NOW() - INTERVAL '1 day'
),
(
    'Crise humanitaire : afflux de réfugiés à la frontière irano-afghane',
    'crise-humanitaire-refugies-frontiere-afghane',
    '<p>Le Haut-Commissariat des Nations Unies pour les réfugiés (HCR) a signalé une augmentation significative du nombre de familles afghanes cherchant refuge en Iran ces dernières semaines.</p><p><img src="/uploads/gi-1.jpg" alt="Réfugiés en transit" /></p><p>Les camps de transit de Zahedan et Mashhad font face à une situation de surpopulation critique. Les organisations humanitaires présentes sur place appellent à une mobilisation internationale urgente pour fournir nourriture, soins médicaux et hébergement aux déplacés.</p><p><img src="/uploads/gi-2.jpg" alt="Camp humanitaire" /></p><p>Le gouvernement iranien a indiqué qu''il travaillait avec les agences onusiennes pour gérer cet afflux, tout en soulignant les limites de ses capacités d''accueil.</p>',
    'Le HCR alerte sur l''afflux massif de réfugiés afghans à la frontière iranienne et appelle à une aide internationale.',
    'gi-3.jpg',
    'Camp de réfugiés à la frontière irano-afghane',
    'Réfugiés afghans en Iran',
    'Crise humanitaire à la frontière irano-afghane avec un afflux massif de réfugiés nécessitant une aide internationale.',
    3,
    NOW()
);

-- Images additionnelles pour les articles
INSERT INTO images (fichier, alt, article_id) VALUES
('gi-1.jpg', 'Salle de conférence diplomatique', 1),
('gi-2.jpg', 'Vue aérienne du détroit d''Ormuz', 2),
('gi-3.jpg', 'Familles de réfugiés au camp de transit', 3);
