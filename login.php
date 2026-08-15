<?php
session_start();

// ================== CONFIG ==================
// Change these when you upload to InfinityFree
$DB_HOST = 'sqlXXX.infinityfree.com';   // your host
$DB_USER = 'if0_XXXXXXX';
$DB_PASS = 'your_password';
$DB_NAME = 'if0_XXXXXXX_cloud';

// Simple hardcoded users for demo (replace with DB later if you want)
$USERS = [
    'admin@nano.com' => 'admin123',
    'ceo@nano-suits.com' => 'secret',
];
// ============================================

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['user'] ?? '');
    $pass = $_POST['pass'] ?? '';

    if (isset($USERS[$user]) && $USERS[$user] === $pass) {
        $_SESSION['loggedin'] = true;
        $_SESSION['user'] = $user;
        $_SESSION['name'] = explode('@', $user)[0];
        header('Location: cloud.php');
        exit;
    } else {
        $error = 'Wrong credentials';
    }
}

if (!empty($_SESSION['loggedin'])) {
    header('Location: cloud.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — Nano Cloud</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg: #050507;
      --card: #121218;
      --border: #1e1e28;
      --text: #f0f0f5;
      --muted: #8b8b9e;
      --accent: #8b5cf6;
    }
    * { margin:0; padding:0; box-sizing:border-box; }
    body {
      font-family: 'Inter', system-ui, sans-serif;
      background: var(--bg);
      color: var(--text);
      min-height: 100vh;
      display: grid;
      place-items: center;
    }
    .box {
      width: 100%;
      max-width: 380px;
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 18px;
      padding: 2.2rem;
    }
    h1 {
      font-size: 1.5rem;
      font-weight: 700;
      margin-bottom: 0.4rem;
    }
    p { color: var(--muted); font-size: 0.9rem; margin-bottom: 1.8rem; }
    label {
      display: block;
      font-size: 0.85rem;
      font-weight: 500;
      margin-bottom: 0.35rem;
      color: var(--muted);
    }
    input {
      width: 100%;
      background: #0a0a0e;
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 0.7rem 0.9rem;
      color: var(--text);
      font-size: 0.95rem;
      margin-bottom: 1.1rem;
      outline: none;
      transition: border-color 0.2s;
    }
    input:focus { border-color: var(--accent); }
    button {
      width: 100%;
      background: linear-gradient(135deg, #8b5cf6, #7c3aed);
      color: white;
      border: none;
      padding: 0.75rem;
      border-radius: 10px;
      font-weight: 600;
      font-size: 0.95rem;
      cursor: pointer;
      margin-top: 0.5rem;
    }
    button:hover { opacity: 0.92; }
    .error {
      background: rgba(239, 68, 68, 0.12);
      border: 1px solid rgba(239, 68, 68, 0.3);
      color: #fca5a5;
      padding: 0.6rem 0.9rem;
      border-radius: 8px;
      font-size: 0.85rem;
      margin-bottom: 1.2rem;
    }
    .back {
      display: block;
      text-align: center;
      margin-top: 1.4rem;
      color: var(--muted);
      font-size: 0.85rem;
      text-decoration: none;
    }
    .back:hover { color: var(--accent); }
  </style>
</head>
<body>
  <div class="box">
    <h1>Staff Login</h1>
    <p>Access the private Nano Cloud</p>

    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
      <label>Email / Username</label>
      <input type="text" name="user" required autocomplete="username" placeholder="admin@nano.com">

      <label>Password</label>
      <input type="password" name="pass" required autocomplete="current-password" placeholder="••••••••">

      <button type="submit">Enter Cloud</button>
    </form>

    <a href="index.html" class="back">← Back to studio</a>
  </div>
</body>
</html>