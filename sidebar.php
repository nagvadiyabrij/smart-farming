<?php
$currentPage = basename($_SERVER['PHP_SELF'] ?? 'user-panel.php');
$links = [
    'user-panel.php' => ['🏡', 'My farm'],
    'crop-production.php' => ['🌱', 'Crop production'],
    'crop-guide.php' => ['🌾', 'Crop guide'],
    'pest-diagnosis.php' => ['🔎', 'Pest check'],
    'farmer-profile.php' => ['👨‍🌾', 'Farm profile'],
];
?>
<aside class="app-sidebar" aria-label="Farmer navigation">
  <a class="side-brand" href="user-panel.php"><img src="assets/logo.png" alt="Smart Farming logo"><span>Smart<br>Farming</span></a>
  <p class="side-label">My workspace</p>
  <nav class="side-nav">
    <?php foreach ($links as $url => [$icon, $label]): ?>
      <a class="<?php echo $currentPage === $url ? 'active' : ''; ?>" href="<?php echo $url; ?>"><span><?php echo $icon; ?></span><?php echo $label; ?></a>
    <?php endforeach; ?>
  </nav>
  <div class="side-help"><strong>Need help in the field?</strong><span>Use Pest check before choosing any treatment.</span></div>
  <a class="side-signout" href="login.php">↗ Sign out</a>
</aside>
