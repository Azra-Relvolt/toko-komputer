<?php
include 'db.php';

$id_invoice = $_POST['id_invoice'] ?? null;
$tgl_transaksi = $_POST['tgl_transaksi'] ?? null;
$kurir = $_POST['kurir'] ?? null;
$id_pelanggan = $_POST['id_pelanggan'] ?? null;
$id_toko = $_POST['id_toko'] ?? null;
$kode_produk = $_POST['kode_produk'] ?? [];
$jumlah_produk = $_POST['jumlah_produk'] ?? [];
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'add' && $id_invoice && $tgl_transaksi && $kurir && $id_pelanggan && $id_toko && !empty($kode_produk) && !empty($jumlah_produk)) {
        $pdo->beginTransaction();
        try {
            // Insert into transaksi table
            $stmt = $pdo->prepare('INSERT INTO transaksi (ID_Invoice, Tgl_Transaksi, Kurir, ID_Pelanggan, ID_Toko) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$id_invoice, $tgl_transaksi, $kurir, $id_pelanggan, $id_toko]);
            
            // Insert into detailtransaksi table
            $stmt = $pdo->prepare('INSERT INTO detailtransaksi (ID_Invoice, Kode_Produk, Jumlah_Produk) VALUES (?, ?, ?)');
            for ($i = 0; $i < count($kode_produk); $i++) {
                $stmt->execute([$id_invoice, $kode_produk[$i], $jumlah_produk[$i]]);
            }
            
            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            $error_message = "Gagal Menambah Data";
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'edit' && $id_invoice && $tgl_transaksi && $kurir && $id_pelanggan && $id_toko) {
        $stmt = $pdo->prepare('UPDATE transaksi SET Tgl_Transaksi = ?, Kurir = ?, ID_Pelanggan = ?, ID_Toko = ? WHERE ID_Invoice = ?');
        $stmt->execute([$tgl_transaksi, $kurir, $id_pelanggan, $id_toko, $id_invoice]);
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete' && $id_invoice) {
        $pdo->beginTransaction();
        try {
            // Delete related rows in detailtransaksi table
            $stmt = $pdo->prepare('DELETE FROM detailtransaksi WHERE ID_Invoice = ?');
            $stmt->execute([$id_invoice]);
            
            // Delete the transaksi record
            $stmt = $pdo->prepare('DELETE FROM transaksi WHERE ID_Invoice = ?');
            $stmt->execute([$id_invoice]);

            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Failed: " . $e->getMessage();
        }
    }
}

$transaksi = $pdo->query('SELECT * FROM transaksi')->fetchAll();
$pelanggan = $pdo->query('SELECT * FROM pelanggan')->fetchAll();
$toko = $pdo->query('SELECT * FROM toko')->fetchAll();
$produk = $pdo->query('SELECT * FROM produk')->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Transaksi</h1>
        <a href="index.html" class="btn btn-secondary mb-3">Back to Home</a>
        <form action="transaksi.php" method="post">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label for="id_invoice">ID Invoice</label>
                <input type="text" class="form-control" id="id_invoice" name="id_invoice" required>
            </div>
            <div class="form-group">
                <label for="tgl_transaksi">Tanggal Transaksi</label>
                <input type="date" class="form-control" id="tgl_transaksi" name="tgl_transaksi" required>
            </div>
            <div class="form-group">
                <label for="kurir">Kurir</label>
                <input type="text" class="form-control" id="kurir" name="kurir" required>
            </div>
            <div class="form-group">
                <label for="id_pelanggan">ID Pelanggan</label>
                <select class="form-control" id="id_pelanggan" name="id_pelanggan" required>
                    <?php foreach ($pelanggan as $p): ?>
                        <option value="<?= $p['ID_Pelanggan'] ?>"><?= $p['Nama_Pelanggan'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id_toko">ID Toko</label>
                <select class="form-control" id="id_toko" name="id_toko" required>
                    <?php foreach ($toko as $t): ?>
                        <option value="<?= $t['ID_Toko'] ?>"><?= $t['Nama_Toko'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="kode_produk">Produk</label>
                <select class="form-control" id="kode_produk" name="kode_produk[]" required>
                    <?php foreach ($produk as $pr): ?>
                        <option value="<?= $pr['Kode_Produk'] ?>"><?= $pr['Nama_Produk'] ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="jumlah_produk">Jumlah</label>
                <input type="number" class="form-control" id="jumlah_produk" name="jumlah_produk[]" required>
            </div>
            <button type="button" class="btn btn-secondary" onclick="addProduct()">Add Another Product</button>
            <button type="submit" class="btn btn-primary">Add Transaksi</button>
        </form>
        <table class="table mt-5">
            <thead>
                <tr>
                    <th>ID Invoice</th>
                    <th>Tanggal Transaksi</th>
                    <th>Kurir</th>
                    <th>ID Pelanggan</th>
                    <th>ID Toko</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transaksi as $t): ?>
                    <tr>
                        <td><?= $t['ID_Invoice'] ?></td>
                        <td><?= $t['Tgl_Transaksi'] ?></td>
                        <td><?= $t['Kurir'] ?></td>
                        <td><?= $t['ID_Pelanggan'] ?></td>
                        <td><?= $t['ID_Toko'] ?></td>
                        <td>
                            <form action="transaksi.php" method="post" style="display:inline;">
                                <input type="hidden" name="id_invoice" value="<?= $t['ID_Invoice'] ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
                            </form>
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#editModal<?= $t['ID_Invoice'] ?>">Edit</button>
                        </td>
                    </tr>

                    <div class="modal fade" id="editModal<?= $t['ID_Invoice'] ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Transaksi</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="transaksi.php" method="post">
                                        <input type="hidden" name="id_invoice" value="<?= $t['ID_Invoice'] ?>">
                                        <input type="hidden" name="action" value="edit">
                                        <div class="form-group">
                                            <label for="tgl_transaksi">Tanggal Transaksi</label>
                                            <input type="date" class="form-control" id="tgl_transaksi" name="tgl_transaksi" value="<?= $t['Tgl_Transaksi'] ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="kurir">Kurir</label>
                                            <input type="text" class="form-control" id="kurir" name="kurir" value="<?= $t['Kurir'] ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="id_pelanggan">ID Pelanggan</label>
                                            <select class="form-control" id="id_pelanggan" name="id_pelanggan" required>
                                                <?php foreach ($pelanggan as $p): ?>
                                                    <option value="<?= $p['ID_Pelanggan'] ?>" <?= $p['ID_Pelanggan'] == $t['ID_Pelanggan'] ? 'selected' : '' ?>><?= $p['Nama_Pelanggan'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="id_toko">ID Toko</label>
                                            <select class="form-control" id="id_toko" name="id_toko" required>
                                                <?php foreach ($toko as $to): ?>
                                                    <option value="<?= $to['ID_Toko'] ?>" <?= $to['ID_Toko'] == $t['ID_Toko'] ? 'selected' : '' ?>><?= $to['Nama_Toko'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            </tbody>
        </table>
    </div