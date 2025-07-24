<?php


class Note
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getByStudent($studentId)
    {
        $query = "SELECT n.*, u.lib_ue, u.credit, u.id_semestre, s.lib_semestre, e.lib_ecue
                 FROM notes n 
                 LEFT JOIN ue u ON n.id_ue = u.id_ue
                 LEFT JOIN semestre s ON u.id_semestre = s.id_semestre
                 LEFT JOIN ecue e ON n.id_ecue = e.id_ecue
                 WHERE n.num_etu = ? 
                 ORDER BY u.id_semestre";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function updateNote($etudiantId, $ueId, $moyenne, $commentaire, $ecueId)
    {
        try {
            if ($ecueId) {
                $query = "UPDATE notes 
                         SET moyenne = ?, commentaire = ?
                         WHERE num_etu = ? AND id_ecue = ? AND id_ue = ?";

                $stmt = $this->db->prepare($query);
                return $stmt->execute([$moyenne, $commentaire, $etudiantId, $ecueId, $ueId]);
            } else {
                $query = "UPDATE notes 
                         SET moyenne = ?, commentaire = ?
                         WHERE num_etu = ? AND id_ue = ?";

                $stmt = $this->db->prepare($query);
                return $stmt->execute([$moyenne, $commentaire, $etudiantId, $ueId]);
            }
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour de la note: " . $e->getMessage());
            return false;
        }
    }

    public function createNote($etudiantId, $ueId, $moyenne, $commentaire, $ecueId)
    {
        try {
            if ($ecueId) {
                $query = "INSERT INTO notes (num_etu, id_ecue,id_ue, moyenne, commentaire) 
                         VALUES (?, ?, ?, ?, ?)";

                $stmt = $this->db->prepare($query);
                return $stmt->execute([$etudiantId, $ecueId, $ueId, $moyenne, $commentaire]);
            } else {
                $query = "INSERT INTO notes (num_etu, id_ue, moyenne, commentaire) 
                         VALUES (?, ?, ?, ?)";

                $stmt = $this->db->prepare($query);
                return $stmt->execute([$etudiantId, $ueId, $moyenne, $commentaire]);
            }
        } catch (PDOException $e) {
            error_log("Erreur lors de la création de la note: " . $e->getMessage());
            return false;
        }
    }

    public function deleteNote($etudiantId, $ueId = null, $ecueId = null)
    {
        try {
            if ($ecueId) {
                $query = "DELETE FROM notes WHERE num_etu = ? AND id_ecue = ?";
                $stmt = $this->db->prepare($query);
                return $stmt->execute([$etudiantId, $ecueId]);
            } else {
                $query = "DELETE FROM notes WHERE num_etu = ? AND id_ue = ?";
                $stmt = $this->db->prepare($query);
                return $stmt->execute([$etudiantId, $ueId]);
            }
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression de la note: " . $e->getMessage());
            return false;
        }
    }

    public function getSemestreByEtudiant($etudiantId)
    {
        $query = "SELECT id_niveau FROM inscriptions WHERE id_etudiant = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$etudiantId]);
        $niveauId = $stmt->fetch(PDO::FETCH_OBJ)->id_niveau;

        $query = "SELECT s.lib_semestre FROM semestre s
                  WHERE  s.id_niv_etude = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$niveauId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }


    public function getMoyenneGenerale($etudiantId)
    {
        // Récupérer le niveau de l'étudiant
        $niveauQuery = "SELECT id_niveau FROM inscriptions WHERE id_etudiant = ? ORDER BY id_inscription DESC LIMIT 1";
        $niveauStmt = $this->db->prepare($niveauQuery);
        $niveauStmt->execute([$etudiantId]);
        $niveauRow = $niveauStmt->fetch(PDO::FETCH_OBJ);
        if (!$niveauRow) {
            return (object)["moyenne_generale" => null];
        }
        $niveauId = $niveauRow->id_niveau;

        if ($niveauId != 9) {
            // Récupérer les deux semestres du niveau
            $semestresQuery = "SELECT id_semestre FROM semestre WHERE id_niv_etude = ? ORDER BY id_semestre ASC LIMIT 2";
            $semestresStmt = $this->db->prepare($semestresQuery);
            $semestresStmt->execute([$niveauId]);
            $semestres = $semestresStmt->fetchAll(PDO::FETCH_OBJ);
            if (count($semestres) < 2) {
                return (object)["moyenne_generale" => null];
            }
            $idSem1 = $semestres[0]->id_semestre;
            $idSem2 = $semestres[1]->id_semestre;

            // Moyenne semestre 1
            $moy1Query = "SELECT AVG(n.moyenne) as moy FROM notes n JOIN ue u ON n.id_ue = u.id_ue WHERE n.num_etu = ? AND u.id_semestre = ?";
            $moy1Stmt = $this->db->prepare($moy1Query);
            $moy1Stmt->execute([$etudiantId, $idSem1]);
            $moy1 = $moy1Stmt->fetch(PDO::FETCH_OBJ)->moy ?? 0;

            // Moyenne semestre 2
            $moy2Stmt = $this->db->prepare($moy1Query);
            $moy2Stmt->execute([$etudiantId, $idSem2]);
            $moy2 = $moy2Stmt->fetch(PDO::FETCH_OBJ)->moy ?? 0;

            $moyenne = ($moy1 * 30 + $moy2 * 30) / 60;
            return (object)["moyenne_generale" => $moyenne];
        } else {
            // Master 2 : calcul spécial
            // UE majeures (crédit >=4)
            $majQuery = "SELECT SUM(n.moyenne * u.credit) as somme_ponderee_maj, SUM(u.credit) as total_maj FROM notes n JOIN ue u ON n.id_ue = u.id_ue WHERE n.num_etu = ? AND u.credit >= 4 AND u.id_niveau_etude = 9";
            $majStmt = $this->db->prepare($majQuery);
            $majStmt->execute([$etudiantId]);
            $majRow = $majStmt->fetch(PDO::FETCH_OBJ);
            $sommePondereeMaj = $majRow->somme_ponderee_maj ?? 0;
            $totalMaj = $majRow->total_maj ?? 0;
            $moyMaj = ($totalMaj > 0) ? ($sommePondereeMaj / $totalMaj) : 0;

            // UE mineures (crédit <4)
            $minQuery = "SELECT SUM(n.moyenne * u.credit) as somme_ponderee_min, SUM(u.credit) as total_min FROM notes n JOIN ue u ON n.id_ue = u.id_ue WHERE n.num_etu = ? AND u.credit < 4 AND u.id_niveau_etude = 9";
            $minStmt = $this->db->prepare($minQuery);
            $minStmt->execute([$etudiantId]);
            $minRow = $minStmt->fetch(PDO::FETCH_OBJ);
            $sommePondereeMin = $minRow->somme_ponderee_min ?? 0;
            $totalMin = $minRow->total_min ?? 0;
            $moyMin = ($totalMin > 0) ? ($sommePondereeMin / $totalMin) : 0;

            // Calcul final
            $moyenne = 0;
            if (($totalMaj + $totalMin) > 0) {
                $moyenne = (($moyMaj * $totalMaj) + ($moyMin * $totalMin)) / 30;
            }
            return (object)["moyenne_generale" => $moyenne];
        }
    }


    public  function getValidUe($etudiantId)
    {
        $query = "SELECT COUNT(id_ue) as nb_ue_valide
                 FROM notes
                 WHERE num_etu = ? AND moyenne >= 10";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$etudiantId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }


    public function getClassementStudent($etudiantId)
    {
        // Étape 1 : Récupérer le niveau actuel de l'étudiant
        $niveauQuery = "SELECT id_niveau FROM inscriptions WHERE id_etudiant = ?";
        $niveauStmt = $this->db->prepare($niveauQuery);
        $niveauStmt->execute([$etudiantId]);
        $niveauRow = $niveauStmt->fetch(PDO::FETCH_OBJ);

        if (!$niveauRow) {
            return null; // Étudiant non inscrit à un niveau
        }

        $niveauId = $niveauRow->id_niveau;

        // Étape 2 : Calculer le classement dans ce niveau
        $classementQuery = "
        SELECT classement FROM (
            SELECT 
                n.num_etu,
                RANK() OVER (ORDER BY AVG(n.moyenne) DESC) AS classement
            FROM notes n
            JOIN inscriptions i ON n.num_etu = i.id_etudiant
            WHERE i.id_niveau = ?
            GROUP BY n.num_etu
        ) AS classement_etudiants
        WHERE num_etu = ?
    ";
        $classementStmt = $this->db->prepare($classementQuery);
        $classementStmt->execute([$niveauId, $etudiantId]);
        $classementRow = $classementStmt->fetch(PDO::FETCH_OBJ);

        if (!$classementRow) {
            return null; // L'étudiant n’a pas de notes pour ce niveau
        }

        $classement = $classementRow->classement;

        // Étape 3 : Nombre total d’étudiants dans le même niveau
        $totalQuery = "SELECT COUNT(DISTINCT id_etudiant) AS total FROM inscriptions WHERE id_niveau = ?";
        $totalStmt = $this->db->prepare($totalQuery);
        $totalStmt->execute([$niveauId]);
        $totalRow = $totalStmt->fetch(PDO::FETCH_OBJ);
        $total = $totalRow ? intval($totalRow->total) : 0;

        // Étape 4 : Format du rang
        $suffixe = $this->getRangSuffixe($classement); 
        $rangAffiche = "{$classement}{$suffixe} sur {$total}";

        return (object)[
            'classement' => $classement,
            'total' => $total,
            'rang_affiche' => $rangAffiche
        ];
    }

    // Fonction utilitaire pour le suffixe ordinal (en français)
    private function getRangSuffixe($rang)
    {
        if (!is_numeric($rang)) return 'e';
        return $rang == 1 ? 'er' : 'e';
    }
}
