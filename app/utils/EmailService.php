<?php
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/../../vendor/autoload.php';

class EmailService {
    private $mailer;

    public function __construct() {
        $this->mailer = new PHPMailer(true);
        $this->configureMailer();
    }

    private function configureMailer() {
        try {
            // Charger la configuration SMTP
            $config = require __DIR__ . '/../config/email.php';
            
            // Configuration du serveur SMTP
            $this->mailer->isSMTP();
            $this->mailer->Host = $config['smtp']['host'];
            if (!is_null($config['smtp']['username']) && !is_null($config['smtp']['password'])) {
                $this->mailer->SMTPAuth = true;
                $this->mailer->Username = $config['smtp']['username'];
                $this->mailer->Password = $config['smtp']['password'];
            } else {
                $this->mailer->SMTPAuth = false;
            }

            if (!is_null($config['smtp']['encryption'])) {
                $this->mailer->SMTPSecure = $config['smtp']['encryption'];
            } else {
                $this->mailer->SMTPSecure = false; // Désactive explicitement le chiffrement
            }
            $this->mailer->Port = $config['smtp']['port'];
            $this->mailer->CharSet = 'UTF-8';

            // Configuration de l'expéditeur
            $this->mailer->setFrom($config['smtp']['from_email'], $config['smtp']['from_name']);
            
        } catch (Exception $e) {
            error_log("Erreur de configuration PHPMailer: " . $e->getMessage());
        }
    }

    public function sendEmail($to, $subject, $message, $isHTML = false) {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();
            $this->mailer->addAddress($to);
            $this->mailer->Subject = $subject;
            
            if ($isHTML) {
                $this->mailer->isHTML(true);
                $this->mailer->Body = $message;
                $this->mailer->AltBody = strip_tags($message);
            } else {
                $this->mailer->Body = $message;
            }

            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Erreur d'envoi d'email: " . $e->getMessage());
            return false;
        }
    }

    public function sendEmailWithAttachment($to, $subject, $message, $attachmentPath, $attachmentName = null, $isHTML = false) {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();
            $this->mailer->addAddress($to);
            $this->mailer->Subject = $subject;
            
            if ($isHTML) {
                $this->mailer->isHTML(true);
                $this->mailer->Body = $message;
                $this->mailer->AltBody = strip_tags($message);
            } else {
                $this->mailer->Body = $message;
            }

            // Ajouter la pièce jointe
            if (file_exists($attachmentPath)) {
                $this->mailer->addAttachment($attachmentPath, $attachmentName);
            } else {
                error_log("Fichier pièce jointe non trouvé: " . $attachmentPath);
            }

            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Erreur d'envoi d'email avec pièce jointe: " . $e->getMessage());
            return false;
        }
    }

    public function sendResultEmail($studentEmail, $studentName, $resume, $decision) {
        $subject = "Résultat de votre candidature à la soutenance";
        
        // Message HTML
        $htmlMessage = $this->generateHTMLResultMessage($studentName, $resume, $decision);
        
        // Message texte simple
        $textMessage = $this->generateTextResultMessage($studentName, $resume, $decision);
        
        // Envoyer en HTML
        $success = $this->sendEmail($studentEmail, $subject, $htmlMessage, true);
        
        if (!$success) {
            // Si l'envoi HTML échoue, essayer en texte simple
            return $this->sendEmail($studentEmail, $subject, $textMessage, false);
        }
        
        return $success;
    }

    private function generateHTMLResultMessage($studentName, $resume, $decision) {
        $statusColor = ($decision === 'Validée') ? '#10B981' : '#EF4444';
        $statusIcon = ($decision === 'Validée') ? '🎉' : '❌';
        
        $html = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
                .status { background-color: {$statusColor}; color: white; padding: 10px 20px; border-radius: 5px; display: inline-block; }
                .step { margin: 15px 0; padding: 15px; border-left: 4px solid #ddd; }
                .step.validé { border-left-color: #10B981; background-color: #f0fdf4; }
                .step.rejeté { border-left-color: #EF4444; background-color: #fef2f2; }
                .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
                .badge.validé { background-color: #10B981; color: white; }
                .badge.rejeté { background-color: #EF4444; color: white; }
                .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 14px; color: #666; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Résultat de votre candidature à la soutenance</h2>
                    <p>Bonjour {$studentName},</p>
                    <p>L'évaluation de votre candidature à la soutenance est terminée.</p>
                </div>
                
                <div class='status'>
                    <h3>{$statusIcon} Décision finale : {$decision}</h3>
                </div>
                
                <h4>Résumé détaillé de l'évaluation :</h4>";
        
        foreach ($resume as $etape => $data) {
            $etapeName = ucfirst($etape);
            $validation = $data['validation'];
            $badgeClass = ($validation === 'validé') ? 'validé' : 'rejeté';
            $stepClass = ($validation === 'validé') ? 'validé' : 'rejeté';
            
            $html .= "
                <div class='step {$stepClass}'>
                    <h5>{$etapeName}</h5>
                    <p><strong>Validation :</strong> <span class='badge {$badgeClass}'>" . strtoupper($validation) . "</span></p>";
            
            // Ajouter les détails spécifiques à chaque étape
            if ($etape === 'scolarite') {
                $html .= "<p><strong>Statut :</strong> {$data['statut']}</p>";
                $html .= "<p><strong>Montant total :</strong> {$data['montant_total']}</p>";
                $html .= "<p><strong>Montant payé :</strong> {$data['montant_paye']}</p>";
            } elseif ($etape === 'stage') {
                $html .= "<p><strong>Entreprise :</strong> {$data['entreprise']}</p>";
                $html .= "<p><strong>Sujet :</strong> {$data['sujet']}</p>";
                $html .= "<p><strong>Période :</strong> {$data['periode']}</p>";
            } elseif ($etape === 'semestre') {
                $html .= "<p><strong>Semestre :</strong> {$data['semestre']}</p>";
                $html .= "<p><strong>Moyenne :</strong> {$data['moyenne']}</p>";
                $html .= "<p><strong>Unités validées :</strong> {$data['unites']}</p>";
            }
            
            $html .= "</div>";
        }
        
        if ($decision === 'Validée') {
            $html .= "
                <div style='background-color: #f0fdf4; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                    <p><strong>🎉 Félicitations !</strong> Votre candidature a été validée. Vous pouvez maintenant procéder à la rédaction de votre rapport.</p>
                </div>";
        } else {
            $html .= "
                <div style='background-color: #fef2f2; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                    <p><strong>❌ Votre candidature a été rejetée.</strong></p>
                    <p>Veuillez corriger les problèmes identifiés et soumettre une nouvelle candidature.</p>
                    <p>Pour toute question, contactez le service pédagogique.</p>
                </div>";
        }
        
        $html .= "
                <div class='footer'>
                    <p>Cordialement,<br>L'équipe pédagogique</p>
                </div>
            </div>
        </body>
        </html>";
        
        return $html;
    }

    private function generateTextResultMessage($studentName, $resume, $decision) {
        $message = "Bonjour {$studentName},\n\n";
        $message .= "L'évaluation de votre candidature à la soutenance est terminée.\n\n";
        
        if ($decision === 'Validée') {
            $message .= "🎉 FÉLICITATIONS ! Votre candidature a été VALIDÉE.\n\n";
        } else {
            $message .= "❌ Votre candidature a été REJETÉE.\n\n";
        }
        
        $message .= "RÉSUMÉ DÉTAILLÉ DE L'ÉVALUATION :\n";
        $message .= "==================================\n\n";
        
        foreach ($resume as $etape => $data) {
            $etapeName = ucfirst($etape);
            $validation = $data['validation'];
            $status = ($validation === 'validé') ? '✅' : '❌';
            
            $message .= "{$status} {$etapeName} : " . strtoupper($validation) . "\n";
        }
        
        $message .= "\n";
        
        if ($decision === 'Validée') {
            $message .= "Vous pouvez maintenant procéder à votre soutenance.\n";
            $message .= "Vous recevrez bientôt les détails de l'organisation.\n";
        } else {
            $message .= "Veuillez corriger les problèmes identifiés et soumettre une nouvelle candidature.\n";
            $message .= "Pour toute question, contactez le service pédagogique.\n";
        }
        
        $message .= "\nCordialement,\nL'équipe pédagogique";
        
        return $message;
    }
} 