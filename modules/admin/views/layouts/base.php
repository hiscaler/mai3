<?php
/* @var $this \yii\web\View */
/* @var $content string */

use app\modules\admin\widgets\MainMenu;
use yii\helpers\Html;

app\modules\admin\assets\AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?> - <?= Yii::$app->name ?></title>
        <?php $this->head() ?>
    </head>
    <body id="mai3-app">
        <?php $this->beginBody() ?>

        <div id="page-hd">
            <div id="page">
                <!-- Header -->
                <div id="header">
                    <div id="logo"><?php echo Html::a(Html::img(Yii::$app->getRequest()->getBaseUrl() . '/admin/images/logo.png'), Yii::$app->homeUrl); ?></div>
                    <div id="main-menu">
                        <?= MainMenu::widget() ?>
                    </div>
                    <div id="header-account-manage">
                        Account
                    </div>
                </div>
                <!-- // Header -->
            </div>
        </div>
        <div id="page-bd">
            <div class="container">
                <?= $content ?>
            </div>
        </div>
        <div id="page-ft">
            <div id="footer">
                Copyright &copy; <?= date('Y'); ?> by <?= Yii::$app->name; ?> All Rights Reserved.
            </div>
        </div>

        <?php $this->endBody() ?>
        <script type="text/javascript">
            Mai3.urls.item.delete = '<?= \yii\helpers\Url::toRoute(['items/delete-sku', 'id' => 0]) ?>';
        </script>
    </body>
</html>
<?php $this->endPage() ?>