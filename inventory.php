<?php
include_once 'header.php';
// ******************************************* DATA BASE CONNECTION ********************************************** //
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventplan";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// ********************************************* END OF DB CON ********************************************** //
?>
<title>Inventory Items</title>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.css" />
<style>
    h2 {
        text-align: center;
    }
</style>
<h2>Inventory Items</h2>
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
    Add Item
</button>
<table id="myTable" class="display" width="100%">
    <thead>
        <tr>
            <th>Item Code</th>
            <th>Item Name</th>
            <th>Item Expiration</th>
            <th>Item Quantity</th>
            <th>Item Price</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "inventplan";

        $conn = new mysqli($servername, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $conn->set_charset("utf8");
        $result = $conn->query("SELECT * FROM `items`");
        if ($result) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['code'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";

                    $expirationDate = strtotime($row['expiration']);
                    $today = strtotime(date('Y-m-d'));
                    $daysDiff = floor(($expirationDate - $today) / (60 * 60 * 24));

                    if ($daysDiff < 0) {
                        echo "<td style='color: red;'>" . $row['expiration'] . "</td>";
                    } elseif ($daysDiff <= 30) {
                        echo "<td style='color: #f39c12;'>" . $row['expiration'] . "</td>";
                    } else {
                        echo "<td style='color: green;'>" . $row['expiration'] . "</td>";
                    }
                    echo "<td>" . $row['quantity'] . "</td>";
                    echo "<td>" . $row['price'] . "</td>";
                    echo "<td>
                        <form method='post' action='update.php' style='display: inline;'>
                            <input type='hidden' name='editItemCode' value='" . $row['code'] . "'>
                            <input type='hidden' name='editItemName' value='" . $row['name'] . "'>
                            <input type='hidden' name='editItemExpiration' value='" . $row['expiration'] . "'>
                            <input type='hidden' name='editItemQuantity' value='" . $row['quantity'] . "'>
                            <input type='hidden' name='editItemPrice' value='" . $row['price'] . "'>
                            <button type='button' class='btn btn-primary edit-button' data-bs-toggle='modal' data-bs-target='#editModal'" . $row['id'] . "'>Edit</button>
                        </form>
                        <button type='button' class='btn btn-danger delete-button' data-id='" . $row['id'] . "' onclick='confirmDelete(" . $row['id'] . ")'>Delete</button>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No inventory items found</td></tr>";
            }
        } else {
            echo "Error: " . $conn->error;
        }
        $conn->close();
        ?>
        <!---------------------------------------------- EDIT FORM ----------------------------------------->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm" method="POST" action="update.php">
                            <div class="mb-3">
                                <label class="form-label">Item Code
                                    <input type="text" class="form-control" id="updatecode" name="editItemCode" autocomplete="off">
                                </label>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Item Name
                                    <input type="text" class="form-control" id="updatename" name="editItemName" autocomplete="off">
                                </label>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Item Expiration
                                    <input type="date" class="form-control" id="updateexp" name="editItemExpiration" autocomplete="off">
                                </label>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Item Quantity
                                    <input type="text" class="form-control" id="updateqty" name="editItemQuantity" autocomplete="off">
                                </label>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Item Price
                                    <input type="number" class="form-control" id="updateprice" name="editItemPrice" autocomplete="off">
                                </label>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" onclick="resetEditForm()">Reset</button>
                                <button type="button" class="btn btn-primary" id="btnUpdate" onclick="updateItem()">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </tbody>
</table>
<!------------------------------------------------- ADD NEW ITEM --------------------------------------------------->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add Item Form</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="add-item-form" method="POST" action="add_item.php">
                    <label for="itemCode">Item Code:</label><br>
                    <input type="text" class="form-control" id="itemCode" name="code" placeholder="Enter Item Code" value="" autocomplete="off">
                    <br>
                    <label for="itemName">Item Name:</label><br>
                    <input type="text" class="form-control" id="itemName" name="name" placeholder="Enter Item Name" autocomplete="off">
                    <br>
                    <label for="itemExpiration">Item Expiration:</label><br>
                    <input type="date" class="form-control" id="itemExpiration" name="expiration" placeholder="Enter Item Expiration" autocomplete="off">
                    <br>
                    <label for="itemQuantity">Item Quantity:</label><br>
                    <input type="text" class="form-control" id="itemQuantity" name="quantity" placeholder="Enter Item Quantity" autocomplete="off">
                    <br>
                    <label for="itemPrice">Item Price:</label><br>
                    <input type="number" class="form-control" id="itemPrice" name="price" placeholder="Enter Item Price" autocomplete="off"><br>
                    <div id="itemList"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="resetForm()">Reset</button>
                <button type="submit" class="btn btn-primary" name="add_item">Add Item</button>
            </div>
            </form>
            <!----------------------------------------------- END OF ADD ITEM -------------------------------------------------->
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
            <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
            <script>
                function checkItemExpiration(itemName, expirationDate) {
                    const currentDate = new Date();
                    const expDate = new Date(expirationDate);
                    const timeDiff = expDate - currentDate;
                    const daysDiff = timeDiff / (1000 * 3600 * 24);

                    if (daysDiff < 0) {
                        Swal.fire({
                            title: `Item Expired`,
                            text: `${itemName} expired on ${expDate.toDateString()}.`,
                            icon: 'error',
                            confirmButtonColor: '#d33'
                        });
                    } else if (daysDiff <= 30) {
                        Swal.fire({
                            title: `Warning: Item Expiring Soon`,
                            text: `${itemName} will expire on ${expDate.toDateString()}.`,
                            icon: 'warning',
                            confirmButtonColor: '#f39c12'
                        });
                    } else {
                        Swal.fire({
                            title: `Item Valid`,
                            text: `${itemName} is valid until ${expDate.toDateString()}.`,
                            icon: 'success',
                            confirmButtonColor: '#28a745'
                        });
                    }
                }
                $(document).ready(function() {
                    $('#myTable').DataTable();
                    $('#myTable tbody tr').each(function() {
                        const itemName = $(this).find("td:nth-child(2)").text();
                        const expirationDate = $(this).find("td:nth-child(3)").text();
                        checkItemExpiration(itemName, expirationDate);
                    });
                });
            </script>
            <?php include 'footer.php'; ?>