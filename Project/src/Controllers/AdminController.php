<?php

namespace App\Controllers;

require "vendor/autoload.php";
require './src/DBAccessor.php';

use App\Controller;
use App\DBAccessor;
use App\ProductModel;
use App\Models\CosmeticHairModel;
use App\Models\CosmeticSkinModel;
use App\Models\HygieneModel;
use App\Models\PharmacyModel;
use App\Models\SupplementModel;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use PDO;
use Dotenv;

session_start();

class AdminController extends Controller
{
    private $dbAccessor;
    private $dotenv;

    public function __construct() 
    { 
      $this->dbAccessor = new DBAccessor(); 
      $this->dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '\\..\\..\\');
      $this->dotenv->load();
    }
   
    private function authAdminGuard()
    {
      if(!isset($_SESSION["adminAccessToken"]))
      {
         echo "<script> window.location.replace('/adminLogin') </script>";
      }
    }

    private function loginGuard()
    {
      if(isset($_SESSION["adminAccessToken"]))
      {
         echo "<script> window.location.replace('/admin') </script>";
      }
    }

    public function showLogin()
    {
      $this->loginGuard();

      $this -> render("adminLogin");
    }

    public function processLogIn()
    {
       $username = filter_input(INPUT_POST, "loginUsername", FILTER_SANITIZE_SPECIAL_CHARS);
       $password = filter_input(INPUT_POST, "loginPassword", FILTER_SANITIZE_SPECIAL_CHARS);

       $salt = "682b78db75a7a";
       $salted = $password.$salt;
       $hashed = md5($salted);
       $env_variables = parse_ini_file("./.env");

       if($username == $env_variables["ADMIN_USERNAME"] && $hashed == $env_variables["ADMIN_PASSWORD"])
       {
         $_SESSION["adminAccessToken"] = uniqid();
         echo "<script>
               alert('Login successful!');
               window.location.replace('/adminLogin');
              </script>";
       }
       else
       {
          echo "<script>
               alert('Invalid Email or Password entered!');
               window.location.replace('/adminLogin'); 
              </script>";
              
       }

    }

    public function processLogOut()
    {

      if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['tokenval']==$_SESSION['adminAccessToken'])
      {
        session_destroy();
        echo "<script>window.location.replace('/adminLogin');</script>";
      }
      
    }

    public function index()
    {
        $this->authAdminGuard();

        $rows = $this -> dbAccessor -> getAllProducts() ;

        $this->render("adminIndex", ["rows" => $rows]);
    }

    public function showAddForm()
    {
        $this->authAdminGuard();

        $this->render("adminAddProduct",[]);
    }

   
    public function addProduct()
    {
        $this->authAdminGuard();

        $request = $_POST;
        
        echo "<br> <br> <br>";
        $product = "";

        switch($request["productType"]){
            case "Hair": $info = $this -> getHairAssoc($request);
                         if(!$this->validateNotNull($info))
                         {
                           echo "<script> window.location.replace('addProduct') </script>";
                           break;
                         }
                         $product = new CosmeticHairModel($info);            
                         $this -> dbAccessor -> executeCostumQuery($product->toCreateQuery());
                         $id = $this -> dbAccessor -> getLatestID();
                         echo "<script> window.location.replace('editProduct?editing=".$id["ProdID"]."') </script>";
                         break;
            case "Skin": $info = $this -> getSkinAssoc($request);
                         if(!$this->validateNotNull($info))
                         {
                           echo "<script> window.location.replace('addProduct') </script>";
                           break;
                         }
                         $product = new CosmeticSkinModel($info);            
                         $this -> dbAccessor -> executeCostumQuery($product->toCreateQuery());
                         $id = $this -> dbAccessor -> getLatestID();
                         echo "<script> window.location.replace('editProduct?editing=".$id["ProdID"]."') </script>";
                         break;
            case "Supplement": $info = $this -> getSupplementAssoc($request);
                               if(!$this->validateNotNull($info))
                               {
                                 echo "<script> window.location.replace('addProduct') </script>";
                                 break;
                               }
                               $product = new SupplementModel($info);            
                               $this -> dbAccessor -> executeCostumQuery($product->toCreateQuery());
                               $id = $this -> dbAccessor -> getLatestID();
                               echo "<script> window.location.replace('editProduct?editing=".$id["ProdID"]."') </script>";
                               break;
            case "Hygiene": $info = $this -> getHygieneAssoc($request);
                            if(!$this->validateNotNull($info))
                            {
                              echo "<script> window.location.replace('addProduct') </script>";
                              break;
                            }
                            $product = new HygieneModel($info);            
                            $this -> dbAccessor -> executeCostumQuery($product->toCreateQuery());
                            $id = $this -> dbAccessor -> getLatestID();
                            echo "<script> window.location.replace('editProduct?editing=".$id["ProdID"]."') </script>";
                            break;
            case "Pharmaceutical": $info = $this -> getPharmacyAssoc($request);
                                   if(!$this->validateNotNull($info))
                                   {
                                     echo "<script> window.location.replace('addProduct') </script>";
                                     break;
                                   }
                                   $product = new PharmacyModel($info);            
                                   $this -> dbAccessor -> executeCostumQuery($product->toCreateQuery());
                                   $id = $this -> dbAccessor -> getLatestID();
                                   echo "<script> window.location.replace('editProduct?editing=".$id["ProdID"]."') </script>";
                                   break;
                               
        }

    }

    public function showEditForm()
    {
        $this->authAdminGuard();

        $id = $_GET["editing"];
        $product = $this -> dbAccessor -> getProductInfo($id);

        if(!empty($this -> dbAccessor -> getProductImage($id)))
        {
          $image = $this -> dbAccessor -> getProductImage($id);
          $product -> setImage($image["PathToImage"]);
        }
        else
        {
          $product -> setImage(null);
        }

        $this->render("inspectProduct", ["product" => $product ]);
    }
    
    public function editProduct()
    {
        $this->authAdminGuard();

        $request = $_POST;

        $product = $this -> dbAccessor -> getProductInfo($request["prodID"]);

        switch($product->getProductType())
        {
            case "Hair": $info = $this->getHairAssoc($request);
                         if(!$this->validateNotNull($info))
                         {
                           echo "<script> window.location.replace('editProduct?editing=".$product->getProdID()."'); </script> </script>";
                           break;
                         }
                         $this -> updateHairInfos($product, $info);
                         echo "<script> alert('Product edited successfully');
                                        window.location.replace('editProduct?editing=".$product->getProdID()."'); </script>";
                         break;
            case "Skin": $info = $this->getSkinAssoc($request);
                         if(!$this->validateNotNull($info))
                         {
                           echo "<script> window.location.replace('editProduct?editing=".$product->getProdID()."'); </script> </script>";
                           break;
                         }
                         $this -> updateSkinInfos($product, $info);
                         echo "<script> alert('Product edited successfully');
                                        window.location.replace('editProduct?editing=".$product->getProdID()."'); </script>";
                         break;
            case "Supplement": $info = $this->getSupplementAssoc($request);
                               if(!$this->validateNotNull($info))
                               {
                                 echo "<script> window.location.replace('editProduct?editing=".$product->getProdID()."'); </script> </script>";
                                 break;
                               }
                               $this -> updateSupplementInfos($product, $info);
                               echo "<script> alert('Product edited successfully');
                                              window.location.replace('editProduct?editing=".$product->getProdID()."'); </script>";
                               break;
            case "Hygiene": $info = $this->getHygieneAssoc($request);
                            if(!$this->validateNotNull($info))
                            {
                              echo "<script> window.location.replace('editProduct?editing=".$product->getProdID()."'); </script> </script>";
                              break;
                            }
                            $this -> updateHygieneInfos($product, $info);
                            echo "<script> alert('Product edited successfully');
                                           window.location.replace('editProduct?editing=".$product->getProdID()."'); </script>";
                            break;
            case "Pharmaceutical": $info = $this->getPharmacyAssoc($request);
                                   if(!$this->validateNotNull($info))
                                   {
                                     echo "<script> window.location.replace('editProduct?editing=".$product->getProdID()."'); </script> </script>";
                                     break;
                                   }
                                   $this -> updatePharmacyInfos($product, $info);
                                   echo "<script> alert('Product edited successfully');
                                                  window.location.replace('editProduct?editing=".$product->getProdID()."'); </script>";
                                   break;
                
        }

    }

    public function deleteProduct()
    {
        $this->authAdminGuard();

        $prodID = $_POST["productToDelete"];
        if(!empty($this -> dbAccessor -> getProductImage($prodID))) //if the product already has an image
        {
          $this->removeImageWithID($prodID); //it is removed
        }
        $product = $this -> dbAccessor -> getProductInfo($prodID);
        $this -> dbAccessor -> executeCostumQuery($product -> toDeleteQuery());

        echo "<script> window.location.replace('/admin') </script>";
    }

    private function getHairAssoc($request)
    {
        return array( "Name" => filter_var($request["productName"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Brand"  => filter_var($request["productBrand"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Price" => filter_var($request["productPrice"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "CurrentStock" => filter_var($request["productStock"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Description" => filter_var($request["productDescription"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Tags" => filter_var($request["productTags"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Contains" => filter_var($request["productContains"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "SuitableFor" => filter_var($request["productSuitableFor"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Usage" => filter_var($request["productUsage"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "IsAccessory" => filter_var($request["productIsAccessory"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "HasStock" => 1, 
                      "HairType" => filter_var($request["productHairType"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "SpecificFor" => filter_var($request["productSpecificFor"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Mass" => filter_var($request["productMass"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Ingredients" => filter_var($request["productIngredients"],FILTER_SANITIZE_SPECIAL_CHARS) );
    }

    private function getSkinAssoc($request)
    {
        return array( "Name" => filter_var($request["productName"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Brand"  => filter_var($request["productBrand"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Price" => filter_var($request["productPrice"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "CurrentStock" => filter_var($request["productStock"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Description" => filter_var($request["productDescription"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Tags" => filter_var($request["productTags"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Contains" => filter_var($request["productContains"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "SuitableFor" => filter_var($request["productSuitableFor"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Usage" => filter_var($request["productUsage"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "IsAccessory" => filter_var($request["productIsAccessory"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "HasStock" => 1, 
                      "SkinType" => filter_var($request["productSkinType"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "SpecificFor" => filter_var($request["productSpecificFor"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Mass" => filter_var($request["productMass"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Ingredients" => filter_var($request["productIngredients"],FILTER_SANITIZE_SPECIAL_CHARS) );
    }

    private function getSupplementAssoc($request)
    {
        return array( "Name" => filter_var($request["productName"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Brand"  => filter_var($request["productBrand"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Price" => filter_var($request["productPrice"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "CurrentStock" => filter_var($request["productStock"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Description" => filter_var($request["productDescription"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Tags" => filter_var($request["productTags"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Contains" => filter_var($request["productContains"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "SuitableFor" =>filter_var( $request["productSuitableFor"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Usage" => filter_var($request["productUsage"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "IsAccessory" => filter_var($request["productIsAccessory"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "HasStock" => 1, 
                      "Mass" => filter_var($request["productMass"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Ingredients" => filter_var($request["productIngredients"],FILTER_SANITIZE_SPECIAL_CHARS) );
    }

    private function getHygieneAssoc($request)
    {
        return array( "Name" => filter_var($request["productName"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Brand"  => filter_var($request["productBrand"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Price" =>filter_var( $request["productPrice"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "CurrentStock" => filter_var($request["productStock"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Description" => filter_var($request["productDescription"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Tags" =>filter_var( $request["productTags"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Contains" => filter_var($request["productContains"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "SuitableFor" => filter_var($request["productSuitableFor"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Usage" => filter_var($request["productUsage"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "IsAccessory" => filter_var($request["productIsAccessory"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "HasStock" => 1, 
                      "Mass" =>filter_var( $request["productMass"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Ingredients" => filter_var($request["productIngredients"],FILTER_SANITIZE_SPECIAL_CHARS) );
    }

    private function getPharmacyAssoc($request)
    {
        return array( "Name" =>filter_var($request["productName"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Brand"  =>filter_var($request["productBrand"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Price" => filter_var($request["productPrice"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "CurrentStock" => filter_var($request["productStock"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Description" =>filter_var( $request["productDescription"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Tags" => filter_var($request["productTags"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Contains" =>filter_var( $request["productContains"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "SuitableFor" =>filter_var( $request["productSuitableFor"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Usage" => filter_var($request["productUsage"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "IsAccessory" => filter_var($request["productIsAccessory"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "HasStock" => 1, 
                      "Prescription" => filter_var($request["productPrescription"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Mass" => filter_var($request["productMass"],FILTER_SANITIZE_SPECIAL_CHARS), 
                      "Ingredients" => filter_var($request["productIngredients"],FILTER_SANITIZE_SPECIAL_CHARS) );
    }

    private function updateHairInfos($product, $info)
    {
        $product -> setName($info["Name"]);
        $product -> setBrand($info["Brand"]);
        $product -> setPrice($info["Price"]);
        $product -> setCurrentStock($info["CurrentStock"]);
        $product -> setTags(explode(",",$info["Tags"]));
        $product -> setDescription($info["Description"]);
        $product -> setContains(explode(",",$info["Contains"]));
        $product -> setSuitableFor(explode(",",$info["SuitableFor"]));
        $product -> setUsage($info["Usage"]);
        $product -> setIsAccessory($info["IsAccessory"]);
        $product -> setHairType(explode(",",$info["HairType"]));
        $product -> setSpecificFor(explode(",",$info["SpecificFor"]));
        $product -> setMass($info["Mass"]);
        $product -> setIngredients(explode(",",$info["Ingredients"]));

        $this -> dbAccessor -> executeCostumQuery($product -> toUpdateQuery());
    }

    private function updateSkinInfos($product, $info)
    {
        $product -> setName($info["Name"]);
        $product -> setBrand($info["Brand"]);
        $product -> setPrice($info["Price"]);
        $product -> setCurrentStock($info["CurrentStock"]);
        $product -> setTags(explode(",",$info["Tags"]));
        $product -> setDescription($info["Description"]);
        $product -> setContains(explode(",",$info["Contains"]));
        $product -> setSuitableFor(explode(",",$info["SuitableFor"]));
        $product -> setUsage($info["Usage"]);
        $product -> setIsAccessory($info["IsAccessory"]);
        $product -> setSkinType(explode(",",$info["SkinType"]));
        $product -> setSpecificFor(explode(",",$info["SpecificFor"]));
        $product -> setMass($info["Mass"]);
        $product -> setIngredients(explode(",",$info["Ingredients"]));

        $this -> dbAccessor -> executeCostumQuery($product -> toUpdateQuery());
    }

    private function updateSupplementInfos($product, $info)
    {
        $product -> setName($info["Name"]);
        $product -> setBrand($info["Brand"]);
        $product -> setPrice($info["Price"]);
        $product -> setCurrentStock($info["CurrentStock"]);
        $product -> setTags(explode(",",$info["Tags"]));
        $product -> setDescription($info["Description"]);
        $product -> setContains(explode(",",$info["Contains"]));
        $product -> setSuitableFor(explode(",",$info["SuitableFor"]));
        $product -> setUsage($info["Usage"]);
        $product -> setIsAccessory($info["IsAccessory"]);
        $product -> setMass($info["Mass"]);
        $product -> setIngredients(explode(",",$info["Ingredients"]));

        $this -> dbAccessor -> executeCostumQuery($product -> toUpdateQuery());
    }

    private function updateHygieneInfos($product, $info)
    {
        $product -> setName($info["Name"]);
        $product -> setBrand($info["Brand"]);
        $product -> setPrice($info["Price"]);
        $product -> setCurrentStock($info["CurrentStock"]);
        $product -> setTags(explode(",",$info["Tags"]));
        $product -> setDescription($info["Description"]);
        $product -> setContains(explode(",",$info["Contains"]));
        $product -> setSuitableFor(explode(",",$info["SuitableFor"]));
        $product -> setUsage($info["Usage"]);
        $product -> setIsAccessory($info["IsAccessory"]);
        $product -> setMass($info["Mass"]);
        $product -> setIngredients(explode(",",$info["Ingredients"]));

        $this -> dbAccessor -> executeCostumQuery($product -> toUpdateQuery());
    }

    private function updatePharmacyInfos($product, $info)
    {
        $product -> setName($info["Name"]);
        $product -> setBrand($info["Brand"]);
        $product -> setPrice($info["Price"]);
        $product -> setCurrentStock($info["CurrentStock"]);
        $product -> setTags(explode(",",$info["Tags"]));
        $product -> setDescription($info["Description"]);
        $product -> setContains(explode(",",$info["Contains"]));
        $product -> setSuitableFor(explode(",",$info["SuitableFor"]));
        $product -> setUsage($info["Usage"]);
        $product -> setIsAccessory($info["IsAccessory"]);
        $product -> setPrescription($info["Prescription"]);
        $product -> setMass($info["Mass"]);
        $product -> setIngredients(explode(",",$info["Ingredients"]));

        $this -> dbAccessor -> executeCostumQuery($product -> toUpdateQuery());
    }

    private function validateNotNull($info)
    {
        foreach($info as $key => $val)
        {
           if(trim($val) == "")
           {
              echo "<script> alert('Please fill the fields correctly') </script>";
              return false;
           }
        }
        
        return true;
    
    }

   //The functions below are linked to the images

  public function insertImage()
  {
      $this->authAdminGuard();

      $files = $_FILES;
      $prodID = $_POST["ProdID"];
      if(!empty($this -> dbAccessor -> getProductImage($prodID))) //if the product already has an image
      {
        $this->removeImageWithID($prodID); //it is removed
      }
      $product = $this -> dbAccessor -> getProductInfo($prodID);
      $product -> setImage( $this -> uploadImages($files, $prodID, $product->getProductType()));

      echo "<script> 
            window.location.replace('editProduct?editing=".$product->getProdID()."'); 
            </script>";
                                 
      
  }

  public function removeImage()
  {
    $this->authAdminGuard();

    $id = $_POST["imgToDelete"];
    $imgInfo = $this -> dbAccessor -> getProductImage($id);
    $path = $imgInfo["PathToImage"];
    unlink($path);
    if(!file_exists($path))
    {
      $this -> dbAccessor -> removeImageFromDB($id);

      echo "<script>
            alert('Image removed successfully!'); 
            window.location.replace('editProduct?editing=".$id."'); 
            </script>";
    }
    else
    {
       echo "<script>
            alert('Error Removing Image!'); 
            window.location.replace('editProduct?editing=".$id."'); 
            </script>";
    }
    
  }

  public function removeImageWithID($id) //this function was created for the cases when the image needs to be deleted without pressing the delete button next to it
  {
    $imgInfo = $this -> dbAccessor -> getProductImage($id);
    $path = $imgInfo["PathToImage"];
    unlink($path);
    $this->dbAccessor->removeImageFromDB($id);
  }

  private function uploadImages($files, $prodID, $type)//this function can work for multiple files too, add "multiple" attribute to the image upload form
  {
    if (empty($files)) {
      echo '<script>alert("no file was uploaded - is file_uploads enabled in your php.ini?")</script>';
      return false;
    }

    $filenum = 1; //count($files["productImage"]["name"]); enable this piece of code here for multiple file support

    for ($i = 0; $i < $filenum; $i++) {

      if ($files["productImage"]["error"][$i] !== UPLOAD_ERR_OK) {
        switch ($files["productImage"]["error"][$i]) {
          case UPLOAD_ERR_PARTIAL:
            echo '<script>alert("file only partially uploaded")</script>';
            return false;
            break;
          case UPLOAD_ERR_NO_FILE:
            echo '<script>alert("No file was uploaded")</script>';
            return false;
            break;
          case UPLOAD_ERR_EXTENSION:
            echo '<script>alert("file stopped by a php extension")</script>';
            return false;
            break;
          case UPLOAD_ERR_FORM_SIZE:
            echo '<script>alert("file exceeds maximum size")</script>';
            return false;
            break;
          case UPLOAD_ERR_INI_SIZE:
            echo '<script>alert("file exceeds maximum size in php.ini")</script>';
            return false;
            break;
          case UPLOAD_ERR_FORM_SIZE:
            echo '<script>alert("file exceeds maximum size")</script>';
            return false;
            break;
          case UPLOAD_ERR_NO_TMP_DIR:
            exit("Temporary folder not found");
            echo '<script>alert("Temporary folder not found")</script>';
            return false;
            break;
          case UPLOAD_ERR_CANT_WRITE:
            echo '<script>alert("failed to write file")</script>';
            return false;
            break;
          default:
            echo '<script>alert("unknown file error")</script>';
            return false;
            break;
        }
      }

      $mime_types = ["image/gif", "image/png", "image/jpeg"];

      if (!in_array($files["productImage"]["type"][$i], $mime_types)) {
        echo '<script>alert("Invalid file type entered")</script>';
        return false;
      }
    }

    $folder = "./img";

    switch ($type) {
      case "Skin":
        $folder = $folder . "/cosmetic/";
        break;
      case "Hair":
        $folder = $folder . "/cosmetic/";
        break;
      case "Supplement":
        $folder = $folder . "/supplement/";
        break;
      case "Hygiene":
        $folder = $folder . "/hygiene/";
        break;
      case "Pharmaceutical":
        $folder = $folder . "/pharmacy/";
        break;
    }
            
    $names = $files["productImage"]["name"];
    $tmp_names = $files["productImage"]["tmp_name"];

    $files_array = array_combine($tmp_names, $names);

    foreach ($files_array as $tmp_name => $img_name) {
      if (file_exists($folder . str_replace(" ", "_", $img_name))) {
        echo "<script> alert('image " . $img_name . " already exists') </script>";
        return;
      }
    }

    foreach ($files_array as $tmp_name => $img_name) {

      $img_name = str_replace(" ", "_", $img_name);

      move_uploaded_file($tmp_name, $folder . $img_name);
      $this->dbAccessor->addImageToDB( $folder.$img_name, $prodID);
    }

    echo "<script> 
          alert('files uploaded successfully')
          </script>";

    return $folder.$img_name;
  }

  //the functions below are for the customers menu
  public function showCustomersMenu()
  {
    $this -> authAdminGuard();

    $customerList = $this -> dbAccessor -> getCustomersList();

    $this -> render("adminCustomerMenu",["customerList" => $customerList ]);
  }

  public function unsubCustomer()
  {
    $this -> authAdminGuard();

    $request = $_POST;

    $id = $request["customerToDelete"];

    $customer = $this -> dbAccessor -> getCustomerInfo($id);

    $this -> dbAccessor -> removeSubscriber($id);

    $this -> sendUnsubMail($customer["EmailAdd"]);

    echo "<script>
          alert('Customer Unsubscribed successfully. Mail notification sent.');
          window.location.replace('/admin/customerMenu');</script> 
          </script>";
      
  }

  //this function is for the message panel
  public function showMessagePanel()
  {
    $this -> authAdminGuard();

    $this -> render("adminMessagePanel");
  }

  public function deliverMessage()
  {
    $this -> authAdminGuard();

    $request = $_POST;
    $subscribers = $this -> dbAccessor -> getCustomersList();

    $subject = filter_var($request["messageSubject"],FILTER_SANITIZE_SPECIAL_CHARS);
    $body = filter_var($request["messageBody"], FILTER_SANITIZE_SPECIAL_CHARS);

    if (!$this->validateNotNull(array($subject, $body))) {
      echo "<script> window.location.replace('/admin/messagePanel')</script> </script>";
    }

    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = $_ENV['SMTP_HOST'];
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['SMTP_ADD'];
    $mail->Password = $_ENV['SMTP_PASS'];
    $mail->SMTPSecure = 'ssl';
    $mail->SMTPKeepAlive = true;
    $mail->Port = $_ENV['SMTP_PORT'];;
    $mail->setFrom($_ENV['SMTP_ADD']);


    foreach($subscribers as $sub)
    {
       $mail->addAddress($sub["EmailAdd"]);
    }

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $body;  

    $mail->send();

    echo "<script>
          alert('Messages delivered successfully.');
          window.location.replace('/admin/messagePanel');</script> 
          </script>";
  }

  private function sendUnsubMail($email)
  {
    $template = file_get_contents("./styles/mailTemplates/unsubnotif.php");
  
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = $_ENV['SMTP_HOST'];
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['SMTP_ADD'];
    $mail->Password = $_ENV['SMTP_PASS'];
    $mail->SMTPSecure = 'ssl';
    $mail->SMTPKeepAlive = true;
    $mail->Port = $_ENV['SMTP_PORT'];;
    $mail->setFrom($_ENV['SMTP_ADD']);

    $mail->addAddress($email);
    
    $mail->isHTML(true);
    $mail->Subject = "Termination of Newsletter Subscription";
    $mail->Body = $template;  

    $mail->send();

  }

}

?>