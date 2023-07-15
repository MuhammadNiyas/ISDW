<div>
  <h2>All Buyers</h2>
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

    $sql = "SELECT * FROM buyers";
    $result = $conn->query($sql);
    $count = 1;

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        ?>
        <tr>
          <td><?=$count?></td>
          <td><?=$row["buyerName"]?></td>
          <td><?=$row["buyerID"]?></td>
          <td><?=$row["buyerUsername"]?></td>
          <td><?=$row["buyerEmail"]?></td>
          <td><?=$row["buyerPhoneNumber"]?></td>
          <td><?=$row["signupDateTime"]?></td>
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
    url: "./controller/deleteCustomersController.php",
    method: "post",
    data: { record: id },
    success: function(data) {
      alert('Customer Successfully deleted');
      showCustomers();
    },
    error: function() {
      alert('Failed to delete customer');
    }
  });
}


</script>