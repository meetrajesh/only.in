<html>

<body onload="document.forms[0].elements[0].focus()">

<h3>Login</h3>

<?php $this->_display_errors() ?>

<form method="post" action="/user/login">

  <p>
    <label for="username">User Name:</label><br/>
    <input name="username" id="username" type="text" size="30" value="<?=pts('username')?>" />
  </p>

  <p>
    <label for="password">Password:</label><br/>
    <input name="password" id="password" type="password" size="30" value="" />
  </p>

  <p>Don&#39;t have an account? <a href="/user/signup">Signup</a> here!</p>

  <?=csrf::html()?>
  <input type="submit" name="btn_submit" value="Login!" />

</form>

</body>
</html>
