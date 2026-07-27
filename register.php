<?php
session_start();

$error = '';
$success = '';
$old = ['full_name' => '', 'username' => '', 'email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['full_name'] = trim($_POST['full_name'] ?? '');
    $old['username'] = trim($_POST['username'] ?? '');
    $old['email'] = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($old['full_name'] === '' || $old['username'] === '' || $old['email'] === '' || $password === '' || $confirmPassword === '') {
        $error = 'Please complete all required fields.';
    } elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $old['username'])) {
        $error = 'Username must be 3-20 characters using letters, numbers, or underscores.';
    } elseif (strlen($password) < 8 || !preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $error = 'Password must be at least 8 characters and include a letter and a number.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } elseif (!isset($_POST['terms'])) {
        $error = 'Please accept the terms to create your account.';
    } else {
        $success = 'Your Smart Farming account is ready, ' . htmlspecialchars($old['full_name'], ENT_QUOTES, 'UTF-8') . '!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#173f2a">
    <title>Create Account | Smart Farming</title>
    <style>
        :root { --ink: #19352b; --muted: #6c7c73; --green: #28734b; --deep: #173f2a; --lime: #d4e8a9; --line: #dce7dc; --paper: #f8fbf5; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; color: var(--ink); font-family: "Trebuchet MS", Arial, sans-serif; background: #dce8d7 url('assets/bg-new.jpg') center/cover fixed; }
        body::before { content: ''; position: fixed; inset: 0; background: linear-gradient(120deg, rgba(19,63,42,.78), rgba(235,244,220,.5)); pointer-events: none; }
        .page { position: relative; z-index: 1; width: min(1120px, calc(100% - 40px)); min-height: 700px; margin: 24px auto; display: grid; grid-template-columns: 1.08fr .92fr; overflow: hidden; border: 1px solid rgba(255,255,255,.58); border-radius: 24px; background: rgba(250,253,248,.97); box-shadow: 0 28px 70px rgba(15,49,28,.28); }
        .story { position: relative; min-height: 700px; padding: 42px; display: flex; flex-direction: column; justify-content: space-between; color: white; overflow: hidden; }
        .story::before { content: ''; position: absolute; inset: 0; background: linear-gradient(180deg, rgba(5,33,18,.2), rgba(5,33,18,.82)); z-index: -1; }
        .story-image { position: absolute; inset: 0; z-index: -2; background: center/cover url('assets/sf2.jpg'); transition: background-image .8s ease; }
        .topline { display: flex; align-items: center; gap: 12px; font-size: 13px; font-weight: bold; letter-spacing: .08em; text-transform: uppercase; } .topline img { width: 38px; height: 38px; object-fit: cover; border-radius: 10px; background: white; }
        .story-content { max-width: 520px; animation: rise .8s ease both; } .eyebrow { margin: 0 0 14px; color: var(--lime); font-size: 12px; font-weight: bold; letter-spacing: .16em; text-transform: uppercase; } h1 { max-width: 560px; margin: 0 0 16px; font-size: clamp(34px, 4vw, 58px); line-height: 1.02; letter-spacing: -.03em; } .story-content p { max-width: 480px; margin: 0; color: rgba(255,255,255,.84); font-size: 16px; line-height: 1.7; }
        .insight { display: flex; align-items: center; justify-content: space-between; gap: 18px; max-width: 520px; padding-top: 22px; border-top: 1px solid rgba(255,255,255,.3); } .insight strong { display: block; margin-bottom: 5px; color: var(--lime); font-size: 24px; } .insight span { color: rgba(255,255,255,.75); font-size: 12px; } .dots { display: flex; gap: 6px; } .dot { width: 7px; height: 7px; border: 0; border-radius: 50%; background: rgba(255,255,255,.42); cursor: pointer; } .dot.active { width: 22px; border-radius: 4px; background: var(--lime); }
        .access { display: flex; align-items: center; padding: 42px clamp(28px, 5vw, 64px); background: var(--paper); } .access-inner { width: 100%; max-width: 390px; margin: auto; } .brand-mark { display: flex; align-items: center; gap: 10px; margin-bottom: 24px; color: var(--green); font-size: 14px; font-weight: bold; letter-spacing: .08em; text-transform: uppercase; } .brand-mark img { width: 46px; height: 46px; object-fit: cover; border-radius: 12px; box-shadow: 0 7px 16px rgba(36,87,53,.15); }
        h2 { margin: 0 0 9px; font-size: 32px; letter-spacing: -.025em; } .lead { margin: 0 0 22px; color: var(--muted); font-size: 14px; line-height: 1.6; } .alert { margin-bottom: 16px; padding: 12px 14px; border-radius: 9px; font-size: 13px; line-height: 1.45; } .alert.error { color: #9a3737; border: 1px solid #edc0c0; background: #fff0f0; } .alert.success { color: #276740; border: 1px solid #bedfbd; background: #effaf0; }
        form { display: grid; gap: 14px; } label { display: grid; gap: 7px; color: #365046; font-size: 13px; font-weight: bold; } input { width: 100%; padding: 12px 14px; border: 1px solid var(--line); border-radius: 9px; outline: none; background: white; color: var(--ink); font: inherit; font-size: 14px; transition: border-color .2s, box-shadow .2s; } input:focus { border-color: var(--green); box-shadow: 0 0 0 4px rgba(40,115,75,.12); }
        .password-row { position: relative; } .password-row input { padding-right: 70px; } .show-password { position: absolute; right: 9px; top: 50%; padding: 6px 7px; border: 0; background: transparent; color: var(--green); font: inherit; font-size: 12px; font-weight: bold; cursor: pointer; transform: translateY(-50%); } .strength { display: flex; gap: 4px; margin-top: -8px; } .strength span { height: 4px; flex: 1; border-radius: 3px; background: #e2e9e2; } .strength span.active { background: var(--green); } .strength-label { margin-top: -9px; color: var(--muted); font-size: 11px; font-weight: normal; }
        .terms { display: flex; grid-template-columns: auto 1fr; align-items: flex-start; gap: 8px; color: var(--muted); font-size: 12px; font-weight: normal; line-height: 1.45; } .terms input { width: auto; margin-top: 2px; accent-color: var(--green); } .terms a { color: var(--green); font-weight: bold; text-decoration: none; }
        .primary { width: 100%; margin-top: 2px; padding: 14px 18px; border: 0; border-radius: 9px; background: var(--deep); color: white; font: inherit; font-size: 14px; font-weight: bold; cursor: pointer; box-shadow: 0 10px 20px rgba(23,63,42,.2); transition: background .2s, transform .2s, box-shadow .2s; } .primary:hover { background: var(--green); transform: translateY(-1px); box-shadow: 0 13px 25px rgba(23,63,42,.28); } .primary:disabled { cursor: wait; opacity: .7; }
        .login-link { margin: 20px 0 0; color: var(--muted); font-size: 13px; text-align: center; } .login-link a { color: var(--green); font-weight: bold; text-decoration: none; }
        @keyframes rise { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        @media (max-width: 820px) { .page { grid-template-columns: 1fr; margin: 18px auto; } .story { min-height: 400px; padding: 30px; } .access { padding: 42px 30px; } }
        @media (max-width: 480px) { body { background-attachment: scroll; } .page { width: 100%; margin: 0; border: 0; border-radius: 0; } .story { min-height: 370px; padding: 24px; } .access { padding: 34px 24px; } h1 { font-size: 38px; } }
    </style>
</head>
<body>
    <main class="page">
        <section class="story" aria-label="Smart Farming community">
            <div class="story-image" id="storyImage"></div>
            <div class="topline"><img src="assets/logo.png" alt=""> Smart Farming platform</div>
            <div class="story-content">
                <p class="eyebrow" id="storyEyebrow">A better way to grow</p>
                <h1 id="storyTitle">Build your farm's next chapter.</h1>
                <p id="storyText">Create one connected space for the people, plans, and progress that keep your operation moving.</p>
            </div>
            <div class="insight"><div><strong id="storyStat">85%</strong><span id="storyStatLabel">average yield improvement</span></div><div class="dots" aria-label="Story slides"><button class="dot active" data-slide="0" aria-label="Show story one"></button><button class="dot" data-slide="1" aria-label="Show story two"></button><button class="dot" data-slide="2" aria-label="Show story three"></button></div></div>
        </section>
        <section class="access">
            <div class="access-inner">
                <div class="brand-mark"><img src="assets/logo.png" alt="Smart Farming logo"> Smart Farming</div>
                <h2>Create your account</h2>
                <p class="lead">Set up your workspace and start growing with better visibility.</p>
                <?php if ($error): ?><div class="alert error" role="alert"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
                <?php if ($success): ?><div class="alert success" role="status"><?php echo $success; ?></div><?php endif; ?>
                <div id="jsError" class="alert error" role="alert" hidden></div>
                <form id="registerForm" method="post" novalidate>
                    <label for="fullName">Full name<input id="fullName" name="full_name" type="text" value="<?php echo htmlspecialchars($old['full_name'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="e.g. Amina Yusuf" autocomplete="name" required></label>
                    <label for="username">Username<input id="username" name="username" type="text" value="<?php echo htmlspecialchars($old['username'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="Choose a username" autocomplete="username" required></label>
                    <label for="email">Email address<input id="email" name="email" type="email" value="<?php echo htmlspecialchars($old['email'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="you@example.com" autocomplete="email" required></label>
                    <label for="password">Password<div class="password-row"><input id="password" name="password" type="password" placeholder="Create a strong password" autocomplete="new-password" required><button class="show-password" id="showPassword" type="button">Show</button></div></label>
                    <div class="strength" aria-hidden="true"><span></span><span></span><span></span><span></span></div><div class="strength-label" id="strengthLabel">Use 8+ characters with a letter and a number.</div>
                    <label for="confirmPassword">Confirm password<input id="confirmPassword" name="confirm_password" type="password" placeholder="Repeat your password" autocomplete="new-password" required></label>
                    <label class="terms"><input id="terms" name="terms" type="checkbox" required><span>I agree to the Smart Farming <a href="#">terms and privacy policy</a>.</span></label>
                    <button class="primary" id="submitButton" type="submit">Create my workspace</button>
                </form>
                <p class="login-link">Already have an account? <a href="login.php">Sign in</a></p>
            </div>
        </section>
    </main>
    <script>
        const form = document.getElementById('registerForm');
        const fullName = document.getElementById('fullName');
        const username = document.getElementById('username');
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirmPassword');
        const terms = document.getElementById('terms');
        const jsError = document.getElementById('jsError');
        const submitButton = document.getElementById('submitButton');
        const strengthBars = Array.from(document.querySelectorAll('.strength span'));
        const strengthLabel = document.getElementById('strengthLabel');
        const stories = [
            { image: 'assets/sf2.jpg', eyebrow: 'A better way to grow', title: "Build your farm's next chapter.", text: 'Create one connected space for the people, plans, and progress that keep your operation moving.', stat: '85%', label: 'average yield improvement' },
            { image: 'assets/sf1.jpg', eyebrow: 'Made for the field', title: 'Keep good work in motion.', text: 'Organize the details that matter so your team can spend more time growing and less time searching.', stat: '120+', label: 'fields in the community' },
            { image: 'assets/sf3.jpg', eyebrow: 'Always in season', title: 'Grow with more confidence.', text: 'Turn daily observations into a clearer plan for tomorrow, next month, and every harvest ahead.', stat: '24/7', label: 'support for your operation' }
        ];
        let storyIndex = 0;
        function showError(message, field) { jsError.textContent = message; jsError.hidden = false; if (field) field.focus(); }
        function clearError() { jsError.textContent = ''; jsError.hidden = true; }
        function showStory(index) { const story = stories[index]; document.getElementById('storyImage').style.backgroundImage = `url('${story.image}')`; document.getElementById('storyEyebrow').textContent = story.eyebrow; document.getElementById('storyTitle').textContent = story.title; document.getElementById('storyText').textContent = story.text; document.getElementById('storyStat').textContent = story.stat; document.getElementById('storyStatLabel').textContent = story.label; document.querySelectorAll('.dot').forEach((dot, dotIndex) => dot.classList.toggle('active', dotIndex === index)); storyIndex = index; }
        document.querySelectorAll('.dot').forEach(dot => dot.addEventListener('click', () => { showStory(Number(dot.dataset.slide)); clearInterval(storyTimer); storyTimer = setInterval(() => showStory((storyIndex + 1) % stories.length), 5200); }));
        let storyTimer = setInterval(() => showStory((storyIndex + 1) % stories.length), 5200);
        document.getElementById('showPassword').addEventListener('click', function () { const visible = password.type === 'text'; password.type = visible ? 'password' : 'text'; confirmPassword.type = visible ? 'password' : 'text'; this.textContent = visible ? 'Show' : 'Hide'; });
        password.addEventListener('input', function () { const value = password.value; let score = 0; if (value.length >= 8) score++; if (/[A-Za-z]/.test(value) && /[0-9]/.test(value)) score++; if (/[^A-Za-z0-9]/.test(value)) score++; if (value.length >= 12) score++; strengthBars.forEach((bar, index) => bar.classList.toggle('active', index < score)); strengthLabel.textContent = score < 2 ? 'Use 8+ characters with a letter and a number.' : score < 4 ? 'Good start. Add a symbol or more length.' : 'Strong password.'; clearError(); });
        form.addEventListener('submit', event => { clearError(); const usernamePattern = /^[a-zA-Z0-9_]{3,20}$/; const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; let message = ''; let field = null; if (!fullName.value.trim()) { message = 'Please enter your full name.'; field = fullName; } else if (!usernamePattern.test(username.value.trim())) { message = 'Username must be 3-20 characters using letters, numbers, or underscores.'; field = username; } else if (!emailPattern.test(email.value.trim())) { message = 'Please enter a valid email address.'; field = email; } else if (password.value.length < 8 || !/[A-Za-z]/.test(password.value) || !/[0-9]/.test(password.value)) { message = 'Password must be at least 8 characters and include a letter and a number.'; field = password; } else if (password.value !== confirmPassword.value) { message = 'Passwords do not match.'; field = confirmPassword; } else if (!terms.checked) { message = 'Please accept the terms to create your account.'; field = terms; } if (message) { event.preventDefault(); showError(message, field); } else { submitButton.disabled = true; submitButton.textContent = 'Creating workspace...'; } });
        [fullName, username, email, confirmPassword].forEach(field => field.addEventListener('input', clearError));
    </script>
</body>
</html>
