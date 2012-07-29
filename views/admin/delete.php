<body onload="document.forms[0].elements[0].focus()">

<h2>Delete Post</h2>

<p><?=$this->_display_msgs(false)?></p>
<p><?=$this->_display_errors(false)?></p>

<form method="post" action="/admin/delete">

  <label>Search for Image:</label><br/>
  <input type="text" name="img_url" size="60" /><br/><br/>
  
  <?=csrf::html()?>
  <input type="submit" name="btn_delete" value="Delete!" />
</form>

