<?php
include 'db.php';

$id_pelanggan = $_POST['id_pelanggan'] ?? null;
$nama_pelanggan = $_POST['nama_pelanggan'] ?? null;
$alamat_pelanggan = $_POST['alamat_pelanggan'] ?? null;
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'add' && $nama_pelanggan && $alamat_pelanggan) {
        $stmt = $pdo->prepare('INSERT INTO pelanggan (Nama_Pelanggan, Alamat_Pelanggan) VALUES (?, ?)');
        $stmt->execute([$nama_pelanggan, $alamat_pelanggan]);
    } elseif (isset($_POST['action']) && $_POST['action'] == 'edit' && $nama_pelanggan && $alamat_pelanggan && $id_pelanggan) {
        $stmt = $pdo->prepare('UPDATE pelanggan SET Nama_Pelanggan = ?, Alamat_Pelanggan = ? WHERE ID_Pelanggan = ?');
        $stmt->execute([$nama_pelanggan, $alamat_pelanggan, $id_pelanggan]);
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete' && $id_pelanggan) {
        $pdo->beginTransaction();
        try {
            // Delete related rows in transaksi table
            $stmt = $pdo->prepare('DELETE FROM transaksi WHERE ID_Pelanggan = ?');
            $stmt->execute([$id_pelanggan]);

            // Delete the pelanggan record
            $stmt = $pdo->prepare('DELETE FROM pelanggan WHERE ID_Pelanggan = ?');
            $stmt->execute([$id_pelanggan]);

            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "Failed: " . $e->getMessage();
        }
    }
}

$pelanggan = $pdo->query('SELECT * FROM pelanggan')->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelanggan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Pelanggan</h1>
        <a href="index.html" class="btn btn-secondary mb-3">Back to Home</a>
        <form action="pelanggan.php" method="post">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label for="nama_pelanggan">Nama Pelanggan</label>
                <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" required>
            </div>
            <div class="form-group">
                <label for="alamat_pelanggan">Alamat Pelanggan</label>
                <textarea class="form-control" id="alamat_pelanggan" name="alamat_pelanggan" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Pelanggan</button>
        </form>
        <table class="table mt-5">
            <thead>
                <tr>
                    <th>ID Pelanggan</th>
                    <th>Nama Pelanggan</th>
                    <th>Alamat Pelanggan</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pelanggan as $p): ?>
                    <tr>
                        <td><?= $p['ID_Pelanggan'] ?></td>
                        <td><?= $p['Nama_Pelanggan'] ?></td>
                        <td><?= $p['Alamat_Pelanggan'] ?></td>
                        <td>
                            <form action="pelanggan.php" method="post" style="display:inline;">
                                <input type="hidden" name="id_pelanggan" value="<?= $p['ID_Pelanggan'] ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
                            </form>
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#editModal<?= $p['ID_Pelanggan'] ?>">Edit</button>
                        </td>
                    </tr>

                    <div class="modal fade" id="editModal<?= $p['ID_Pelanggan'] ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Pelanggan</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="pelanggan.php" method="post">
                                        <input type="hidden" name="id_pelanggan" value="<?= $p['ID_Pelanggan'] ?>">
                                        <input type="hidden" name="action" value="edit">
                                        <div class="form-group">
                                            <label for="nama_pelanggan">Nama Pelanggan</label>
                                            <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" value="<?= $p['Nama_Pelanggan'] ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="alamat_pelanggan">Alamat Pelanggan</label>
                                            <textarea class="form-control" id="alamat_pelanggan" name="alamat_pelanggan" required><?= $p['Alamat_Pelanggan'] ?></textarea>
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

    <?php if ($error_message): ?>
        <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="errorModalLabel">Error</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?= htmlspecialchars($error_message) ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function(){
                $('#errorModal').modal('show');
            });
        </script>
    <?php endif; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>