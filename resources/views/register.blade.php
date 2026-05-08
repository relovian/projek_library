<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
 <link rel="stylesheet" href="/catatan/public/css/style.css">
</head>
<body>
    <h1>Perubahan Baru</h1>
    <div class="container">
        <div class="card">
            <div class="card-headrer">Register Admin Baru</div>
            <?php if(isset($error)) echo"<div class='alert alert-danger'>$error</div>";?>
            <?php if(isset($success)) echo"<div class='alert alert-success'>$success</div>";?>
            <form action="../catatan/index.php?act=register-process" method="POST">
                <div class="form-group">
                    <label>Username:</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="form=group">
                    <label>Password:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                 <button type="submit" class="btn">Daftar</button>
            </form>
            <hr>
            <a href="/catatan/index.php?act=login" class="text-center">Sudah punya akun?</a>
                <a href="login.blade.php">Login</a>
    </div>
</body>
</html>