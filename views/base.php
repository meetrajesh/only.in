<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">

    <title><? $t->block('title'); ?>Only.in<? $t->endblock(true); ?></title>
    <meta name="description" content="">

    <link href='http://fonts.googleapis.com/css?family=Bree+Serif|Open+Sans:400,400italic,600,700' rel='stylesheet' type='text/css'>

    <?
        $this->_add_css(array(
            'less/main.less',
        ));
    ?>

    <? foreach ($this->_stylesheets as $stylesheet): ?>
        <link rel="stylesheet/less" href="<?= PATH_PREFIX . $stylesheet; ?>">
    <? endforeach; ?>
    <script>
        less={};
        less.env = "development";
        localStorage.clear();
    </script>
    <script src="<?= PATH_PREFIX . STATIC_PREFIX ?>/js/libs/less-1.3.0.min.js"></script>

    <script src="<?= PATH_PREFIX . STATIC_PREFIX ?>/js/libs/modernizr-2.5.3.min.js"></script>
</head>
<body>
    <header>
        <div class="container_12">
            <div class="grid_8">
                <h1>Only.in</h1>
            </div>
            <div class="grid_4">
                <input id="main-search" type="text" placeholder="Search Only.in">
            </div>
        </div>
    </header>

    <div class="container_12" id="main-content">
        <div class="grid_8">
            <ul class="pillbox cf" id="main-filter">
                <li class="btn">Popular</li>
                <li class="btn">Latest</li>
                <li class="btn">Debated</li>
                <li class="btn">Top</li>
            </ul>

            <? $t->block('content'); ?>
            <? $t->endblock(true); ?>
        </div>

        <div class="grid_4">
            <ul class="pillbox cf" id="auth-buttons">
                <li>Sign In</li>
                <li>Register</li>
            </ul>

            <aside id="quickpost">
                <h3>Post Something</h3>
                <div>
                    <form method="post" action="/post/add" enctype="multipart/form-data">
                        <?=csrf::html()?>
                        <input type="hidden" name="MAX_FILE_SIZE" value="<?=UPLOAD_MAX_SIZE?>">
                        <input type="submit" name="btn_submit" value="Add Post" />
                        <input type="text" name="place" placeholder="Place">
                        <input type="text" name="title" placeholder="Title">
                        <input type="text" name="content" placeholder="URL">
                    </form>
                </div>
            </aside>

            <aside>
                <h3>Popular Places</h3>
                <div></div>
            </aside>
        </div>
    </div>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.7.2.min.js"><\/script>')</script>
    <script>
        $(document).data('api_url', '<?= API_BASE_URL; ?>');
        $(document).data('api_key', '<?= api_key(API_SECRET); ?>')
    </script>
    <? foreach ($this->_scripts as $script): ?>
        <script src="<?= PATH_PREFIX . $script; ?>"></script>
    <? endforeach; ?>
</body>
</html>