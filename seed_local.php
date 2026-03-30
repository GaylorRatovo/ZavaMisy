<?php
// Script de seed pour créer les articles de test
// Se connecte directement à la base PostgreSQL via localhost

$host = 'localhost';
$port = '5432';
$db   = 'zavamisy';
$user = 'zava_user';
$pass = 'secret';

try {
    $dsn = sprintf('pgsql:host=%s;port=%s;dbname=%s', $host, $port, $db);
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // Vider les tables
    $pdo->exec("DELETE FROM images");
    $pdo->exec("DELETE FROM articles");
    $pdo->exec("ALTER SEQUENCE articles_id_seq RESTART WITH 1");
    $pdo->exec("ALTER SEQUENCE images_id_seq RESTART WITH 1");

    // Articles de test
    $articles = [
        [
            'titre' => 'Négociations à Vienne : un nouvel espoir pour l\'accord nucléaire',
            'slug' => 'negociations-vienne-nouvel-espoir-accord-nucleaire',
            'contenu' => '<p>Les pourparlers entre l\'Iran et les puissances occidentales ont repris ce lundi à Vienne, marquant une nouvelle étape dans les efforts pour relancer l\'accord sur le nucléaire iranien (JCPOA).</p><p>Selon les diplomates présents, les discussions ont porté sur la levée progressive des sanctions économiques et les garanties de non-retrait des États-Unis. Le chef de la délégation iranienne s\'est montré prudemment optimiste quant à l\'issue de ces négociations.</p><p>« Nous avons constaté une réelle volonté de dialogue de la part de toutes les parties », a déclaré un porte-parole européen à l\'issue de la première journée de pourparlers. Les experts estiment qu\'un accord préliminaire pourrait être annoncé d\'ici la fin du mois.</p>',
            'extrait' => 'Les pourparlers entre l\'Iran et les puissances occidentales ont repris à Vienne, marquant une nouvelle étape dans les efforts pour relancer l\'accord nucléaire.',
            'image' => 'gi-1.jpg',
            'image_alt' => 'Diplomates réunis autour d\'une table de négociation à Vienne',
            'meta_titre' => 'Négociations nucléaires Iran : espoir à Vienne',
            'meta_description' => 'Les pourparlers Iran-Occident reprennent à Vienne pour relancer l\'accord nucléaire JCPOA. Analyse des enjeux et perspectives.',
            'categorie_id' => 1,
            'date_publication' => date('Y-m-d H:i:s', strtotime('-2 days'))
        ],
        [
            'titre' => 'Tensions dans le détroit d\'Ormuz : la marine iranienne renforce sa présence',
            'slug' => 'tensions-detruit-ormuz-marine-iranienne-renforce-presence',
            'contenu' => '<p>La marine iranienne a déployé plusieurs navires supplémentaires dans le détroit d\'Ormuz cette semaine, en réponse à ce que Téhéran qualifie de « provocations occidentales » dans la région.</p><p>Ce passage stratégique, par lequel transite près de 20% du pétrole mondial, fait l\'objet d\'une surveillance accrue depuis plusieurs mois. Les autorités iraniennes ont justifié ce renforcement par la nécessité de protéger leurs eaux territoriales et leurs intérêts économiques.</p><p>Washington a réagi en appelant à la retenue et en réaffirmant son engagement pour la liberté de navigation dans les eaux internationales. Les analystes craignent une escalade des tensions dans cette zone déjà volatile.</p>',
            'extrait' => 'La marine iranienne déploie des navires supplémentaires dans le détroit d\'Ormuz, passage stratégique pour le commerce pétrolier mondial.',
            'image' => 'gi-2.jpg',
            'image_alt' => 'Navire de guerre iranien patrouillant dans le détroit d\'Ormuz',
            'meta_titre' => 'Détroit d\'Ormuz : la marine iranienne se renforce',
            'meta_description' => 'Tensions dans le détroit d\'Ormuz alors que l\'Iran renforce sa présence militaire navale. Enjeux stratégiques et réactions internationales.',
            'categorie_id' => 2,
            'date_publication' => date('Y-m-d H:i:s', strtotime('-1 day'))
        ],
        [
            'titre' => 'Impact des sanctions : la population iranienne face à la crise économique',
            'slug' => 'impact-sanctions-population-iranienne-crise-economique',
            'contenu' => '<p>Alors que les sanctions internationales continuent de peser sur l\'économie iranienne, la population civile subit de plein fouet les conséquences de cette pression économique.</p><p>Le rial a perdu plus de 60% de sa valeur ces dernières années, entraînant une inflation galopante qui affecte particulièrement les produits de première nécessité. Les médicaments importés se font rares dans les pharmacies, tandis que le chômage touche désormais près de 12% de la population active.</p><p>Des organisations humanitaires appellent à la mise en place de corridors d\'aide pour garantir l\'accès aux soins et à l\'alimentation. « La distinction entre sanctions ciblées et impact humanitaire devient de plus en plus floue », souligne un représentant de Médecins Sans Frontières présent sur place.</p>',
            'extrait' => 'La population iranienne subit les conséquences des sanctions internationales : inflation, pénuries de médicaments et chômage en hausse.',
            'image' => 'gi-3.jpg',
            'image_alt' => 'Marché de Téhéran montrant les effets de la crise économique',
            'meta_titre' => 'Iran : la crise économique frappe la population',
            'meta_description' => 'Analyse de l\'impact des sanctions sur la vie quotidienne des Iraniens. Inflation, pénuries et appels à l\'aide humanitaire.',
            'categorie_id' => 3,
            'date_publication' => date('Y-m-d H:i:s')
        ]
    ];

    $stmt = $pdo->prepare("
        INSERT INTO articles (titre, slug, contenu, extrait, image, image_alt, meta_titre, meta_description, categorie_id, date_publication)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    foreach ($articles as $article) {
        $stmt->execute([
            $article['titre'],
            $article['slug'],
            $article['contenu'],
            $article['extrait'],
            $article['image'],
            $article['image_alt'],
            $article['meta_titre'],
            $article['meta_description'],
            $article['categorie_id'],
            $article['date_publication']
        ]);
        echo "Article créé: " . $article['titre'] . "\n";
    }

    echo "\n✓ Base vidée et 3 articles de test créés avec succès!\n";

} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
    exit(1);
}
