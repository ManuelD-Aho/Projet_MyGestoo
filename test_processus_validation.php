<?php
// Configuration temporaire pour le test local
class Database {
    private static $host = 'localhost'; // Changement temporaire pour le test local
    private static $db   = 'soutenance_manager';
    private static $user = 'root';
    private static $pass = 'password';
    private static $charset = 'utf8';

    public static function getConnection() {
        try {
            $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$db . ";charset=" . self::$charset;
            $pdo = new PDO($dsn, self::$user, self::$pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }
}

require_once __DIR__ . '/app/controllers/ProcessusValidationController.php';

try {
    $controller = new ProcessusValidationController();
    $donnees = $controller->getDonneesPage();
    
    echo "=== TEST DU CONTRÔLEUR PROCESSUS VALIDATION ===\n\n";
    
    echo "1. Statistiques :\n";
    print_r($donnees['statistiques']);
    echo "\n";
    
    echo "2. Nombre de rapports : " . count($donnees['rapports']) . "\n";
    
    if (!empty($donnees['rapports'])) {
        echo "3. Premier rapport :\n";
        $premierRapport = $donnees['rapports'][0];
        echo "   - ID: " . $premierRapport['id_rapport'] . "\n";
        echo "   - Nom: " . $premierRapport['nom_rapport'] . "\n";
        echo "   - Étudiant: " . $premierRapport['nom_etu'] . " " . $premierRapport['prenom_etu'] . "\n";
        echo "   - Statut: " . $premierRapport['statut_vote']['message'] . "\n";
        echo "   - Nombre d'évaluations: " . count($premierRapport['evaluations']) . "\n";
    }
    
    echo "\n4. Nombre de membres de la commission : " . count($donnees['membres_commission']) . "\n";
    
    if (!empty($donnees['membres_commission'])) {
        echo "5. Membres de la commission :\n";
        foreach ($donnees['membres_commission'] as $membre) {
            echo "   - " . $membre['nom_enseignant'] . " " . $membre['prenom_enseignant'] . "\n";
        }
    }
    
    echo "\n=== TEST TERMINÉ AVEC SUCCÈS ===\n";
    
} catch (Exception $e) {
    echo "ERREUR : " . $e->getMessage() . "\n";
    echo "Fichier : " . $e->getFile() . "\n";
    echo "Ligne : " . $e->getLine() . "\n";
} 