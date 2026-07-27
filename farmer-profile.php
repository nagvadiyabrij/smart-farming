<?php
session_start();
$user = $_SESSION['user'] ?? 'Farm Manager';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farm Profile | Smart Farming</title>
    <style>
        :root { --forest: #173f2a; --green: #3c8a5a; --lime: #d5e7a5; --ink: #18352b; --muted: #718078; --line: #e1e9e1; --canvas: #f4f7f2; --mint: #e4f2e3; }
        * { box-sizing: border-box; } 
        body { margin: 0; min-height: 100vh; color: var(--ink); background: var(--canvas); font-family: "Trebuchet MS", Arial, sans-serif; } 
        
        /* Layout */
        .shell { width: min(1120px, calc(100% - 38px)); margin: auto; padding-bottom: 40px;} 
        .topbar { display: flex; justify-content: space-between; align-items: center; padding: 24px 0; } 
        .brand { display: flex; align-items: center; gap: 10px; color: var(--forest); font-weight: bold; letter-spacing: .08em; text-decoration: none; text-transform: uppercase; } 
        .brand img { width: 42px; height: 42px; border-radius: 11px; } 
        .back { color: var(--green); font-size: 13px; font-weight: bold; text-decoration: none; }
        
        /* Hero Section */
        .profile-hero { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            min-height: 380px; 
            overflow: hidden; 
            border-radius: 18px; 
            color: white; 
            background: var(--forest); 
            box-shadow: 0 16px 35px rgba(23,63,42,.16); 
            position: relative;
        } 
        .hero-photo { 
            background: linear-gradient(0deg, rgba(7,39,22,.48), transparent), url('assets/smart_farm_hero.png') center/cover; 
        } 
        .hero-copy { 
            display: flex; 
            flex-direction: column; 
            justify-content: center; 
            padding: 42px; 
            position: relative;
        } 
        .eyebrow { margin: 0 0 10px; color: var(--lime); font-size: 11px; font-weight: bold; letter-spacing: .15em; text-transform: uppercase; display: flex; align-items: center; gap: 6px; } 
        .eyebrow .live-dot { width: 8px; height: 8px; background: var(--lime); border-radius: 50%; box-shadow: 0 0 10px var(--lime); animation: pulse 2s infinite; }
        h1 { margin: 0 0 10px; font-size: clamp(30px, 4vw, 48px); letter-spacing: -.04em; line-height: 1; } 
        .hero-copy p { max-width: 450px; margin: 0 0 22px; color: rgba(255,255,255,.78); font-size: 14px; line-height: 1.7; } 
        
        .farmer-line { display: flex; gap: 22px; color: rgba(255,255,255,.72); font-size: 11px; align-items: center; } 
        .farmer-avatar { width: 50px; height: 50px; border-radius: 12px; object-fit: cover; border: 2px solid var(--lime); }
        .farmer-line strong { display: block; margin-bottom: 4px; color: white; font-size: 13px; }
        
        /* Stats Grid */
        .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin: 22px 0; } 
        .stat { 
            padding: 20px; 
            border: 1px solid var(--line); 
            border-radius: 12px; 
            background: white; 
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
            overflow: hidden;
        } 
        .stat:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.06); border-color: var(--green); }
        .stat::before { content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: var(--green); }
        .stat strong { display: block; font-size: 26px; color: var(--forest); } 
        .stat span { display: block; margin-top: 6px; color: var(--muted); font-size: 11px; text-transform: uppercase; font-weight: bold; letter-spacing: 0.05em;}
        
        /* Dynamic Sync Button */
        .sync-btn {
            background: var(--mint);
            color: var(--green);
            border: 1px solid var(--green);
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .sync-btn:hover { background: var(--green); color: white; }
        
        .section-head { display: flex; justify-content: space-between; align-items: end; margin: 35px 0 15px; } 
        .section-head h2 { margin: 0; font-size: 22px; color: var(--forest); } 
        .section-head p { margin: 0; color: var(--muted); font-size: 13px; } 
        
        /* IoT Grid */
        .grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; padding-bottom: 35px; } 
        .card { overflow: hidden; border: 1px solid var(--line); border-radius: 12px; background: white; transition: all 0.2s ease; } 
        .card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(23,63,42,0.1); }
        .card-img-wrap { position: relative; height: 170px; background: #eee; }
        .card img { width: 100%; height: 100%; object-fit: cover; } 
        .card-status { position: absolute; top: 12px; right: 12px; background: rgba(255,255,255,0.9); padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: bold; color: var(--green); display: flex; align-items: center; gap: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .card-status.warn { color: #d35400; }
        
        .card-body { padding: 18px; } 
        .card-body h3 { margin: 0 0 8px; font-size: 17px; color: var(--forest); } 
        .card-body p { margin: 0; color: var(--muted); font-size: 13px; line-height: 1.55; } 
        .tag { display: inline-block; margin-top: 12px; padding: 6px 10px; border-radius: 6px; color: var(--green); background: var(--mint); font-size: 11px; font-weight: bold; }
        
        /* Info Area */
        .info { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; padding-bottom: 35px; } 
        .info-card { padding: 24px; border: 1px solid var(--line); border-radius: 12px; background: white; } 
        .info-card h2 { margin: 0 0 20px; font-size: 18px; color: var(--forest); } 
        .info-row { display: flex; justify-content: space-between; align-items: center; gap: 15px; padding: 12px 0; border-top: 1px solid var(--line); font-size: 13px; } 
        .info-row:last-child { padding-bottom: 0; }
        .info-row span { color: var(--muted); }
        .info-row strong { color: var(--ink); }
        
        /* Sidebar styles */
        .app-sidebar{position:fixed;z-index:10;top:0;bottom:0;left:0;display:flex;flex-direction:column;width:230px;padding:24px 16px;background:#173f2a;color:#c3d6c7}.side-brand{display:flex;align-items:center;gap:10px;padding:0 9px 28px;color:#fff;font-size:13px;font-weight:800;letter-spacing:.08em;line-height:1.15;text-decoration:none;text-transform:uppercase}.side-brand img{width:39px;height:39px;border-radius:10px;object-fit:cover}.side-label{margin:0 10px 10px;color:#91b19c;font-size:10px;font-weight:800;letter-spacing:.15em;text-transform:uppercase}.side-nav{display:grid;gap:5px}.side-nav a{display:flex;align-items:center;gap:11px;padding:11px 10px;border-radius:8px;color:#c3d6c7;font-size:13px;font-weight:700;text-decoration:none}.side-nav a span{width:19px;text-align:center;font-size:15px}.side-nav a:hover,.side-nav a.active{background:rgba(255,255,255,.12);color:#fff}.side-help{margin-top:auto;padding:14px 10px;border-top:1px solid rgba(255,255,255,.15);color:#aac3b0;font-size:11px;line-height:1.5}.side-help strong{display:block;margin-bottom:4px;color:#fff;font-size:12px}.side-signout{padding:13px 10px 0;color:#c3d6c7;font-size:12px;font-weight:700;text-decoration:none}@media(min-width:761px){body{padding-left:230px}}@media(max-width:760px){.app-sidebar{position:static;display:block;width:100%;padding:12px 16px}.side-brand{padding:0 0 10px}.side-label,.side-help,.side-signout{display:none}.side-nav{display:flex;gap:4px;overflow-x:auto}.side-nav a{flex:0 0 auto;padding:8px 9px;font-size:11px}}
        
        @media (max-width: 760px) { .profile-hero { grid-template-columns: 1fr; } .hero-photo { min-height: 260px; } .hero-copy { padding: 30px; } .stats { grid-template-columns: repeat(2, 1fr); } .grid, .info { grid-template-columns: 1fr; } } 
        @media (max-width: 480px) { .shell { width: min(100% - 28px, 1120px); } .topbar { padding: 15px 0; } .farmer-line { gap: 12px; flex-wrap: wrap; } }
        
        @keyframes pulse { 0% { opacity: 1; transform: scale(1); } 50% { opacity: 0.5; transform: scale(1.5); } 100% { opacity: 1; transform: scale(1); } }
        @keyframes spin { 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>
    
    <?php include 'sidebar.php'; ?>
    
    <div class="shell">
        <header class="topbar">
            <a class="brand" href="user-panel.php"><img src="assets/logo.png" alt="Smart Farming logo" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAgMTAwIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iIzNjOGE1YSIvPjwvc3ZnPg=='"> Smart Farming</a>
            <a class="back" href="user-panel.php">← Back to overview</a>
        </header>
        
        <main>
            <section class="profile-hero">
                <div class="hero-photo" role="img" aria-label="Smart Farm field view"></div>
                <div class="hero-copy">
                    <p class="eyebrow"><span class="live-dot"></span> IoT Connected Farm</p>
                    <h1>Smart Farm Command</h1>
                    <p><?php echo htmlspecialchars($user); ?> manages a tech-enabled smart farm in Gujarat, combining real-time IoT sensors with sustainable soil-first practices.</p>
                    <div class="farmer-line">
                        <img src="assets/farmer_avatar.png" alt="Farmer" class="farmer-avatar" onerror="this.style.display='none'">
                        <div>
                            <strong>Farmer</strong>
                            <?php echo htmlspecialchars($user); ?>
                        </div>
                        <div>
                            <strong>Experience</strong>
                            Since 2014
                        </div>
                        <div>
                            <strong>Method</strong>
                            Precision Organic
                        </div>
                    </div>
                </div>
            </section>
            
            <div class="section-head">
                <div>
                    <h2>Real-time Analytics</h2>
                    <p>Live data aggregated from field sensors</p>
                </div>
                <button class="sync-btn" onclick="syncData(this)">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Sync Sensors
                </button>
            </div>
            
            <section class="stats">
                <div class="stat">
                    <strong id="val-acres">12.5</strong>
                    <span>Acres Active</span>
                </div>
                <div class="stat">
                    <strong id="val-moisture">42%</strong>
                    <span>Avg Soil Moisture</span>
                </div>
                <div class="stat">
                    <strong id="val-temp">32°C</strong>
                    <span>Field Temperature</span>
                </div>
                <div class="stat">
                    <strong id="val-health">87%</strong>
                    <span>Overall Crop Health</span>
                </div>
            </section>
            
            <div class="section-head">
                <div>
                    <h2>Connected Zones</h2>
                    <p>Monitoring active crop areas</p>
                </div>
            </div>
            
            <section class="grid">
                <article class="card">
                    <div class="card-img-wrap">
                        <img src="assets/bg-alt.jpg" alt="Zone A" onerror="this.style.display='none'">
                        <div class="card-status"><div class="live-dot" style="width:6px;height:6px;background:var(--green);border-radius:50%;animation:pulse 2s infinite;"></div> Optimal</div>
                    </div>
                    <div class="card-body">
                        <h3>Zone A: Cotton Hybrid</h3>
                        <p>Drip irrigation active. Soil nutrient levels are stable.</p>
                        <span class="tag">4.2 acres</span>
                    </div>
                </article>
                
                <article class="card">
                    <div class="card-img-wrap">
                        <img src="assets/sf1.jpg" alt="Zone B" onerror="this.style.display='none'">
                        <div class="card-status"><div class="live-dot" style="width:6px;height:6px;background:var(--green);border-radius:50%;animation:pulse 2s infinite;"></div> Optimal</div>
                    </div>
                    <div class="card-body">
                        <h3>Zone B: Wheat (Rabi)</h3>
                        <p>Monitoring moisture levels closely for the next watering cycle.</p>
                        <span class="tag">5.0 acres</span>
                    </div>
                </article>
                
                <article class="card">
                    <div class="card-img-wrap">
                        <img src="assets/sf3.jpg" alt="Zone C" onerror="this.style.display='none'">
                        <div class="card-status warn"><div class="live-dot" style="width:6px;height:6px;background:#d35400;border-radius:50%;animation:pulse 2s infinite;"></div> Low Moisture</div>
                    </div>
                    <div class="card-body">
                        <h3>Zone C: Greenhouse</h3>
                        <p>Temperature slightly high. Irrigation cycle recommended soon.</p>
                        <span class="tag">1.5 acres</span>
                    </div>
                </article>
            </section>
            
            <section class="info">
                <article class="info-card">
                    <h2>Farm Configuration</h2>
                    <div class="info-row"><span>Location</span><strong>Mehsana, Gujarat</strong></div>
                    <div class="info-row"><span>Primary Soil</span><strong>Medium black loam</strong></div>
                    <div class="info-row"><span>Irrigation Setup</span><strong>IoT Drip + Solenoid valves</strong></div>
                    <div class="info-row"><span>Power Source</span><strong>Solar Microgrid</strong></div>
                </article>
                <article class="info-card">
                    <h2>Action Center</h2>
                    <div class="info-row"><span>Next action</span><strong style="color:#d35400;">Irrigate Zone C</strong></div>
                    <div class="info-row"><span>Weather Alert</span><strong>Light rain in 4 hrs</strong></div>
                    <div class="info-row"><span>Sensors</span><strong>14/15 Nodes Online</strong></div>
                    <div class="info-row"><span>Crop details</span><a href="crop-guide.php" style="color: var(--green); font-weight: bold; text-decoration: none;">Open crop guide →</a></div>
                </article>
            </section>
        </main>
    </div>

    <script>
        function syncData(btn) {
            if (btn.classList.contains('syncing')) return;
            btn.classList.add('syncing');
            
            const originalText = btn.innerHTML;
            btn.innerHTML = `<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="animation: spin 1s linear infinite;"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Syncing...`;
            
            setTimeout(() => {
                btn.innerHTML = `<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg> Synced`;
                
                // Randomize stats slightly to simulate live data fetch
                const health = Math.floor(Math.random() * (98 - 85) + 85);
                const moisture = Math.floor(Math.random() * (48 - 38) + 38);
                const temp = Math.floor(Math.random() * (35 - 28) + 28);

                document.getElementById('val-health').innerText = health + '%';
                document.getElementById('val-moisture').innerText = moisture + '%';
                document.getElementById('val-temp').innerText = temp + '°C';
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.classList.remove('syncing');
                }, 3000);
            }, 1200);
        }
    </script>
</body>
</html>
