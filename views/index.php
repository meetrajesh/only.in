<html>

<body onload="document.forms[0].elements[0].focus()">

<form method="post" action="post/add/">
  Type in your funny lolcat image url here:<br/>
  <input type="text" size="30" name="content" />
  
  <?=csrf::html()?>
  <input type="submit" name="btn_submit" value="Submit!" />
</form>

<hr />

<?php

while ($row = $data['posts']->fetch_assoc()) {
    echo '<p>User ' . hsc($row['user_id']) . ' says: <em>' . hsc($row['content']) . '</em> at ' . date('r', $row['stamp']) . '</p>';
}

?>

</body>
</html>
