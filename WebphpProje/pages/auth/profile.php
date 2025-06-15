<?php
require_once '../../includes/config.php';
require_once '../../includes/db.php';
require_once '../../includes/functions.php';
require_once '../../includes/header.php';  // header.php içinde <main> açıldığını varsayıyorum

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Kullanıcı bilgilerini çek
$stmtUser = $pdo->prepare("SELECT username, email FROM users WHERE id = ?");
$stmtUser->execute([$user_id]);
$user = $stmtUser->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<div class='alert alert-danger'>Kullanıcı bulunamadı.</div>";
    exit;
}

// Kullanıcının açılışları
$stmt1 = $pdo->prepare("SELECT * FROM openings WHERE user_id = ?");
$stmt1->execute([$user_id]);
$myOpenings = $stmt1->fetchAll();

// Kullanıcının yorumları
$stmt2 = $pdo->prepare("SELECT c.content, c.created_at, o.name AS opening_name 
                        FROM comments c 
                        JOIN openings o ON c.opening_id = o.id 
                        WHERE c.user_id = ?");
$stmt2->execute([$user_id]);
$myComments = $stmt2->fetchAll();
?>

<style>
  /* Profil container */
  .profile-container {
  max-width: 700px;
  margin: 40px auto;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: #333;
  
  /* Üst boşluk ekledim */
  padding-top: 80px;
}


  /* Başlıklar */
  .profile-container h2,
  .profile-container h3 {
    color: #2c3e50;
    margin-bottom: 20px;
    font-weight: 700;
  }

  /* Açılışlar listesi */
  .openings-list {
    list-style-type: none;
    padding: 0;
    margin-bottom: 40px;
  }

  .openings-list li {
    background: #f0f4f8;
    margin-bottom: 12px;
    border-radius: 8px;
    padding: 12px 18px;
    transition: background-color 0.3s ease;
  }

  .openings-list li:hover {
    background: #d9e6f2;
  }

  .openings-list a {
    text-decoration: none;
    color: #1a73e8;
    font-weight: 600;
    font-size: 1.1rem;
  }

  .openings-list a:hover {
    text-decoration: underline;
  }

  /* Yorumlar listesi */
  .comments-list {
    list-style-type: none;
    padding: 0;
  }

  .comments-list li {
    background: #fff9e6;
    border-left: 5px solid #f9a825;
    margin-bottom: 15px;
    padding: 15px 20px;
    border-radius: 6px;
    box-shadow: 0 1px 4px rgb(0 0 0 / 0.1);
  }

  .comments-list strong {
    color: #b26500;
  }

  .comments-list em {
    display: block;
    font-size: 0.85rem;
    color: #666;
    margin-top: 6px;
  }

  /* Profil bilgileri */
  .profile-info {
    background: #e9ecef;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;
  }

  .profile-info p {
    font-size: 1.1rem;
    margin: 0 0 10px 0;
  }

  .btn-edit-profile {
    display: inline-block;
    margin-top: 10px;
  }
</style>

<div class="profile-container">

  <h2>Profilim</h2>

  <div class="profile-info">
    <p><strong>Kullanıcı Adı:</strong> <?= htmlspecialchars($user['username']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <a href="/WebphpProje/duzenle.php?id=<?= $user_id ?>" class="btn btn-primary btn-edit-profile">Bilgileri Düzenle</a>
  </div>

  <h3>Eklediğim Açılışlar</h3>
  <ul class="openings-list">
    <?php if (count($myOpenings) > 0): ?>
      <?php foreach ($myOpenings as $open): ?>
        <li>
          <a href="../openings/view.php?id=<?= htmlspecialchars($open['id']) ?>">
            <?= htmlspecialchars($open['name']) ?> (<?= htmlspecialchars($open['eco_code']) ?>)
          </a>
        </li>
      <?php endforeach; ?>
    <?php else: ?>
      <li>Henüz açılış eklenmemiş.</li>
    <?php endif; ?>
  </ul>

  <h3>Yorumlarım</h3>
  <ul class="comments-list">
    <?php if (count($myComments) > 0): ?>
      <?php foreach ($myComments as $c): ?>
        <li>
          <strong><?= htmlspecialchars($c['opening_name']) ?>:</strong>
          <?= htmlspecialchars($c['content']) ?>
          <em>(<?= htmlspecialchars($c['created_at']) ?>)</em>
        </li>
      <?php endforeach; ?>
    <?php else: ?>
      <li>Henüz yorum yapılmamış.</li>
    <?php endif; ?>
  </ul>

</div>

<?php require_once '../../includes/footer.php'; ?>
