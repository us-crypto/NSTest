<?php
session_start();

if (empty($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

$USERS_FILE = __DIR__ . '/users.json';

function loadUsers() {
    global $USERS_FILE;
    if (!file_exists($USERS_FILE)) return [];
    return json_decode(file_get_contents($USERS_FILE), true) ?: [];
}

function saveUsers($users) {
    global $USERS_FILE;
    file_put_contents($USERS_FILE, json_encode($users, JSON_PRETTY_PRINT));
}

$isAdmin = ($_SESSION['role'] ?? '') === 'admin';
$msg = '';

// ========== USER MANAGEMENT (Admin only) ==========
if ($isAdmin && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create_user') {
        $email = trim($_POST['email'] ?? '');
        $pass  = $_POST['password'] ?? '';
        $name  = trim($_POST['name'] ?? '') ?: explode('@', $email)[0];
        $role  = ($_POST['role'] ?? 'user') === 'admin' ? 'admin' : 'user';

        if ($email && $pass) {
            $users = loadUsers();
            if (isset($users[$email])) {
                $msg = "User already exists";
            } else {
                $users[$email] = [
                    'password' => $pass,
                    'role'     => $role,
                    'name'     => $name
                ];
                saveUsers($users);
                $msg = "User created: $email";
            }
        }
    }

    if ($action === 'delete_user' && !empty($_POST['email'])) {
        $email = $_POST['email'];
        $users = loadUsers();

        // Prevent deleting yourself
        if ($email === $_SESSION['user']) {
            $msg = "You cannot delete your own account";
        } elseif (isset($users[$email])) {
            unset($users[$email]);
            saveUsers($users);
            $msg = "User deleted: $email";
        }
    }
}
// ==================================================

// Root directory for files
define('ROOT', __DIR__ . '/files/');
if (!is_dir(ROOT)) mkdir(ROOT, 0755, true);

$path = ROOT;
if (!empty($_POST['path'])) {
    $requested = realpath(ROOT . $_POST['path']);
    if ($requested && str_starts_with($requested, realpath(ROOT))) {
        $path = $requested . (is_dir($requested) ? '/' : '');
    }
}
$rel = str_replace(realpath(ROOT), '', realpath($path));
$rel = $rel === '' ? '/' : $rel;

// File actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'mkdir' && !empty($_POST['name'])) {
        $name = basename($_POST['name']);
        if (@mkdir($path . $name, 0755)) $msg = "Folder '$name' created";
        else $msg = "Could not create folder";
    }

    if ($action === 'upload' && !empty($_FILES['file']['name'][0])) {
        foreach ($_FILES['file']['name'] as $i => $name) {
            if ($_FILES['file']['error'][$i] === 0) {
                $safe = basename($name);
                move_uploaded_file($_FILES['file']['tmp_name'][$i], $path . $safe);
                $msg = "Uploaded: $safe";
            }
        }
    }

    if ($action === 'delete' && !empty($_POST['items'])) {
        foreach ((array)$_POST['items'] as $item) {
            $full = realpath($path . basename($item));
            if ($full && str_starts_with($full, realpath(ROOT))) {
                if (is_dir($full)) {
                    $it = new RecursiveDirectoryIterator($full, RecursiveDirectoryIterator::SKIP_DOTS);
                    $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
                    foreach ($files as $f) $f->isDir() ? rmdir($f) : unlink($f);
                    rmdir($full);
                } else {
                    unlink($full);
                }
            }
        }
        $msg = 'Deleted selected items';
    }

    if ($action === 'rename' && !empty($_POST['old']) && !empty($_POST['new'])) {
        $old = realpath($path . basename($_POST['old']));
        $new = $path . basename($_POST['new']);
        if ($old && str_starts_with($old, realpath(ROOT)) && @rename($old, $new)) {
            $msg = 'Renamed successfully';
        }
    }
}

// List files
$items = [];
if (is_dir($path)) {
    foreach (scandir($path) as $f) {
        if ($f === '.' || $f === '..') continue;
        $full = $path . $f;
        $items[] = [
            'name'   => $f,
            'is_dir' => is_dir($full),
            'size'   => is_file($full) ? round(filesize($full)/1024, 1) . ' KB' : '—',
            'mtime'  => date('Y-m-d H:i', filemtime($full))
        ];
    }
}

$allUsers = $isAdmin ? loadUsers() : [];

function h($s) { return htmlspecialchars($s ?? '', ENT_QUOTES); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nano Cloud</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg: #050507; --card: #0f0f14; --border: #1c1c26;
      --text: #eee; --muted: #888; --accent: #8b5cf6;
    }
    * { margin:0; padding:0; box-sizing:border-box; }
    body {
      font-family: 'Inter', system-ui, sans-serif;
      background: var(--bg);
      color: var(--text);
      min-height: 100vh;
    }
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 1.5rem;
      border-bottom: 1px solid var(--border);
      background: #0a0a0e;
    }
    .logo { font-weight: 700; font-size: 1.15rem; }
    .logo span { color: var(--accent); }
    .user { color: var(--muted); font-size: 0.85rem; }
    .user a { color: var(--accent); text-decoration: none; margin-left: 1rem; }
    .badge {
      background: rgba(139,92,246,0.2);
      color: #c4b5fd;
      font-size: 0.7rem;
      padding: 0.15rem 0.5rem;
      border-radius: 999px;
      margin-left: 0.5rem;
    }

    .wrap { max-width: 960px; margin: 0 auto; padding: 1.5rem; }

    .tabs {
      display: flex;
      gap: 0.5rem;
      margin-bottom: 1.5rem;
    }
    .tab {
      background: transparent;
      border: 1px solid var(--border);
      color: var(--muted);
      padding: 0.5rem 1.1rem;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 500;
      font-size: 0.9rem;
    }
    .tab.active {
      background: var(--accent);
      border-color: var(--accent);
      color: white;
    }

    .section { display: none; }
    .section.active { display: block; }

    .toolbar {
      display: flex;
      flex-wrap: wrap;
      gap: 0.6rem;
      margin-bottom: 1.2rem;
      align-items: center;
    }
    input[type=text], input[type=password], input[type=file], select {
      background: #0a0a0e;
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 0.5rem 0.8rem;
      color: var(--text);
      font-size: 0.9rem;
    }
    button, .btn {
      background: var(--accent);
      color: white;
      border: none;
      padding: 0.5rem 1rem;
      border-radius: 8px;
      font-weight: 600;
      font-size: 0.85rem;
      cursor: pointer;
    }
    button.ghost {
      background: transparent;
      border: 1px solid var(--border);
      color: var(--text);
    }
    button.danger {
      background: #dc2626;
    }
    .msg {
      background: rgba(139,92,246,0.12);
      border: 1px solid rgba(139,92,246,0.3);
      color: #c4b5fd;
      padding: 0.6rem 1rem;
      border-radius: 8px;
      margin-bottom: 1rem;
      font-size: 0.9rem;
    }

    .path {
      font-family: 'JetBrains Mono', monospace;
      font-size: 0.85rem;
      color: var(--muted);
      margin-bottom: 1rem;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: var(--card);
      border-radius: 12px;
      overflow: hidden;
      border: 1px solid var(--border);
    }
    th, td {
      padding: 0.75rem 1rem;
      text-align: left;
      border-bottom: 1px solid var(--border);
      font-size: 0.9rem;
    }
    th { background: #0a0a0e; color: var(--muted); font-weight: 500; }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: rgba(139,92,246,0.05); }
    .dir { font-weight: 600; color: #a78bfa; cursor: pointer; }
    .size, .date { color: var(--muted); font-size: 0.85rem; }

    form.inline { display: inline; }

    .card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
    }
    .card h3 { margin-bottom: 1rem; font-size: 1.1rem; }
  </style>
</head>
<body>
  <header>
    <div class="logo">Nano<span>Cloud</span></div>
    <div class="user">
      <?= h($_SESSION['name'] ?? 'User') ?>
      <?php if ($isAdmin): ?><span class="badge">ADMIN</span><?php endif; ?>
      <a href="?logout=1">Logout</a>
    </div>
  </header>

  <?php
  if (isset($_GET['logout'])) {
      session_destroy();
      header('Location: login.php');
      exit;
  }
  ?>

  <div class="wrap">
    <?php if ($msg): ?><div class="msg"><?= h($msg) ?></div><?php endif; ?>

    <?php if ($isAdmin): ?>
    <div class="tabs">
      <button class="tab active" onclick="showTab('files')">Files</button>
      <button class="tab" onclick="showTab('users')">Users</button>
    </div>
    <?php endif; ?>

    <!-- ==================== FILES SECTION ==================== -->
    <div id="files" class="section active">
      <div class="path">📁 <?= h($rel) ?></div>

      <div class="toolbar">
        <?php if ($rel !== '/'): ?>
          <form method="post" class="inline">
            <input type="hidden" name="path" value="<?= h(dirname($rel) === '\\' || dirname($rel) === '.' ? '' : dirname($rel)) ?>">
            <button type="submit" class="ghost">↑ Up</button>
          </form>
        <?php endif; ?>

        <form method="post" class="inline" style="display:flex;gap:0.4rem">
          <input type="hidden" name="path" value="<?= h(ltrim($rel,'/')) ?>">
          <input type="hidden" name="action" value="mkdir">
          <input type="text" name="name" placeholder="New folder" required style="width:130px">
          <button type="submit">Create</button>
        </form>

        <form method="post" enctype="multipart/form-data" class="inline" style="display:flex;gap:0.4rem">
          <input type="hidden" name="path" value="<?= h(ltrim($rel,'/')) ?>">
          <input type="hidden" name="action" value="upload">
          <input type="file" name="file[]" multiple required>
          <button type="submit">Upload</button>
        </form>
      </div>

      <form method="post">
        <input type="hidden" name="path" value="<?= h(ltrim($rel,'/')) ?>">
        <input type="hidden" name="action" value="delete">

        <table>
          <thead>
            <tr>
              <th style="width:40px"></th>
              <th>Name</th>
              <th>Size</th>
              <th>Modified</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($items)): ?>
              <tr><td colspan="4" style="color:var(--muted)">Empty folder</td></tr>
            <?php else: foreach ($items as $item): ?>
              <tr>
                <td><input type="checkbox" name="items[]" value="<?= h($item['name']) ?>"></td>
                <td>
                  <?php if ($item['is_dir']): ?>
                    <form method="post" class="inline">
                      <input type="hidden" name="path" value="<?= h(trim($rel,'/').'/'.$item['name']) ?>">
                      <button type="submit" class="dir" style="background:none;border:none;padding:0;font:inherit;color:inherit;cursor:pointer">
                        📁 <?= h($item['name']) ?>
                      </button>
                    </form>
                  <?php else: ?>
                    📄 <?= h($item['name']) ?>
                  <?php endif; ?>
                </td>
                <td class="size"><?= h($item['size']) ?></td>
                <td class="date"><?= h($item['mtime']) ?></td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>

        <div style="margin-top:1rem">
          <button type="submit" class="ghost" onclick="return confirm('Delete selected?')">Delete selected</button>
        </div>
      </form>

      <form method="post" style="margin-top:1.5rem;display:flex;gap:0.5rem;align-items:center">
        <input type="hidden" name="path" value="<?= h(ltrim($rel,'/')) ?>">
        <input type="hidden" name="action" value="rename">
        <input type="text" name="old" placeholder="Old name" style="width:140px" required>
        <input type="text" name="new" placeholder="New name" style="width:140px" required>
        <button type="submit">Rename</button>
      </form>
    </div>

    <!-- ==================== USERS SECTION (Admin) ==================== -->
    <?php if ($isAdmin): ?>
    <div id="users" class="section">
      <div class="card">
        <h3>Create New Account</h3>
        <form method="post" style="display:flex;flex-wrap:wrap;gap:0.6rem;align-items:end">
          <input type="hidden" name="action" value="create_user">
          <div>
            <label style="font-size:0.8rem;color:var(--muted)">Email</label><br>
            <input type="text" name="email" required placeholder="user@nano.com" style="width:180px">
          </div>
          <div>
            <label style="font-size:0.8rem;color:var(--muted)">Password</label><br>
            <input type="password" name="password" required style="width:140px">
          </div>
          <div>
            <label style="font-size:0.8rem;color:var(--muted)">Name</label><br>
            <input type="text" name="name" placeholder="Optional" style="width:120px">
          </div>
          <div>
            <label style="font-size:0.8rem;color:var(--muted)">Role</label><br>
            <select name="role">
              <option value="user">User</option>
              <option value="admin">Admin</option>
            </select>
          </div>
          <button type="submit">Create Account</button>
        </form>
      </div>

      <div class="card">
        <h3>All Accounts</h3>
        <table>
          <thead>
            <tr>
              <th>Email</th>
              <th>Name</th>
              <th>Role</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($allUsers as $email => $u): ?>
              <tr>
                <td><?= h($email) ?></td>
                <td><?= h($u['name'] ?? '') ?></td>
                <td><?= h($u['role'] ?? 'user') ?></td>
                <td>
                  <?php if ($email !== $_SESSION['user']): ?>
                    <form method="post" class="inline" onsubmit="return confirm('Delete this user?')">
                      <input type="hidden" name="action" value="delete_user">
                      <input type="hidden" name="email" value="<?= h($email) ?>">
                      <button type="submit" class="danger" style="padding:0.3rem 0.7rem;font-size:0.8rem">Delete</button>
                    </form>
                  <?php else: ?>
                    <span style="color:var(--muted);font-size:0.8rem">You</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php endif; ?>
  </div>

  <script>
    function showTab(id) {
      document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
      document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
      document.getElementById(id).classList.add('active');
      event.target.classList.add('active');
    }
  </script>
</body>
</html>