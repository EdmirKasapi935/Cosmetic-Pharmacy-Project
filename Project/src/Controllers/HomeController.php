<?php

namespace App\Controllers;

require __DIR__.'/../ProductModel.php';

use App\Controller;
use App\DBAccessor;
use App\ProductModel;
use App\Models\CosmeticHairModel;
use App\Models\PharmacyModel;
use App\Models\SupplementModel;

class HomeController extends Controller
{
    private $cosmeticProdHair = array();
    private $pharmacyProd = array();
    private $supplementsProd = array();
    private $allProducts = array();

    private $dbAccessor;

    public function __construct() { $this->dbAccessor = new DBAccessor(); }

    public function index()
    {
        $selectedCategory = $_GET['selected'] ?? 'all'; // Default to 'all' if no query parameter is provided

        // Fetch products based on the selected category
        switch ($selectedCategory) {
            case 'dermokozmetike':
                foreach ($this->dbAccessor->getCosmeticHairProducts() as $hairProduct)
                    $this->cosmeticProdHair[] = new CosmeticHairModel($hairProduct);

                foreach ($this->cosmeticProdHair as $hairProduct) 
                    $hairProduct->setImage($this->dbAccessor->getProductImage($hairProduct->getProdID())['PathToImage']);
                

                $this->render('index', ['products' => $this->cosmeticProdHair]); // Render with only hair products              
                break;
            case 'farmaci':
                foreach ($this->dbAccessor->getPharmacyProducts() as $pharmacyProduct)
                    $this->pharmacyProd[] = new PharmacyModel($pharmacyProduct);

                foreach ($this->pharmacyProd as $pharmacyProduct)
                    $pharmacyProduct->setImage($this->dbAccessor->getProductImage($pharmacyProduct->getProdID())['PathToImage']);

                $this->render('index', ['products' => $this->pharmacyProd]); // Render with only pharmacy products
                break;
            case 'suplemente':
                foreach ($this->dbAccessor->getSupplementProducts() as $supplementProduct)
                    $this->supplementsProd[] = new SupplementModel($supplementProduct);

                foreach ($this->supplementsProd as $supplementProduct)
                    $supplementProduct->setImage($this->dbAccessor->getProductImage($supplementProduct->getProdID())['PathToImage']);
                
                $this->render('index', ['products' => $this->supplementsProd]); // Render with only supplement products
                break;
            default:
                foreach ($this->dbAccessor->getAllProducts() as $product)
                    $this->allProducts[] = new ProductModel($product);

                foreach ($this->allProducts as $product)
                    $product->setImage($this->dbAccessor->getProductImage($product->getProdID())['PathToImage']);
                
                $this->render('index', ['products' => $this->allProducts]); // Render with all products
                break;
        }
    }

    public function termsAndConditions() { $this->render('termsAndConditions'); }
    
    public function privacyPolicy() { $this->render('privacyPolicy'); }

    public function quiz() { $this->render('quizPage'); }

    public function buyGuide() { $this->render('BuyGuidePage'); }

    public function delivery() { $this->render('dergesaPage'); }

    public function track() { $this->render('gjurmimiPage'); }

    public function contactUs() { $this->render('kontaktPage'); }

    public function paymentMethods() { $this->render('menyratpagesesPage'); }

    public function ourMission() { $this->render('misioniPage'); }

    public function blog() { 
        if (!isset($_GET["blogPage"])) {
            $this->render('blog'); 
            return;
        }

        switch ($_GET["blogPage"]) {
            case '1':
                $this->render('blogPage1');
                return;
            case '2':
                $this->render('blogPage2');
                return;
            case '3':
                $this->render('blogPage3');
                return;
        };
    }
}