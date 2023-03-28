<?php
require "dbconnection.php";
$dbcon = createDbConnection();

if (isset($_GET['artist_id'])) {
    $artist_id = $_GET['artist_id'];

    try {
        $dbcon->beginTransaction();

        $stmt1 = $dbcon->prepare("DELETE FROM invoice_items WHERE TrackId IN (SELECT TrackId FROM tracks WHERE AlbumId IN (SELECT AlbumId FROM albums WHERE ArtistId = ?))");
        $stmt1->execute([$artist_id]);
        $stmt2 = $dbcon->prepare("DELETE FROM tracks WHERE AlbumId IN (SELECT AlbumId FROM albums WHERE ArtistId = ?)");
        $stmt2->execute([$artist_id]);
        $stmt3 = $dbcon->prepare("DELETE FROM albums WHERE ArtistId = ?");
        $stmt3->execute([$artist_id]);
        $stmt4 = $dbcon->prepare("DELETE FROM artists WHERE ArtistId = ?");
        $stmt4->execute([$artist_id]);

        $dbcon->commit();

        echo "Artisti ja siihen liittyvät tiedot on poistettu onnistuneesti.";
    } catch (PDOException $e) {
        $dbcon->rollBack();
        echo "Virhe: " . $e->getMessage();
    }
} else {
    echo "artist_id:tä ei ole annettu.";
}