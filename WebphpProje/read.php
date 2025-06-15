<?php
require_once 'db.php'; // PDO bağlantı dosyan

$stmt = $pdo->query("SELECT id, username, email, created_at FROM users ORDER BY id DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Kullanıcı Listesi</h2>
<table border="1" cellpadding="5" cellspacing="0">
  <thead>
    <tr>
      <th>ID</th>
      <th>Kullanıcı Adı</th>
      <th>Email</th>
      <th>Oluşturulma Tarihi</th>
      <th>İşlemler</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $user): ?>
      <tr>
        <td><?= htmlspecialchars($user['id']) ?></td>
        <td><?= htmlspecialchars($user['username']) ?></td>
        <td><?= htmlspecialchars($user['email']) ?></td>
        <td><?= htmlspecialchars($user['created_at']) ?></td>
        <td>
          <a href="duzenle.php?id=<?= $user['id'] ?>">Düzenle</a> |
          <a href="sil.php?id=<?= $user['id'] ?>" onclick="return confirm('Kullanıcıyı silmek istediğinize emin misiniz?');">Sil</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
