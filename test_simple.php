<?php
require_once 'app/config/database.php';

echo "<h2>Test simple de connexion</h2>";

try {
    $pdo = Database::getConnection();
    echo "<p>✅ Connexion réussie</p>";
    
    // Tester une requête simple
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM enseignants");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>Nombre d'enseignants : " . $result['total'] . "</p>";
    
    // Tester une requête sur la table valider
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM valider");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>Nombre de validations : " . $result['total'] . "</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur : " . $e->getMessage() . "</p>";
} 