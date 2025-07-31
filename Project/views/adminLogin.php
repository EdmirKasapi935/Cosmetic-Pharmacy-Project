<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>
  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-color: #f0f2f5;
      font-family: Arial, sans-serif;
    }
    .login-container {
      background-color: white;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      width: 300px;
    }
    .login-container h2 {
      text-align: center;
      margin-bottom: 1.5rem;
    }
    .login-container input[type="text"],
    .login-container input[type="password"] {
      width: 92%;
      padding: 0.75rem;
      margin: 0.5rem 0;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .login-container button {
      width: 100%;
      padding: 0.75rem;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      margin-top: 1rem;
    }
    .login-container button:hover {
      background-color: #45a049;
    }
  </style>
</head>
<body>
<div class="login-container">
  <h2>Login</h2>
  <form action="/adminLoginProcess" method="post">
    <input type="text" placeholder="Username" name="loginUsername" required>
    <input type="password" placeholder="Password" name="loginPassword" required>
    <button type="submit">Login</button>
  </form>
</div>
</body>
</html>

