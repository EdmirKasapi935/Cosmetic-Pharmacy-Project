<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <style>
/* General Styles */
body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  margin: 0;
  padding: 20px;
  background-color: #f5f7fa;
  color: #333;
}

/* Admin Container */
.admin-container {
  max-width: 1200px;
  margin: 0 auto;
  background-color: #fff;
  padding: 30px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  border-radius: 8px;
}

/* Header Section */
.admin-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
}

.admin-header h1 {
  font-size: 28px;
  color: #007BFF;
  margin: 0;
}

.add-product-link button {
  background-color: #007BFF;
  border: none;
  color: white;
  padding: 8px 16px;
  border-radius: 5px;
  font-size: 14px;
  cursor: pointer;
  transition: background-color 0.2s ease-in-out;
    margin: 10px;
}

.add-product-link button:hover {
  background-color: #0056b3;
}

/* Table Styling */
.product-table table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
  background-color: #fff;
}

.product-table th,
.product-table td {
  padding: 12px 16px;
  border: 1px solid #ddd;
  text-align: left;
  vertical-align: top;
}

.product-table th {
  background-color: #007BFF;
  color: white;
  font-weight: 600;
}

.product-table td {
  background-color: #fafafa;
}

/* Action Buttons */
.product-table button,
.product-table input[type="submit"] {
  background-color: #28a745;
  border: none;
  color: white;
  padding: 6px 12px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 13px;
  transition: background-color 0.2s ease-in-out;
}

.product-table input[type="submit"] {
  background-color: #dc3545; /* Red for delete */
}

.product-table button:hover {
  background-color: #218838;
}

.product-table input[type="submit"]:hover {
  background-color: #c82333;
}

/* Responsive Design */
@media screen and (max-width: 768px) {
  .admin-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 15px;
  }

  .product-table table,
  .product-table thead,
  .product-table tbody,
  .product-table th,
  .product-table td,
  .product-table tr {
    display: block;
  }

  .product-table tr {
    margin-bottom: 15px;
    border-bottom: 2px solid #eee;
    padding-bottom: 10px;
  }

  .product-table td {
    padding-left: 50%;
    position: relative;
  }

  .product-table td::before {
    content: attr(data-label);
    position: absolute;
    left: 15px;
    width: 45%;
    padding-right: 10px;
    font-weight: bold;
    white-space: nowrap;
  }

  .product-table th {
    display: none;
  }
}
.logout-container input[type="submit"] {
    float: right;
    background-color: #007BFF;
    border: none;
    color: white;
    padding: 8px 16px;
    border-radius: 5px;
    font-size: 14px;
    cursor: pointer;
    transition: background-color 0.2s ease-in-out;
    margin: 10px;
}
.logout-container input[type="submit"]:hover {
    background-color: #45a049;
}
  </style>
</head>
<body>

  <div class="admin-container">
      <div class="logout-container">
          <form action="/adminLogout" method="post">
              <input type="hidden" value="<?php echo $_SESSION["adminAccessToken"] ?>" name="tokenval" >
              <input type="submit" value="Log Out">
          </form>
      </div>

    <div class="admin-header">
      <h1>Main Menu</h1>
      <a href="/admin/customerMenu" class="add-product-link">
        <button>View Subscribers</button>
      </a>
      <a href="/admin/messagePanel" class="add-product-link">
        <button>Message Panel</button>
      </a>
      <a href="admin/addProduct" class="add-product-link">
        <button>Add Product</button>
      </a>
    </div>


    <div class="product-table">
      <table border="1">
        <thead>
          <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Description</th>
            <th>Suitable For</th>
            <th>Tags</th>
            <th>Contains</th>
            <th colspan="2">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($rows as $row): ?>
            <tr>
              <td data-label="Name"><?= $row["Name"] ?></td>
              <td data-label="Price"><?= $row["Price"] ?></td>
              <td data-label="Stock"><?= $row["CurrentStock"] ?></td>
              <td data-label="Description"><?= $row["Description"] ?></td>
              <td data-label="Suitable For"><?= $row["SuitableFor"] ?></td>
              <td data-label="Tags"><?= $row["Tags"] ?></td>
              <td data-label="Contains"><?= $row["Contains"] ?></td>
              <td data-label="Delete">
                <form action="admin/deleteProduct" method="POST" class="delete-form">
                  <input type="hidden" name="productToDelete" value="<?= $row["ProdID"] ?>">
                  <input type="submit" name="deleteRequest" value="Delete">
                </form>
              </td>
              <td data-label="View/Edit">
                <a href="admin/editProduct?editing=<?= $row["ProdID"] ?>">
                  <button>View</button>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
    }
  </script>
</body>
</html>
