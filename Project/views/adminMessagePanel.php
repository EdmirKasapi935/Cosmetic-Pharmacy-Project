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
.product-table {
  width: 100%;
  max-width: 500px;
  margin: 40px auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 12px;
  background-color: #f9f9f9;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  font-family: Arial, sans-serif;
}

form {
  display: flex;
  flex-direction: column;
}

label {
  margin-top: 15px;
  font-weight: bold;
}

.product-table input[type="text"],
.product-table textarea {
  margin-top: 5px;
  padding: 10px;
  border: 1px solid #aaa;
  border-radius: 8px;
  font-size: 14px;
  width: 100%;
  box-sizing: border-box;
}

.product table textarea {
  height: 120px;
  resize: vertical;
}

#sendButton {
  margin-top: 15px;
  padding: 10px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 16px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

#sendButton:hover {
  background-color: #0056b3;
}

/* Responsive Design */
@media screen and (max-width: 768px) {
  .admin-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 15px;
  }
  
.logout-container input[type="submit"] {
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
  background-color: #0056b3;
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
      <h1>Message Panel</h1>
      <a href="/admin" class="add-product-link">
        <button>Main menu</button>
      </a>
      <a href="/admin/customerMenu" class="add-product-link">
        <button>View Subscribers</button>
      </a>
    </div>


    <div class="product-table">

      <form action="/admin/deliverMessage" method="post">
        <label style="margin: top 15px;" >Subject:</label><br>
        <input type="text" name="messageSubject" id="subjectField" placeholder="Enter the subject here..." style="margin: top 5px;" required> <br>
        <label style="margin: top 15px;" >Body:</label><br>
        <textarea name="messageBody" id="messageField" style="margin: top 5px;" required></textarea><br>
        <input type="submit" value="Send Message" id="sendButton" style="margin: top 5px;" >
      </form>

    </div>

    <h4>Use this panel to deliver newsletter and promotion messages to all subscribers</h4>

  </div>

  <script>
    if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
    }
  </script>
</body>
</html>
