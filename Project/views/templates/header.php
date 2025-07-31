<!DOCTYPE html>
<html lang="sq">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cosmetic Pharmacy – Homepage</title>
  <link rel="stylesheet" href="../styles/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet" />
</head>
<body>
<!-- Top bar -->
<div class="top-bar">
  <div>info@cosmeticpharmacy.al | +355 00 00 00 000</div>
  <div>
    <a href="#">Facebook</a>
    <a href="#">Instagram</a>
    <a href="#">YouTube</a>
  </div>
</div>

<!-- Logo & Search -->
<div class="logo-search">
  <div class="logo"><a href="/">Cosmetic Pharmacy</a></div>
  <form class="search-box" method="GET" action="/products">
    <input type="text" id="searchInput" name="query" placeholder="Kërkoni për produkte..." />
  </form>
  <?php 
    if (session_status() === PHP_SESSION_NONE) session_start(); 
    $cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
  ?>
  <a href="/addToCart" style="text-decoration: none"><div class="cart" id="cart">Cart (<?php echo $cartCount; ?>)</div></a>
</div>

<!-- Language & Currency Switchers -->
<div class="switchers">
  <div class="language-switcher">
    <label for="language">Gjuha:</label>
    <select id="language">
      <option value="sq">Shqip</option>
      <option value="en">English</option>
    </select>
  </div>
  <div class="currency-switcher">
    <label for="currency">Monedha:</label>
    <select id="currency">
      <option value="ALL">ALL</option>
      <option value="EUR">EUR</option>
      <option value="USD">USD</option>
    </select>
  </div>
</div>

<nav class="mega-navbar">
  <ul class="nav-categories">
    <li class="has-mega-menu" id="dermokozmetike-btn">
      <a href="/products?query=cosmetic">DERMOKOZMETIKË</a>
    </li>
    <div class="mega-menu" id="dermokozmetike-menu">
      <div class="mega-column">
        <h4>Fytyra</h4>
        <ul>
          <li><a href="/products?query=face">Pastrimi i fytyrës</a></li>
          <li><a href="/products?query=hydration">Hidratim dhe ushqim</a></li>
          <li><a href="/products?query=anti-acne">Trajtim anti-akne</a></li>
          <li><a href="/products?query=anti-age">Anti-age dhe anti-rrudhe</a></li>
          <li><a href="/products?query=men">Meshkuj</a></li>
          <li><a href="/products?query=eyes">Sytë</a></li>
        </ul>
      </div>
      <div class="mega-column">
        <h4>Flokët</h4>
        <ul>
          <li><a href="/products?query=shampoo">Shampo</a></li>
          <li><a href="/products?query=conditioner">Balsam</a></li>
          <li><a href="/products?query=dandruff">Trajtim kundër zbokthit</a></li>
          <li><a href="/products?query=hair-dye">Bojë flokësh</a></li>
        </ul>
      </div>
      <div class="mega-column">
        <h4>Trupi</h4>
        <ul>
          <li><a href="/products?query=scrub">Scrub & Exfoliant</a></li>
          <li><a href="/products?query=anti-cellulite">Anti-cellulit</a></li>
          <li><a href="/products?query=hydration-body">Hidratim</a></li>
          <li><a href="/products?query=deodorant">Deodorant</a></li>
        </ul>
      </div>
      <div class="mega-column">
        <h4>Make-Up</h4>
        <ul>
          <li><a href="/products?query=eyes-lips">Sy & buzë</a></li>
          <li><a href="/products?query=face-makeup">Fytyrë</a></li>
        </ul>
      </div>
      <div class="mega-column">
        <h4>Mbrojtje nga dielli</h4>
        <ul>
          <li><a href="/products?query=sun-body">Trupi</a></li>
          <li><a href="/products?query=sun-face">Fytyra</a></li>
          <li><a href="/products?query=self-tanning">Auto-bronzantë</a></li>
        </ul>
      </div>
      <div class="mega-column">
        <h4>Sport & Tattoo</h4>
        <ul>
          <li><a href="/products?query=tattoo">Tatuazh</a></li>
          <li><a href="/products?query=piercing">Piercing</a></li>
        </ul>
      </div>
      <div class="mega-column">
        <h4>Parfum</h4>
        <ul>
          <li><a href="/products?query=women-perfume">Femra</a></li>
          <li><a href="/products?query=men-perfume">Meshkuj</a></li>
        </ul>
      </div>
      <div class="mega-column">
        <h4>Paketim dhuratë</h4>
        <ul>
          <li><a href="/products?query=gift-sets">Sete</a></li>
          <li><a href="/products?query=gift-packaging">Ambalazh</a></li>
        </ul>
      </div>
    </div>

    <li class="has-mega-menu" id="farmaci-btn"><a href="/products?query=pharmacy">FARMACI</a></li>
    <div class="mega-menu" id="farmaci-menu">
      <div class="mega-column">
        <h4>Medikamente</h4>
        <ul>
          <li><a href="/products?query=pain">Dhimbje</a></li>
          <li><a href="/products?query=cold">Ftohje</a></li>
          <li><a href="/products?query=fever">Temperaturë</a></li>
        </ul>
      </div>
      <div class="mega-column">
        <h4>Alergji</h4>
        <ul>
          <li><a href="/products?query=antihistamines">Antihistaminikë</a></li>
          <li><a href="/products?query=nose-sprays">Spraje hunde</a></li>
        </ul>
      </div>
      <div class="mega-column">
        <h4>Vitamina</h4>
        <ul>
          <li><a href="/products?query=multivitamins">Multivitamina</a></li>
          <li><a href="/products?query=vitamin-c">Vitamina C</a></li>
        </ul>
      </div>
    </div>

    <li class="has-mega-menu" id="suplemente-btn"><a href="/products?query=supplement">SUPLEMENTE</a></li>
    <div class="mega-menu" id="suplemente-menu">
      <div class="mega-column">
        <h4>Imunitet</h4>
        <ul>
          <li><a href="/products?query=vitamin-d">Vitamina D</a></li>
          <li><a href="/products?query=zinc">Zink</a></li>
          <li><a href="/products?query=probiotics">Probiotikë</a></li>
        </ul>
      </div>
      <div class="mega-column">
        <h4>Energjia</h4>
        <ul>
          <li><a href="/products?query=mg-b6">Mg + B6</a></li>
          <li><a href="/products?query=ginseng">Ginseng</a></li>
        </ul>
      </div>
      <div class="mega-column">
        <h4>Fëmijë</h4>
        <ul>
          <li><a href="/products?query=kids-supplements">Suplemente pediatrike</a></li>
        </ul>
      </div>
    </div>

    <li class="has-mega-menu" id="higjiene-btn"><a href="/products?query=hyigene">HIGJIENË</a></li>
    <div class="mega-menu" id="higjiene-menu">
      <div class="mega-column">
        <h4>Trupi</h4>
        <ul>
          <li><a href="/products?query=shower">Dush</a></li>
          <li><a href="/products?query=deodorant">Deodorant</a></li>
          <li><a href="/products?query=hand-cream">Krem duar</a></li>
        </ul>
      </div>
      <div class="mega-column">
        <h4>Dhembë</h4>
        <ul>
          <li><a href="/products?query=toothbrush">Furça</a></li>
          <li><a href="/products?query=toothpaste">Pasta dhëmbësh</a></li>
          <li><a href="/products?query=mouthwash">Shpëlarës goje</a></li>
        </ul>
      </div>
    </div>

    <li class="has-mega-menu" id="bebe-btn"><a href="#">BEBE DHE NËNA</a></li>
    <div class="mega-menu" id="bebe-menu">
      <div class="mega-column">
        <h4>Bebe</h4>
        <ul>
          <li><a href="/products?query=milk">Qumësht</a></li>
          <li><a href="/products?query=diapers">Pelenat</a></li>
          <li><a href="/products?query=baby-skin-care">Kujdesi për lëkurën</a></li>
        </ul>
      </div>
      <div class="mega-column">
        <h4>Nëna</h4>
        <ul>
          <li><a href="/products?query=postpartum">Pas lindjes</a></li>
          <li><a href="/products?query=pregnancy">Shtatzënia</a></li>
        </ul>
      </div>
    </div>

    <li><a href="/products?query=Gift Card">GIFT CARD</a></li>
    <li><a href="/offers">OFERTË</a></li>
    <li><a href="/quiz">QUIZ</a></li>
  </ul>
</nav> 
