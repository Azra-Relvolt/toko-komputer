<?php
include 'db.php';

$id_invoice = $_GET['id_invoice'] ?? null;

if ($id_invoice) {
    $stmt = $pdo->prepare('
        SELECT 
            transaksi.Tgl_Transaksi,
            pelanggan.Nama_Pelanggan,
            toko.Nama_Toko,
            produk.Kode_Produk, 
            produk.Nama_Produk, 
            produk.Harga_Produk, 
            detailtransaksi.Jumlah_Produk, 
            (produk.Harga_Produk * detailtransaksi.Jumlah_Produk) AS Total_Harga
        FROM detailtransaksi
        JOIN transaksi ON detailtransaksi.ID_Invoice = transaksi.ID_Invoice
        JOIN produk ON detailtransaksi.Kode_Produk = produk.Kode_Produk
        JOIN pelanggan ON transaksi.ID_Pelanggan = pelanggan.ID_Pelanggan
        JOIN toko ON transaksi.ID_Toko = toko.ID_Toko
        WHERE detailtransaksi.ID_Invoice = ?
    ');
    $stmt->execute([$id_invoice]);
    $purchases = $stmt->fetchAll();

    // Calculate the overall total price
    $overall_total = 0;
    foreach ($purchases as $purchase) {
        $overall_total += $purchase['Total_Harga'];
    }

    // Get transaction date, customer name, and store name
    $transaction_date = $purchases[0]['Tgl_Transaksi'] ?? null;
    $customer_name = $purchases[0]['Nama_Pelanggan'] ?? null;
    $store_name = $purchases[0]['Nama_Toko'] ?? null;
} else {
    $purchases = [];
    $overall_total = 0;
    $transaction_date = null;
    $customer_name = null;
    $store_name = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Invoice Details</h1>
        <a href="select_invoice.php" class="btn btn-secondary mb-3">Back to Select Invoice</a>
        <?php if ($purchases): ?>
            <div class="d-flex justify-content-between">
                <div>
                    <h3>Invoice ID: <?= htmlspecialchars($id_invoice) ?></h3>
                    <h4>Nama Pelanggan: <?= htmlspecialchars($customer_name) ?></h4>
                </div>
                <div>
                    <h4>Tanggal Transaksi: <?= htmlspecialchars($transaction_date) ?></h4>
                    <h4>Nama Toko: <?= htmlspecialchars($store_name) ?></h4>
                </div>
            </div>
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($purchases as $purchase): ?>
                        <tr>
                            <td><?= htmlspecialchars($purchase['Kode_Produk']) ?></td>
                            <td><?= htmlspecialchars($purchase['Nama_Produk']) ?></td>
                            <td><?= number_format($purchase['Harga_Produk'], 2) ?></td>
                            <td><?= htmlspecialchars($purchase['Jumlah_Produk']) ?></td>
                            <td><?= number_format($purchase['Total_Harga'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-right">Keseluruhan Total Harga:</th>
                        <th><?= number_format($overall_total, 2) ?></th>
                    </tr>
                </tfoot>
            </table>
        <?php else: ?>
            <p>No purchases found for this Invoice ID.</p>
        <?php endif; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>