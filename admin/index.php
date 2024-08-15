<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<div class="header">
    
      <a href="add_products.php">Add product</a>
      
      
    </div>
</body>
</html>

<style>
    body {
  font-family: Playfair Display;
  line-height: 1.5;
  min-height: 100vh;
  flex-direction: column;
  margin: 0;
  background-color: #ffffff;
}

.header {
  background-color: darkblue;
  color: #fff;
  padding: 0px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.header a {
  color: #fff;
  text-decoration: none;
  padding: 10px;
  font-size: 16px;
}

.header a:hover {
  background-color: rgb(156, 49, 6);
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
  transform: scale(1);
  color: #ffffff;
  border-radius: 5px;
  padding: 5px;
}

.header .search-container {
  display: flex;
  align-items: center;
}
</style>