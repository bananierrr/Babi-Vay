<?php
// Configuration email
$destinataire = "tanguyboura@hotmail.fr"; // REMPLACEZ par votre email
$sujet = "Nouvelle demande de réservation - Villa Méditerranée";

// Vérifier si le formulaire a été soumis
if ($_POST) {
    
    // Récupérer et nettoyer les données
    $nom = htmlspecialchars(trim($_POST['nom']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $telephone = htmlspecialchars(trim($_POST['telephone']));
    $dates = htmlspecialchars(trim($_POST['dates']));
    $personnes = htmlspecialchars(trim($_POST['personnes']));
    $services = htmlspecialchars(trim($_POST['services']));
    $message = htmlspecialchars(trim($_POST['message']));
    
    // Validation des champs obligatoires
    if (empty($nom) || empty($email)) {
        echo "<script>alert('Veuillez remplir tous les champs obligatoires.'); history.back();</script>";
        exit;
    }
    
    // Validation de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Adresse email non valide.'); history.back();</script>";
        exit;
    }
    
    // Construire le contenu de l'email
    $contenu = "=== NOUVELLE DEMANDE DE RÉSERVATION ===\n\n";
    $contenu .= "Nom : $nom\n";
    $contenu .= "Email : $email\n";
    $contenu .= "Téléphone : " . ($telephone ? $telephone : "Non renseigné") . "\n";
    $contenu .= "Dates souhaitées : " . ($dates ? $dates : "Non renseignées") . "\n";
    $contenu .= "Nombre de personnes : " . ($personnes ? $personnes : "Non renseigné") . "\n\n";
    
    if (!empty($services)) {
        $contenu .= "Services supplémentaires :\n$services\n\n";
    }
    
    if (!empty($message)) {
        $contenu .= "Message :\n$message\n\n";
    }
    
    $contenu .= "---\nDemande envoyée le : " . date('d/m/Y à H:i') . "\n";
    $contenu .= "Adresse IP : " . $_SERVER['REMOTE_ADDR'] . "\n";
    
    // En-têtes de l'email
    $headers = array(
        'From' => $email,
        'Reply-To' => $email,
        'X-Mailer' => 'PHP/' . phpversion(),
        'Content-Type' => 'text/plain; charset=utf-8'
    );
    
    // Convertir les en-têtes en format string
    $headers_string = "";
    foreach ($headers as $key => $value) {
        $headers_string .= "$key: $value\r\n";
    }
    
    // Envoyer l'email
    if (mail($destinataire, $sujet, $contenu, $headers_string)) {
        // Succès - rediriger avec message
        echo "<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <title>Message envoyé - Villa Méditerranée</title>
    <style>
        body { font-family: Georgia, serif; text-align: center; padding: 50px; background: #f8f9fa; }
        .success { background: white; padding: 40px; border-radius: 15px; max-width: 500px; margin: 0 auto; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .success h1 { color: #27ae60; }
        .btn { display: inline-block; padding: 15px 30px; background: linear-gradient(45deg, #ff6b6b, #ee5a24); color: white; text-decoration: none; border-radius: 8px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class='success'>
        <h1>✓ Message envoyé avec succès !</h1>
        <p>Merci <strong>$nom</strong> pour votre demande. Nous vous recontacterons rapidement à l'adresse <strong>$email</strong>.</p>
        <a href='index.html' class='btn'>Retour à l'accueil</a>
    </div>
</body>
</html>";
    } else {
        // Erreur - rediriger avec message d'erreur
        echo "<script>alert('Erreur lors de l\\'envoi du message. Veuillez réessayer ou nous contacter directement.'); history.back();</script>";
    }
    
} else {
    // Accès direct au fichier PHP sans données POST
    header('Location: index.html');
    exit;
}
?>