<?php
include 'db.php';

$invoices = $pdo->query('SELECT ID_Invoice, Tgl_Transaksi FROM transaksi')->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Invoice</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Select Invoice</h1>
        <a href="index.html" class="btn btn-secondary mb-3">Back to Home</a>
        <form action="invoice.php" method="get">
            <div class="form-group">
                <label for="id_invoice">ID Invoice</label>
                <select class="form-control" id="id_invoice" name="id_invoice" required>
                    <?php foreach ($invoices as $invoice): ?>
                        <option value="<?= htmlspecialchars($invoice['ID_Invoice']) ?>"><?= htmlspecialchars($invoice['ID_Invoice']) ?> - <?= htmlspecialchars($invoice['Tgl_Transaksi']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">View Invoice</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>