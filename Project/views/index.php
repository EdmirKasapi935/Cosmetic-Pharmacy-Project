<?php include_once __DIR__ . '/templates/header.php'; 

  if (isset($_GET["message"])) 
    echo "<script> alert('".$_GET["message"]."') </script>";
?>


<!-- Produkte në ofertë -->
<section class="products">
  <h2 data-i18n="offer_products">Produkte në ofertë</h2>
  <p class="sub" data-i18n="spring_offers">OFERTAT E PRANVERËS</p>

  <div class="dropdown-container">
    <label for="categoryDropdown">Zgjidh Kategorinë:</label>
    <select id="categoryDropdown" onchange="location.href='?selected=' + this.value;">
      <option value="all">Të gjitha</option>
      <option value="dermokozmetike">Dermokozmetikë</option>
      <option value="farmaci">Farmaci</option>
      <option value="suplemente">Suplemente</option>
    </select>
  </div>

  <div class="product-list">
    <?php
      if (count($products) != 0) 
      {
        if (count($products) > 10) 
        {
          for ($i = 0; $i < 10; $i++) 
            echo $products[$i]->toHTMLIndexCard($_GET['selected'] ?? 'all'); // Pass top 10 products to the product card
        }
        else 
        {
          foreach ($products as $product)
            echo $product->toHTMLIndexCard($_GET['selected'] ?? 'all'); // Pass the selected category to the product card
        }
      }
    ?>
  </div>
</section>

<div id="subscribe" class="contact-us-container">
    <h2> Subscribe To Our Newsletter</h2> <br>
    <form method="POST" action="/getsubscription">
      <Label>Name:</Label> <input type="text" name="UserSubName" required> <br>
      <Label>Email:</Label> <input type="email" name="UserSubEmail" required style="margin-top: 5px; "> <br>
      <div class="g-recaptcha" data-sitekey="6LfmST4rAAAAALV_FY8q5sEA9bfUZ1D8wiaahPFQ"></div>
      <input style="margin-top: 5px;" type="submit" value="subscribe" name="subscription">
    </form>
</div>

<script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
</script>

<?php include_once __DIR__ . '/templates/footer.php'; ?>