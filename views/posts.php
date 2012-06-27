<html>
<head>
  <title>Only.in</title>
</head>

<body onload="document.forms[0].elements[0].focus()">

<form method="post" action="/post/add" enctype="multipart/form-data">
  Type in your funny lolcat image url here:<br/>
  <input type="text" size="30" name="content" /> or
  <input type="file" name="photo" />
  
  <?=csrf::html()?>
  <input type="hidden" name="MAX_FILE_SIZE" value="<?=UPLOAD_MAX_SIZE?>" />
  <input type="submit" name="btn_submit" value="Submit!" />
</form>

<hr />

<?php

while ($row = $data['posts']->fetch_assoc()) {
    echo '<p>User ' . hsc($row['user_id']) . ' says: <em>' . hsc($row['content']) . '</em> at ' . date('r', $row['stamp']) . '</p>';
    if (!empty($row['img_url'])) {
        echo '<img src="' . $row['img_url'] . '" />';
    }
}

?>

<hr />

<?php if (!session::logged_in()) { ?>
  <p><a href="/user/signup">signup</a> | <a href="/user/login">login</a></p>
<? } else { ?>
  <p>
    You are logged in as <em><?=hsc(session::current_username())?></em>
    (<a href="/user/logout">logout</a>)
  </p>
<? } ?>


<? /* google analytics js snippet */ ?>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-32926555-1']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>

</body>
</html>
