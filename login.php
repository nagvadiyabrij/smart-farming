<?php
session_start();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === 'farmer' && $password === 'farmer123') {
        $_SESSION['user'] = 'Farmer';
        header('Location: user-panel.php'); exit;
    } else {
        $error = 'The username or password is incorrect.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#173f2a">
    <title>Sign In | Smart Farming</title>
    <style>
        :root { --ink: #19352b; --muted: #6c7c73; --green: #28734b; --deep: #173f2a; --lime: #d4e8a9; --line: #dce7dc; --paper: #f8fbf5; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; color: var(--ink); font-family: "Trebuchet MS", Arial, sans-serif; background: #dce8d7 url('assets/bg-new.jpg') center/cover fixed; }
        body::before { content: ''; position: fixed; inset: 0; background: linear-gradient(120deg, rgba(19,63,42,.78), rgba(235,244,220,.5)); pointer-events: none; }
        .page { position: relative; z-index: 1; width: min(1120px, calc(100% - 40px)); min-height: 650px; margin: 32px auto; display: grid; grid-template-columns: 1.08fr .92fr; overflow: hidden; border: 1px solid rgba(255,255,255,.58); border-radius: 24px; background: rgba(250,253,248,.97); box-shadow: 0 28px 70px rgba(15,49,28,.28); }
        .story { position: relative; min-height: 650px; padding: 42px; display: flex; flex-direction: column; justify-content: space-between; color: white; overflow: hidden; }
        .story::before { content: ''; position: absolute; inset: 0; background: linear-gradient(180deg, rgba(5,33,18,.2), rgba(5,33,18,.8)); z-index: -1; }
        .story-image { position: absolute; inset: 0; z-index: -2; background: center/cover url('assets/sf1.jpg'); transition: background-image .8s ease; }
        .topline { display: flex; align-items: center; gap: 12px; font-size: 13px; font-weight: bold; letter-spacing: .08em; text-transform: uppercase; }
        .topline img { width: 38px; height: 38px; object-fit: cover; border-radius: 10px; background: white; }
        .story-content { max-width: 520px; animation: rise .8s ease both; }
        .eyebrow { margin: 0 0 14px; color: var(--lime); font-size: 12px; font-weight: bold; letter-spacing: .16em; text-transform: uppercase; }
        h1 { max-width: 560px; margin: 0 0 16px; font-size: clamp(34px, 4vw, 58px); line-height: 1.02; letter-spacing: -.03em; }
        .story-content p { max-width: 480px; margin: 0; color: rgba(255,255,255,.84); font-size: 16px; line-height: 1.7; }
        .insight { display: flex; align-items: center; justify-content: space-between; gap: 18px; max-width: 520px; padding-top: 22px; border-top: 1px solid rgba(255,255,255,.3); }
        .insight strong { display: block; margin-bottom: 5px; color: var(--lime); font-size: 24px; } .insight span { color: rgba(255,255,255,.75); font-size: 12px; }
        .dots { display: flex; gap: 6px; } .dot { width: 7px; height: 7px; border: 0; border-radius: 50%; background: rgba(255,255,255,.42); cursor: pointer; } .dot.active { width: 22px; border-radius: 4px; background: var(--lime); }
        .access { display: flex; align-items: center; padding: 58px clamp(28px, 5vw, 64px); background: var(--paper); }
        .access-inner { width: 100%; max-width: 390px; margin: auto; }
        .brand-mark { display: flex; align-items: center; gap: 10px; margin-bottom: 34px; color: var(--green); font-size: 14px; font-weight: bold; letter-spacing: .08em; text-transform: uppercase; } .brand-mark img { width: 46px; height: 46px; object-fit: cover; border-radius: 12px; box-shadow: 0 7px 16px rgba(36,87,53,.15); }
        h2 { margin: 0 0 9px; font-size: 32px; letter-spacing: -.025em; } .lead { margin: 0 0 28px; color: var(--muted); font-size: 14px; line-height: 1.6; }
        .alert { margin-bottom: 18px; padding: 12px 14px; border-radius: 9px; font-size: 13px; line-height: 1.45; } .alert.error { color: #9a3737; border: 1px solid #edc0c0; background: #fff0f0; } .alert.success { color: #276740; border: 1px solid #bedfbd; background: #effaf0; }
        form { display: grid; gap: 17px; } label { display: grid; gap: 8px; color: #365046; font-size: 13px; font-weight: bold; }
        input { width: 100%; padding: 14px 15px; border: 1px solid var(--line); border-radius: 9px; outline: none; background: white; color: var(--ink); font: inherit; font-size: 14px; transition: border-color .2s, box-shadow .2s; } input:focus { border-color: var(--green); box-shadow: 0 0 0 4px rgba(40,115,75,.12); }
        .password-row { position: relative; } .password-row input { padding-right: 70px; } .show-password { position: absolute; right: 9px; top: 50%; padding: 6px 7px; border: 0; background: transparent; color: var(--green); font: inherit; font-size: 12px; font-weight: bold; cursor: pointer; transform: translateY(-50%); }
        .options { display: flex; align-items: center; justify-content: space-between; gap: 12px; color: var(--muted); font-size: 12px; } .check { display: flex; flex-direction: row; align-items: center; gap: 7px; font-weight: normal; } .check input { width: auto; accent-color: var(--green); } .options a, .join a { color: var(--green); font-weight: bold; text-decoration: none; }
        .primary { width: 100%; margin-top: 3px; padding: 14px 18px; border: 0; border-radius: 9px; background: var(--deep); color: white; font: inherit; font-size: 14px; font-weight: bold; cursor: pointer; box-shadow: 0 10px 20px rgba(23,63,42,.2); transition: background .2s, transform .2s, box-shadow .2s; } .primary:hover { background: var(--green); transform: translateY(-1px); box-shadow: 0 13px 25px rgba(23,63,42,.28); } .primary:disabled { cursor: wait; opacity: .7; }
        .join { margin: 25px 0 0; color: var(--muted); font-size: 13px; text-align: center; }
        @keyframes rise { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        @media (max-width: 820px) { .page { grid-template-columns: 1fr; margin: 18px auto; } .story { min-height: 420px; padding: 30px; } .access { padding: 42px 30px; } }
        @media (max-width: 480px) { body { background-attachment: scroll; } .page { width: 100%; margin: 0; border: 0; border-radius: 0; } .story { min-height: 390px; padding: 24px; } .access { padding: 36px 24px; } h1 { font-size: 38px; } .insight { align-items: flex-end; } }
    </style>
</head>
<body>
    <main class="page">
        <section class="story" aria-label="Smart Farming overview">
            <div class="story-image" id="storyImage"></div>
            <div class="topline"><img src="assets/logo.png" alt=""> Smart Farming platform</div>
            <div class="story-content">
                <p class="eyebrow" id="storyEyebrow">Connected agriculture</p>
                <h1 id="storyTitle">Turn every season into insight.</h1>
                <p id="storyText">Bring your fields, resources, and daily decisions into one calm, intelligent workspace.</p>
            </div>
            <div class="insight"><div><strong id="storyStat">120+</strong><span id="storyStatLabel">fields monitored by our community</span></div><div class="dots" aria-label="Story slides"><button class="dot active" data-slide="0" aria-label="Show story one"></button><button class="dot" data-slide="1" aria-label="Show story two"></button><button class="dot" data-slide="2" aria-label="Show story three"></button></div></div>
        </section>
        <section class="access">
            <div class="access-inner">
                <div class="brand-mark"><img src="assets/logo.png" alt="Smart Farming logo"> Smart Farming</div>
                <h2>Welcome back</h2>
                <p class="lead">Sign in to see what is happening across your farm today.</p>
                <?php if ($error): ?><div class="alert error" role="alert"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
                <?php if ($success): ?><div class="alert success" role="status"><?php echo $success; ?></div><?php endif; ?>
                <div id="jsError" class="alert error" role="alert" hidden></div>
                <form id="loginForm" method="post" novalidate>
                    <label for="username">Username<input id="username" name="username" type="text" placeholder="Enter your username" autocomplete="username" required autofocus></label>
                    <label for="password">Password<div class="password-row"><input id="password" name="password" type="password" placeholder="Enter your password" autocomplete="current-password" required><button class="show-password" id="showPassword" type="button">Show</button></div></label>
                    <div class="options"><label class="check"><input type="checkbox" name="remember"> Remember me</label><a href="#" id="forgotPassword">Forgot password?</a></div>
                    <button class="primary" id="submitButton" type="submit">Sign in to dashboard</button>
                </form>
                <p class="join">New to Smart Farming? <a href="register.php">Create your account</a></p>
            </div>
        </section>
    </main>
    <script>
        const form = document.getElementById('loginForm');
        const username = document.getElementById('username');
        const password = document.getElementById('password');
        const jsError = document.getElementById('jsError');
        const submitButton = document.getElementById('submitButton');
        const stories = [
            { image: 'assets/sf1.jpg', eyebrow: 'Connected agriculture', title: 'Turn every season into insight.', text: 'Bring your fields, resources, and daily decisions into one calm, intelligent workspace.', stat: '120+', label: 'fields monitored by our community' },
            { image: 'assets/sf2.jpg', eyebrow: 'Better decisions, naturally', title: 'See the bigger picture.', text: 'Spot patterns early, coordinate your team, and give every crop the attention it needs.', stat: '85%', label: 'average yield improvement' },
            { image: 'assets/sf3.jpg', eyebrow: 'Always in season', title: 'Your farm, in rhythm.', text: 'Stay close to the work with timely updates and a clearer view of what comes next.', stat: '24/7', label: 'support for your growing operation' }
        ];
        let storyIndex = 0;
        function showError(message, field) { jsError.textContent = message; jsError.hidden = false; if (field) field.focus(); }
        function clearError() { jsError.textContent = ''; jsError.hidden = true; }
        function showStory(index) { const story = stories[index]; document.getElementById('storyImage').style.backgroundImage = `url('${story.image}')`; document.getElementById('storyEyebrow').textContent = story.eyebrow; document.getElementById('storyTitle').textContent = story.title; document.getElementById('storyText').textContent = story.text; document.getElementById('storyStat').textContent = story.stat; document.getElementById('storyStatLabel').textContent = story.label; document.querySelectorAll('.dot').forEach((dot, dotIndex) => dot.classList.toggle('active', dotIndex === index)); storyIndex = index; }
        document.querySelectorAll('.dot').forEach(dot => dot.addEventListener('click', () => { showStory(Number(dot.dataset.slide)); clearInterval(storyTimer); storyTimer = setInterval(() => showStory((storyIndex + 1) % stories.length), 5200); }));
        let storyTimer = setInterval(() => showStory((storyIndex + 1) % stories.length), 5200);
        document.getElementById('showPassword').addEventListener('click', function () { const visible = password.type === 'text'; password.type = visible ? 'password' : 'text'; this.textContent = visible ? 'Show' : 'Hide'; });
        document.getElementById('forgotPassword').addEventListener('click', event => { event.preventDefault(); showError('Password recovery is not configured yet. Please contact your farm administrator.'); });
        form.addEventListener('submit', event => { clearError(); if (!username.value.trim()) { event.preventDefault(); showError('Please enter your username.', username); } else if (!password.value) { event.preventDefault(); showError('Please enter your password.', password); } else { submitButton.disabled = true; submitButton.textContent = 'Signing in...'; } });
        [username, password].forEach(field => field.addEventListener('input', clearError));
    </script>
</body>
</html>
