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
    <title>Pest & Disease Guide | Smart Farming</title>
    <style>
        :root { --forest:#173f2a; --green:#3c8a5a; --lime:#d5e7a5; --ink:#18352b; --muted:#718078; --line:#e1e9e1; --canvas:#f4f7f2; --mint:#e4f2e3; --amber:#a8671d; --red:#a34535; }
        * { box-sizing:border-box; } body { margin:0; min-height:100vh; color:var(--ink); background:var(--canvas); font-family:"Trebuchet MS",Arial,sans-serif; } .shell { width:min(1200px,calc(100% - 38px)); margin:auto; }
        .topbar { display:flex; align-items:center; justify-content:space-between; gap:18px; padding:24px 0; } .brand { display:flex; align-items:center; gap:10px; color:var(--forest); font-size:14px; font-weight:bold; letter-spacing:.08em; text-decoration:none; text-transform:uppercase; } .brand img { width:42px; height:42px; border-radius:11px; } .back { color:var(--green); font-size:13px; font-weight:bold; text-decoration:none; }
        .hero { position:relative; min-height:270px; padding:42px; overflow:hidden; border-radius:18px; color:white; background:linear-gradient(110deg,rgba(15,54,33,.96),rgba(65,91,45,.7)),url('assets/crop-tomato.svg') center/cover; } .hero::after { content:'⚠'; position:absolute; top:32px; right:40px; color:var(--lime); font-size:38px; } .eyebrow { margin:0 0 12px; color:var(--lime); font-size:11px; font-weight:bold; letter-spacing:.16em; text-transform:uppercase; } h1 { max-width:670px; margin:0 0 12px; font-size:clamp(32px,5vw,58px); letter-spacing:-.045em; line-height:1; } .hero p { max-width:650px; margin:0; color:rgba(255,255,255,.8); font-size:15px; line-height:1.7; }
        .toolbar { display:flex; align-items:center; justify-content:space-between; gap:15px; margin:24px 0 15px; } .toolbar h2 { margin:0; font-size:20px; } .tools { display:flex; gap:8px; } input,select { padding:11px 12px; border:1px solid var(--line); border-radius:8px; background:white; color:var(--ink); font:inherit; font-size:12px; } input { width:220px; }
        .pest-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:14px; padding-bottom:26px; } .pest-card { display:grid; grid-template-columns:145px 1fr; overflow:hidden; border:1px solid var(--line); border-radius:12px; background:white; box-shadow:0 8px 22px rgba(30,73,43,.05); } .pest-card img { width:145px; height:100%; min-height:190px; object-fit:cover; } .pest-content { padding:17px; } .crop-label { margin:0 0 7px; color:var(--green); font-size:10px; font-weight:bold; letter-spacing:.12em; text-transform:uppercase; } .pest-content h3 { margin:0 0 7px; font-size:18px; } .pest-content p { margin:0 0 10px; color:var(--muted); font-size:11px; line-height:1.55; } .symptom { padding:9px; border-radius:7px; color:#765a31; background:#fff8e9; font-size:11px; line-height:1.45; } .action { margin-top:9px; padding:9px; border-left:3px solid var(--green); color:#4f6a5a; background:var(--mint); font-size:11px; line-height:1.5; } .no-results { display:none; grid-column:1/-1; padding:30px; color:var(--muted); border:1px dashed var(--line); border-radius:10px; text-align:center; }
        .safety { margin-bottom:35px; padding:18px 20px; border:1px solid #f0dfbf; border-radius:10px; background:#fffaf1; color:#6f624f; font-size:12px; line-height:1.65; } .safety strong { color:var(--amber); }
        @media(max-width:760px){ .pest-grid{grid-template-columns:1fr;} .pest-card{grid-template-columns:125px 1fr;} .pest-card img{width:125px;} } @media(max-width:520px){ .shell{width:min(100% - 28px,1200px);} .hero{padding:30px 24px;min-height:310px;} .toolbar{align-items:stretch;flex-direction:column;} .tools{display:grid;grid-template-columns:1fr 1fr;} input{width:auto;} .pest-card{grid-template-columns:1fr;} .pest-card img{width:100%;height:150px;min-height:0;} }
    </style>
</head>
<body>
    <div class="shell">
        <header class="topbar"><a class="brand" href="index.php"><img src="assets/logo.png" alt="Smart Farming logo"> Smart Farming</a><a class="back" href="crop-guide.php">← Crop guide</a></header>
        <section class="hero"><p class="eyebrow">Smart Farming protection center</p><h1>Find the problem before it spreads.</h1><p>પાક પ્રમાણે સામાન્ય જીવાત, લક્ષણો અને Integrated Pest Management (IPM) પગલાં જુઓ. પહેલા ઓળખ અને scouting, પછી જ મંજૂર product label પ્રમાણે નિર્ણય.</p></section>
        <div class="toolbar"><h2>જીવાત અને રોગ માર્ગદર્શન</h2><div class="tools"><input id="search" type="search" placeholder="Search pest or crop..." aria-label="Search pests"><select id="cropFilter" aria-label="Filter by crop"><option value="all">All crops</option></select></div></div>
        <section class="pest-grid" id="pestGrid" aria-label="Pest and disease cards"></section>
        <div class="safety"><strong>સલામતી:</strong> પાન/ફળના લક્ષણથી એકલા દવા પસંદ ન કરો. જીવાતની સાચી ઓળખ, economic threshold, સ્થાનિક કૃષિ અધિકારી અને મંજૂર label તપાસો. gloves, mask અને protective clothing વાપરો; અલગ દવાઓનું cocktail spray ન કરો.</div>
    </div>
    <script>
        const pests = [
            { crop:'ઘઉં · Wheat', key:'wheat', image:'assets/crop-wheat.svg', pest:'માહુ / Aphid', search:'wheat aphid rust', symptoms:'પાન વળે, ચીકણો રસ અને કાળી sooty layer દેખાય.', action:'અઠવાડિક પાન તપાસ, નીંદણ દૂર અને ladybird જેવા natural enemies બચાવો. વધતા ઉપદ્રવ માટે local label guidance.' },
            { crop:'કપાસ · Cotton', key:'cotton', image:'assets/crop-cotton.svg', pest:'સફેદમાખી / Whitefly', search:'cotton whitefly bollworm', symptoms:'પાનની નીચે નાના સફેદ જીવાત, પાન પીળાં અને ચીકણાં બને.', action:'પીળા sticky traps, નીંદણ નિયંત્રણ અને પાનની નીચે scouting. જરૂર પડે ત્યારે અધિકારીની ઓળખ પછી label product.' },
            { crop:'મગફળી · Groundnut', key:'groundnut', image:'assets/crop-groundnut.svg', pest:'ટિક્કા રોગ / Leaf spot', search:'groundnut tikka leaf spot thrips', symptoms:'પાન પર ગોળ ભૂખરા ટપકા, વહેલું પાનખર અને વૃદ્ધિ ઘટે.', action:'સ્વચ્છ બીજ, પાક ફેરબદલી, સારી હવા અને પાન ભીનું રહે તેવું ટાળો. રોગ confirm થયા પછી જ fungicide label.' },
            { crop:'જીરું · Cumin', key:'cumin', image:'assets/crop-cumin.svg', pest:'ચરમી / Blight', search:'cumin blight wilt fungus', symptoms:'નાજુક પાન પર ભૂરા ડાઘ, છોડ સુકાય અને ભેજમાં રોગ ઝડપથી વધે.', action:'સારો drainage, હવા ચાલે તેવું અંતર, clean seed અને over-irrigation ટાળો. નિષ્ણાત ઓળખ પછી મંજૂર product.' },
            { crop:'ડાંગર · Paddy', key:'paddy', image:'assets/crop-paddy.svg', pest:'સ્ટેમ બોરર / Stem borer', search:'paddy rice stem borer insect', symptoms:'મધ્યનું પાન સુકાય, dead heart અથવા white ear દેખાય.', action:'ફેરોમોન traps, field scouting, balanced nitrogen અને નીંદણ નિયંત્રણ. threshold પછી જ સ્થાનિક ભલામણ.' },
            { crop:'ટામેટા · Tomato', key:'tomato', image:'assets/crop-tomato.svg', pest:'ફળછેદક / Fruit borer', search:'tomato fruit borer caterpillar', symptoms:'ફળમાં છિદ્ર, અંદર caterpillar અને સડવું શરૂ થાય.', action:'અસરગ્રસ્ત ફળ નાશ કરો, pheromone traps અને net nursery રાખો. chemical spray પહેલાં સાચી ઓળખ જરૂરી.' },
            { crop:'મકાઈ · Maize', key:'maize', image:'assets/crop-maize.svg', pest:'Fall armyworm', search:'maize fall armyworm', symptoms:'નવા પાનમાં લાંબા છિદ્ર અને મધ્યમાં ભૂરા droppings દેખાય.', action:'પાનની અંદર scouting, pheromone traps અને bird perches. early detection પછી જ approved advice.' },
            { crop:'ચણા · Chickpea', key:'chickpea', image:'assets/crop-chickpea.svg', pest:'Pod borer', search:'chickpea gram pod borer', symptoms:'ફૂલ/શીંગમાં છિદ્ર અને અંદરથી દાણા ખવાય.', action:'ફેરોમોન traps, bird perches અને અઠવાડિક scouting. threshold પછી local agriculture recommendation.' }
        ];
        const grid = document.getElementById('pestGrid'); const filter = document.getElementById('cropFilter');
        pests.forEach(pest => { const option = document.createElement('option'); option.value = pest.key; option.textContent = pest.crop; filter.appendChild(option); });
        function render() { const query = document.getElementById('search').value.toLowerCase().trim(); const crop = filter.value; const result = pests.filter(item => item.search.includes(query) || item.crop.toLowerCase().includes(query) || item.pest.toLowerCase().includes(query)).filter(item => crop === 'all' || item.key === crop); grid.innerHTML = result.map(item => `<article class="pest-card"><img src="${item.image}" alt="${item.crop}"><div class="pest-content"><p class="crop-label">${item.crop}</p><h3>${item.pest}</h3><p>સામાન્ય ઓળખ માટેનું માર્ગદર્શન</p><div class="symptom"><strong>લક્ષણ:</strong> ${item.symptoms}</div><div class="action"><strong>IPM પગલું:</strong> ${item.action}</div></div></article>`).join('') || '<div class="no-results" style="display:block">No pest or crop found.</div>'; }
        document.getElementById('search').addEventListener('input', render); filter.addEventListener('change', render); render();
    </script>
</body>
</html>
