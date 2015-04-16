<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Заголовок</title>
    <link rel="stylesheet/less" type="text/css" href="../../css/messages.less" />
    <script src="//cdn.jsdelivr.net/less/1.7.3/less.min.js"></script>
</head>
<body>

<div class="container">

    <div class="mrkp-tbl">
        <div class="leftside clearfix">
            <header>
                <div class="logo">
                    <a href="/">
                        <img src="../../img/logo-small@2x.png" height="40" width="40" alt="" data-retina="">
                        <span>Стимул</span>
                    </a>
                </div>
            </header>
       <?php echo $content;?>
    </div>

</div>
    <script src="../../js/jquery-2.1.1.min.js"></script>
    <script src="../../js/jquery-ui.js"></script>
    <script src="../../js/messages.js"></script>
</body>
</html>