<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Action.php';
require_once __DIR__ . '/../models/AnneeAcademique.php';
require_once __DIR__ . '/../models/Ecue.php';
require_once __DIR__ . '/../models/Fonction.php';
require_once __DIR__ . '/../models/Grade.php';
require_once __DIR__ . '/../models/GroupeUtilisateur.php';
require_once __DIR__ . '/../models/NiveauAccesDonnees.php';
require_once __DIR__ . '/../models/NiveauApprobation.php';
require_once __DIR__ . '/../models/Specialite.php';
require_once __DIR__ . '/../models/StatutJury.php';
require_once __DIR__ . '/../models/TypeUtilisateur.php';
require_once __DIR__ . '/../models/Ue.php';
require_once __DIR__ . '/../models/NiveauEtude.php';
require_once __DIR__ . '/../models/Semestre.php';
require_once __DIR__ . '/../models/Traitement.php';
require_once __DIR__ . '/../models/Entreprise.php';
require_once __DIR__ . '/../models/Message.php';
require_once __DIR__ . '/../models/Attribution.php';
require_once __DIR__ . '/../models/Enseignant.php';
require_once __DIR__ . '/../models/AuditLog.php';

class ParametreController
{
    private $baseViewPath;
    private $action;
    private $anneeAcademique;
    private $ecue;
    private $fonction;
    private $grade;
    private $groupeUtilisateur;
    private $niveauAccesDonnees;
    private $niveauApprobation;
    private $niveauEtude;
    private $specialite;
    private $statutJury;
    private $typeUtilisateur;
    private $ue;
    private $semestre;
    private $entreprise;
    private $traitement;
    private $message;

    private $attribution;
    private $enseignant;
    private $auditLog;

    public function __construct()
    {
        $this->baseViewPath = __DIR__ . '/../../ressources/views/parametres_generaux/';
        $this->anneeAcademique = new AnneeAcademique(Database::getConnection());
        $this->action = new Action(Database::getConnection());
        $this->fonction = new Fonction(Database::getConnection());
        $this->grade = new Grade(Database::getConnection());
        $this->groupeUtilisateur = new GroupeUtilisateur(Database::getConnection());
        $this->niveauAccesDonnees = new NiveauAccesDonnees(Database::getConnection());
        $this->niveauApprobation = new NiveauApprobation(Database::getConnection());
        $this->typeUtilisateur = new TypeUtilisateur(Database::getConnection());
        $this->ue = new Ue(Database::getConnection());
        $this->ecue = new Ecue(Database::getConnection());
        $this->statutJury = new StatutJury(Database::getConnection());
        $this->specialite = new Specialite(Database::getConnection());
        $this->niveauEtude = new NiveauEtude(Database::getConnection());
        $this->semestre = new Semestre(Database::getConnection());
        $this->traitement = new Traitement(Database::getConnection());
        $this->entreprise = new Entreprise(Database::getConnection());
        $this->message = new Message(Database::getConnection());
        $this->attribution = new Attribution(Database::getConnection());
        $this->enseignant = new Enseignant(Database::getConnection());
        $this->auditLog = new AuditLog(Database::getConnection());
    }


    //=============================GESTION ANNEE ACADEMIQUE=============================
    public function gestionAnnees()
    {
        $annee_a_modifier = null;
        $messageErreur = '';
        $messageSuccess = '';

        // Ajout ou modification
        if (isset($_POST['btn_add_annees_academiques']) || isset($_POST['btn_modifier_annees_academiques'])) {
            $dateDebut = $_POST['date_debut'];
            $dateFin = $_POST['date_fin'];
            $annee1 = date("Y", strtotime($dateDebut));
            $annee2 = date("Y", strtotime($dateFin));

           if (($annee1 == $annee2) || ($dateDebut >= $dateFin)) {
                $messageErreur = "Les dates de début et de fin ne sont pas valides.";
            } else {
                // Calculer le nouvel ID basé sur les nouvelles dates
                $nouvel_id = substr($annee2, 0, 1) . substr($annee2, 2, 2) . substr($annee1, 2, 2);
                
                if ($this->anneeAcademique->isAnneeAcademiqueExist($nouvel_id, $dateDebut, $dateFin)) {
                    $messageErreur = "Cette année académique existe déjà.";
                    $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'annee_academique', 'Erreur');
                }
                // Vérification de l'existence de l'année académique
                if ($this->anneeAcademique->isAnneeAcademiqueInUse($nouvel_id)) {
                    $messageErreur = "Cette année académique est déjà utilisée.";
                    $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'annee_academique', 'Erreur');
                }

                if (empty($messageErreur)) {
                    if (!empty($_POST['id_annee_acad'])) {
                        // MODIFICATION
                        if ($this->anneeAcademique->updateAnneeAcademique($nouvel_id, $dateDebut, $dateFin)) {
                            $messageSuccess = "Année académique modifiée avec succès.";
                            $this->auditLog->logModification($_SESSION['id_utilisateur'], 'annee_academique', 'Succès');
                        } else {
                            $messageErreur = "Erreur lors de la mise à jour de l'année académique.";
                            $this->auditLog->logModification($_SESSION['id_utilisateur'], 'annee_academique', 'Erreur');
                        }
                    } else {
                        // AJOUT
                        if ($this->anneeAcademique->ajouterAnneeAcademique($dateDebut, $dateFin)) {
                            $messageSuccess = "Année académique ajoutée avec succès.";
                            $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'annee_academique', 'Succès');
                        } else {
                            $messageErreur = "Erreur lors de l'ajout de l'année académique.";
                            $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'annee_academique', 'Erreur');
                        }
                    }
                }
            }
        }

        // Suppression multiple
        if (isset($_POST['submit_delete_multiple']) && $_POST['submit_delete_multiple'] == '1' && isset($_POST['selected_ids'])) {
            $success = true;
            foreach ($_POST['selected_ids'] as $id) {
                if (!$this->anneeAcademique->deleteAnneeAcademique($id)) {
                    $success = false;
                    break;
                }
            }

            if ($success) {
                $messageSuccess = "Années académiques supprimées avec succès.";
                $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'annee_academique', 'Succès');
            } else {
                $messageErreur = "Erreur lors de la suppression des années académiques.";
                $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'annee_academique', 'Erreur');
            }
        }

        // Récupération de l'année à modifier pour affichage dans le formulaire
        if (isset($_GET['id_annee_acad'])) {
            $annee_a_modifier = $this->anneeAcademique->getAnneeAcademiqueById($_GET['id_annee_acad']);
        }

        // 📦 Variables disponibles pour la vue
        $GLOBALS['annee_a_modifier'] = $annee_a_modifier;
        $GLOBALS['listeAnnees'] = $this->anneeAcademique->getAllAnneeAcademiques();
        $GLOBALS['messageErreur'] = $messageErreur;
        $GLOBALS['messageSuccess'] = $messageSuccess;
    }
    //=============================FIN GESTION ANNEE ACADEMIQUE=============================


    //=============================GESTION GRADES=============================
    public function gestionGrade()
    {
        $grades_a_modifier = null;
        $messageErreur = '';
        $messageSuccess = '';

        // Ajout ou modification
        if (isset($_POST['btn_add_grades']) || isset($_POST['btn_modifier_grades'])) {
            $lib_grade = $_POST['grades'];

            if (!empty($_POST['id_grade'])) {
                // MODIFICATION
                if ($this->grade->updateGrade($_POST['id_grade'], $lib_grade)) {
                    $messageSuccess = "Grade modifié avec succès.";
                    $this->auditLog->logModification($_SESSION['id_utilisateur'], 'grade', 'Succès');
                } else {
                    $messageErreur = "Erreur lors de la modification du grade.";
                    $this->auditLog->logModification($_SESSION['id_utilisateur'], 'grade', 'Erreur');
                }
            } else {
                // AJOUT
                if ($this->grade->ajouterGrade($lib_grade)) {
                    $messageSuccess = "Grade ajouté avec succès.";
                    $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'grade', 'Succès');
                } else {
                    $messageErreur = "Erreur lors de l'ajout du grade.";
                    $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'grade', 'Erreur');
                }
            }
        }

        // Suppression multiple
        if (isset($_POST['submit_delete_multiple']) && isset($_POST['selected_ids'])) {
            $success = true;
            foreach ($_POST['selected_ids'] as $id) {
                if (!$this->grade->deleteGrade($id)) {
                    $success = false;
                    break;
                }
            }

            if ($success) {
                $messageSuccess = "Grades supprimés avec succès.";
                $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'grade', 'Succès');
            } else {
                $messageErreur = "Erreur lors de la suppression des grades.";
                $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'grade', 'Erreur');
            }
        }

        // Récupération du grade à modifier pour affichage dans le formulaire
        if (isset($_GET['id_grade'])) {
            $grades_a_modifier = $this->grade->getGradeById($_GET['id_grade']);
        }

        // 📦 Variables disponibles pour la vue
        $GLOBALS['grade_a_modifier'] = $grades_a_modifier;
        $GLOBALS['listeGrade'] = $this->grade->getAllGrades();
        $GLOBALS['messageErreur'] = $messageErreur;
        $GLOBALS['messageSuccess'] = $messageSuccess;
    }
    //=============================FIN GESTION GRADES=============================




    //=============================GESTION FONCTION UTILISATEUR=============================
    public function gestionFonctionUtilisateur()
    {
        $groupe_a_modifier = null;
        $type_a_modifier = null;
        $messageErreur = '';
        $messageSuccess = '';

        //======PARTIE GROUPE UTILISATEUR======
        if (isset($_GET['tab']) && $_GET['tab'] === 'groupes') {
            if (isset($_POST['submit_add_groupe']) || isset($_POST['btn_modifier_groupe'])) {
                $lib_groupe = $_POST['lib_groupe'];

                if (!empty($_POST['id_groupe'])) {
                    if ($this->groupeUtilisateur->updateGroupeUtilisateur($_POST['id_groupe'], $lib_groupe)) {
                        $messageSuccess = "Groupe utilisateur modifié avec succès.";
                        $this->auditLog->logModification($_SESSION['id_utilisateur'], 'groupe_utilisateur', 'Succès');
                    } else {
                        $messageErreur = "Erreur lors de la modification du groupe utilisateur.";
                        $this->auditLog->logModification($_SESSION['id_utilisateur'], 'groupe_utilisateur', 'Erreur');
                    }
                } else {
                    if ($this->groupeUtilisateur->ajouterGroupeUtilisateur($lib_groupe)) {
                        $messageSuccess = "Groupe utilisateur ajouté avec succès.";
                        $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'groupe_utilisateur', 'Succès');
                    } else {
                        $messageErreur = "Erreur lors de l'ajout du groupe utilisateur.";
                        $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'groupe_utilisateur', 'Erreur');
                    }
                }
            }

            // Suppression multiple
            if (isset($_POST['submit_delete_multiple']) && isset($_POST['selected_ids'])) {
                $success = true;
                foreach ($_POST['selected_ids'] as $id) {
                    if (!$this->groupeUtilisateur->deleteGroupeUtilisateur($id)) {
                        $success = false;
                        break;
                    }
                }

                if ($success) {
                    $messageSuccess = "Groupes utilisateurs supprimés avec succès.";
                    $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'groupe_utilisateur', 'Succès');
                } else {
                    $messageErreur = "Erreur lors de la suppression des groupes utilisateurs.";
                    $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'groupe_utilisateur', 'Erreur');
                }
            }

            // Récupération du groupe à modifier
            if (isset($_GET['id_groupe'])) {
                $groupe_a_modifier = $this->groupeUtilisateur->getGroupeUtilisateurById($_GET['id_groupe']);
            }

            // 📦 Variables disponibles pour la vue
            $GLOBALS['groupe_a_modifier'] = $groupe_a_modifier;
            $GLOBALS['listeGroupes'] = $this->groupeUtilisateur->getAllGroupeUtilisateur();
        }

        //======PARTIE TYPE UTILISATEUR======
        if (isset($_GET['tab']) && $_GET['tab'] === 'types') {
            if (isset($_POST['submit_add_type']) || isset($_POST['btn_modifier_type'])) {
                $lib_type = $_POST['lib_type_utilisateur'];

                if (!empty($_POST['id_type_utilisateur'])) {
                    if ($this->typeUtilisateur->updateTypeUtilisateur($_POST['id_type_utilisateur'], $lib_type)) {
                        $messageSuccess = "Type utilisateur modifié avec succès.";
                        $this->auditLog->logModification($_SESSION['id_utilisateur'], 'type_utilisateur', 'Succès');
                    } else {
                        $messageErreur = "Erreur lors de la modification du type utilisateur.";
                        $this->auditLog->logModification($_SESSION['id_utilisateur'], 'type_utilisateur', 'Erreur');
                    }
                } else {
                    if ($this->typeUtilisateur->ajouterTypeUtilisateur($lib_type)) {
                        $messageSuccess = "Type utilisateur ajouté avec succès.";
                        $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'type_utilisateur', 'Succès');
                    } else {
                        $messageErreur = "Erreur lors de l'ajout du type utilisateur.";
                        $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'type_utilisateur', 'Erreur');
                    }
                }
            }

            // Suppression multiple
            if (isset($_POST['submit_delete_multiple']) && isset($_POST['selected_ids'])) {
                $success = true;
                foreach ($_POST['selected_ids'] as $id) {
                    if (!$this->typeUtilisateur->deleteTypeUtilisateur($id)) {
                        $success = false;
                        break;
                    }
                }

                if ($success) {
                    $messageSuccess = "Types utilisateurs supprimés avec succès.";
                    $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'type_utilisateur', 'Succès');
                } else {
                    $messageErreur = "Erreur lors de la suppression des types utilisateurs.";
                    $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'type_utilisateur', 'Erreur');
                }
            }

            // Récupération du type à modifier
            if (isset($_GET['id_type'])) {
                $type_a_modifier = $this->typeUtilisateur->getTypeUtilisateurById($_GET['id_type']);
            }

            // 📦 Variables disponibles pour la vue
            $GLOBALS['type_a_modifier'] = $type_a_modifier;
            $GLOBALS['listeTypes'] = $this->typeUtilisateur->getAllTypeUtilisateur();
        }

        // 📦 Variables communes pour la vue
        $GLOBALS['messageErreur'] = $messageErreur;
        $GLOBALS['messageSuccess'] = $messageSuccess;
    }
    //=============================FIN GESTION FONCTION UTILISATEUR=============================


    //=============================GESTION SPECIALITE=============================
    public function gestionSpecialite()
    {
        $specialite_a_modifier = null;
        $messageErreur = '';
        $messageSuccess = '';

        // Ajout ou modification
        if (isset($_POST['btn_add_specialite']) || isset($_POST['btn_modifier_specialite'])) {
            $lib_specialite = $_POST['specialite'];

            if (!empty($_POST['id_specialite'])) {
                if ($this->specialite->updateSpecialite($_POST['id_specialite'], $lib_specialite)) {
                    $messageSuccess = "Spécialité modifiée avec succès.";
                    $this->auditLog->logModification($_SESSION['id_utilisateur'], 'specialite', 'Succès');
                } else {
                    $messageErreur = "Erreur lors de la modification de la spécialité.";
                    $this->auditLog->logModification($_SESSION['id_utilisateur'], 'specialite', 'Erreur');
                }
            } else {
                if ($this->specialite->ajouterSpecialite($lib_specialite)) {
                    $messageSuccess = "Spécialité ajoutée avec succès.";
                    $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'specialite', 'Succès');
                } else {
                    $messageErreur = "Erreur lors de l'ajout de la spécialité.";
                    $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'specialite', 'Erreur');
                }
            }
        }

        // Suppression multiple
        if (isset($_POST['submit_delete_multiple']) && isset($_POST['selected_ids'])) {
            $success = true;
            foreach ($_POST['selected_ids'] as $id) {
                if (!$this->specialite->deleteSpecialite($id)) {
                    $success = false;
                    break;
                }
            }

            if ($success) {
                $messageSuccess = "Spécialités supprimées avec succès.";
                $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'specialite', 'Succès');
            } else {
                $messageErreur = "Erreur lors de la suppression des spécialités.";
                $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'specialite', 'Erreur');
            }
        }

        // Récupération de la spécialité à modifier
        if (isset($_GET['id_specialite'])) {
            $specialite_a_modifier = $this->specialite->getSpecialiteById($_GET['id_specialite']);
        }

        // 📦 Variables disponibles pour la vue
        $GLOBALS['specialite_a_modifier'] = $specialite_a_modifier;
        $GLOBALS['listeSpecialites'] = $this->specialite->getAllSpecialites();
        $GLOBALS['messageErreur'] = $messageErreur;
        $GLOBALS['messageSuccess'] = $messageSuccess;
    }
    //=============================FIN GESTION SPECIALITE=============================


    //=============================GESTION NIVEAU ETUDE=============================
    public function gestionNiveauEtude()
    {
        $niveau_a_modifier = null;
        $messageErreur = '';
        $messageSuccess = '';

        if (isset($_POST['btn_add_niveau']) || isset($_POST['btn_modifier_niveau'])) {
            $lib_niveau = $_POST['lib_niv_etude'];
            $montant_scolarite = $_POST['montant_scolarite'];
            $montant_inscription = $_POST['montant_inscription'];
            $id_enseignant = isset($_POST['id_enseignant']) ? $_POST['id_enseignant'] : null;

            if (!empty($_POST['id_niv_etude'])) {
                if ($this->niveauEtude->updateNiveauEtude($_POST['id_niv_etude'], $lib_niveau, $montant_scolarite, $montant_inscription, $id_enseignant)) {
                    $messageSuccess = "Niveau d'étude modifié avec succès.";
                    $this->auditLog->logModification($_SESSION['id_utilisateur'], 'niveau_etude', 'Succès');
                } else {
                    $messageErreur = "Erreur lors de la modification du niveau d'étude.";
                    $this->auditLog->logModification($_SESSION['id_utilisateur'], 'niveau_etude', 'Erreur');
                }
            } else {
                if ($this->niveauEtude->ajouterNiveauEtude($lib_niveau, $montant_scolarite, $montant_inscription, $id_enseignant)) {
                    $messageSuccess = "Niveau d'étude ajouté avec succès.";
                    $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'niveau_etude', 'Succès');
                } else {
                    $messageErreur = "Erreur lors de l'ajout du niveau d'étude.";
                    $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'niveau_etude', 'Erreur');
                }
            }
        }

        if (isset($_POST['submit_delete_multiple']) && isset($_POST['selected_ids'])) {
            $success = true;
            foreach ($_POST['selected_ids'] as $id) {
                if (!$this->niveauEtude->deleteNiveauEtude($id)) {
                    $success = false;
                    break;
                }
            }

            if ($success) {
                $messageSuccess = "Niveaux d'étude supprimés avec succès.";
                $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'niveau_etude', 'Succès');
            } else {
                $messageErreur = "Erreur lors de la suppression des niveaux d'étude.";
                $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'niveau_etude', 'Erreur');
            }
        }

        if (isset($_GET['id_niv_etude'])) {
            $niveau_a_modifier = $this->niveauEtude->getNiveauEtudeById($_GET['id_niv_etude']);
        }

        $GLOBALS['niveau_a_modifier'] = $niveau_a_modifier;
        $GLOBALS['listeNiveaux'] = $this->niveauEtude->getAllNiveauxEtudes();
        $GLOBALS['listeEnseignants'] = $this->enseignant->getAllEnseignants();
        $GLOBALS['messageErreur'] = $messageErreur;
        $GLOBALS['messageSuccess'] = $messageSuccess;
    }
    //=============================FIN GESTION NIVEAU ETUDE=============================


    //=============================GESTION UE=============================
    public function gestionUe()
    {
        $ue_a_modifier = null;
        $messageErreur = '';
        $messageSuccess = '';

        if (isset($_POST['btn_add_ue']) || isset($_POST['btn_modifier_ue'])) {
            $lib_ue = $_POST['lib_ue'];
            $credit = $_POST['credit'];
            $id_niveau_etude = $_POST['niveau_etude'];
            $id_semestre = $_POST['semestre'];
            $id_annee = $_POST['annee_academique'];
            $id_enseignant = $_POST['professeur_responsable'] ?? null;

            if (!empty($_POST['id_ue'])) {
                if ($this->ue->updateUe($_POST['id_ue'], $lib_ue, $id_niveau_etude, $id_semestre, $id_annee, $credit, $id_enseignant)) {
                    $messageSuccess = "UE modifiée avec succès.";
                    $this->auditLog->logModification($_SESSION['id_utilisateur'], 'ue', 'Succès');
                } else {
                    $messageErreur = "Erreur lors de la modification de l'UE.";
                    $this->auditLog->logModification($_SESSION['id_utilisateur'], 'ue', 'Erreur');
                }
            } else {
                if ($this->ue->ajouterUe($lib_ue, $id_niveau_etude, $id_semestre, $id_annee, $credit, $id_enseignant)) {
                    $messageSuccess = "UE ajoutée avec succès.";
                    $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'ue', 'Succès');
                } else {
                    $messageErreur = "Erreur lors de l'ajout de l'UE.";
                    $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'ue', 'Erreur');
                }
            }
        }

        if (isset($_POST['submit_delete_multiple']) && isset($_POST['selected_ids'])) {
            $success = true;
            foreach ($_POST['selected_ids'] as $id) {
                if (!$this->ue->deleteUe($id)) {
                    $success = false;
                    break;
                }
            }

            if ($success) {
                $messageSuccess = "UEs supprimées avec succès.";
                $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'ue', 'Succès');
            } else {
                $messageErreur = "Erreur lors de la suppression des UEs.";
                $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'ue', 'Erreur');
            }
        }

        if (isset($_GET['id_ue'])) {
            $ue_a_modifier = $this->ue->getUeById($_GET['id_ue']);
        }

        $GLOBALS['ue_a_modifier'] = $ue_a_modifier;
        $GLOBALS['listeUes'] = $this->ue->getAllUes();
        $GLOBALS['listeNiveauxEtude'] = $this->niveauEtude->getAllNiveauxEtudes();
        $GLOBALS['listeSemestres'] = $this->semestre->getAllSemestres();
        $GLOBALS['listeAnnees'] = $this->anneeAcademique->getAllAnneeAcademiques();
        $GLOBALS['listeEnseignants'] = $this->enseignant->getAllEnseignants();
        $GLOBALS['messageErreur'] = $messageErreur;
        $GLOBALS['messageSuccess'] = $messageSuccess;
    }
    //=============================FIN GESTION UE=============================


    //=============================GESTION ECUE=============================
    public function gestionEcue()
    {
        $ecue_a_modifier = null;
        $messageErreur = '';
        $messageSuccess = '';

        if (isset($_POST['btn_add_ecue']) || isset($_POST['btn_modifier_ecue'])) {
            $id_ue = $_POST['id_ue'];
            $lib_ecue = $_POST['lib_ecue'];
            $credit = $_POST['credit'];
            $id_enseignant = $_POST['professeur_responsable'] ?? null;

            if (!empty($_POST['id_ecue'])) {
                if ($this->ecue->updateEcue($_POST['id_ecue'], $id_ue, $lib_ecue, $credit, $id_enseignant)) {
                    $messageSuccess = "ECUE modifiée avec succès.";
                    $this->auditLog->logModification($_SESSION['id_utilisateur'], 'ecue', 'Succès');
                } else {
                    $messageErreur = "Erreur lors de la modification de l'ECUE.";
                    $this->auditLog->logModification($_SESSION['id_utilisateur'], 'ecue', 'Erreur');
                }
            } else {
                if ($this->ecue->ajouterEcue($id_ue, $lib_ecue, $credit, $id_enseignant)) {
                    $messageSuccess = "ECUE ajoutée avec succès.";
                    $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'ecue', 'Succès');
                } else {
                    $messageErreur = "Erreur lors de l'ajout de l'ECUE.";
                    $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'ecue', 'Erreur');
                }
            }
        }

        if (isset($_POST['submit_delete_multiple']) && isset($_POST['selected_ids'])) {
            $success = true;
            foreach ($_POST['selected_ids'] as $id) {
                if (!$this->ecue->deleteEcue($id)) {
                    $success = false;
                    break;
                }
            }

            if ($success) {
                $messageSuccess = "ECUEs supprimées avec succès.";
                $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'ecue', 'Succès');
            } else {
                $messageErreur = "Erreur lors de la suppression des ECUEs.";
                $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'ecue', 'Erreur');
            }
        }

        if (isset($_GET['id_ecue'])) {
            $ecue_a_modifier = $this->ecue->getEcueById($_GET['id_ecue']);
        }

        $GLOBALS['ecue_a_modifier'] = $ecue_a_modifier;
        $GLOBALS['listeEcues'] = $this->ecue->getAllEcues();
        $GLOBALS['listeUes'] = $this->ue->getAllUes();
        $GLOBALS['listeEnseignants'] = $this->enseignant->getAllEnseignants();
        $GLOBALS['messageErreur'] = $messageErreur;
        $GLOBALS['messageSuccess'] = $messageSuccess;
    }
    //=============================FIN GESTION ECUE=============================


    //=============================GESTION STATUT JURY=============================
    public function gestionStatutJury()
    {
        $statut_a_modifier = null;
        $messageErreur = '';
        $messageSuccess = '';

        if (isset($_POST['btn_add_statut_jury']) || isset($_POST['btn_modifier_statut_jury'])) {
            $lib_statut = $_POST['statut_jury'];

            if (!empty($_POST['id_statut_jury'])) {
                if ($this->statutJury->updateStatutJury($_POST['id_statut_jury'], $lib_statut)) {
                    $messageSuccess = "Statut jury modifié avec succès.";
                    $this->auditLog->logModification($_SESSION['id_utilisateur'], 'statut_jury', 'Succès');
                } else {
                    $messageErreur = "Erreur lors de la modification du statut jury.";
                    $this->auditLog->logModification($_SESSION['id_utilisateur'], 'statut_jury', 'Erreur');
                }
            } else {
                if ($this->statutJury->ajouterStatutJury($lib_statut)) {
                    $messageSuccess = "Statut jury ajouté avec succès.";
                    $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'statut_jury', 'Succès');
                } else {
                    $messageErreur = "Erreur lors de l'ajout du statut jury.";
                    $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'statut_jury', 'Erreur');
                }
            }
        }

        if (isset($_POST['submit_delete_multiple']) && isset($_POST['selected_ids'])) {
            $success = true;
            foreach ($_POST['selected_ids'] as $id) {
                if (!$this->statutJury->deleteStatutJury($id)) {
                    $success = false;
                    break;
                }
            }

            if ($success) {
                $messageSuccess = "Statuts jury supprimés avec succès.";
                $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'statut_jury', 'Succès');
            } else {
                $messageErreur = "Erreur lors de la suppression des statuts jury.";
                $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'statut_jury', 'Erreur');
            }
        }

        if (isset($_GET['id_statut_jury'])) {
            $statut_a_modifier = $this->statutJury->getStatutJuryById($_GET['id_statut_jury']);
        }

        $GLOBALS['statut_a_modifier'] = $statut_a_modifier;
        $GLOBALS['listeStatuts'] = $this->statutJury->getAllStatutsJury();
        $GLOBALS['messageErreur'] = $messageErreur;
        $GLOBALS['messageSuccess'] = $messageSuccess;
    }
    //=============================FIN GESTION STATUT JURY=============================


    //=============================GESTION NIVEAU APPROBATION=============================
    public function gestionNiveauApprobation()
    {
        $niveau_a_modifier = null;
        $messageErreur = '';
        $messageSuccess = '';

        if (isset($_POST['btn_add_niveau_approbation']) || isset($_POST['btn_modifier_niveau_approbation'])) {
            $lib_niveau = $_POST['niveaux_approbation'];

            if (!empty($_POST['id_approb'])) {
                if ($this->niveauApprobation->updateNiveauApprobation($_POST['id_approb'], $lib_niveau)) {
                    $messageSuccess = "Niveau d'approbation modifié avec succès.";
                    $this->auditLog->logModification($_SESSION['id_utilisateur'], 'niveau_approbation', 'Succès');
                } else {
                    $messageErreur = "Erreur lors de la modification du niveau d'approbation.";
                    $this->auditLog->logModification($_SESSION['id_utilisateur'], 'niveau_approbation', 'Erreur');
                }
            } else {
                if ($this->niveauApprobation->ajouterNiveauApprobation($lib_niveau)) {
                    $messageSuccess = "Niveau d'approbation ajouté avec succès.";
                    $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'niveau_approbation', 'Succès');
                } else {
                    $messageErreur = "Erreur lors de l'ajout du niveau d'approbation.";
                    $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'niveau_approbation', 'Erreur');
                }
            }
        }

        if (isset($_POST['submit_delete_multiple']) && isset($_POST['selected_ids'])) {
            $success = true;
            foreach ($_POST['selected_ids'] as $id) {
                if (!$this->niveauApprobation->deleteNiveauApprobation($id)) {
                    $success = false;
                    break;
                }
            }

            if ($success) {
                $messageSuccess = "Niveaux d'approbation supprimés avec succès.";
                $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'niveau_approbation', 'Succès');
            } else {
                $messageErreur = "Erreur lors de la suppression des niveaux d'approbation.";
                $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'niveau_approbation', 'Erreur');
            }
        }

        if (isset($_GET['id_approb'])) {
            $niveau_a_modifier = $this->niveauApprobation->getNiveauApprobationById($_GET['id_approb']);
        }

        $GLOBALS['niveau_a_modifier'] = $niveau_a_modifier;
        $GLOBALS['listeNiveaux'] = $this->niveauApprobation->getAllNiveauxApprobation();
        $GLOBALS['messageErreur'] = $messageErreur;
        $GLOBALS['messageSuccess'] = $messageSuccess;
    }
    //=============================FIN GESTION NIVEAU APPROBATION=============================


    //=============================GESTION SEMESTRES=============================
    public function gestionSemestre()
    {
        $semestre_a_modifier = null;
        $messageErreur = '';
        $messageSuccess = '';

        if (isset($_POST['btn_add_semestre']) || isset($_POST['btn_modifier_semestre'])) {
            $lib_semestre = $_POST['lib_semestre'];
            $id_niv_etude = $_POST['niveau_etude'];

            if (!empty($_POST['id_semestre'])) {
                if ($this->semestre->updateSemestre($_POST['id_semestre'], $lib_semestre, $id_niv_etude)) {
                    $messageSuccess = "Semestre modifié avec succès.";
                    $this->auditLog->logModification($_SESSION['id_utilisateur'], 'semestre', 'Succès');
                } else {
                    $messageErreur = "Erreur lors de la modification du semestre.";
                    $this->auditLog->logModification($_SESSION['id_utilisateur'], 'semestre', 'Erreur');
                }
            } else {
                if ($this->semestre->ajouterSemestre($lib_semestre, $id_niv_etude)) {
                    $messageSuccess = "Semestre ajouté avec succès.";
                    $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'semestre', 'Succès');
                } else {
                    $messageErreur = "Erreur lors de l'ajout du semestre.";
                    $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'semestre', 'Erreur');
                }
            }
        }

        if (isset($_POST['submit_delete_multiple']) && isset($_POST['selected_ids'])) {
            $success = true;
            foreach ($_POST['selected_ids'] as $id) {
                if (!$this->semestre->deleteSemestre($id)) {
                    $success = false;
                    break;
                }
            }

            if ($success) {
                $messageSuccess = "Semestres supprimés avec succès.";
                $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'semestre', 'Succès');
            } else {
                $messageErreur = "Erreur lors de la suppression des semestres.";
                $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'semestre', 'Erreur');
            }
        }

        if (isset($_GET['id_semestre'])) {
            $semestre_a_modifier = $this->semestre->getSemestreById($_GET['id_semestre']);
        }

        $GLOBALS['semestre_a_modifier'] = $semestre_a_modifier;
        $GLOBALS['listeSemestres'] = $this->semestre->getAllSemestres();
        $GLOBALS['listeNiveauxEtude'] = $this->niveauEtude->getAllNiveauxEtudes();
        $GLOBALS['messageErreur'] = $messageErreur;
        $GLOBALS['messageSuccess'] = $messageSuccess;
    }
    //=============================FIN GESTION SEMESTRES=============================


    //=============================GESTION NIVEAU ACCES DONNEES=============================
    public function gestionNiveauAccesDonnees()
    {
        $niveau_a_modifier = null;
        $messageErreur = '';
        $messageSuccess = '';

        if (isset($_POST['btn_add_niveau']) || isset($_POST['btn_modifier_niveau_acces'])) {
            $lib_niveau = $_POST['lib_niveau_acces_donnees'];

            if (!empty($_POST['id_niveau_acces_donnees'])) {
                if ($this->niveauAccesDonnees->updateNiveauAccesDonnees($_POST['id_niveau_acces_donnees'], $lib_niveau)) {
                    $messageSuccess = "Niveau d'accès modifié avec succès.";
                    $this->auditLog->logModification($_SESSION['id_utilisateur'], 'niveau_acces_donnees', 'Succès');
                } else {
                    $messageErreur = "Erreur lors de la modification du niveau d'accès.";
                    $this->auditLog->logModification($_SESSION['id_utilisateur'], 'niveau_acces_donnees', 'Erreur');
                }
            } else {
                if ($this->niveauAccesDonnees->ajouterNiveauAccesDonnees($lib_niveau)) {
                    $messageSuccess = "Niveau d'accès ajouté avec succès.";
                    $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'niveau_acces_donnees', 'Succès');
                } else {
                    $messageErreur = "Erreur lors de l'ajout du niveau d'accès.";
                    $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'niveau_acces_donnees', 'Erreur');
                }
            }
        }

        if (isset($_POST['submit_delete_multiple']) && isset($_POST['selected_ids'])) {
            $success = true;
            foreach ($_POST['selected_ids'] as $id) {
                if (!$this->niveauAccesDonnees->deleteNiveauAccesDonnees($id)) {
                    $success = false;
                    break;
                }
            }

            if ($success) {
                $messageSuccess = "Niveaux d'accès supprimés avec succès.";
                $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'niveau_acces_donnees', 'Succès');
            } else {
                $messageErreur = "Erreur lors de la suppression des niveaux d'accès.";
                $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'niveau_acces_donnees', 'Erreur');
            }
        }

        if (isset($_GET['id_niveau'])) {
            $niveau_a_modifier = $this->niveauAccesDonnees->getNiveauAccesDonneesById($_GET['id_niveau']);
        }

        $GLOBALS['niveau_a_modifier'] = $niveau_a_modifier;
        $GLOBALS['listeNiveaux'] = $this->niveauAccesDonnees->getAllNiveauxAccesDonnees();
        $GLOBALS['messageErreur'] = $messageErreur;
        $GLOBALS['messageSuccess'] = $messageSuccess;
    }
    //=============================FIN GESTION NIVEAU ACCES DONNEES=============================


    //=============================GESTION TRAITEMENT=============================
    public function gestionTraitement()
    {
        $traitement_a_modifier = null;
        $messageErreur = '';
        $messageSuccess = '';

        // Ajout ou modification
        if (isset($_POST['btn_add_traitement']) || isset($_POST['btn_modifier_traitement'])) {
            $lib_traitement = $_POST['lib_traitement'];
            $label_traitement = $_POST['label_traitement'];
            $icone_traitement = $_POST['icone_traitement'];
            $ordre_traitement = $_POST['ordre_traitement'];

            if (!empty($_POST['id_traitement'])) {
                if ($this->traitement->updateTraitement($_POST['id_traitement'], $lib_traitement, $label_traitement, $icone_traitement, $ordre_traitement)) {
                    $messageSuccess = "Traitement modifié avec succès.";
                    $this->auditLog->logModification($_SESSION['id_utilisateur'], 'traitement', 'Succès');
                } else {
                    $messageErreur = "Erreur lors de la modification du traitement.";
                    $this->auditLog->logModification($_SESSION['id_utilisateur'], 'traitement', 'Erreur');
                }
            } else {
                if ($this->traitement->addTraitement($lib_traitement, $label_traitement, $icone_traitement, $ordre_traitement)) {
                    $messageSuccess = "Traitement ajouté avec succès.";
                    $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'traitement', 'Succès');
                } else {
                    $messageErreur = "Erreur lors de l'ajout du traitement.";
                    $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'traitement', 'Erreur');
                }
            }
        }

        // Suppression multiple
        if (isset($_POST['submit_delete_multiple']) && isset($_POST['selected_ids'])) {
            $success = true;
            foreach ($_POST['selected_ids'] as $id) {
                if (!$this->traitement->deleteTraitement($id)) {
                    $success = false;
                    break;
                }
            }

            if ($success) {
                $messageSuccess = "Traitements supprimés avec succès.";
                $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'traitement', 'Succès');
            } else {
                $messageErreur = "Erreur lors de la suppression des traitements.";
                $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'traitement', 'Erreur');
            }
        }

        if (isset($_GET['id_traitement'])) {
            $traitement_a_modifier = $this->traitement->getTraitementById($_GET['id_traitement']);
        }

        $GLOBALS['traitement_a_modifier'] = $traitement_a_modifier;
        $GLOBALS['listeTraitements'] = $this->traitement->getAllTraitements();
        $GLOBALS['messageErreur'] = $messageErreur;
        $GLOBALS['messageSuccess'] = $messageSuccess;
    }
    //=============================FIN GESTION TRAITEMENT============================


    //=============================GESTION ENTREPRISE=============================
    public function gestionEntreprise()
    {
        $entreprise_a_modifier = null;
        $messageErreur = '';
        $messageSuccess = '';

        if (isset($_POST['btn_add_entreprise']) || isset($_POST['btn_modifier_entreprise'])) {
            $lib_entreprise = $_POST['lib_entreprise'];

            if (!empty($_POST['id_entreprise'])) {
                if ($this->entreprise->updateEntreprise($_POST['id_entreprise'], $lib_entreprise)) {
                    $messageSuccess = "Entreprise modifiée avec succès.";
                    $this->auditLog->logModification($_SESSION['id_utilisateur'], 'entreprise', 'Succès');
                } else {
                    $messageErreur = "Erreur lors de la modification de l'entreprise.";
                    $this->auditLog->logModification($_SESSION['id_utilisateur'], 'entreprise', 'Erreur');
                }
            } else {
                if ($this->entreprise->ajouterEntreprise($lib_entreprise)) {
                    $messageSuccess = "Entreprise ajoutée avec succès.";
                    $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'entreprise', 'Succès');
                } else {
                    $messageErreur = "Erreur lors de l'ajout de l'entreprise.";
                    $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'entreprise', 'Erreur');
                }
            }
        }

        if (isset($_POST['submit_delete_multiple']) && isset($_POST['selected_ids'])) {
            $success = true;
            foreach ($_POST['selected_ids'] as $id) {
                if (!$this->entreprise->deleteEntreprise($id)) {
                    $success = false;
                    break;
                }
            }

            if ($success) {
                $messageSuccess = "Entreprises supprimées avec succès.";
                $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'entreprise', 'Succès');
            } else {
                $messageErreur = "Erreur lors de la suppression des entreprises.";
                $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'entreprise', 'Erreur');
            }
        }

        if (isset($_GET['id_entreprise'])) {
            $entreprise_a_modifier = $this->entreprise->getEntrepriseById($_GET['id_entreprise']);
        }

        $GLOBALS['entreprise_a_modifier'] = $entreprise_a_modifier;
        $GLOBALS['listeEntreprises'] = $this->entreprise->getAllEntreprises();
        $GLOBALS['messageErreur'] = $messageErreur;
        $GLOBALS['messageSuccess'] = $messageSuccess;
    }
    //=============================FIN GESTION ENTREPRISE============================

    //=============================GESTION ACTION=============================
    public function gestionAction()
    {
        $action_a_modifier = null;
        $messageErreur = '';
        $messageSuccess = '';

        if (isset($_POST['btn_add_action']) || isset($_POST['btn_modifier_action'])) {
            $lib_action = $_POST['action'];

            if (!empty($_POST['id_action'])) {
                if ($this->action->updateAction($_POST['id_action'], $lib_action)) {
                    $messageSuccess = "Action modifiée avec succès.";
                    $this->auditLog->logModification($_SESSION['id_utilisateur'], 'action', 'Succès');
                } else {
                    $messageErreur = "Erreur lors de la modification de l'action.";
                    $this->auditLog->logModification($_SESSION['id_utilisateur'], 'action', 'Erreur');
                }
            } else {
                if ($this->action->ajouterAction($lib_action)) {
                    $messageSuccess = "Action ajoutée avec succès.";
                    $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'action', 'Succès');
                } else {
                    $messageErreur = "Erreur lors de l'ajout de l'action.";
                    $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'action', 'Erreur');
                }
            }
        }

        if (isset($_POST['submit_delete_multiple']) && isset($_POST['selected_ids'])) {
            $success = true;
            foreach ($_POST['selected_ids'] as $id) {
                if (!$this->action->deleteAction($id)) {
                    $success = false;
                    break;
                }
            }

            if ($success) {
                $messageSuccess = "Actions supprimées avec succès.";
                $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'action', 'Succès');
            } else {
                $messageErreur = "Erreur lors de la suppression des actions.";
                $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'action', 'Erreur');
            }
        }

        if (isset($_GET['id_action'])) {
            $action_a_modifier = $this->action->getActionById($_GET['id_action']);
        }

        $GLOBALS['action_a_modifier'] = $action_a_modifier;
        $GLOBALS['listeActions'] = $this->action->getAllAction();
        $GLOBALS['messageErreur'] = $messageErreur;
        $GLOBALS['messageSuccess'] = $messageSuccess;
    }
    //=============================FIN GESTION ACTION============================

    //=============================GESTION FONCTION=============================
    public function gestionFonction()
    {
        $fonction_a_modifier = null;
        $messageErreur = '';
        $messageSuccess = '';

        if (isset($_POST['btn_add_fonction']) || isset($_POST['btn_modifier_fonction'])) {
            $lib_fonction = $_POST['lib_fonction'];

            if (!empty($_POST['id_fonction'])) {
                if ($this->fonction->updateFonction($_POST['id_fonction'], $lib_fonction)) {
                    $messageSuccess = "Fonction modifiée avec succès.";
                    $this->auditLog->logModification($_SESSION['id_utilisateur'], 'fonction', 'Succès');
                } else {
                    $messageErreur = "Erreur lors de la modification de la fonction.";
                    $this->auditLog->logModification($_SESSION['id_utilisateur'], 'fonction', 'Erreur');
                }
            } else {
                if ($this->fonction->ajouterFonction($lib_fonction)) {
                    $messageSuccess = "Fonction ajoutée avec succès.";
                    $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'fonction', 'Succès');
                } else {
                    $messageErreur = "Erreur lors de l'ajout de la fonction.";
                    $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'fonction', 'Erreur');
                }
            }
        }

        if (isset($_POST['submit_delete_multiple']) && isset($_POST['selected_ids'])) {
            $success = true;
            foreach ($_POST['selected_ids'] as $id) {
                if (!$this->fonction->deleteFonction($id)) {
                    $success = false;
                    break;
                }
            }

            if ($success) {
                $messageSuccess = "Fonctions supprimées avec succès.";
                $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'fonction', 'Succès');
            } else {
                $messageErreur = "Erreur lors de la suppression des fonctions.";
                $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'fonction', 'Erreur');
            }
        }

        if (isset($_GET['id_fonction'])) {
            $fonction_a_modifier = $this->fonction->getFonctionById($_GET['id_fonction']);
        }

        $GLOBALS['fonction_a_modifier'] = $fonction_a_modifier;
        $GLOBALS['listeFonctions'] = $this->fonction->getAllFonctions();
        $GLOBALS['messageErreur'] = $messageErreur;
        $GLOBALS['messageSuccess'] = $messageSuccess;
    }
    //=============================FIN GESTION FONCTION============================

    //=============================GESTION MESSAGERIE=============================
    public function gestionMessagerie()
    {
        $message_a_modifier = null;
        $messageErreur = '';
        $messageSuccess = '';

        if (isset($_POST['btn_add_message']) || isset($_POST['btn_modifier_message'])) {
            $contenu_message = $_POST['contenu_message'];
            $lib_message = $_POST['lib_message'];
            $type_message = $_POST['type_message'];

            if (!empty($_POST['id_message'])) {
                if ($this->message->updateMessage($_POST['id_message'], $contenu_message, $lib_message, $type_message)) {
                    $messageSuccess = "Message modifié avec succès.";
                    $this->auditLog->logModification($_SESSION['id_utilisateur'], 'message', 'Succès');
                } else {
                    $messageErreur = "Erreur lors de la modification du message.";
                    $this->auditLog->logModification($_SESSION['id_utilisateur'], 'message', 'Erreur');
                }
            } else {
                if ($this->message->ajouterMessage($contenu_message, $lib_message, $type_message)) {
                    $messageSuccess = "Message ajouté avec succès.";
                    $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'message', 'Succès');
                } else {
                    $messageErreur = "Erreur lors de l'ajout du message.";
                    $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'message', 'Erreur');
                }
            }
        }

        if (isset($_POST['submit_delete_multiple']) && isset($_POST['selected_ids'])) {
            $success = true;
            foreach ($_POST['selected_ids'] as $id) {
                if (!$this->message->deleteMessage($id)) {
                    $success = false;
                    break;
                }
            }

            if ($success) {
                $messageSuccess = "Messages supprimés avec succès.";
                $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'message', 'Succès');
            } else {
                $messageErreur = "Erreur lors de la suppression des messages.";
                $this->auditLog->logSuppression($_SESSION['id_utilisateur'], 'message', 'Erreur');
            }
        }

        if (isset($_GET['id_message'])) {
            $message_a_modifier = $this->message->getMessageById($_GET['id_message']);
        }

        $GLOBALS['message_a_modifier'] = $message_a_modifier;
        $GLOBALS['listeMessages'] = $this->message->getAllMessages();
        $GLOBALS['messageErreur'] = $messageErreur;
        $GLOBALS['messageSuccess'] = $messageSuccess;
    }
    //=============================FIN GESTION MESSAGERIE============================


    //============================GESTION ATTRIBUTION==================================

    public function gestionAttribution()
    {
        $messageErreur = '';
        $messageSuccess = '';
        $attribution_a_modifier = null;

        // Récupérer tous les groupes et traitements
        $listeGroupes = $this->groupeUtilisateur->getAllGroupeUtilisateur();
        $listeTraitements = $this->traitement->getAllTraitements();

        // Debug
        error_log("Liste des groupes: " . print_r($listeGroupes, true));
        error_log("Liste des traitements: " . print_r($listeTraitements, true));

        // Récupérer le groupe sélectionné
        $selectedGroupeId = isset($_GET['groupe']) ? $_GET['groupe'] : null;
        $selectedGroupe = null;
        $attributionsGroupe = [];

        if ($selectedGroupeId) {
            $selectedGroupe = $this->groupeUtilisateur->getGroupeUtilisateurById($selectedGroupeId);
            // Récupérer les traitements attribués au groupe
            $attributionsGroupe = $this->attribution->getTraitementsByGroupe($selectedGroupeId);
            
            // Debug
            error_log("Groupe sélectionné: " . print_r($selectedGroupe, true));
            error_log("Attributions du groupe: " . print_r($attributionsGroupe, true));
        }

        // Traiter le formulaire de soumission
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_GU'])) {
            $this->handleAttributionSubmit($_POST);
        }

        // Préparer les données pour la vue
        $attributionsMap = [];
        foreach ($listeGroupes as $groupe) {
            $attributionsMap[$groupe->id_GU] = $this->attribution->getTraitementsByGroupe($groupe->id_GU);
        }

        // Debug
        error_log("Map des attributions: " . print_r($attributionsMap, true));

        $GLOBALS['attributionsMap'] = $attributionsMap;
        $GLOBALS['listeGroupes'] = $listeGroupes;
        $GLOBALS['listeTraitements'] = $listeTraitements;
        $GLOBALS['selectedGroupe'] = $selectedGroupe;
        $GLOBALS['attributionsGroupe'] = $attributionsGroupe;
        $GLOBALS['messageErreur'] = $messageErreur;
        $GLOBALS['messageSuccess'] = $messageSuccess;
        $GLOBALS['attribution_a_modifier'] = $attribution_a_modifier;
    }
    
    private function handleAttributionSubmit($postData) {
        $groupeId = $postData['id_GU'];
        $selectedTraitements = isset($postData['traitements']) ? $postData['traitements'] : [];
        
        try {
            // Supprimer toutes les attributions existantes pour ce groupe
            $this->attribution->deleteAttribution($groupeId);
            
            // Ajouter les nouvelles attributions
            foreach ($selectedTraitements as $traitementId) {
                $this->attribution->ajouterAttribution($groupeId, $traitementId);
            }

            $this->auditLog->logModification($_SESSION['id_utilisateur'], 'attribution', 'Succès');
            // Rediriger avec un message de succès
            header('Location: ?page=parametres_generaux&action=gestion_attribution&groupe=' . $groupeId . '&success=1');
            exit;
        } catch (Exception $e) {
            $this->auditLog->logModification($_SESSION['id_utilisateur'], 'attribution', 'Erreur');
            // Rediriger avec un message d'erreur
            header('Location: ?page=parametres_generaux&action=gestion_attribution&groupe=' . $groupeId . '&error=1');
            exit;
        }
    }
}
    

//==============================FIN GESTION ATTRIBUTION==============================



/*Ce fichier est le contrôleur principal pour la gestion des paramètres généraux de l'application.
    Il gère les actions liées aux entités telles que les années académiques, les grades, les ECUE, etc.
    Chaque méthode correspond à une fonctionnalité spécifique et interagit avec le modèle approprié. */