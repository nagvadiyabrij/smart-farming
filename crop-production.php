<?php
session_start();
$user = $_SESSION['user'] ?? 'Farm Manager';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#173f2a">
    <title>Crop Production | Smart Farming</title>
    <style>
        :root { --forest: #173f2a; --green: #3c8a5a; --lime: #d5e7a5; --ink: #18352b; --muted: #718078; --line: #e1e9e1; --canvas: #f4f7f2; --mint: #e4f2e3; --amber: #a8671d; }
        * { box-sizing: border-box; } body { margin: 0; min-height: 100vh; color: var(--ink); background: var(--canvas); font-family: "Trebuchet MS", Arial, sans-serif; line-height: 1.6; }
        .shell { width: min(1240px, calc(100% - 38px)); margin: 0 auto; padding-bottom: 60px; } .topbar { display: flex; align-items: center; justify-content: space-between; gap: 18px; padding: 24px 0; } .brand { display: flex; align-items: center; gap: 10px; color: var(--forest); font-size: 14px; font-weight: bold; letter-spacing: .08em; text-transform: uppercase; text-decoration: none; } .brand img { width: 42px; height: 42px; object-fit: cover; border-radius: 11px; } .back { color: var(--green); font-size: 13px; font-weight: bold; text-decoration: none; transition: opacity .2s; } .back:hover { opacity: .7; }
        
        .hero { position: relative; min-height: 340px; padding: 50px; overflow: hidden; border-radius: 20px; color: white; background: linear-gradient(to right, rgba(12,52,31,.95) 20%, rgba(35,88,59,.4)), url('assets/hero_crop.jpg') center/cover; display: flex; flex-direction: column; justify-content: center; box-shadow: 0 15px 35px rgba(23,63,42,.15); }
        .eyebrow { margin: 0 0 12px; color: var(--lime); font-size: 13px; font-weight: bold; letter-spacing: .16em; text-transform: uppercase; } 
        h1 { max-width: 700px; margin: 0 0 18px; font-size: clamp(38px, 5vw, 64px); letter-spacing: -.03em; line-height: 1.05; } 
        .hero p { max-width: 650px; margin: 0; color: rgba(255,255,255,.9); font-size: 17px; line-height: 1.7; }
        
        .section-title { margin: 50px 0 30px; font-size: 28px; letter-spacing: -.02em; color: var(--forest); display: flex; align-items: center; gap: 12px; }
        .section-title::after { content: ''; flex: 1; height: 1px; background: var(--line); }

        .importance-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 60px; }
        .i-card { background: white; padding: 30px; border-radius: 16px; border: 1px solid var(--line); box-shadow: 0 10px 25px rgba(0,0,0,.03); transition: transform .3s ease, box-shadow .3s ease; }
        .i-card:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(30,73,43,.08); }
        .i-card-icon { width: 48px; height: 48px; border-radius: 12px; background: var(--mint); display: flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 20px; }
        .i-card h3 { margin: 0 0 10px; font-size: 18px; color: var(--forest); }
        .i-card p { margin: 0; color: var(--muted); font-size: 14px; }

        .stages { display: flex; flex-direction: column; gap: 40px; }
        .stage { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: center; background: white; border-radius: 20px; overflow: hidden; border: 1px solid var(--line); box-shadow: 0 12px 30px rgba(0,0,0,.04); transition: transform .4s cubic-bezier(0.165, 0.84, 0.44, 1); }
        .stage:hover { transform: scale(1.01); box-shadow: 0 20px 40px rgba(23,63,42,.1); }
        .stage:nth-child(even) { grid-template-columns: 1fr 1fr; }
        .stage:nth-child(even) .stage-img-wrap { order: 2; }
        .stage-img-wrap { height: 100%; min-height: 380px; position: relative; overflow: hidden; }
        .stage-img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; transition: transform .8s ease; }
        .stage:hover .stage-img { transform: scale(1.05); }
        .stage-content { padding: 40px; }
        .stage-num { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: var(--green); color: white; font-weight: bold; border-radius: 50%; font-size: 14px; margin-bottom: 15px; }
        .stage-content h3 { margin: 0 0 15px; font-size: 26px; color: var(--forest); letter-spacing: -.02em; }
        .stage-content p { margin: 0 0 20px; color: #4a5c52; font-size: 15px; }
        .stage-features { list-style: none; padding: 0; margin: 0; display: grid; gap: 10px; }
        .stage-features li { display: flex; align-items: center; gap: 10px; font-size: 13px; color: var(--muted); font-weight: bold; }
        .stage-features li::before { content: '✓'; color: var(--green); font-size: 16px; }

        @media (max-width: 950px) { .importance-cards { grid-template-columns: 1fr; } .stage { grid-template-columns: 1fr !important; } .stage:nth-child(even) .stage-img-wrap { order: -1; } .stage-img-wrap { min-height: 280px; } .stage-content { padding: 30px; } }
        @media (max-width: 570px) { .hero { padding: 35px 25px; min-height: 300px; } h1 { font-size: 32px; } }
        
        .app-sidebar{position:fixed;z-index:10;top:0;bottom:0;left:0;display:flex;flex-direction:column;width:230px;padding:24px 16px;background:#173f2a;color:#c3d6c7}.side-brand{display:flex;align-items:center;gap:10px;padding:0 9px 28px;color:#fff;font-size:13px;font-weight:800;letter-spacing:.08em;line-height:1.15;text-decoration:none;text-transform:uppercase}.side-brand img{width:39px;height:39px;border-radius:10px;object-fit:cover}.side-label{margin:0 10px 10px;color:#91b19c;font-size:10px;font-weight:800;letter-spacing:.15em;text-transform:uppercase}.side-nav{display:grid;gap:5px}.side-nav a{display:flex;align-items:center;gap:11px;padding:11px 10px;border-radius:8px;color:#c3d6c7;font-size:13px;font-weight:700;text-decoration:none}.side-nav a span{width:19px;text-align:center;font-size:15px}.side-nav a:hover,.side-nav a.active{background:rgba(255,255,255,.12);color:#fff}.side-help{margin-top:auto;padding:14px 10px;border-top:1px solid rgba(255,255,255,.15);color:#aac3b0;font-size:11px;line-height:1.5}.side-help strong{display:block;margin-bottom:4px;color:#fff;font-size:12px}.side-signout{padding:13px 10px 0;color:#c3d6c7;font-size:12px;font-weight:700;text-decoration:none}@media(min-width:761px){body{padding-left:230px}}@media(max-width:760px){.app-sidebar{position:static;display:block;width:100%;padding:12px 16px}.side-brand{padding:0 0 10px}.side-label,.side-help,.side-signout{display:none}.side-nav{display:flex;gap:4px;overflow-x:auto}.side-nav a{flex:0 0 auto;padding:8px 9px;font-size:11px}}
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="shell">
        <header class="topbar">
            <a class="brand" href="user-panel.php"><img src="assets/logo.png" alt="Smart Farming logo"> Smart Farming</a>
            <a class="back" href="user-panel.php">← Back to overview</a>
        </header>

        <section class="hero">
            <p class="eyebrow">Agriculture Essentials</p>
            <h1>The Science & Art of Crop Production.</h1>
            <p>Crop production is the meticulous process of growing crops for food, fiber, fodder, and other vital products. It requires careful planning, deep knowledge, and respect for the environment.</p>
        </section>

        <h2 class="section-title">Why it matters</h2>
        <div class="importance-cards">
            <div class="i-card">
                <div class="i-card-icon">🌍</div>
                <h3>Food Security</h3>
                <p>Ensures a steady, reliable supply of nutritious food to meet the demands of a growing global population, preventing hunger and malnutrition.</p>
            </div>
            <div class="i-card">
                <div class="i-card-icon">👨‍👩‍👧‍👦</div>
                <h3>Farmers' Livelihoods</h3>
                <p>Provides the primary source of income for millions of rural families worldwide, sustaining communities and preserving agricultural heritage.</p>
            </div>
            <div class="i-card">
                <div class="i-card-icon">📈</div>
                <h3>Economic Growth</h3>
                <p>Drives the national economy by supplying raw materials for industries, creating jobs, and significantly contributing to exports.</p>
            </div>
        </div>

        <h2 class="section-title">The 8 Stages of Crop Production</h2>
        <div class="stages">
            
            <div class="stage">
                <div class="stage-img-wrap"><img src="assets/land_preparation.png" alt="Land Preparation" class="stage-img"></div>
                <div class="stage-content">
                    <span class="stage-num">1</span>
                    <h3>Land Preparation</h3>
                    <p>The foundation of a good harvest. The soil is loosened, turned, and leveled to ensure proper aeration, root penetration, and moisture retention. This stage removes old crop residues and weeds.</p>
                    <ul class="stage-features">
                        <li>Plowing to turn the soil</li>
                        <li>Harrowing to break clods</li>
                        <li>Leveling for even irrigation</li>
                    </ul>
                </div>
            </div>

            <div class="stage">
                <div class="stage-img-wrap"><img src="assets/seed_selection.png" alt="Seed Selection" class="stage-img"></div>
                <div class="stage-content">
                    <span class="stage-num">2</span>
                    <h3>Seed Selection</h3>
                    <p>Choosing high-quality, disease-resistant, and high-yielding seeds is critical. Good seeds ensure higher germination rates and robust plants capable of withstanding local climate stresses.</p>
                    <ul class="stage-features">
                        <li>Checking purity and viability</li>
                        <li>Selecting climate-adapted varieties</li>
                        <li>Seed treatment for disease prevention</li>
                    </ul>
                </div>
            </div>

            <div class="stage">
                <div class="stage-img-wrap"><img src="assets/sowing.png" alt="Sowing" class="stage-img"></div>
                <div class="stage-content">
                    <span class="stage-num">3</span>
                    <h3>Sowing</h3>
                    <p>Planting the seeds at the correct depth and spacing. Proper sowing ensures each plant gets adequate sunlight, nutrients, and water without competing too heavily with its neighbors.</p>
                    <ul class="stage-features">
                        <li>Maintaining optimal seed depth</li>
                        <li>Ensuring correct row spacing</li>
                        <li>Timing based on soil moisture and season</li>
                    </ul>
                </div>
            </div>

            <div class="stage">
                <div class="stage-img-wrap"><img src="assets/irrigation.png" alt="Irrigation" class="stage-img"></div>
                <div class="stage-content">
                    <span class="stage-num">4</span>
                    <h3>Irrigation</h3>
                    <p>The artificial application of water to the soil. Consistent and appropriate moisture is essential for nutrient absorption, photosynthesis, and overall crop development.</p>
                    <ul class="stage-features">
                        <li>Drip systems for water conservation</li>
                        <li>Sprinkler irrigation for even coverage</li>
                        <li>Monitoring soil moisture levels</li>
                    </ul>
                </div>
            </div>

            <div class="stage">
                <div class="stage-img-wrap"><img src="assets/fertilization.png" alt="Fertilization" class="stage-img"></div>
                <div class="stage-content">
                    <span class="stage-num">5</span>
                    <h3>Fertilization</h3>
                    <p>Enriching the soil with essential nutrients (Nitrogen, Phosphorus, Potassium) that crops need to grow vigorously. A balanced approach using organic and synthetic fertilizers ensures long-term soil health.</p>
                    <ul class="stage-features">
                        <li>Soil testing before application</li>
                        <li>Adding organic compost and manure</li>
                        <li>Applying micro-nutrients as needed</li>
                    </ul>
                </div>
            </div>

            <div class="stage">
                <div class="stage-img-wrap"><img src="assets/weed_pest.png" alt="Weed and Pest Control" class="stage-img"></div>
                <div class="stage-content">
                    <span class="stage-num">6</span>
                    <h3>Weed & Pest Control</h3>
                    <p>Protecting the crop from unwanted plants that compete for nutrients, and insects or diseases that can damage the yield. Integrated Pest Management (IPM) is crucial here.</p>
                    <ul class="stage-features">
                        <li>Manual or mechanical weeding</li>
                        <li>Using biological controls and traps</li>
                        <li>Targeted, safe application of pesticides</li>
                    </ul>
                </div>
            </div>

            <div class="stage">
                <div class="stage-img-wrap"><img src="assets/harvesting.jpg" alt="Harvesting" class="stage-img"></div>
                <div class="stage-content">
                    <span class="stage-num">7</span>
                    <h3>Harvesting</h3>
                    <p>Gathering the mature crop from the field. Timing is everything—harvesting too early reduces yield quality, while waiting too long can lead to shattering and spoilage.</p>
                    <ul class="stage-features">
                        <li>Checking moisture content of grains</li>
                        <li>Using combine harvesters for efficiency</li>
                        <li>Careful handling to prevent bruising</li>
                    </ul>
                </div>
            </div>

            <div class="stage">
                <div class="stage-img-wrap"><img src="assets/sf2.jpg" alt="Storage" class="stage-img"></div>
                <div class="stage-content">
                    <span class="stage-num">8</span>
                    <h3>Storage</h3>
                    <p>Safely storing the harvested produce to protect it from moisture, rodents, insects, and fungi. Proper storage extends shelf life and allows farmers to sell when market prices are favorable.</p>
                    <ul class="stage-features">
                        <li>Drying produce to safe moisture levels</li>
                        <li>Using modern silos and cold storage</li>
                        <li>Fumigation and proper ventilation</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
