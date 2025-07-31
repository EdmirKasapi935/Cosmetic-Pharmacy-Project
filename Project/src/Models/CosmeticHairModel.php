<?php

namespace App\Models;

use App\ProductModel;
use App\Queryable;
use Dotenv;

class CosmeticHairModel extends ProductModel implements Queryable
{
    private const TABLE_NAME = 'CosmeticHair'; // Constant for table name

    private $id;
    private $hairType;
    private $specificFor;
    private $mass;
    private $ingredients;
    private $dotenv;

    public function __construct(array $data = []) // Flexible constructor
    {
        // Specific to CosmeticHairModel
        $this->id = $data['ID'] ?? null; // Matches 'ID' in the CosmeticHair table
        $this->hairType = isset($data['HairType']) ? explode(',', trim($data['HairType'])) : []; // Matches 'HairType'
        $this->specificFor = isset($data['SpecificFor']) ? explode(',', trim($data['SpecificFor'])) : []; // Matches 'SpecificFor'
        $this->mass = isset($data['Mass']) ? (float)$data['Mass'] : 0.0; // Matches 'Mass'
        $this->ingredients = isset($data['Ingredients']) ? explode(',', trim($data['Ingredients'])) : []; // Matches 'Ingredients'
        // Load environment variables
        $this->dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '\\..\\..\\');
        $this->dotenv->load();
        // Common to all product models
        parent::__construct($data); // Call parent constructor for common properties
    }

    public function getHairType() { return $this->hairType; }
    public function getSpecificFor() { return $this->specificFor; }
    public function getMass() { return $this->mass; }
    public function getIngredients() { return $this->ingredients; }
    public function getProductType() {return "Hair";}
    
    public function setHairType($hairType)
    {
        if (!is_array($hairType)) {
            throw new \InvalidArgumentException("HairType must be an array.");
        }
        $this->hairType = $hairType;
    }

    public function setSpecificFor($specificFor)
    {
        if (!is_array($specificFor)) {
            throw new \InvalidArgumentException("SpecificFor must be an array.");
        }
        $this->specificFor = $specificFor;
    }

    public function setMass($mass)
    {
        if (!is_numeric($mass) || $mass <= 0) {
            throw new \InvalidArgumentException("Mass must be a positive number.");
        }
        $this->mass = $mass;
    }

    public function setIngredients($ingredients)
    {
        if (!is_array($ingredients)) {
            throw new \InvalidArgumentException("Ingredients must be an array.");
        }
        $this->ingredients = $ingredients;
    }

    public function toUpdateQuery()
    {
        $productUpdateQuery = "UPDATE Products SET 
            Name = '{$this->name}', 
            Price = {$this->price}, 
            Brand = '{$this->brand}', 
            HasStock = " . ($this->hasStock ? 1 : 0) . ", 
            CurrentStock = {$this->currentStock}, 
            Description = '{$this->description}', 
            Tags = '" . implode(',', $this->tags) . "', 
            Contains = '" . implode(',', $this->contains) . "', 
            SuitableFor = '" . implode(',', $this->suitableFor) . "', 
            Usage = '{$this->usage}', 
            IsAccessory = " . ($this->isAccessory ? 1 : 0) . " 
            WHERE ProdID = {$this->prodID};";

        $cosmeticHairUpdateQuery = "UPDATE " . self::TABLE_NAME . " SET 
            HairType = '" . implode(',', $this->hairType) . "', 
            SpecificFor = '" . implode(',', $this->specificFor) . "', 
            Mass = '{$this->mass}', 
            Ingredients = '" . implode(',', $this->ingredients) . "' 
            WHERE ProdID = {$this->prodID};";

        return $productUpdateQuery . " " . $cosmeticHairUpdateQuery;
    }

    public function toCreateQuery()
    {
        $productCreateQuery = "INSERT INTO Products 
            (Name, Price, Brand, HasStock, CurrentStock, Description, Tags, Contains, SuitableFor, Usage, IsAccessory) 
            VALUES (
                '{$this->name}', 
                {$this->price}, 
                '{$this->brand}', 
                " . ($this->hasStock ? 1 : 0) . ", 
                {$this->currentStock}, 
                '{$this->description}', 
                '" . implode(',', $this->tags) . "', 
                '" . implode(',', $this->contains) . "', 
                '" . implode(',', $this->suitableFor) . "', 
                '{$this->usage}', 
                " . ($this->isAccessory ? 1 : 0) . "
            );";

        $cosmeticHairCreateQuery = "INSERT INTO " . self::TABLE_NAME . " 
            (HairType, SpecificFor, Mass, Ingredients, ProdID) 
            VALUES (
                '" . implode(',', $this->hairType) . "', 
                '" . implode(',', $this->specificFor) . "', 
                '{$this->mass}', 
                '" . implode(',', $this->ingredients) . "', 
                (SELECT ProdID FROM Products WHERE Name = '{$this->name}' AND Price = {$this->price} LIMIT 1)
            );";

        return $productCreateQuery . " " . $cosmeticHairCreateQuery;
    }

    public function toDeleteQuery()
    {
        $cosmeticHairDeleteQuery = "DELETE FROM " . self::TABLE_NAME . " WHERE ProdID = {$this->prodID};";
        $productDeleteQuery = "DELETE FROM Products WHERE ProdID = {$this->prodID};";

        return $cosmeticHairDeleteQuery . " " . $productDeleteQuery;
    }

    public function toHTMLProdCard()
    {
        return '<div class="product-card"
            data-category="'. $this->getProducttype() .'"
            data-brand="'. $this->brand .'"
            data-suitable-for="'. htmlspecialchars(implode(',', $this->suitableFor ?? [])) .'"
            data-price="'. $this->price .'"
            data-accessory="'. ($this->isAccessory ? 'true' : 'false') .'"
            data-hair-type="'. htmlspecialchars(implode(',', $this->hairType ?? [])).'"
            >
            <h4>'. $this->name .'</h4>
            <a href="/prodInfo?prodID='. $this->prodID .'"><img src="'. $this->image .'"></a>
            <p class="price" data-price="'. $this->price .'">'. $this->price .' L</p>
            <form method="POST" action="/addToCart">
            <input type="hidden" name="prodID" value="'. $this->prodID .'">
            <input type="hidden" name="redirect" value="'. htmlspecialchars($_SERVER['REQUEST_URI']) .'">
            <button type="submit" class="add-to-cart" data-i18n="add_to_cart">Shto në shportë</button>
            </form>
            <a href=https://wa.me/'.$_ENV['WHATSAPP_NUM'].'?text='. urlencode("Hi, I'm interested in this product: http://localhost:8000/prodInfo?prodID=$this->prodID") .' class="whatsapp-button">Porosit në WhatsApp</a>
            </div>';
    }
}