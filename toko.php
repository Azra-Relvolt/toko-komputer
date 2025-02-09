<?php
include 'db.php';

$id_toko = $_POST['id_toko'] ?? null;
$nama_toko = $_POST['nama_toko'] ?? null;
$alamat_toko = $_POST['alamat_toko'] ?? null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'add' && $nama_toko && $alamat_toko) {
        $stmt = $pdo->prepare('INSERT INTO toko (Nama_Toko, Alamat_Toko) VALUES (?, ?)');
        $stmt->execute([$nama_toko, $alamat_toko]);
    } elseif (isset($_POST['action']) && $_POST['action'] == 'edit' && $nama_toko && $alamat_toko && $id_toko) {
        $stmt = $pdo->prepare('UPDATE toko SET Nama_Toko = ?, Alamat_Toko = ? WHERE ID_Toko = ?');
        $stmt->execute([$nama_toko, $alamat_toko, $id_toko]);
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete' && $id_toko) {
        $pdo->beginTransaction();
        try {
            // Delete related rows in transaksi table
            $stmt = $pdo->prepare('DELETE FROM transaksi WHERE ID_Toko = ?');
            $stmt->execute([$id_toko]);

            // Delete the toko record
            $stmt = $pdo->prepare('DELETE FROM toko WHERE ID_Toko = ?');
            $stmt->execute([$id_toko]);

            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Failed: " . $e->getMessage();
        }
    }
}

$toko = $pdo->query('SELECT * FROM toko')->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Toko</h1>
        <a href="index.html" class="btn btn-secondary mb-3">Back to Home</a>
        <form action="toko.php" method="post">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label for="nama_toko">Nama Toko</label>
                <input type="text" class="form-control" id="nama_toko" name="nama_toko" required>
            </div>
            <div class="form-group">
                <label for="alamat_toko">Alamat Toko</label>
                <textarea class="form-control" id="alamat_toko" name="alamat_toko" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Toko</button>
        </form>
        <table class="table mt-5">
            <thead>
                <tr>
                    <th>ID Toko</th>
                    <th>Nama Toko</th>
                    <th>Alamat Toko</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($toko as $t): ?>
                    <tr>
                        <td><?= $t['ID_Toko'] ?></td>
                        <td><?= $t['Nama_Toko'] ?></td>
                        <td><?= $t['Alamat_Toko'] ?></td>
                        <td>
                            <form action="toko.php" method="post" style="display:inline;">
                                <input type="hidden" name="id_toko" value="<?= $t['ID_Toko'] ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
                            </form>
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#editModal<?= $t['ID_Toko'] ?>">Edit</button>
                        </td>
                    </tr>

                    <div class="modal fade" id="editModal<?= $t['ID_Toko'] ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Toko</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="toko.php" method="post">
                                        <input type="hidden" name="id_toko" value="<?= $t['ID_Toko'] ?>">
                                        <input type="hidden" name="action" value="edit">
                                        <div class="form-group">
                                            <label for="nama_toko">Nama Toko</label>
                                            <input type="text" class="form-control" id="nama_toko" name="nama_toko" value="<?= $t['Nama_Toko'] ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="alamat_toko">Alamat Toko</label>
                                            <textarea class="form-control" id="alamat_toko" name="alamat_toko" required><?= $t['Alamat_Toko'] ?></textarea>
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