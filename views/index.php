<html>

<body>

<form method="post" action="post/add/">
  Type in your funny lolcat image url here:<br/>
  <input type="text" size="30" name="content" />
  
  <input type="submit" name="btn_submit" value="Submit!" />
</form>

<hr />

<?php

while ($row = $data['posts']->fetch_assoc()) {
    echo '<p>User ' . hsc($row['user_id']) . ' says: ' . hsc($row['content']) . ' at ' . date('r', $row['stamp']) . '</p>';
}

?>

</body>
</html>
