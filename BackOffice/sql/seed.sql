-- ========================================
-- Données de test pour le site Iran Conflit
-- ========================================

-- S'assurer que les catégories existent
INSERT INTO categories (nom, slug) VALUES
('Politique', 'politique'),
('Militaire', 'militaire'),
('Humanitaire', 'humanitaire'),
('Economie', 'economie')
ON CONFLICT (slug) DO NOTHING;

-- Articles de test
INSERT INTO articles (titre, slug, contenu, extrait, categorie_id, date_publication, date_creation) VALUES

-- Article 1 - Politique
(
    'Négociations diplomatiques : une nouvelle phase s''ouvre',
    'negociations-diplomatiques-nouvelle-phase',
    'Les pourparlers entre les différentes parties prenantes du conflit ont repris cette semaine dans un contexte de tensions persistantes. Les diplomates présents soulignent l''importance de maintenir le dialogue malgré les difficultés rencontrées sur le terrain.

Les discussions portent principalement sur trois axes majeurs : le cessez-le-feu humanitaire, l''échange de prisonniers et la mise en place d''un corridor d''aide humanitaire. Les observateurs internationaux restent prudents quant aux chances de succès de ces négociations.

« Nous avons fait des progrès significatifs sur certains points, mais beaucoup reste à faire », a déclaré un représentant de la médiation internationale. Les prochaines sessions sont prévues pour la semaine prochaine.',
    'Les pourparlers entre les différentes parties prenantes du conflit ont repris cette semaine dans un contexte de tensions persistantes.',
    (SELECT id FROM categories WHERE slug = 'politique'),
    CURRENT_TIMESTAMP - INTERVAL '2 days',
    CURRENT_TIMESTAMP - INTERVAL '2 days'
),

-- Article 2 - Militaire
(
    'Analyse : les équilibres stratégiques dans la région',
    'analyse-equilibres-strategiques-region',
    'L''évolution du conflit au cours des derniers mois a profondément modifié les rapports de force dans la région. Les analystes militaires observent une reconfiguration des alliances et des stratégies déployées par les différents acteurs.

Les nouvelles technologies de surveillance et de défense jouent un rôle croissant dans ce théâtre d''opérations. Les drones et les systèmes de défense antiaérienne sont devenus des éléments centraux des stratégies militaires.

Cette situation complexe nécessite une analyse approfondie des motivations et des capacités de chaque partie pour comprendre les dynamiques en jeu.',
    'L''évolution du conflit au cours des derniers mois a profondément modifié les rapports de force dans la région.',
    (SELECT id FROM categories WHERE slug = 'militaire'),
    CURRENT_TIMESTAMP - INTERVAL '5 days',
    CURRENT_TIMESTAMP - INTERVAL '5 days'
),

-- Article 3 - Humanitaire
(
    'Crise humanitaire : les ONG tirent la sonnette d''alarme',
    'crise-humanitaire-ong-alarme',
    'Plusieurs organisations non gouvernementales ont publié un rapport conjoint alertant sur la dégradation rapide des conditions de vie des populations civiles dans les zones de conflit.

Selon ce rapport, plus de 500 000 personnes seraient actuellement en situation d''insécurité alimentaire grave. L''accès à l''eau potable et aux soins médicaux reste extrêmement limité dans de nombreuses régions.

« La communauté internationale doit agir de toute urgence », appellent les signataires du rapport. Ils demandent notamment l''ouverture de corridors humanitaires sécurisés et une augmentation significative de l''aide internationale.

Les besoins les plus urgents concernent la nourriture, les médicaments et les abris temporaires pour les populations déplacées.',
    'Plusieurs organisations non gouvernementales ont publié un rapport conjoint alertant sur la dégradation rapide des conditions de vie.',
    (SELECT id FROM categories WHERE slug = 'humanitaire'),
    CURRENT_TIMESTAMP - INTERVAL '1 day',
    CURRENT_TIMESTAMP - INTERVAL '1 day'
),

-- Article 4 - Economie
(
    'Impact économique : les sanctions et leurs conséquences',
    'impact-economique-sanctions-consequences',
    'Les sanctions économiques imposées par la communauté internationale continuent d''avoir des répercussions majeures sur l''économie régionale. Les experts économiques analysent les effets à court et long terme de ces mesures.

Le secteur bancaire est particulièrement touché, avec des difficultés croissantes pour les transactions internationales. Le commerce extérieur a chuté de 40% selon les dernières estimations disponibles.

L''inflation galopante affecte le quotidien des populations civiles, rendant l''accès aux produits de première nécessité de plus en plus difficile. La monnaie locale a perdu plus de 60% de sa valeur face au dollar depuis le début du conflit.',
    'Les sanctions économiques imposées par la communauté internationale continuent d''avoir des répercussions majeures.',
    (SELECT id FROM categories WHERE slug = 'economie'),
    CURRENT_TIMESTAMP - INTERVAL '3 days',
    CURRENT_TIMESTAMP - INTERVAL '3 days'
),

-- Article 5 - Politique
(
    'Réactions internationales face à l''escalade des tensions',
    'reactions-internationales-escalade-tensions',
    'Les grandes puissances mondiales ont exprimé leurs préoccupations croissantes face à l''escalade des tensions observée ces dernières semaines. Plusieurs chefs d''État ont appelé à la retenue et au dialogue.

Le Conseil de Sécurité des Nations Unies se réunira en session extraordinaire pour examiner la situation. Des propositions de résolution sont actuellement en discussion entre les membres permanents.

L''Union Européenne a annoncé l''envoi d''un émissaire spécial pour faciliter les pourparlers. Cette initiative diplomatique vise à créer les conditions d''un cessez-le-feu durable.',
    'Les grandes puissances mondiales ont exprimé leurs préoccupations croissantes face à l''escalade des tensions.',
    (SELECT id FROM categories WHERE slug = 'politique'),
    CURRENT_TIMESTAMP - INTERVAL '12 hours',
    CURRENT_TIMESTAMP - INTERVAL '12 hours'
),

-- Article 6 - Humanitaire
(
    'Les réfugiés : témoignages et parcours de vie',
    'refugies-temoignages-parcours-vie',
    'Des milliers de familles ont été contraintes de fuir leurs foyers depuis le début du conflit. Nous avons recueilli les témoignages de plusieurs d''entre elles dans les camps de réfugiés de la région.

« Nous avons tout perdu en une nuit », raconte Fatima, mère de trois enfants. « Maintenant, nous vivons dans l''incertitude, sans savoir si nous pourrons un jour rentrer chez nous. »

Les conditions dans les camps restent précaires malgré les efforts des organisations humanitaires. Le manque d''espace, les problèmes sanitaires et l''accès limité à l''éducation pour les enfants sont les principales préoccupations des réfugiés.',
    'Des milliers de familles ont été contraintes de fuir leurs foyers depuis le début du conflit.',
    (SELECT id FROM categories WHERE slug = 'humanitaire'),
    CURRENT_TIMESTAMP - INTERVAL '4 days',
    CURRENT_TIMESTAMP - INTERVAL '4 days'
);
