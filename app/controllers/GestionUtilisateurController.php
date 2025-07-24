<?php


require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . "/../models/Utilisateur.php";
require_once __DIR__ . "/../models/TypeUtilisateur.php";
require_once __DIR__ . "/../models/GroupeUtilisateur.php";
require_once __DIR__ . "/../models/NiveauAccesDonnees.php";
require_once __DIR__ . "/../models/AuditLog.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../vendor/autoload.php';

class GestionUtilisateurController
{
    private $utilisateur;
    private $baseViewPath;

    private $typeUtilisateur;

    private $groupeUtilisateur;

    private $niveauAcces;
    private $auditLog;



    public function __construct()
    {

        $this->baseViewPath = __DIR__ . '/../../ressources/views/';
        $this->utilisateur = new Utilisateur(Database::getConnection());
        $this->groupeUtilisateur = new GroupeUtilisateur(Database::getConnection());
        $this->typeUtilisateur = new TypeUtilisateur(Database::getConnection());
        $this->niveauAcces = new NiveauAccesDonnees(Database::getConnection());
        $this->auditLog = new AuditLog(Database::getConnection());

    }

    // Afficher la liste des étudiants
// Fichier : app/controllers/GestionUtilisateurController.php

    public function index()
    {
        $utilisateur_a_modifier = null;
        $messageErreur = '';
        $messageSuccess = '';
        $action = $_GET['action'] ?? '';

        try {
            // ---- 1. DÉTERMINATION DU RÔLE DE L'UTILISATEUR CONNECTÉ ----
            // L'ID du groupe "Responsable scolarité" est 8
            $isScolariteManager = (isset($_SESSION['id_GU']) && $_SESSION['id_GU'] == 8);

            // ---- 2. FILTRAGE DES DONNÉES EN FONCTION DU RÔLE ----
            if ($isScolariteManager) {
                // Un gestionnaire de scolarité ne voit et ne gère QUE les étudiants
                $enseignantsNonUtilisateurs = [];
                $personnelNonUtilisateurs = [];
                $etudiantsNonUtilisateurs = $this->utilisateur->getEtudiantsMaster2NonUtilisateurs();
                $allUtilisateurs = $this->utilisateur->getAllUtilisateursByType('Etudiant');
                $GLOBALS['pageTitle'] = "Gestion des Comptes Étudiants"; // Titre de page personnalisé
            } else {
                // Un administrateur voit tout le monde
                $enseignantsNonUtilisateurs = $this->utilisateur->getEnseignantsNonUtilisateurs();
                $personnelNonUtilisateurs = $this->utilisateur->getPersonnelNonUtilisateurs();
                $etudiantsNonUtilisateurs = $this->utilisateur->getEtudiantsMaster2NonUtilisateurs();
                $allUtilisateurs = $this->utilisateur->getAllUtilisateurs();
                $GLOBALS['pageTitle'] = "Gestion des Utilisateurs";
            }

            // ---- 3. GESTION DES ACTIONS GET (AFFICHAGE DES MODALES) ----
            if ($action === 'edit' && isset($_GET['id_utilisateur'])) {
                $utilisateur_a_modifier = $this->utilisateur->getUtilisateurById($_GET['id_utilisateur']);
                if (!$utilisateur_a_modifier) {
                    $messageErreur = "Utilisateur non trouvé.";
                } else {
                    // Sécurité : Un gestionnaire scolarité ne peut modifier qu'un étudiant
                    $userType = $this->utilisateur->getLibelleTypeUtilisateur($utilisateur_a_modifier->id_utilisateur);
                    if ($isScolariteManager && $userType !== 'Etudiant') {
                        $messageErreur = "Accès non autorisé : vous ne pouvez modifier que les comptes étudiants.";
                        $utilisateur_a_modifier = null; // Empêche l'affichage des données
                    }
                }
            } elseif ($action === 'add') {
                $utilisateur_a_modifier = (object)[
                    'id_utilisateur' => '', 'nom_utilisateur' => '', 'login_utilisateur' => '',
                    'id_type_utilisateur' => '', 'statut_utilisateur' => 'Actif', 'id_GU' => '',
                    'id_niv_acces_donnee' => ''
                ];
            }

            // ---- 4. GESTION DES ACTIONS POST (SOUMISSION DE FORMULAIRES) ----
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // -- Action: Ajouter un utilisateur
                if (isset($_POST['btn_add_utilisateur'])) {
                    $id_type_utilisateur = $_POST['id_type_utilisateur'] ?? '';
                    $typeUtilisateurLibelle = $this->typeUtilisateur->getTypeUtilisateurById($id_type_utilisateur)->lib_type_utilisateur ?? '';

                    // Sécurité : le gestionnaire scolarité ne peut ajouter que des étudiants
                    if ($isScolariteManager && $typeUtilisateurLibelle !== 'Etudiant') {
                        $messageErreur = "Action non autorisée. Vous ne pouvez créer que des comptes étudiants.";
                    } else {
                        $nom_utilisateur = $_POST['nom_utilisateur'] ?? '';
                        $id_GU = $_POST['id_GU'] ?? '';
                        $login_utilisateur = $_POST['login_utilisateur'] ?? '';
                        $statut_utilisateur = $_POST['statut_utilisateur'] ?? '';
                        $id_niveau_acces = $_POST['id_niveau_acces'] ?? '';

                        if (empty($nom_utilisateur) || empty($id_type_utilisateur) || empty($id_GU) || empty($login_utilisateur) || empty($statut_utilisateur) || empty($id_niveau_acces)) {
                            $messageErreur = "Tous les champs sont obligatoires.";
                        } elseif ($this->utilisateur->isLoginUsed($login_utilisateur)) {
                            $messageErreur = "Ce login (email) est déjà utilisé par un autre utilisateur.";
                        } else {
                            $mdp = $this->generateRandomPassword();
                            $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);

                            if ($this->utilisateur->ajouterUtilisateur($nom_utilisateur, $id_type_utilisateur, $id_GU, $id_niveau_acces, $statut_utilisateur, $login_utilisateur, $mdp_hash)) {
                                if ($this->envoyerEmailInscriptionPHPMailer($login_utilisateur, $nom_utilisateur, $login_utilisateur, $mdp)) {
                                    $messageSuccess = "Utilisateur ajouté avec succès et email envoyé.";
                                    $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'utilisateur', 'Succès');
                                } else {
                                    $messageErreur = "Utilisateur ajouté, mais l'envoi de l'email a échoué.";
                                    $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'utilisateur', 'Erreur Email');
                                }
                            } else {
                                $messageErreur = "Erreur lors de l'ajout de l'utilisateur.";
                                $this->auditLog->logCreation($_SESSION['id_utilisateur'], 'utilisateur', 'Erreur');
                            }
                        }
                    }
                }

                // -- Action: Modifier un utilisateur
                if (isset($_POST['btn_modifier_utilisateur'])) {
                    $id_utilisateur = $_POST['id_utilisateur'] ?? '';
                    $id_type_utilisateur = $_POST['id_type_utilisateur'] ?? '';
                    $typeUtilisateurLibelle = $this->typeUtilisateur->getTypeUtilisateurById($id_type_utilisateur)->lib_type_utilisateur ?? '';

                    // Sécurité : le gestionnaire scolarité ne peut modifier que des étudiants
                    if ($isScolariteManager && $typeUtilisateurLibelle !== 'Etudiant') {
                        $messageErreur = "Action non autorisée. Vous ne pouvez modifier que des comptes étudiants.";
                    } else {
                        $nom_utilisateur = $_POST['nom_utilisateur'] ?? '';
                        $id_GU = $_POST['id_GU'] ?? '';
                        $login_utilisateur = $_POST['login_utilisateur'] ?? '';
                        $statut_utilisateur = $_POST['statut_utilisateur'] ?? '';
                        $id_niveau_acces = $_POST['id_niveau_acces'] ?? '';

                        if (empty($id_utilisateur) || empty($nom_utilisateur) || empty($id_type_utilisateur) || empty($id_GU) || empty($login_utilisateur) || empty($statut_utilisateur) || empty($id_niveau_acces)) {
                            $messageErreur = "Tous les champs sont obligatoires.";
                        } else {
                            if ($this->utilisateur->updateUtilisateur($nom_utilisateur, $id_type_utilisateur, $id_GU, $id_niveau_acces, $statut_utilisateur, $login_utilisateur, $id_utilisateur)) {
                                $messageSuccess = "Utilisateur modifié avec succès.";
                                $this->auditLog->logModification($_SESSION['id_utilisateur'], 'utilisateur', 'Succès');
                            } else {
                                $messageErreur = "Erreur lors de la modification de l'utilisateur.";
                                $this->auditLog->logModification($_SESSION['id_utilisateur'], 'utilisateur', 'Erreur');
                            }
                        }
                    }
                }

                // -- Action: Désactiver / Réactiver
                if (isset($_POST['selected_ids'])) {
                    $success = true;
                    foreach ($_POST['selected_ids'] as $id) {
                        $userToModify = $this->utilisateur->getUtilisateurById($id);
                        $userTypeToModify = $this->utilisateur->getLibelleTypeUtilisateur($userToModify->id_type_utilisateur);

                        // Sécurité : le gestionnaire scolarité ne peut agir que sur des étudiants
                        if ($isScolariteManager && $userTypeToModify !== 'Etudiant') {
                            $messageErreur = "Action non autorisée sur un compte non-étudiant.";
                            $success = false;
                            break;
                        }

                        if (isset($_POST['submit_enable_multiple']) && $_POST['submit_enable_multiple'] == 3) {
                            if (!$this->utilisateur->reactiverUtilisateur($id)) $success = false;
                        } elseif (isset($_POST['submit_disable_multiple']) && $_POST['submit_disable_multiple'] == 2) {
                            if (!$this->utilisateur->desactiverUtilisateur($id)) $success = false;
                        }
                    }

                    if ($success && !$messageErreur) {
                        $actionText = (isset($_POST['submit_enable_multiple'])) ? 'activés' : 'désactivés';
                        $messageSuccess = "Utilisateurs $actionText avec succès.";
                        $this->auditLog->logModification($_SESSION['id_utilisateur'], 'utilisateur', 'Succès');
                    } elseif (!$success && !$messageErreur) {
                        $actionText = (isset($_POST['submit_enable_multiple'])) ? 'activation' : 'désactivation';
                        $messageErreur = "Erreur lors de l'{$actionText} des utilisateurs.";
                        $this->auditLog->logModification($_SESSION['id_utilisateur'], 'utilisateur', 'Erreur');
                    }
                }
            }
        } catch (Exception $e) {
            $messageErreur = "Une erreur inattendue est survenue : " . $e->getMessage();
        }

        // ---- 5. PRÉPARATION DES DONNÉES FINALES POUR LA VUE ----
        $GLOBALS['messageErreur'] = $messageErreur;
        $GLOBALS['messageSuccess'] = $messageSuccess;
        $GLOBALS['utilisateurs'] = $allUtilisateurs; // La liste déjà filtrée
        $GLOBALS['types_utilisateur'] = $this->typeUtilisateur->getAllTypeUtilisateur();
        $GLOBALS['groupes_utilisateur'] = $this->groupeUtilisateur->getAllGroupeUtilisateur();
        $GLOBALS['niveau_acces'] = $this->niveauAcces->getAllNiveauxAccesDonnees();
        $GLOBALS['utilisateur_a_modifier'] = $utilisateur_a_modifier;
        $GLOBALS['action'] = $action;
        $GLOBALS['enseignantsNonUtilisateurs'] = $enseignantsNonUtilisateurs;
        $GLOBALS['personnelNonUtilisateurs'] = $personnelNonUtilisateurs;
        $GLOBALS['etudiantsNonUtilisateurs'] = $etudiantsNonUtilisateurs;
        $GLOBALS['isScolariteManager'] = $isScolariteManager; // Important pour la vue
    }


    // Fonction pour générer un mot de passe aléatoire
    function generateRandomPassword($length = 12)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $password;
    }

    function construireMessageHTML($nom, $login, $motDePasse)
    {
        // Construction du sujet
        $sujet = "Bienvenue sur Soutenance Manager, " . htmlspecialchars($nom) . " !";

        // Construction du corps du message HTML
        $message = '
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <title>Bienvenue</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #10b981; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background-color: #f9fafb; }
                .footer { margin-top: 20px; padding: 10px; text-align: center; font-size: 12px; color: #6b7280; }
                .button {
                    display: inline-block; padding: 10px 20px; background-color: #10b981; 
                    color: white; text-decoration: none; border-radius: 5px; margin: 15px 0;
                }
                .credentials { background-color: #e5e7eb; padding: 15px; border-radius: 5px; margin: 15px 0; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1> '.htmlspecialchars($sujet).'</h1>
                </div>
                
                <div class="content">
                    <p>Bonjour ' . htmlspecialchars($nom) . ',</p>
                    <p>Votre compte a été créé avec succès sur notre plateforme.</p>
                    
                    <div class="credentials">
                        <p><strong>Identifiant de connexion:</strong> ' . htmlspecialchars($login) . '</p>';
        
                      // Ajout du mot de passe temporaire si fourni
                   if ($motDePasse) {
                $message .= '<p><strong>Mot de passe temporaire:</strong> ' . htmlspecialchars($motDePasse) . '</p>
                        <p style="color: #ef4444; font-size: 0.9em;">
                            Pour des raisons de sécurité, nous vous recommandons de changer ce mot de passe après votre première connexion.
                        </p>';
                      }
        
                $message .= '
                    </div>
                    
                    <p>Vous pouvez dès maintenant vous connecter à votre compte :</p>
                    <a href="http://localhost:8080/page_connexion.php" class="button " style="color:#fff">Se connecter</a>
                    
                    <p>Si vous n\'êtes pas à l\'origine de cette création de compte, veuillez ignorer cet email ou contacter notre support.</p>
                </div>
                
                <div class="footer">
                    <p>© ' . date('Y') . ' Soutenance Manager. Tous droits réservés.</p>
                </div>
            </div>
        </body>
        </html>';


        return $message;
    }


    function envoyerEmailInscriptionPHPMailer($email, $nom, $login, $motDePasse)
    {
        $mail = new PHPMailer(true);

        try {
            // Configuration du serveur SMTP
            $mail->SMTPDebug = 2; // Active le débogage détaillé
            $mail->Debugoutput = function($str, $level) {
                error_log("PHPMailer Debug: $str");
            };
            
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'managersoutenance@gmail.com';
            $mail->Password = 'iweglnpanhpkoqfe';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';

            // Destinataires
            $mail->setFrom('managersoutenance@gmail.com', 'Soutenance Manager'); // Utiliser une adresse email valide
            $mail->addAddress($email, $nom);
            $mail->addReplyTo('managersoutenance@gmail.com', 'Support technique');

            // Contenu
            $mail->isHTML(true);
            $mail->Subject = "Bienvenue sur notre plateforme, $nom !";

            // Construction du message HTML
            $message = $this->construireMessageHTML($nom, $login, $motDePasse);
            $mail->Body = $message;
            $mail->AltBody = strip_tags($message);

            error_log("Tentative d'envoi d'email à : " . $email);
            $result = $mail->send();
            error_log("Email envoyé avec succès à : " . $email);
            return true;
        } catch (Exception $e) {
            error_log("Erreur PHPMailer détaillée: " . $e->getMessage());
            error_log("Erreur PHPMailer: {$mail->ErrorInfo}");
            return false;
        }
    }

   

}