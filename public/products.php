<?php
require_once 'config.php';

// Handle form submission to add product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('INSERT INTO products (name_en, name_ar, category_id, supplier_id, cost, price, stock, description) VALUES (?,?,?,?,?,?,?,?)');
    $stmt->execute([
        $_POST['name_en'],
        $_POST['name_ar'],
        $_POST['category_id'] ?: null,
        $_POST['supplier_id'] ?: null,
        $_POST['cost'],
        $_POST['price'],
        $_POST['stock'],
        $_POST['description'],
    ]);
}

$products = $pdo->query('SELECT p.*, s.name AS supplier_name, c.name_en AS category_name FROM products p LEFT JOIN suppliers s ON p.supplier_id = s.id LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC')->fetchAll();
$suppliers = $pdo->query('SELECT id, name FROM suppliers')->fetchAll();
$categories = $pdo->query('SELECT id, name_en FROM categories')->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Products</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { padding: 8px; border: 1px solid #ccc; }
        form { margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Products</h1>
    <form method="post">
        <input type="text" name="name_en" placeholder="Name (EN)" required>
        <input type="text" name="name_ar" placeholder="Name (AR)" required>
        <select name="category_id">
            <option value="">Select Category</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name_en']) ?></option>
            <?php endforeach; ?>
        </select>
        <select name="supplier_id">
            <option value="">Select Supplier</option>
            <?php foreach ($suppliers as $sup): ?>
                <option value="<?= $sup['id'] ?>"><?= htmlspecialchars($sup['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="number" step="0.01" name="cost" placeholder="Cost" required>
        <input type="number" step="0.01" name="price" placeholder="Price" required>
        <input type="number" name="stock" placeholder="Stock" value="0">
        <input type="text" name="description" placeholder="Description">
        <button type="submit">Add Product</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name (EN)</th>
                <th>Name (AR)</th>
                <th>Category</th>
                <th>Supplier</th>
                <th>Cost</th>
                <th>Price</th>
                <th>Stock</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= htmlspecialchars($p['name_en']) ?></td>
                    <td><?= htmlspecialchars($p['name_ar']) ?></td>
                    <td><?= htmlspecialchars($p['category_name']) ?></td>
                    <td><?= htmlspecialchars($p['supplier_name']) ?></td>
                    <td><?= $p['cost'] ?></td>
                    <td><?= $p['price'] ?></td>
                    <td><?= $p['stock'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
