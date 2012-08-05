<?php

    $this->_add_js('js/searchbox.js');

?><!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">

    <title><? $t->block('title'); ?>Only.in<? $t->endblock(true); ?></title>
    <meta name="description" content="">
    <meta property="og:site_name" content="Only.in" />
    <? $t->block('meta'); ?>
    <? $t->endblock(true); ?>

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
                <h1><a href="<?= hsc(absolutize('/')); ?>">Only.in</a><?= @$t->notempty(ucwords($data['subin_name']), ' | <a href="' . absolutize('/' . $data['subin_slug']) . '">', '</a>'); ?></h1>
            </div>
            <div class="grid_4">
                <input id="main-search" type="text" placeholder="Search Only.in">
            </div>
        </div>
    </header>

    <div class="container_12" id="main-content">
        <div class="grid_8" id="all-posts">
            <ul class="pillbox cf" id="main-filter">
                <? foreach (post::$POST_TABS as $tab => $tab_name): ?>
                    <li class="<?= (isset($data['tab']) && $data['tab'] == $tab) ? 'selected' : ''; ?>">
                        <a href="<?= hsc(absolutize($t->notempty($data['subin_slug'], '/') . '/' . $tab)); ?>">
                            <? /* hsc($t->notempty(ucwords($data['subin_name']),  '', '/') . $tab_name) */ ?>
                            <?= hsc($tab_name) ?>
                        </a>
                    </li>
                <? endforeach; ?>
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
                <div class="cf">
                    <form method="post" action="/post/add" enctype="multipart/form-data">
                        <?=csrf::html()?>
                        <input type="hidden" name="MAX_FILE_SIZE" value="<?=UPLOAD_MAX_SIZE?>">
                        <div id="qp-place-field">
                            <label for="place">only.in/</label><input type="text" id="place" name="place" placeholder="Place">
                        </div>
                        <input type="text" name="content" placeholder="Image or Youtube URL">
                        <input type="text" name="title" placeholder="Title (Optional)">
                        <input type="text" name="caption" placeholder="Caption (Optional)">
                        <button type="submit" class="btn"><span>Submit</span></button>
                    </form>
                </div>
            </aside>

            <aside id="popular-places">
                <h3>Popular Places <span>(<a href="/places">See all places</a>)</span></h3>
                <div>
                    <ul id="popular-place-list">
                        <? foreach (subin::get_popular() as $place) : ?>
                            <li><a href="<?= hsc(absolutize($place['permalink'])) ?>"><?= hsc($place['name']); ?></a></li>
                        <? endforeach; ?>
                    </ul>
                </div>
            </aside>
        </div>
    </div>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.7.2.min.js"><\/script>')</script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js"></script>

    <script>
        $(document).data('api_url', '<?= API_BASE_URL; ?>');
        $(document).data('api_key', '<?= api_key(API_SECRET); ?>');
        $(document).data('subin_min_len', <?= (int)SUBIN_MIN_LEN ?>);
        $(document).data('view_data', <?= json_encode(array('tab' => $data['tab'], 'subin_slug' => $data['subin_slug'])) ?>);
    </script>
    <? foreach ($this->_scripts as $script): ?>
        <script src="<?=PATH_PREFIX . $script?>"></script>
    <? endforeach; ?>

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
