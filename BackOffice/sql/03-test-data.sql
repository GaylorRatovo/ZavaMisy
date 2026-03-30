-- ========================================
-- Données de test - 20 articles avec images
-- Pour tester la pagination et le carrousel
-- ========================================

-- Nettoyer les tables (optionnel, décommenter si besoin)
-- TRUNCATE TABLE images, articles, categories RESTART IDENTITY CASCADE;

-- S'assurer que les catégories existent
INSERT INTO categories (nom, slug) VALUES
('Politique', 'politique'),
('Militaire', 'militaire'),
('Humanitaire', 'humanitaire'),
('Economie', 'economie')
ON CONFLICT (slug) DO NOTHING;

-- ========================================
-- CATEGORIE POLITIQUE (8 articles pour tester pagination)
-- ========================================

INSERT INTO articles (titre, slug, contenu, extrait, image, image_alt, categorie_id, date_publication) VALUES
(
    'Sommet diplomatique historique à Genève',
    'sommet-diplomatique-historique-geneve',
    '<p>Un sommet diplomatique d''une importance capitale s''est tenu à Genève cette semaine, réunissant les principales puissances impliquées dans le conflit iranien.</p>
    <p><img src="/uploads/image-2.jpg" alt="Salle de conférence diplomatique" /></p>
    <p>Les négociateurs ont abordé plusieurs points cruciaux, notamment la question du programme nucléaire et les sanctions économiques. Selon les sources présentes, des progrès significatifs auraient été réalisés sur certains dossiers sensibles.</p>
    <h2>Les enjeux de la rencontre</h2>
    <p>Cette rencontre intervient dans un contexte de tensions accrues, mais aussi d''ouverture diplomatique. Les observateurs internationaux restent prudents quant aux résultats concrets de ces discussions.</p>',
    'Un sommet diplomatique majeur réunit les puissances mondiales à Genève pour discuter du conflit iranien.',
    'image-1.jpg',
    'Drapeaux des nations participantes au sommet',
    (SELECT id FROM categories WHERE slug = 'politique'),
    NOW() - INTERVAL '1 hour'
),
(
    'Nouvelles sanctions américaines contre l''Iran',
    'nouvelles-sanctions-americaines-iran',
    '<p>Washington a annoncé un nouveau train de sanctions visant plusieurs secteurs clés de l''économie iranienne.</p>
    <p><img src="/uploads/image-1.jpg" alt="Conférence de presse" /></p>
    <p>Ces mesures ciblent principalement les industries pétrolière et bancaire, ainsi que certains responsables gouvernementaux. La communauté internationale réagit diversement à cette décision.</p>
    <h2>Réaction de Téhéran</h2>
    <p>Le gouvernement iranien a immédiatement condamné ces sanctions, les qualifiant d''acte de guerre économique.</p>',
    'Les États-Unis imposent de nouvelles sanctions économiques contre l''Iran, ciblant les secteurs stratégiques.',
    'image-2.jpg',
    'Département d''État américain',
    (SELECT id FROM categories WHERE slug = 'politique'),
    NOW() - INTERVAL '2 hours'
),
(
    'L''Union Européenne propose une médiation',
    'union-europeenne-propose-mediation',
    '<p>Face à l''impasse diplomatique, Bruxelles propose ses services de médiation pour relancer le dialogue.</p>
    <p><img src="/uploads/image-3.jpg" alt="Parlement européen" /></p>
    <p>Le Haut Représentant de l''UE pour les affaires étrangères a présenté un plan en plusieurs points visant à rapprocher les positions divergentes.</p>
    <h2>Un plan ambitieux</h2>
    <p>Ce plan prévoit notamment la création d''un groupe de contact permanent et l''organisation de réunions régulières entre les parties.</p>',
    'L''Union Européenne se positionne comme médiateur dans le conflit, proposant un nouveau cadre de négociation.',
    'image-3.jpg',
    'Siège de l''Union Européenne à Bruxelles',
    (SELECT id FROM categories WHERE slug = 'politique'),
    NOW() - INTERVAL '1 day'
),
(
    'La Russie appelle à la désescalade',
    'russie-appelle-desescalade',
    '<p>Moscou a lancé un appel solennel à toutes les parties pour éviter une escalade militaire dangereuse.</p>
    <p><img src="/uploads/image-1.jpg" alt="Kremlin Moscou" /></p>
    <p>Le ministre russe des Affaires étrangères a souligné l''importance du dialogue et de la diplomatie. La Russie propose d''organiser une nouvelle conférence internationale.</p>
    <h2>Position stratégique</h2>
    <p>Cette déclaration reflète les intérêts stratégiques de Moscou dans la région et son souhait de maintenir son influence.</p>',
    'La Russie lance un appel à la désescalade et propose une nouvelle conférence internationale.',
    'image-2.jpg',
    'Le Kremlin à Moscou',
    (SELECT id FROM categories WHERE slug = 'politique'),
    NOW() - INTERVAL '2 days'
),
(
    'La Chine renforce ses liens avec Téhéran',
    'chine-renforce-liens-teheran',
    '<p>Pékin et Téhéran ont signé un accord de coopération stratégique d''une durée de 25 ans.</p>
    <p><img src="/uploads/image-2.jpg" alt="Signature de l''accord" /></p>
    <p>Cet accord couvre de nombreux domaines : économie, défense, technologie et énergie. Il marque un tournant dans les relations sino-iraniennes.</p>
    <h2>Implications géopolitiques</h2>
    <p>Les analystes voient dans cet accord une réponse directe aux sanctions occidentales et un rééquilibrage des forces dans la région.</p>',
    'La Chine et l''Iran signent un accord stratégique majeur couvrant 25 années de coopération.',
    'image-3.jpg',
    'Cérémonie de signature à Pékin',
    (SELECT id FROM categories WHERE slug = 'politique'),
    NOW() - INTERVAL '3 days'
),
(
    'Le Conseil de Sécurité de l''ONU en session extraordinaire',
    'conseil-securite-onu-session-extraordinaire',
    '<p>Le Conseil de Sécurité des Nations Unies s''est réuni en session extraordinaire pour examiner la situation.</p>
    <p><img src="/uploads/image-1.jpg" alt="Conseil de Sécurité ONU" /></p>
    <p>Les débats ont été animés, avec des positions très divergentes entre les membres permanents. Une résolution est en cours de négociation.</p>
    <h2>Vers une résolution ?</h2>
    <p>Les diplomates travaillent d''arrache-pied pour trouver un texte acceptable par toutes les parties.</p>',
    'Le Conseil de Sécurité de l''ONU se réunit en urgence pour examiner la crise iranienne.',
    'image-2.jpg',
    'Salle du Conseil de Sécurité',
    (SELECT id FROM categories WHERE slug = 'politique'),
    NOW() - INTERVAL '4 days'
),
(
    'Élections législatives en Iran : premiers résultats',
    'elections-legislatives-iran-premiers-resultats',
    '<p>Les premières projections des élections législatives iraniennes montrent une avance des conservateurs.</p>
    <p><img src="/uploads/image-3.jpg" alt="Bureau de vote iranien" /></p>
    <p>Le taux de participation serait en baisse par rapport aux scrutins précédents. Les réformistes dénoncent des irrégularités.</p>
    <h2>Impact sur la politique étrangère</h2>
    <p>Ces résultats pourraient influencer significativement la position iranienne dans les négociations internationales.</p>',
    'Les élections législatives en Iran donnent l''avantage aux conservateurs, avec un taux de participation en recul.',
    'image-1.jpg',
    'Électeurs iraniens',
    (SELECT id FROM categories WHERE slug = 'politique'),
    NOW() - INTERVAL '5 days'
),
(
    'Visite surprise du Secrétaire Général de l''ONU à Téhéran',
    'visite-surprise-secretaire-general-onu-teheran',
    '<p>Le Secrétaire Général des Nations Unies s''est rendu à Téhéran pour des entretiens de haut niveau.</p>
    <p><img src="/uploads/image-2.jpg" alt="Rencontre diplomatique" /></p>
    <p>Cette visite non annoncée vise à relancer le dialogue et à évaluer les possibilités de compromis. Les discussions auraient été franches et constructives.</p>
    <h2>Une démarche personnelle</h2>
    <p>Cette initiative personnelle du Secrétaire Général témoigne de l''urgence de la situation.</p>',
    'Le Secrétaire Général de l''ONU effectue une visite surprise à Téhéran pour relancer le dialogue.',
    'image-3.jpg',
    'Palais présidentiel iranien',
    (SELECT id FROM categories WHERE slug = 'politique'),
    NOW() - INTERVAL '6 days'
);

-- ========================================
-- CATEGORIE MILITAIRE (5 articles)
-- ========================================

INSERT INTO articles (titre, slug, contenu, extrait, image, image_alt, categorie_id, date_publication) VALUES
(
    'Mouvements de troupes dans le Golfe Persique',
    'mouvements-troupes-golfe-persique',
    '<p>Des déploiements militaires inhabituels ont été observés dans la région du Golfe Persique ces derniers jours.</p>
    <p><img src="/uploads/image-1.jpg" alt="Navires militaires" /></p>
    <p>Plusieurs nations ont renforcé leur présence navale, créant une concentration de forces sans précédent dans cette zone stratégique.</p>
    <h2>Analyse des experts</h2>
    <p><img src="/uploads/image-3.jpg" alt="Carte stratégique" /></p>
    <p>Les analystes militaires s''interrogent sur les intentions réelles de ces manœuvres et leurs possibles conséquences.</p>',
    'Des mouvements de troupes importants sont signalés dans le Golfe Persique, augmentant les tensions régionales.',
    'image-2.jpg',
    'Flotte navale dans le Golfe',
    (SELECT id FROM categories WHERE slug = 'militaire'),
    NOW() - INTERVAL '12 hours'
),
(
    'Test d''un nouveau missile balistique iranien',
    'test-nouveau-missile-balistique-iranien',
    '<p>L''Iran a annoncé le succès d''un test de missile balistique à longue portée, provoquant des réactions internationales.</p>
    <p><img src="/uploads/image-2.jpg" alt="Lancement de missile" /></p>
    <p>Selon les autorités iraniennes, ce missile dispose de capacités avancées de pénétration des défenses anti-missiles.</p>
    <h2>Condamnations internationales</h2>
    <p>Les puissances occidentales ont immédiatement condamné ce test, le qualifiant de violation des résolutions de l''ONU.</p>',
    'L''Iran teste avec succès un nouveau missile balistique, provoquant la condamnation de la communauté internationale.',
    'image-1.jpg',
    'Missile balistique iranien',
    (SELECT id FROM categories WHERE slug = 'militaire'),
    NOW() - INTERVAL '1 day 6 hours'
),
(
    'Exercices militaires conjoints dans le détroit d''Ormuz',
    'exercices-militaires-conjoints-detroit-ormuz',
    '<p>Plusieurs pays ont participé à des exercices militaires d''envergure dans le détroit d''Ormuz.</p>
    <p><img src="/uploads/image-3.jpg" alt="Exercices navals" /></p>
    <p>Ces manœuvres visent à démontrer les capacités de projection de force et à sécuriser les routes maritimes vitales pour le commerce pétrolier.</p>
    <h2>Objectifs affichés</h2>
    <p><img src="/uploads/image-1.jpg" alt="Opérations navales" /></p>
    <p>Les organisateurs insistent sur le caractère défensif de ces exercices.</p>',
    'Des exercices militaires internationaux se déroulent dans le stratégique détroit d''Ormuz.',
    'image-2.jpg',
    'Navires pendant les exercices',
    (SELECT id FROM categories WHERE slug = 'militaire'),
    NOW() - INTERVAL '3 days'
),
(
    'Renforcement des systèmes de défense aérienne',
    'renforcement-systemes-defense-aerienne',
    '<p>Plusieurs pays de la région ont annoncé l''acquisition de nouveaux systèmes de défense anti-aérienne.</p>
    <p><img src="/uploads/image-1.jpg" alt="Système de défense" /></p>
    <p>Cette course aux armements reflète les inquiétudes croissantes face aux menaces balistiques.</p>
    <h2>Technologies avancées</h2>
    <p>Les systèmes acquis représentent le dernier cri de la technologie militaire.</p>',
    'La course aux armements s''intensifie avec l''acquisition de systèmes de défense aérienne perfectionnés.',
    'image-3.jpg',
    'Batterie anti-aérienne',
    (SELECT id FROM categories WHERE slug = 'militaire'),
    NOW() - INTERVAL '4 days'
),
(
    'Incident naval évité de justesse',
    'incident-naval-evite-justesse',
    '<p>Un incident entre navires militaires a failli dégénérer en confrontation armée dans le Golfe.</p>
    <p><img src="/uploads/image-2.jpg" alt="Navires face à face" /></p>
    <p>Selon les témoignages, les deux bâtiments se sont approchés dangereusement avant que la situation ne soit désamorcée.</p>
    <h2>Communication rétablie</h2>
    <p><img src="/uploads/image-3.jpg" alt="Communication navale" /></p>
    <p>Cet incident souligne l''importance des canaux de communication entre les forces armées des différentes nations.</p>',
    'Un incident naval entre plusieurs navires militaires a été évité de justesse dans le Golfe Persique.',
    'image-1.jpg',
    'Incident en mer',
    (SELECT id FROM categories WHERE slug = 'militaire'),
    NOW() - INTERVAL '5 days'
);

-- ========================================
-- CATEGORIE HUMANITAIRE (4 articles)
-- ========================================

INSERT INTO articles (titre, slug, contenu, extrait, image, image_alt, categorie_id, date_publication) VALUES
(
    'Crise alimentaire : l''ONU lance un appel urgent',
    'crise-alimentaire-onu-appel-urgent',
    '<p>Les agences humanitaires de l''ONU alertent sur une crise alimentaire majeure touchant des millions de personnes.</p>
    <p><img src="/uploads/image-1.jpg" alt="Distribution alimentaire" /></p>
    <p>Le Programme Alimentaire Mondial estime les besoins à plusieurs milliards de dollars pour éviter une famine généralisée.</p>
    <h2>Mobilisation internationale</h2>
    <p><img src="/uploads/image-2.jpg" alt="Aide humanitaire" /></p>
    <p>Plusieurs pays ont annoncé des contributions supplémentaires, mais les fonds restent insuffisants.</p>',
    'L''ONU lance un appel urgent pour faire face à une crise alimentaire touchant des millions de personnes.',
    'image-3.jpg',
    'Distribution de nourriture',
    (SELECT id FROM categories WHERE slug = 'humanitaire'),
    NOW() - INTERVAL '8 hours'
),
(
    'Réfugiés : les camps débordent',
    'refugies-camps-debordent',
    '<p>Les camps de réfugiés aux frontières sont en situation de surpopulation critique.</p>
    <p><img src="/uploads/image-2.jpg" alt="Camp de réfugiés" /></p>
    <p>Les conditions sanitaires se dégradent rapidement, faisant craindre des épidémies. Les organisations humanitaires demandent une aide d''urgence.</p>
    <h2>Témoignages poignants</h2>
    <p>Les familles racontent leur parcours difficile et leurs espoirs d''un avenir meilleur.</p>',
    'Les camps de réfugiés sont submergés, créant une situation humanitaire préoccupante.',
    'image-1.jpg',
    'Familles dans un camp',
    (SELECT id FROM categories WHERE slug = 'humanitaire'),
    NOW() - INTERVAL '2 days'
),
(
    'Accès aux soins : la situation empire',
    'acces-soins-situation-empire',
    '<p>L''accès aux soins médicaux devient de plus en plus difficile pour les populations civiles.</p>
    <p><img src="/uploads/image-3.jpg" alt="Hôpital de campagne" /></p>
    <p>Les hôpitaux manquent de médicaments essentiels et de personnel qualifié. Médecins Sans Frontières tire la sonnette d''alarme.</p>
    <h2>Pénurie de médicaments</h2>
    <p><img src="/uploads/image-1.jpg" alt="Soins médicaux" /></p>
    <p>Les maladies chroniques ne peuvent plus être traitées correctement, mettant des vies en danger.</p>',
    'L''accès aux soins médicaux se dégrade dramatiquement, affectant les populations les plus vulnérables.',
    'image-2.jpg',
    'Personnel médical humanitaire',
    (SELECT id FROM categories WHERE slug = 'humanitaire'),
    NOW() - INTERVAL '3 days'
),
(
    'Les enfants, premières victimes du conflit',
    'enfants-premieres-victimes-conflit',
    '<p>L''UNICEF publie un rapport alarmant sur la situation des enfants dans les zones de conflit.</p>
    <p><img src="/uploads/image-1.jpg" alt="Enfants réfugiés" /></p>
    <p>Des millions d''enfants sont privés d''éducation et exposés à des traumatismes psychologiques durables.</p>
    <h2>Actions de l''UNICEF</h2>
    <p><img src="/uploads/image-3.jpg" alt="Programme éducatif" /></p>
    <p>Des programmes d''urgence sont mis en place pour assurer un minimum d''éducation et de soutien psychologique.</p>',
    'L''UNICEF alerte sur la situation dramatique des enfants, privés d''éducation et traumatisés par le conflit.',
    'image-2.jpg',
    'Enfants dans un centre d''accueil',
    (SELECT id FROM categories WHERE slug = 'humanitaire'),
    NOW() - INTERVAL '4 days'
);

-- ========================================
-- CATEGORIE ECONOMIE (3 articles)
-- ========================================

INSERT INTO articles (titre, slug, contenu, extrait, image, image_alt, categorie_id, date_publication) VALUES
(
    'Le prix du pétrole atteint des sommets',
    'prix-petrole-atteint-sommets',
    '<p>Les cours du pétrole ont atteint leur plus haut niveau depuis plusieurs années en raison des tensions géopolitiques.</p>
    <p><img src="/uploads/image-2.jpg" alt="Plateforme pétrolière" /></p>
    <p>Les marchés craignent une perturbation de l''approvisionnement via le détroit d''Ormuz, par où transite une grande partie du pétrole mondial.</p>
    <h2>Impact sur l''économie mondiale</h2>
    <p><img src="/uploads/image-1.jpg" alt="Graphiques économiques" /></p>
    <p>Cette hausse des prix pèse sur les économies importatrices et alimente l''inflation globale.</p>',
    'Les tensions géopolitiques font grimper le prix du pétrole à des niveaux records, impactant l''économie mondiale.',
    'image-3.jpg',
    'Terminal pétrolier',
    (SELECT id FROM categories WHERE slug = 'economie'),
    NOW() - INTERVAL '6 hours'
),
(
    'Les marchés financiers en zone de turbulence',
    'marches-financiers-zone-turbulence',
    '<p>Les bourses mondiales ont connu une séance agitée suite aux derniers développements du conflit.</p>
    <p><img src="/uploads/image-1.jpg" alt="Salle des marchés" /></p>
    <p>Les investisseurs se tournent vers les valeurs refuges comme l''or et le franc suisse.</p>
    <h2>Volatilité accrue</h2>
    <p>Les analystes prévoient une période d''incertitude prolongée sur les marchés.</p>',
    'Les marchés financiers réagissent nerveusement aux tensions, avec une volatilité accrue et une fuite vers les valeurs refuges.',
    'image-2.jpg',
    'Écrans de cotation boursière',
    (SELECT id FROM categories WHERE slug = 'economie'),
    NOW() - INTERVAL '1 day'
),
(
    'Commerce international : les chaînes d''approvisionnement menacées',
    'commerce-international-chaines-approvisionnement',
    '<p>Les tensions actuelles perturbent les routes commerciales vitales et menacent les chaînes d''approvisionnement mondiales.</p>
    <p><img src="/uploads/image-3.jpg" alt="Porte-conteneurs" /></p>
    <p>Les entreprises cherchent des alternatives pour sécuriser leurs approvisionnements.</p>
    <h2>Adaptation des entreprises</h2>
    <p><img src="/uploads/image-2.jpg" alt="Logistique internationale" /></p>
    <p>De nombreuses sociétés diversifient leurs sources et leurs routes logistiques pour limiter les risques.</p>',
    'Les chaînes d''approvisionnement mondiales sont perturbées, forçant les entreprises à s''adapter.',
    'image-1.jpg',
    'Port commercial',
    (SELECT id FROM categories WHERE slug = 'economie'),
    NOW() - INTERVAL '2 days'
);

-- ========================================
-- IMAGES ADDITIONNELLES (2 images par article minimum)
-- ========================================

INSERT INTO images (fichier, alt, article_id) VALUES
-- Politique
('image-2.jpg', 'Vue de la salle de conférence', (SELECT id FROM articles WHERE slug = 'sommet-diplomatique-historique-geneve')),
('image-3.jpg', 'Délégations en discussion', (SELECT id FROM articles WHERE slug = 'sommet-diplomatique-historique-geneve')),
('image-1.jpg', 'Conférence de presse', (SELECT id FROM articles WHERE slug = 'nouvelles-sanctions-americaines-iran')),
('image-3.jpg', 'Réaction diplomatique', (SELECT id FROM articles WHERE slug = 'nouvelles-sanctions-americaines-iran')),
('image-1.jpg', 'Session au Parlement européen', (SELECT id FROM articles WHERE slug = 'union-europeenne-propose-mediation')),
('image-2.jpg', 'Discussions diplomatiques', (SELECT id FROM articles WHERE slug = 'union-europeenne-propose-mediation')),
('image-2.jpg', 'Ministère des Affaires étrangères russe', (SELECT id FROM articles WHERE slug = 'russie-appelle-desescalade')),
('image-3.jpg', 'Conférence de presse Moscou', (SELECT id FROM articles WHERE slug = 'russie-appelle-desescalade')),
('image-1.jpg', 'Cérémonie officielle', (SELECT id FROM articles WHERE slug = 'chine-renforce-liens-teheran')),
('image-2.jpg', 'Poignée de main diplomatique', (SELECT id FROM articles WHERE slug = 'chine-renforce-liens-teheran')),
('image-3.jpg', 'Salle du Conseil', (SELECT id FROM articles WHERE slug = 'conseil-securite-onu-session-extraordinaire')),
('image-1.jpg', 'Votes au Conseil', (SELECT id FROM articles WHERE slug = 'conseil-securite-onu-session-extraordinaire')),
('image-2.jpg', 'Files d''attente aux urnes', (SELECT id FROM articles WHERE slug = 'elections-legislatives-iran-premiers-resultats')),
('image-3.jpg', 'Décompte des voix', (SELECT id FROM articles WHERE slug = 'elections-legislatives-iran-premiers-resultats')),
('image-1.jpg', 'Arrivée de la délégation ONU', (SELECT id FROM articles WHERE slug = 'visite-surprise-secretaire-general-onu-teheran')),
('image-2.jpg', 'Entretiens officiels', (SELECT id FROM articles WHERE slug = 'visite-surprise-secretaire-general-onu-teheran')),

-- Militaire
('image-3.jpg', 'Formation navale', (SELECT id FROM articles WHERE slug = 'mouvements-troupes-golfe-persique')),
('image-1.jpg', 'Surveillance maritime', (SELECT id FROM articles WHERE slug = 'mouvements-troupes-golfe-persique')),
('image-3.jpg', 'Base de lancement', (SELECT id FROM articles WHERE slug = 'test-nouveau-missile-balistique-iranien')),
('image-2.jpg', 'Trajectoire du missile', (SELECT id FROM articles WHERE slug = 'test-nouveau-missile-balistique-iranien')),
('image-1.jpg', 'Manœuvres coordonnées', (SELECT id FROM articles WHERE slug = 'exercices-militaires-conjoints-detroit-ormuz')),
('image-2.jpg', 'Opérations aériennes', (SELECT id FROM articles WHERE slug = 'exercices-militaires-conjoints-detroit-ormuz')),
('image-2.jpg', 'Installation du système', (SELECT id FROM articles WHERE slug = 'renforcement-systemes-defense-aerienne')),
('image-1.jpg', 'Test de détection', (SELECT id FROM articles WHERE slug = 'renforcement-systemes-defense-aerienne')),
('image-3.jpg', 'Navires en alerte', (SELECT id FROM articles WHERE slug = 'incident-naval-evite-justesse')),
('image-1.jpg', 'Communication d''urgence', (SELECT id FROM articles WHERE slug = 'incident-naval-evite-justesse')),

-- Humanitaire
('image-3.jpg', 'Convoi humanitaire', (SELECT id FROM articles WHERE slug = 'crise-alimentaire-onu-appel-urgent')),
('image-2.jpg', 'Distribution aux familles', (SELECT id FROM articles WHERE slug = 'crise-alimentaire-onu-appel-urgent')),
('image-3.jpg', 'Vue aérienne du camp', (SELECT id FROM articles WHERE slug = 'refugies-camps-debordent')),
('image-1.jpg', 'Tentes de fortune', (SELECT id FROM articles WHERE slug = 'refugies-camps-debordent')),
('image-1.jpg', 'Consultations médicales', (SELECT id FROM articles WHERE slug = 'acces-soins-situation-empire')),
('image-2.jpg', 'Stock de médicaments', (SELECT id FROM articles WHERE slug = 'acces-soins-situation-empire')),
('image-3.jpg', 'Classe improvisée', (SELECT id FROM articles WHERE slug = 'enfants-premieres-victimes-conflit')),
('image-1.jpg', 'Activités pour enfants', (SELECT id FROM articles WHERE slug = 'enfants-premieres-victimes-conflit')),

-- Economie
('image-1.jpg', 'Raffinerie', (SELECT id FROM articles WHERE slug = 'prix-petrole-atteint-sommets')),
('image-2.jpg', 'Cours du brut', (SELECT id FROM articles WHERE slug = 'prix-petrole-atteint-sommets')),
('image-3.jpg', 'Traders en action', (SELECT id FROM articles WHERE slug = 'marches-financiers-zone-turbulence')),
('image-2.jpg', 'Indices boursiers', (SELECT id FROM articles WHERE slug = 'marches-financiers-zone-turbulence')),
('image-2.jpg', 'Chargement de containers', (SELECT id FROM articles WHERE slug = 'commerce-international-chaines-approvisionnement')),
('image-3.jpg', 'Entrepôt logistique', (SELECT id FROM articles WHERE slug = 'commerce-international-chaines-approvisionnement'));
