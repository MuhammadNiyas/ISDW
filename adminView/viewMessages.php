<div>
  <h2>All Feedback</h2>
  <table class="table">
    <thead>
      <tr>
        <th class="text-center">S.N.</th>
        <th class="text-center">Name</th>
        <th class="text-center">I.D.</th>
        <th class="text-center">Email</th>
        <th class="text-center">Contact Number</th>
        <th class="text-center">Description</th>
        <th class="text-center">Date Time</th>
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

    $sql = "SELECT * FROM submissions";
    $result = $conn->query($sql);
    $count = 1;

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        ?>
        <tr>
          <td><?=$count?></td>
          <td><?=$row["first_name"]?></td>
          <td><?=$row["id"]?></td>
          <td><?=$row["email"]?></td>
          <td><?=$row["phone"]?></td>
          <td><?=$row["message"]?></td>
          <td><?=$row["created_at"]?></td>
          <td><button class="btn btn-danger" style="height:40px" onclick="messagesDelete('<?=$row['id']?>')">Delete</button></td>
        </tr>
        <?php
        $count++;
      }
    }
    ?>
  </table>
</div>


<script>
function messagesDelete(id) {
  $.ajax({
    url: "./controller/deleteMessagesController.php",
    method: "post",
    data: { record: id },
    success: function(data) {
      alert('Message Successfully deleted');
      showMessages();
    },
    error: function() {
      alert('Failed to delete Message');
    }
  });
}


</script>