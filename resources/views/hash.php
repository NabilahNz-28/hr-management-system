<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Buat Hash Password</title>
</head>
<body>
    <form method="post">
        <input type="text" name="password" placeholder="Masukkan password">
        <button type="submit">Buat Hash</button>
    </form>

    <?php if (!empty($hash)) : ?>
        <p>Hash:</p>
        <textarea rows="3" cols="80"><?php echo htmlspecialchars($hash); ?></textarea>
    <?php endif; ?>
</body>
</html>