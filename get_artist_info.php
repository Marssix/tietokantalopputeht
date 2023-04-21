<?php
require "dbconnection.php";
$dbcon = createDbConnection();

if (isset($_GET['artist_id'])) {
    $artist_id = $_GET['artist_id'];

    $stmt = $dbcon->prepare(
        "SELECT artists.Name AS artist_name, albums.AlbumId, albums.Title AS album_title, tracks.Name AS track_name
         FROM artists
         INNER JOIN albums ON artists.ArtistId = albums.ArtistId
         INNER JOIN tracks ON albums.AlbumId = tracks.AlbumId
         WHERE artists.ArtistId = ?
         ORDER BY albums.AlbumId, tracks.TrackId"
    );

    $stmt->execute([$artist_id]);
    $result = $stmt->fetchAll();

    if (count($result) > 0) {
        $artist_name = $result[0]['artist_name'];
        $albums = array();
        $current_album_id = null;
        foreach ($result as $row) {
            if ($row['AlbumId'] !== $current_album_id) {
                $current_album_id = $row['AlbumId'];
                $album = array(
                    "id" => $row['AlbumId'],
                    "title" => $row['album_title'],
                    "tracks" => array()
                );
                $albums[] = $album;
            }
            $album["tracks"][] = $row['track_name'];
        }
        $response = array(
            "artist" => $artist_name,
            "albums" => $albums
        );
        echo json_encode($response);
    } else {
        echo json_encode(array("error" => "Artist not found."));
    }
} else {
    echo json_encode(array("error" => "artist_id is not provided."));
}
?>
