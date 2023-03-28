<?php
require "dbconnection.php";
$dbcon = createDbConnection();

if (isset($_GET['playlist_id'])) {
    $playlist_id = $_GET['playlist_id'];

    $stmt = $dbcon->prepare(
        "SELECT tracks.Name AS track_name, artists.Name AS artist_name
         FROM tracks INNER JOIN albums ON tracks.AlbumId = albums.AlbumId
          INNER JOIN artists ON albums.ArtistId = artists.ArtistId
           INNER JOIN playlist_track ON tracks.TrackId = playlist_track.TrackId
            WHERE playlist_track.PlaylistId = ?"
    );

    $stmt->execute([$playlist_id]);
    $result = $stmt->fetchAll();

    if (count($result) > 0) {
        foreach ($result as $row) {
            echo $row['track_name'] . " - " . $row['artist_name'] . "<br>";
        }
    } else {
        echo "Soittolistaa ei löytynyt.";
    }
} else {
    echo "playlist_id:tä ei ole annettu.";
}
?>