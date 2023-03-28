<?php
require "dbconnection.php";
$dbcon = createDbConnection();

$data = json_decode(file_get_contents('php://input'), true);
$artist_name = $data['artist_name'];
$album_name = $data['album_name'];
$tracks = $data['tracks'];

$stmt = $pdo->prepare('INSERT INTO artists (Name) VALUES (?)');
$stmt->execute([$artist_name]);

$artist_id = $pdo->lastInsertId();

$stmt = $pdo->prepare('INSERT INTO albums (Title, ArtistId) VALUES (?, ?)');
$stmt->execute([$album_name, $artist_id]);

$album_id = $pdo->lastInsertId();

foreach ($tracks as $track) {
    $track_name = $track['name'];
    $stmt = $pdo->prepare('INSERT INTO tracks (Name, AlbumId) VALUES (?, ?)');
    $stmt->execute([$track_name, $album_id]);
}

echo "Uusi artisti, albumi ja kappaleet lisätty onnistuneesti!";
?>