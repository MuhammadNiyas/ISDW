<div>
  <h2>All Sellers</h2>
  <table class="table">
    <thead>
      <tr>
        <th class="text-center">S.N.</th>
        <th class="text-center">Name</th>
        <th class="text-center">I.D.</th>
        <th class="text-center">Username</th>
        <th class="text-center">Email</th>
        <th class="text-center">Phone</th>
        <th class="text-center">Signup Date Time</th>
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

    $sql = "SELECT * FROM sellers";
    $result = $conn->query($sql);
    $count = 1;

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        ?>
        <tr>
          <td><?=$count?></td>
          <td><?=$row["sellerName"]?></td>
          <td><?=$row["sellerID"]?></td>
          <td><?=$row["sellerUsername"]?></td>
          <td><?=$row["sellerEmail"]?></td>
          <td><?=$row["sellerPhoneNumber"]?></td>
          <td><?=$row["signupDateTime"]?></td>
          <td><button class="btn btn-danger" style="height:40px" onclick="sellerDelete('<?=$row['sellerID']?>')">Delete</button></td>
        </tr>
        <?php
        $count++;
      }
    }
    ?>
  </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function sellerDelete(id) {
  $.ajax({
    url: "./controller/deleteSellersController.php",
    method: "post",
    data: { record: id },
    success: function(data) {
      alert('Seller Successfully deleted');
      showSellers();
    },
    error: function() {
      alert('Failed to delete seller');
    }
  });
}


</script>
   