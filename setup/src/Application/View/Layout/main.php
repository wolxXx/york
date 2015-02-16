<?php
/**
 * @var string $content
 */
$title = isset($title) ? $title . ' - ' : '';
$title .= \York\Dependency\Manager::getApplicationConfiguration()->getSafely('app_name', 'MyPage');

\Application\Configuration\Dependency::getAssetManager()->addCssFile('/css/syles.css');
?>
<!DOCTYPE html>
<head>
    <title><?php echo $title ?></title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <link type="text/plain" rel="author" href="/humans.txt"/>
    <link rel="SHORTCUT ICON" href="/favicon.ico">
    <meta name="description" content="my page">
    <meta name="keywords" content="my page>">
</head>
<body>
    <div id="main">
        <div id="menu">
            <a href="/">home</a>
            <a href="/impress">impress</a>
        </div>
        <div id="content">
            <div>
                <h1><?php echo $this->get('headline') ?></h1>
            </div>
            <?php echo $content ?>
        </div>
    </div>
    <div id="sources">
        <?php \Application\Configuration\Dependency::getAssetManager()->display(); ?>
    </div>
</body>
