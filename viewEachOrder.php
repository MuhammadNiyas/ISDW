<div class="container">
<table class="table table-striped">
    <thead>
        <tr>
            <th>S.N.</th>
            <th>Product Image</th>
            <th>Product Name</th>
            <th>Size</th>
            <th>Quantity</th>
            <th>Unit Price</th>
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

    $sql = "SELECT * FROM checkout";
    $result = $conn->query($sql);
    $count = 1;

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        ?>
          <tr>
          <td><?=$count?></td>
          <td><?=$row["productName"]?></td>
          <td><?=$row["productID"]?></td>
          <td><?=$row["productPrice"]?></td>
          <td><?=$row["productName"]?></td>
          <td><?=$row["productPrice"]?></td>

        </tr>
        <?php
        $count++;
      }
    }
    ?>
  </table>
</div>