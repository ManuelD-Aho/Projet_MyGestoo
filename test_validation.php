<?php
// Script de test pour la validation
require_once 'app/config/database.php';
require_once 'app/models/Valider.php';
require_once 'app/controllers/EvaluationDossiersController.php';

// Activer l'affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Test de la validation des rapports</h2>";

try {
    $pdo = Database::getConnection();
    echo "<p>✅ Connexion à la base de données réussie</p>";
    
    // Créer une instance du contrôleur
    $controller = new EvaluationDossiersController();
    
    // Test 1: Vérifier la structure de la table valider
    echo "<h3>1. Structure de la table valider :</h3>";
    $stmt = $pdo->query("DESCRIBE valider");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1'>";
    echo "<tr><th>Champ</th><th>Type</th><th>Null</th><th>Clé</th><th>Défaut</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . $column['Field'] . "</td>";
        echo "<td>" . $column['Type'] . "</td>";
        echo "<td>" . $column['Null'] . "</td>";
        echo "<td>" . $column['Key'] . "</td>";
        echo "<td>" . $column['Default'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Test 2: Vérifier les enseignants disponibles
    echo "<h3>2. Enseignants disponibles :</h3>";
    $stmt = $pdo->query("SELECT id_enseignant, nom_enseignant, prenom_enseignant FROM enseignants ORDER BY id_enseignant");
    $enseignants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($enseignants)) {
        echo "<p style='color: red;'>❌ Aucun enseignant trouvé dans la base de données</p>";
    } else {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Nom</th><th>Prénom</th></tr>";
        foreach ($enseignants as $enseignant) {
            echo "<tr>";
            echo "<td>" . $enseignant['id_enseignant'] . "</td>";
            echo "<td>" . $enseignant['nom_enseignant'] . "</td>";
            echo "<td>" . $enseignant['prenom_enseignant'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Test 3: Vérifier les rapports disponibles
    echo "<h3>3. Rapports disponibles :</h3>";
    $stmt = $pdo->query("SELECT id_rapport, nom_rapport, etape_validation FROM rapport_etudiants ORDER BY id_rapport");
    $rapports = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($rapports)) {
        echo "<p style='color: red;'>❌ Aucun rapport trouvé dans la base de données</p>";
    } else {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Nom</th><th>Étape</th></tr>";
        foreach ($rapports as $rapport) {
            echo "<tr>";
            echo "<td>" . $rapport['id_rapport'] . "</td>";
            echo "<td>" . $rapport['nom_rapport'] . "</td>";
            echo "<td>" . $rapport['etape_validation'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Test 4: Tester la fonction getEnseignantIdFromAdmin
    echo "<h3>4. Test de la fonction getEnseignantIdFromAdmin :</h3>";
    
    // Utiliser la réflexion pour accéder à la méthode privée
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('getEnseignantIdFromAdmin');
    $method->setAccessible(true);
    
    // Tester avec différents IDs
    $testIds = [1, 2, 999]; // 999 pour tester le fallback
    
    foreach ($testIds as $testId) {
        try {
            $enseignantId = $method->invoke($controller, $testId);
            echo "<p>ID Personnel Admin $testId → ID Enseignant: " . ($enseignantId ?: 'null') . "</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>Erreur avec ID $testId: " . $e->getMessage() . "</p>";
        }
    }
    
    // Test 5: Tester l'insertion directe dans la table valider
    echo "<h3>5. Test d'insertion directe dans la table valider :</h3>";
    
    if (!empty($enseignants) && !empty($rapports)) {
        $id_enseignant_test = $enseignants[0]['id_enseignant'];
        $id_rapport_test = $rapports[0]['id_rapport'];
        
        echo "<p>Test avec : Enseignant ID = $id_enseignant_test, Rapport ID = $id_rapport_test</p>";
        
        try {
            $result = Valider::insererDecision($id_enseignant_test, $id_rapport_test, 'valider', 'Test de validation réussi');
            echo "<p style='color: green;'>✅ Insertion réussie !</p>";
            
            // Vérifier l'insertion
            $decisions = Valider::getByRapport($id_rapport_test);
            echo "<p>Nombre de décisions pour ce rapport : " . count($decisions) . "</p>";
            
            if (!empty($decisions)) {
                echo "<p>Dernière décision :</p>";
                echo "<pre>" . print_r($decisions[0], true) . "</pre>";
            }
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Erreur lors de l'insertion : " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p style='color: orange;'>⚠️ Impossible de tester l'insertion : données manquantes</p>";
    }
    
    echo "<h3>✅ Tests terminés</h3>";
    
} catch (Exception $e) {
    echo "<h3 style='color: red;'>❌ Erreur générale :</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
} 