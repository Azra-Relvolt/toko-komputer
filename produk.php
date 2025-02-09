<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_produk = $_POST['kode_produk'];
    $nama_produk = $_POST['nama_produk'] ?? null ;
    $harga_produk = $_POST['harga_produk'] ?? null ;
    
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        $stmt = $pdo->prepare('INSERT INTO produk (Kode_Produk, Nama_Produk, Harga_Produk) VALUES (?, ?, ?)');
        $stmt->execute([$kode_produk, $nama_produk, $harga_produk]);
    } elseif (isset($_POST['action']) && $_POST['action'] == 'edit') {
        $stmt = $pdo->prepare('UPDATE produk SET Nama_Produk = ?, Harga_Produk = ? WHERE Kode_Produk = ?');
        $stmt->execute([$nama_produk, $harga_produk, $kode_produk]);
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $stmt = $pdo->prepare('DELETE FROM produk WHERE Kode_Produk = ?');
        $stmt->execute([$kode_produk]);
    }
}

$produk = $pdo->query('SELECT * FROM produk')->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Produk</h1>
        <a href="index.html" class="btn btn-secondary mb-3">Back to Home</a>
        <form action="produk.php" method="post">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label for="kode_produk">Kode Produk</label>
                <input type="text" class="form-control" id="kode_produk" name="kode_produk" required>
            </div>
            <div class="form-group">
                <label for="nama_produk">Nama Produk</label>
                <input type="text" class="form-control" id="nama_produk" name="nama_produk" required>
            </div>
            <div class="form-group">
                <label for="harga_produk">Harga Produk</label>
                <input type="number" class="form-control" id="harga_produk" name="harga_produk" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Produk</button>
        </form>
        <table class="table mt-5">
            <thead>
                <tr>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Harga Produk</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produk as $p): ?>
                    <tr>
                        <td><?= $p['Kode_Produk'] ?></td>
                        <td><?= $p['Nama_Produk'] ?></td>
                        <td><?= $p['Harga_Produk'] ?></td>
                        <td>
                            <form action="produk.php" method="post" style="display:inline;">
                                <input type="hidden" name="kode_produk" value="<?= $p['Kode_Produk'] ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#editModal<?= $p['Kode_Produk'] ?>">Edit</button>
                        </td>
                    </tr>

                    <div class="modal fade" id="editModal<?= $p['Kode_Produk'] ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Produk</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="produk.php" method="post">
                                        <input type="hidden" name="kode_produk" value="<?= $p['Kode_Produk'] ?>">
                                        <input type="hidden" name="action" value="edit">
                                        <div class="form-group">
                                            <label for="nama_produk">Nama Produk</label>
                                            <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="<?= $p['Nama_Produk'] ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="harga_produk">Harga Produk</label>
                                            <input type="number" class="form-control" id="harga_produk" name="harga_produk" value="<?= $p['Harga_Produk'] ?>" required>
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
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>