<?php

class Scolarite {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Récupérer le montant de la scolarité pour un niveau d'études
    public function getMontantScolarite($id_niveau) {
        $query = "SELECT montant_scolarite FROM niveau_etude WHERE id_niv_etude= ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id_niveau]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['montant_scolarite'];
    }

    // Créer une inscription avec le premier versement
    public function creerInscription($id_etudiant, $id_niveau, $id_annee_acad, $montant_premier_versement,$nombre_tranches,$reste_a_payer,$methode_paiement) {
        $this->db->beginTransaction();
        try {
            // Créer l'inscription
            $query = "INSERT INTO inscriptions (id_etudiant, id_niveau, id_annee_acad, date_inscription, statut_inscription, nombre_tranche, reste_a_payer, montant_paye) 
                     VALUES (?, ?, ?, NOW(), 'En cours', ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id_etudiant, $id_niveau, $id_annee_acad, $nombre_tranches, $reste_a_payer, $montant_premier_versement]);
            $id_inscription = $this->db->lastInsertId();

            // Enregistrer le premier versement
            $query = "INSERT INTO versements (id_inscription, montant, date_versement, type_versement, methode_paiement) 
                     VALUES (?, ?, NOW(), 'Premier versement', ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id_inscription, $montant_premier_versement, $methode_paiement]);

            $this->db->commit();
            return $id_inscription;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    // Récupérer les étudiants non inscrits
    public function getEtudiantsNonInscrits() {
        $query = "SELECT num_etu, nom_etu, prenom_etu FROM etudiants WHERE num_etu NOT IN (SELECT id_etudiant FROM inscriptions)";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer les informations d'un étudiant
    public function getInfoEtudiant($numEtu) {
        $query = "SELECT num_etu, nom_etu, prenom_etu FROM etudiants WHERE num_etu = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$numEtu]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupérer tous les niveaux d'études
    public function getNiveauxEtudes() {
        $query = "SELECT id_niv_etude, lib_niv_etude,montant_scolarite, montant_inscription FROM niveau_etude";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function creerEcheance($id_inscription, $montant, $date_echeance) {
        $query = "INSERT INTO echeances (id_inscription, montant, date_echeance, statut_echeance) 
                 VALUES (?, ?, ?, 'En attente')";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id_inscription, $montant, $date_echeance]);
    }

    public function getEtudiantsInscrits() {
        $query = "SELECT DISTINCT i.*, e.nom_etu as nom, e.prenom_etu as prenom, n.lib_niv_etude as nom_niveau, 
                        a.date_deb, a.date_fin, n.montant_scolarite, n.montant_inscription
                 FROM inscriptions i 
                 JOIN etudiants e ON i.id_etudiant = e.num_etu 
                 JOIN niveau_etude n ON i.id_niveau = n.id_niv_etude
                 JOIN annee_academique a ON i.id_annee_acad = a.id_annee_acad
                 LEFT JOIN versements v ON i.id_inscription = v.id_inscription
                 ORDER BY i.date_inscription DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer tous les étudiants
    public function getAllEtudiants() {
        $query = "SELECT * FROM etudiants ORDER BY nom_etu, prenom_etu";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Vérifier si un étudiant est déjà inscrit pour une année académique spécifique
    public function estEtudiantInscritPourAnnee($num_etu, $id_annee_acad) {
        $query = "SELECT COUNT(*) as count FROM inscriptions WHERE id_etudiant = ? AND id_annee_acad = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$num_etu, $id_annee_acad]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    // Modifier une inscription
    public function modifierInscription($id_inscription, $id_niveau, $id_annee_acad,$montant_premier_versement,$nombre_tranches,$methode_versement) {
        $this->db->beginTransaction();
        try {
            // Mettre à jour l'inscription
            $query = "UPDATE inscriptions SET id_niveau = ?, id_annee_acad = ?, nombre_tranche = ?  WHERE id_inscription = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id_niveau,$id_annee_acad,$nombre_tranches, $id_inscription]);

            // Mettre à jour le premier versement
            $query = "UPDATE versements SET montant = ?, methode_paiement = ? WHERE id_inscription = ? AND type_versement = 'Premier versement'";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$montant_premier_versement, $methode_versement, $id_inscription]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    // Supprimer les échéances d'une inscription
    public function supprimerEcheances($id_inscription) {
        $query = "DELETE FROM echeances WHERE id_inscription = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id_inscription]);
    }

    // Supprimer une inscription
    public function supprimerInscription($id_inscription) {
        $this->db->beginTransaction();
        try {
            // Supprimer d'abord les échéances
            $query = "DELETE FROM echeances WHERE id_inscription = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id_inscription]);

            // Supprimer ensuite les versements
            $query = "DELETE FROM versements WHERE id_inscription = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id_inscription]);

            // Enfin supprimer l'inscription
            $query = "DELETE FROM inscriptions WHERE id_inscription = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id_inscription]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    // Récupérer une inscription par son ID
    public function getInscriptionById($id_inscription) {
        $query = "SELECT i.*, n.lib_niv_etude as nom_niveau, n.montant_scolarite as montant_total, 
                        a.date_deb, a.date_fin, v.montant as montant_premier_versement ,v.methode_paiement as methode_paiement,
                        e.nom_etu as nom_etudiant, e.prenom_etu as prenom_etudiant
                 FROM inscriptions i 
                 JOIN niveau_etude n ON i.id_niveau = n.id_niv_etude 
                 JOIN annee_academique a ON i.id_annee_acad = a.id_annee_acad 
                 JOIN etudiants e ON i.id_etudiant = e.num_etu
                 LEFT JOIN versements v ON i.id_inscription = v.id_inscription AND v.type_versement = 'Premier versement'
                 WHERE i.id_inscription = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id_inscription]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupérer tous les versements avec les informations associées
    public function getAllVersements() {
        $query = "SELECT v.*, e.nom_etu as nom_etudiant, e.prenom_etu as prenom_etudiant, i.id_inscription 
                  FROM versements v
                  JOIN inscriptions i ON v.id_inscription = i.id_inscription
                  JOIN etudiants e ON i.id_etudiant = e.num_etu
                  ORDER BY v.date_versement DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer un versement par son ID
    public function getVersementById($id_versement) {
        $query = "SELECT v.*, e.nom_etu as nom_etudiant, e.prenom_etu as prenom_etudiant, i.id_inscription 
                  FROM versements v
                  JOIN inscriptions i ON v.id_inscription = i.id_inscription
                  JOIN etudiants e ON i.id_etudiant = e.num_etu
                  WHERE v.id_versement = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id_versement]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Ajouter un nouveau versement
    public function addVersement($data) {
        try {
            // Insérer le versement
            $sql = "INSERT INTO versements (id_inscription, montant, methode_paiement, type_versement) 
                    VALUES (:id_inscription, :montant, :methode_paiement, 'Tranche')";
            $stmt = $this->db->prepare($sql);

            $result = $stmt->execute([
                'id_inscription' => $data['id_inscription'],
                'montant' => $data['montant'],
                'methode_paiement' => $data['methode_paiement']
            ]);

            if(!$result){
                return false;
            } else {
                // Mettre à jour le montant payé dans l'inscription
                $sql = "UPDATE inscriptions i 
                        JOIN niveau_etude n ON i.id_niveau = n.id_niv_etude
                        SET i.montant_paye = i.montant_paye + :montant,
                            i.reste_a_payer = n.montant_scolarite - i.montant_paye
                        WHERE i.id_inscription = :id_inscription";
                $stmt = $this->db->prepare($sql);

                $result = $stmt->execute([
                    'montant' => $data['montant'],
                    'id_inscription' => $data['id_inscription']
                ]);

                return true;
            }
        } catch (Exception $e) {
            error_log("Erreur lors de l'ajout du versement : " . $e->getMessage());
            return false;
        }
    }

    // Modifier un versement existant
    public function updateVersement($id_versement, $data) {
        try {
            // Récupérer le versement actuel
            $query = "SELECT v.*, i.reste_a_payer, i.montant_paye 
                     FROM versements v 
                     JOIN inscriptions i ON v.id_inscription = i.id_inscription 
                     WHERE v.id_versement = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id_versement]);
            $versement = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$versement) {
                throw new Exception("Versement non trouvé");
            }


            // Mettre à jour le versement
            $query = "UPDATE versements 
                     SET montant = ?, 
                         methode_paiement = ? 
                     WHERE id_versement = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $data['montant'],
                $data['methode_paiement'],
                $id_versement
            ]);

            // Mettre à jour le montant payé dans l'inscription
                $sql = "UPDATE inscriptions i 
                        JOIN niveau_etude n ON i.id_niveau = n.id_niv_etude
                        SET i.montant_paye = i.montant_paye - :difference,
                            i.reste_a_payer = n.montant_scolarite - i.montant_paye
                        WHERE i.id_inscription = :id_inscription";
                $stmt = $this->db->prepare($sql);

                $stmt->execute([
                    'difference' => $data['difference'],
                    'id_inscription' => $versement['id_inscription']
                ]);


            return true;
        } catch (Exception $e) {
            error_log("Erreur lors de la mise à jour du versement : " . $e->getMessage());
            return false;
        }
    }


    // Récupérer l'ID de l'inscription par ID de l'étudiant
    public function getInscriptionByEtudiantId($id_etudiant) {
        $query = "SELECT i.*, n.lib_niv_etude,n.montant_inscription, n.montant_scolarite, a.date_deb, a.date_fin
                 FROM inscriptions i 
                 JOIN niveau_etude n ON i.id_niveau = n.id_niv_etude 
                 JOIN annee_academique a ON i.id_annee_acad = a.id_annee_acad 
                 WHERE i.id_etudiant = ?
                 ORDER BY i.date_inscription DESC 
                 LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id_etudiant]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupérer les informations d'inscription d'un étudiant
    public function getInscriptionEtudiant($num_etu) {
        $query = "SELECT i.*, n.lib_niv_etude, n.montant_scolarite,n.montant_inscription, a.date_deb, a.date_fin
                 FROM inscriptions i 
                 JOIN niveau_etude n ON i.id_niveau = n.id_niv_etude 
                 JOIN annee_academique a ON i.id_annee_acad = a.id_annee_acad 
                 WHERE i.id_etudiant = ?
                 ORDER BY i.date_inscription DESC 
                 LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$num_etu]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupérer les informations de scolarité d'un étudiant
    public function getScolariteEtudiant($num_etu) {
        $query = "SELECT i.*, n.montant_scolarite as montant_total, n.montant_inscription,
                        (SELECT MAX(v.date_versement) FROM versements v WHERE v.id_inscription = i.id_inscription) as dernier_paiement
                 FROM inscriptions i 
                 JOIN niveau_etude n ON i.id_niveau = n.id_niv_etude 
                 WHERE i.id_etudiant = ?
                 ORDER BY i.date_inscription DESC 
                 LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$num_etu]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$result) {
            return [
                'reste_a_payer' => 0,
                'montant_total' => 0,
                'dernier_paiement' => null
            ];
        }
        
        return $result;
    }

} 