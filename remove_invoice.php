<?php
require "dbconnection.php";
$dbcon = createDbConnection();

if (isset($_GET['invoice_id'])) {
    $invoice_id = $_GET['invoice_id'];

    $stmt = $dbcon->prepare("DELETE FROM invoice_items WHERE InvoiceId = ?");
    $stmt->execute([$invoice_id]);

    echo "Poistettu onnistuneesti.";
} else {
    echo "invoice_id ei ole annettu.";
}
?>