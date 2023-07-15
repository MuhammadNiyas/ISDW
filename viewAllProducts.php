<div >
  <h2>Product Items</h2>
  <table class="table ">
    <thead>
      <tr>
        <th class="text-center">S.N.</th>
        <th class="text-center">Product Image</th>
        <th class="text-center">Product Name</th>
        <th class="text-center">Product Description</th>
        <th class="text-center">Category Name</th>
        <th class="text-center">Unit Price</th>
        <th class="text-center" colspan="2">Action</th>
      </tr>
    </thead>
    <?php
      $server = "localhost";
      $username = "root";
      $password = "";
      $dbname = "online_store_db";

      // Create a new mysqli connection
      $conn = new mysqli($server, $username, $password, $dbname);

      // Check the connection
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      $sql = "SELECT * FROM products";
      $result = $conn->query($sql);
      $count = 1;

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
    ?>
    <tr>
      <td><?=$count?></td>
      <td><img height='100px' src='<?=$row["productImage"]?>'></td>
      <td><?=$row["productName"]?></td>
      <td><?=$row["productDescription"]?></td>      
      <td><?=$row["productCategories"]?></td> 
      <td><?=$row["productPrice"]?></td>     
      <td><button class="btn btn-danger" style="height:40px" onclick="itemDelete('<?=$row['productID']?>')">Delete</button></td>
    </tr>
    <?php
          $count=$count+1;
        }
      }
      ?>
  </table>
</div>