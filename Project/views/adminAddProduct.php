<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <style>
            /* General Reset */
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

/* Body Styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f6f8;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 100vh;
    padding-top: 40px;
}

/* Main Container */
.container {
    background-color: #ffffff;
    padding: 30px;
    width: 400px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Form Elements */
form label {
    display: block;
    margin-bottom: 6px;
    font-weight: bold;
    margin-top: 15px;
}

form input[type="text"],
form input[type="number"],
form textarea,
form select {
    width: 100%;
    padding: 8px 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
}

/* Textarea Specific */
textarea {
    resize: vertical;
    min-height: 60px;
}

/* Button Styling */
form input[type="submit"],
a button {
    margin-top: 20px;
    padding: 10px;
    width: 100%;
    background-color: #007bff;
    border: none;
    border-radius: 4px;
    color: white;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

form input[type="submit"]:hover,
a button:hover {
    background-color: #0056b3;
}

/* Checkbox */
input[type="checkbox"] {
    margin-right: 5px;
}

/* Responsive */
@media (max-width: 450px) {
    .container {
        width: 90%;
        padding: 20px;
    }
}
        </style>
    </head>

    <body>


        <div class="container">

            <form action="addProductReq" method="post">

                <label>Name:</label> <br> <input type="text" name="productName" placeholder="Product name here...." required> <br>
                <label style="margin-top:15px">Price:</label> <br> <input type="number" name="productPrice" min="1" value="1" required> <br>
                <label style="margin-top:15px">Brand:</label> <br> <input type="text" name="productBrand" placeholder="Product brand here...." required> <br>
                <label style="margin-top:15px">Stock:</label> <br> <input type="number" name="productStock" min="0" value="0" required> <br>
                <label style="margin-top:15px">Description:</label> <br> <textarea name="productDescription" required></textarea> <br>
                <label style="margin-top:15px">Ingedients:</label> <br> <input type="text" name="productContains" placeholder="Product contains...." required> <br>
                <label style="margin-top:15px">Suitable For:</label> <br>
                <select id="suitableFor" name="productSuitableFor" required>
                    <option value="Infants">Infants</option>
                    <option value="Toddlers">Toddlers</option>
                    <option value="Kids">Kids</option>
                    <option value="Teens">Teens</option>
                    <option value="Adults">Adults</option>
                    <option value="Elders">Elders</option>
                </select> <br>
                <label style="margin-top:15px">Tags:</label> <br> <input type="text" name="productTags" placeholder="Product tags here...." required> <br>
                <label style="margin-top:15px">Usage:</label> <br> <input type="text" name="productUsage" placeholder="Rub on skin...." required> <br>
                <label style="margin-top:15px">Type:</label> 
                <select id="type" name="productType" required>
                    <option disabled selected value=""> --Please select an option-- </option>
                    <option value="Skin">Skin</option>
                    <option value="Hair">Hair</option>
                    <option value="Supplement">Supplement</option>
                    <option value="Hygiene">Hygiene</option>
                    <option value="Pharmaceutical">Pharmaceutical</option>
                </select> <br>

                <input type="hidden" name="productIsAccessory" value="0">

                <label>Accessory:</label><input type="checkbox" name="productIsAccessory" value="1"> <br>

                <div id="secondpart"></div>

                <script>
                    var type = document.getElementById("type");
                    var div = document.getElementById("secondpart");

                    const skinForm = '<label style="margin-top:15px">Skin Type:</label> <br> <input type="text" name="productSkinType" required> \
                                      </select> <br>\
                                      <label style="margin-top:15px">Specific For:</label> <br> <input type="text" name="productSpecificFor" required> <br>\
                                      <label style="margin-top:15px">Mass:</label> <br> <input type="text" name="productMass" required> <br>\
                                      <label style="margin-top:15px">Ingredients:</label> <br> <input type="text" name="productIngredients" placeholder="calcium,Protein,Suplhate...." required> <br>';
 
                    const hairForm = '<label style="margin-top:15px">Hair Type:</label> <br> <input type="text" name="productHairType"  required> <br>\
                                      <label style="margin-top:15px">Specific For:</label> <br> <input type="text" name="productSpecificFor" required> <br>\
                                      <label style="margin-top:15px">Mass:</label> <br> <input type="text" name="productMass" required> <br>\
                                      <label style="margin-top:15px">Ingredients:</label> <br> <input type="text" name="productIngredients" placeholder="calcium,Protein,Suplhate...." required> <br>';

                    const hygieneForm = '<label style="margin-top:15px">Mass:</label> <br> <input type="text" name="productMass" required> <br>\
                                         <label style="margin-top:15px">Ingredients:</label> <br> <input type="text" name="productIngredients" placeholder="calcium,Protein,Suplhate...." required> <br>';

                    const suplementForm = '<label style="margin-top:15px">Mass:</label> <br> <input type="text" name="productMass" required> <br>\
                                           <label style="margin-top:15px">Ingredients:</label> <br> <input type="text" name="productIngredients" placeholder="calcium,Protein,Suplhate...."  required> <br>';

                    const pharmacyForm = '<input type="hidden" name="productPrescription" value="0">\
                                          <label>Accessory:</label> <input type="checkbox" name="productPrescription" value="1"> <br>\
                                          <label style="margin-top:15px">Mass:</label> <br> <input type="text" name="productMass" required> <br>\
                                          <label style="margin-top:15px">Ingredients:</label> <br> <input type="text" name="productIngredients" placeholder="calcium,Protein,Suplhate...." required> <br>';



                    type.addEventListener("input", function() {
                        switch (type.value) {
                            case "Skin":
                                div.innerHTML = skinForm;
                                break;
                            case "Hair":
                                div.innerHTML = hairForm;
                                break;
                            case "Supplement":
                                div.innerHTML = suplementForm;
                                break;
                            case "Hygiene":
                                div.innerHTML = hygieneForm;
                                break;
                            case "Pharmaceutical":
                                div.innerHTML = pharmacyForm;
                                break;
                        }
                    })
                </script>

                <input style="margin-top: 15px;" type="submit" name="addRequest" value="Add Product">

            </form>

                <a href="\admin"> <button style="margin-top:5px;"> Back to Menu </button> </a>

        </div>

        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>

    </body>

    </html>
