<?php 

namespace App;

use \PDO;
use App\ProductModel;
use App\Models\CosmeticHairModel;
use App\Models\CosmeticSkinModel;
use App\Models\HygieneModel;
use App\Models\PharmacyModel;
use App\Models\SupplementModel;

class DBAccessor
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = new PDO("sqlite:pharmacy.db");
    }

    public function getHygieneProducts()
    {
        $query = "SELECT Products.*, Hygiene.Mass, Hygiene.Ingredients 
                  FROM Products 
                  JOIN Hygiene ON Products.ProdID = Hygiene.ProdID";
        $statement = $this->pdo->query($query);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCosmeticHairProducts()
    {
        $query = "SELECT Products.*, CosmeticHair.HairType, CosmeticHair.SpecificFor, CosmeticHair.Mass, CosmeticHair.Ingredients 
                  FROM Products 
                  JOIN CosmeticHair ON Products.ProdID = CosmeticHair.ProdID";
        $statement = $this->pdo->query($query);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCosmeticSkinProducts()
    {
        $query = "SELECT Products.*, CosmeticSkin.SkinType, CosmeticSkin.SpecificFor, CosmeticSkin.Mass, CosmeticSkin.Ingredients 
                  FROM Products 
                  JOIN CosmeticSkin ON Products.ProdID = CosmeticSkin.ProdID";
        $statement = $this->pdo->query($query);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPharmacyProducts()
    {
        $query = "SELECT Products.*, Pharmacy.Prescription, Pharmacy.Mass, Pharmacy.Ingredients 
                  FROM Products 
                  JOIN Pharmacy ON Products.ProdID = Pharmacy.ProdID";
        $statement = $this->pdo->query($query);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSupplementProducts()
    {
        $query = "SELECT Products.*, Supplement.Mass, Supplement.Ingredients 
                  FROM Products 
                  JOIN Supplement ON Products.ProdID = Supplement.ProdID";
        $statement = $this->pdo->query($query);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllProducts()
    {
        $query = "SELECT * FROM Products";
        $statement = $this->pdo->query($query);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHygieneProduct($id)
    {
        $query = "SELECT Products.*, Hygiene.Mass, Hygiene.Ingredients 
                  FROM Products, Hygiene  WHERE Products.ProdID=$id AND Hygiene.ProdID=$id";
        $statement = $this->pdo->query($query);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }


    public function getCosmeticHairProduct($id)
    {
        $query = "SELECT Products.*, CosmeticHair.HairType, CosmeticHair.SpecificFor, CosmeticHair.Mass, CosmeticHair.Ingredients 
                  FROM Products, CosmeticHair WHERE Products.ProdID=$id AND CosmeticHair.ProdID = $id";
        $statement = $this->pdo->query($query);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function getCosmeticSkinProduct($id)
    {
        $query = "SELECT Products.*, CosmeticSkin.SkinType, CosmeticSkin.SpecificFor, CosmeticSkin.Mass, CosmeticSkin.Ingredients 
                  FROM Products, CosmeticSkin WHERE Products.ProdID=$id AND CosmeticSkin.ProdID=$id";
        $statement = $this->pdo->query($query);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function getPharmacyProduct($id)
    {
        $query = "SELECT Products.*, Pharmacy.Prescription, Pharmacy.Mass, Pharmacy.Ingredients 
                  FROM Products, Pharmacy WHERE Products.ProdID=$id AND Pharmacy.ProdID=$id";
        $statement = $this->pdo->query($query);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function getSupplementProduct($id)
    {
        $query = "SELECT Products.*, Supplement.Mass, Supplement.Ingredients 
                  FROM Products, Supplement WHERE Products.ProdID=$id AND Supplement.ProdID=$id";
        $statement = $this->pdo->query($query);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function getProductInfo($prodID)
    {
        if(count($this -> pdo->query("SELECT * FROM CosmeticHair WHERE ProdID=$prodID")->fetchAll(PDO::FETCH_ASSOC)) == 1)
        {
            return new CosmeticHairModel($this -> getCosmeticHairProduct($prodID));
        }
        else if(count($this -> pdo->query("SELECT * FROM CosmeticSkin WHERE ProdID=$prodID")->fetchAll(PDO::FETCH_ASSOC)) == 1)
        {
            return new CosmeticSkinModel($this -> getCosmeticSkinProduct($prodID));
        }
        else if(count($this -> pdo->query("SELECT * FROM Hygiene WHERE ProdID=$prodID")->fetchAll(PDO::FETCH_ASSOC)) == 1)
        {
            return new HygieneModel($this -> getHygieneProduct($prodID));
        }
        else if(count($this -> pdo->query("SELECT * FROM Supplement WHERE ProdID=$prodID")->fetchAll(PDO::FETCH_ASSOC)) == 1)
        {
            return new SupplementModel($this -> getSupplementProduct($prodID));
        }
        else if(count($this -> pdo->query("SELECT * FROM Pharmacy WHERE ProdID=$prodID")->fetchAll(PDO::FETCH_ASSOC)) == 1)
        {
            return new PharmacyModel($this -> getPharmacyProduct($prodID));
        }
    }

    public function getLatestID()
    {
        $query = "SELECT ProdID FROM Products
        ORDER BY ProdID DESC
        LIMIT 1";
        $statement = $this -> pdo -> query($query);
        return $statement -> fetch(PDO::FETCH_ASSOC);
    }

    public function addImageToDB($productImage, $productID)
    {
        $query = "INSERT INTO Resources (PathToImage, ProdID)
                     VALUES('$productImage', $productID)";
        $stmnt = $this->pdo->prepare($query);
        $stmnt->execute();
    }

    public function removeImageFromDB($productID)
    {
        $query = "DELETE FROM Resources WHERE ProdID=$productID";
        $stmnt = $this->pdo->prepare($query);
        $stmnt->execute();
    }

    public function getProductImage($productId)
    {
        $query = "SELECT * FROM Resources WHERE ProdID=$productId";
        $statement = $this -> pdo -> query($query);
        return $statement -> fetch(PDO::FETCH_ASSOC);
    }

     public function registerSubscriberToDB($name, $email)
    {
        $date = date("m/d/Y");

        $query = "INSERT INTO Customer (UserName, EmailAdd, RegitrationDate)
                     VALUES('$name', '$email', '$date')";
        $stmnt = $this->pdo->prepare($query);
        $stmnt->execute();
    }

    public function getCustomersList()
    {
        $query = "SELECT * FROM Customer";
        $statement = $this -> pdo -> query($query);
        return $statement -> fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCustomerInfo($id)
    {
        $query = "SELECT * FROM Customer WHERE ID=$id";
        $statement = $this -> pdo -> query($query);
        return $statement -> fetch(PDO::FETCH_ASSOC);

    }

    public function removeSubscriber($id)
    {
        $query = "DELETE FROM Customer Where ID=$id";
        $stmnt = $this->pdo->prepare($query);
        $stmnt->execute();
    }

    public function executeCostumQuery($query)
    {
        try {
            $this->pdo->exec($query);
            return true; 
        } 
        catch (\PDOException $e) { return false; }
    }
}

?>