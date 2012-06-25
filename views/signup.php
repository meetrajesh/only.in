<html>

<body onload="document.forms[0].elements[0].focus()">

<h3>New User Signup</h3>

<?php $this->_display_errors() ?>

<form method="post" action="/user/signup">

  <p>
    <label for="username">User Name:</label><br/>
    <input name="username" id="username" type="text" size="30" value="<?=pts('username')?>" />
  </p>

  <p>
    <label for="password">Password:</label><br/>
    <input name="password" id="password" type="password" size="30" value="<?=pts('password')?>" />
  </p>

  <p>
    <label for="name">Full Name:</label><br/>
    <input name="name" id="name" type="text" size="30" value="<?=pts('name')?>" />
  </p>

  <p>
    <label for="email">Email:</label><br/>
    <input name="email" id="email" type="text" size="30" value="<?=pts('email')?>" />
  </p>

  <?=csrf::html()?>
  <input type="submit" name="btn_submit" value="Signup!" />

</form>

</body>
</html>
