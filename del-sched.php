<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Delivery Schedule</title>
<link rel="stylesheet" href="../asset/css/del.css">
</head>
<body>

<h2>Delivery Schedule</h2>

<div class="container">
  <div class="form-container">
    <form id="deliveryForm">

      <label for="productName">Product Name:</label>
      <input type="text" id="productName" class="form-control" name="productName" autocomplete="off" required>
      <br><br>
      <label for="productPrice">Product Price:</label>
      <input type="number" id="productPrice" class="form-control" name="productPrice" autocomplete="off" required>
      <br><br>
      <label for="deliveryDate">Select Delivery Date:</label>
      <input type="date" id="deliveryDate" class="form-control" name="deliveryDate" required>
      <br><br>
      <label for="deliveryTime">Select Delivery Time:</label>
      <input type="time" id="deliveryTime" class="form-control" name="deliveryTime" required>
      <br><br>
      <button type="submit">Schedule Delivery</button>
    </form>
  </div>
  <div id="timer"></div>
</div>
<?php include 'footer.php' ?>