<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .inspect-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .inspect-container h3 {
            margin-top: 30px;
            font-size: 20px;
            color: #333;
        }

        .inspect-container form {
            margin-top: 20px;
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .inspect-container input[type="text"],
        .inspect-container input[type="number"],
        .inspect-container textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .inspect-container input[type="submit"],
        .inspect-container button {
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .inspect-container input[type="submit"]:hover,
        .inspect-container button:hover {
            background-color: #0056b3;
        }

        .inspect-container ul {
            list-style-type: none;
            padding: 0;
        }

        .inspect-container ul li {
            margin-bottom: 15px;
        }

        .inspect-container img {
            border-radius: 6px;
            margin-right: 10px;
            vertical-align: middle;
        }
        .image-container h3 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 10px;
        }

        .image-container ul {
            list-style-type: none;
            padding: 0;
        }

        .image-container ul li {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .image-container ul li img {
            border-radius: 8px;
            border: 1px solid #ddd;
            width: 150px;
            height: 150px;
            object-fit: cover;
        }

        .image-container ul li form {
            display: inline-block;
        }

        .image-container ul li input[type="submit"] {
            background-color: #dc3545; /* Bootstrap-style red */
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .image-container ul li input[type="submit"]:hover {
            background-color: #c82333;
        }
    </style>
</head>

<body>


    <div class="inspect-container">

        <form action="editProductReq" method="post">

            <label>Name:</label> <br> <input type="text" name="productName" value="<?php echo $product->getName() ?>" required> <br>
            <label style="margin-top:15px">Price:</label> <br> <input type="number" name="productPrice" min="1" value="<?php echo $product->getPrice() ?>" required> <br>
            <label style="margin-top:15px">Brand:</label> <br> <input type="text" name="productBrand" value="<?php echo $product->getBrand() ?>" required> <br>
            <label style="margin-top:15px">Stock:</label> <br> <input type="number" name="productStock" min="0" value="<?php echo $product->getCurrentStock() ?>" required> <br>
            <label style="margin-top:15px">Description:</label> <br> <textarea name="productDescription" required><?php echo $product->getDescription() ?></textarea> <br>
            <label style="margin-top:15px">Contains:</label> <br> <input type="text" name="productContains" value="<?php echo implode(',', $product->getContains()) ?>" required> <br>
            <label style="margin-top:15px">Suitable For:</label> <br> <input type="text" name="productSuitableFor" value="<?php echo   implode(',', $product->getSuitableFor()) ?>" required> <br>
            <label style="margin-top:15px">Tags:</label> <br> <input type="text" name="productTags" value="<?php echo implode(',', $product->getTags()) ?>" required> <br>
            <label style="margin-top:15px">Usage:</label> <br> <input type="text" name="productUsage" value="<?php echo $product->getUsage() ?>" required> <br>

            <input type="hidden" name="productIsAccessory" value="0">
            <label>Accessory:</label><input type="checkbox" name="productIsAccessory" value="1" <?php if ($product->getIsAccessory() == 1) echo "checked"; ?>> <br>
            
            <input type="hidden" name="productType" value="<?php echo $product->getProductType() ?>" >
            <input type="hidden" name="prodID" value="<?php echo $product->getProdID() ?>" >

            <div id="secondpart" style="margin-top: 15px;">

            <?php
            
            switch ($product->getProductType()) {
              case "Skin":
                  $skinForm = '<label style="margin-top:15px">Skin Type:</label> <br> <input type="text" name="productSkinType" value="'.implode(",", $product->getSkinType()).'" required>
                               <label style="margin-top:15px">Specific For:</label> <br> <input type="text" name="productSpecificFor" value="'.implode(",", $product->getSpecificFor()).'" required> <br>
                               <label style="margin-top:15px">Mass:</label> <br> <input type="text" name="productMass" value="'.$product->getMass().'" required> <br>
                               <label style="margin-top:15px">Ingredients:</label> <br> <input type="text" name="productIngredients" value="'.implode(",", $product->getIngredients()).'" required> <br>';
                  echo $skinForm;
                  break;
              case "Hair":
                  $hairForm = '<label style="margin-top:15px">Hair Type:</label> <br> <input type="text" name="productHairType" value="'.implode(",", $product->getHairType()).'"  required> <br>
                               <label style="margin-top:15px">Specific For:</label> <br> <input type="text" name="productSpecificFor" value="'.implode(",", $product->getSpecificFor()).'"  required> <br>
                               <label style="margin-top:15px">Mass:</label> <br> <input type="text" name="productMass" value="'.$product->getMass().'"  required> <br>
                               <label style="margin-top:15px">Ingredients:</label> <br> <input type="text" name="productIngredients" value="'.implode(",", $product->getIngredients()).'"  required> <br>';
                  echo $hairForm;
                  break;
              case "Supplement":
                  $suplementForm = '<label style="margin-top:15px">Mass:</label> <br> <input type="text" name="productMass" value="'.$product->getMass().'" required> <br>
                                    <label style="margin-top:15px">Ingredients:</label> <br> <input type="text" name="productIngredients" value="'.implode(",", $product->getIngredients()).'"  required> <br>';
                  echo $suplementForm;
                  break;
              case "Hygiene":
                  $hygieneForm = '<label style="margin-top:15px">Mass:</label> <br> <input type="text" name="productMass" value="'.$product->getMass().'" required> <br>
                                  <label style="margin-top:15px">Ingredients:</label> <br> <input type="text" name="productIngredients" value="'.implode(",", $product->getIngredients()).'" required> <br>';
                  echo $hygieneForm;
                  break;
              case "Pharmaceutical":
                  $checkedPrescription = '<input type="hidden" name="productPrescription" value="false">
                                          <label>Prescription:</label> <input type="checkbox" name="productPrescription" value="true" checked> <br>';
                  $uncheckedPrescription = '<input type="hidden" name="productPrescription" value="0">
                                            <label>Prescription:</label> <input type="checkbox" name="productPrescription" value="1"> <br>';
                  $pharmacyForm = '<label style="margin-top:15px">Mass:</label> <br> <input type="text" name="productMass" value="'.$product->getMass().'" required> <br>
                                   <label style="margin-top:15px">Ingredients:</label> <br> <input type="text" name="productIngredients" value="'.implode(",", $product->getIngredients()).'" required> <br>';

                  if($product->getPrescription() == 1)
                  {
                     echo $checkedPrescription;
                  } 
                  else
                  {
                     echo $uncheckedPrescription;
                  }                
                  echo $pharmacyForm;
                  break;
          }
            
            ?>

            </div><br>

            <input type="submit" name="editRequest" value="Edit Product">

        </form>

        <form action="/admin/insertImage" method="post" enctype="multipart/form-data" style="margin-top: 20px;" >

            <label>Add/Change Image: </label><br> <input type="file" name="productImage[]" id="productImage" accept="image/jpeg,image/png" required><br>
            <input type="hidden" value="<?php echo $product->getProdID() ?>" name="ProdID">
            <input type="submit" value="Insert Picture" name="pictureAddRequest" style="margin-top: 5px;">

        </form>

        

        <a href="\admin"> <button style="margin-top:15px;"> Main Menu </button> </a>

    </div>

    
    <div class="image-container">
    <h3>Images</h3>
    <ul>
    <?php
     
      if(empty($product->getImage()))
      {
        echo "There are currently no images for this product";
      }
      else
      {
          $imgstring = ".".$product->getImage();
          echo '<li><img src="'.$imgstring.'" width="150px" height="150px" ><form action="removeImage" method="post" > <input type="hidden" value="'.$product->getProdID().'" name="imgToDelete"> <input type="submit" value="Remove Image" name="imageDeleteRequest"> </form> </li>';
      }

    ?>    
    </ul>
    </div>

    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

</body>

</html>