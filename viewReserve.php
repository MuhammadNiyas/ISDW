<div>
  <h2>Reserved Products</h2>
  <table class="table">
    <thead>
      <tr>
      <th class="text-center">S.N.</th>
        <th class="text-center">Buyer ID</th>
        <th class="text-center">Product Image</th>
        <th class="text-center">Product Name</th>
        <th class="text-center">Product Price</th>
        <th class="text-center">Date</th>
        <th class="text-center">Time</th>
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

    $sql = "SELECT * FROM reservations";
    $result = $conn->query($sql);
    $count = 1;

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        ?>
        <tr>
          <td><?=$count?></td>
          <td><?=$row["buyerID"]?></td>
          <td><?=$row["productImage"]?></td>
          <td><?=$row["productName"]?></td>
          <td><?=$row["productPrice"]?></td>
          <td><?=$row["reserveDate"]?></td>
          <td><?=$row["reserveTime"]?></td>
          <td><button class="btn btn-danger" style="height:40px" onclick="customerDelete('<?=$row['buyerID']?>')">Delete</button></td>
        </tr>
        <?php
        $count++;
      }
    }
    ?>
  </table>
</div>


<script>
function customerDelete(id) {
  $.ajax({
    url: "./controller/deleteReserveController.php",
    method: "post",
    data: { record: id },
    success: function(data) {
      alert('Reservation Successfully deleted');
      showReserves();
    },
    error: function() {
      alert('Failed to delete Reservation');
    }
  });
}


</script>