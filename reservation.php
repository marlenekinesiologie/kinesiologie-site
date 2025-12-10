<?php
header('Content-Type: application/json');

// Récupérer les données POST
$nom = $_POST['nom'] ?? '';
$prenom = $_POST['prenom'] ?? '';
$email = $_POST['email'] ?? '';
$telephone = $_POST['telephone'] ?? '';
$datetime = $_POST['date'] ?? '';
$message = $_POST['message'] ?? '';

// Vérifier que tous les champs sont remplis
if (!$nom || !$prenom || !$email || !$telephone || !$datetime) {
    echo json_encode(['success'=>false, 'msg'=>'Veuillez remplir tous les champs.']);
    exit;
}

// Fichier JSON pour stocker les réservations
$file = 'reservations.json';
$reservations = [];
if(file_exists($file)){
    $reservations = json_decode(file_get_contents($file), true);
}

// Vérifier si date déjà réservée
foreach($reservations as $r){
    if($r['date'] === $datetime){
        echo json_encode(['success'=>false, 'msg'=>'Cette date est déjà réservée']);
        exit;
    }
}

// Ajouter réservation
$reservations[] = [
    'nom'=>$nom,
    'prenom'=>$prenom,
    'email'=>$email,
    'telephone'=>$telephone,
    'date'=>$datetime,
    'message'=>$message
];
file_put_contents($file, json_encode($reservations, JSON_PRETTY_PRINT));

// Envoyer un email
$to = "ton-email@example.com"; // <-- mets ton email ici
$subject = "Nouvelle réservation: $prenom $nom";
$body = "Nouvelle réservation:\n\nNom: $nom\nPrénom: $prenom\nEmail: $email\nTéléphone: $telephone\nDate: $datetime\nMessage: $message";
$headers = "From: noreply@ton-site.com";

@mail($to, $subject, $body, $headers);

// Réponse JSON
echo json_encode(['success'=>true]);
?>
