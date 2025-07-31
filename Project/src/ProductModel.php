<?php

namespace App;

class ProductModel 
{
    protected $prodID;
    protected $name;
    protected $description;
    protected $price;
    protected $brand;
    protected $image;
    protected $hasStock;
    protected $currentStock;
    protected $tags;
    protected $contains;
    protected $suitableFor;
    protected $usage;
    protected $isAccessory;

    public function __construct(array $data = []) // Flexible constructor
    {
        $this->prodID = $data['ProdID'] ?? null; // Matches 'ProdID' in the Products table
        $this->name = $data['Name'] ?? ''; // Matches 'Name'
        $this->description = $data['Description'] ?? ''; // Matches 'Description'
        $this->price = $data['Price'] ?? 0; // Matches 'Price'
        $this->brand = $data['Brand'] ?? ''; // Matches 'Brand'
        $this->image = $data['Image'] ?? ''; // Matches 'Image'
        $this->hasStock = isset($data['HasStock']) ? (bool)$data['HasStock'] : false; // Matches 'HasStock'
        $this->currentStock = $data['CurrentStock'] ?? 0; // Matches 'CurrentStock'
        $this->tags = isset($data['Tags']) ? explode(',', trim($data['Tags'])) : []; // Matches 'Tags'
        $this->contains = isset($data['Contains']) ? explode(',', trim($data['Contains'])) : []; // Matches 'Contains'
        $this->suitableFor = isset($data['SuitableFor']) ? explode(',', trim($data['SuitableFor'])) : []; // Matches 'SuitableFor'
        $this->usage = $data['Usage'] ?? ''; // Matches 'Usage'
        $this->isAccessory = isset($data['IsAccessory']) ? (bool)$data['IsAccessory'] : false; // Matches 'IsAccessory'
    }

    public function getProdID() { return $this->prodID; }

    public function getName() { return $this->name; }

    public function getDescription() { return $this->description; }

    public function getPrice() { return $this->price; }

    public function getBrand() { return $this->brand; }

    public function getImage() { return $this->image; }

    public function getHasStock() { return $this->hasStock; }

    public function getCurrentStock() { return $this->currentStock; }

    public function getTags() { return $this->tags; }

    public function getContains() { return $this->contains; }

    public function getSuitableFor() { return $this->suitableFor; }

    public function getUsage() { return $this->usage; }

    public function getIsAccessory() { return $this->isAccessory; }

    public function setName($name) { $this->name = $name; }

    public function setDescription($description) { $this->description = $description; }

    public function setPrice($price) { $this->price = $price; }

    public function setBrand($brand) { $this->brand = $brand; }

    public function setImage($image) { $this->image = $image; }

    public function setHasStock($hasStock) { $this->hasStock = $hasStock; }

    public function setCurrentStock($currentStock) { $this->currentStock = $currentStock; }

    public function setTags($tags) { $this->tags = $tags; }

    public function setContains($contains) { $this->contains = $contains; }

    public function setSuitableFor($suitableFor) { $this->suitableFor = $suitableFor; }

    public function setUsage($usage) { $this->usage = $usage; }

    public function setIsAccessory($isAccessory) { $this->isAccessory = $isAccessory; }

    public function toHTMLIndexCard($dataCategory) 
    {
        return '<div class="product-card" data-category="'. $dataCategory .'">
            <a href="/prodInfo?prodID='. $this->prodID .'"><img src="'. $this->image .'"></a>
            <h3>'. $this->name .'</h3>
            <p class="price" data-price="'. $this->price .'">'. $this->price .' L</p>
            <form method="POST" action="/addToCart">
            <input type="hidden" name="redirect" value="'.htmlspecialchars($_SERVER['REQUEST_URI']) .'">
            <input type="hidden" name="prodID" value="'. $this->prodID .'">
            <button type="submit" class="add-to-cart" data-i18n="add_to_cart">Shto në shportë</button>
            </form>
            </div>';
    }

    public function toHTMLCartCard()
    {
        return '<li class="cart-list-item">
            <h3 class="cart-prod-name">' . htmlspecialchars($this->name) . '</h3>
            <h3 class="price" data-price="' . htmlspecialchars($this->price) . '">' . htmlspecialchars($this->price) . ' L</h3>
            <input type="number" name="'. htmlspecialchars($this->prodID) .'" min="1" value="1" class="cart-amount-picker" style="width:60px; margin-right:8px;" form="checkout-form">
            <form method="GET" action="/removeFromCart" style="display:inline;">
                <input type="hidden" name="prodID" value="' . htmlspecialchars($this->prodID) . '">
                <button type="submit" class="remove-from-cart" data-prodID="' . htmlspecialchars($this->prodID) . '">Remove</button>
            </form>
        </li>';
    }
}