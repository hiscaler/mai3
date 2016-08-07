<?php
$baseUrl = Yii::$app->getRequest()->getBaseUrl();
?>
<!doctype html>
<html lang="en">
    <head>
        <script src="//cdn.optimizely.com/js/1096093.js"></script>
        <meta charset="utf-8">
        <meta name="viewport" content="initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="Bitbucket is a Git and Mercurial based source code management and collaboration solution in the cloud. Bitbucket is simple and powerful, enables code collaboration for teams, works with the tools that matter, and is easy to use even for teams new to Git and Mercurial">

        <meta id="bb-canon-url" name="bb-canon-url" content="https://bitbucket.org">

        <meta name="bb-view-name" content="bitbucket.apps.account.views.bb_login">
        <meta name="ignore-whitespace" content="False">
        <meta name="tab-size" content="None">

        <meta name="application-name" content="Bitbucket">
        <meta name="apple-mobile-web-app-title" content="Bitbucket">
        <meta name="theme-color" content="#205081">
        <meta name="msapplication-TileColor" content="#205081">
        <meta name="msapplication-TileImage" content="https://d301sr5gafysq2.cloudfront.net/bf2b94402438/img/logos/bitbucket/white-256.png">
        <link rel="apple-touch-icon" sizes="192x192" type="image/png" href="https://d301sr5gafysq2.cloudfront.net/bf2b94402438/img/bitbucket_avatar/192/bitbucket.png">
        <link rel="icon" sizes="192x192" type="image/png" href="https://d301sr5gafysq2.cloudfront.net/bf2b94402438/img/bitbucket_avatar/192/bitbucket.png">
        <link rel="icon" sizes="16x16 32x32" type="image/x-icon" href="/favicon.ico">
        <link rel="search" type="application/opensearchdescription+xml" href="/opensearch.xml" title="Bitbucket">
        <meta name="description" content=""/>

        <title>后台登录</title>
        <link rel="stylesheet" href="<?= $baseUrl ?>/css/aui.min.css" media="all">
        <link rel="stylesheet" href="<?= $baseUrl ?>/css/login.css" />

    </head>

    <body class="aid-login aui-page-focused aui-page-focused-xsmall aui-page-size-xsmall"
          id="atlassian-id-login">

        <header id="aui-message-bar">

        </header>
        <header class="microbranding">
            <h1 class="microbranding--atlassian-logo">Log in to continue</h1>
            <a class="microbranding--bitbucket-logo" href="/">Bitbucket</a>
        </header>
        <header id="aui-message-bar"></header>

        <section class="content atlassian-id--content" role="main">

            <section data-module="atlassian-id/login">
                <section id="login-form">

                    <section class="aui-page-panel-inner">

                        <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'aid-login-form', 'options' => [ 'class' => 'aui aid-form errors-below-inputs']]); ?>

                        <div id="js-global-login-error-container" class="aui-message aui-message-error hidden">
                        </div>




                        <section class="text-inputs">
                            <?= $form->field($model, 'username', ['template' => '{input}'])->textInput(['class' => 'override-aui text'])->label(false) ?>
                            
                            
                            
                            <?= $form->field($model, 'password', ['template' => '{input}'])->passwordInput(['class' => 'override-aui text'])->label(false) ?>
                        </section>

                        <div class="button-container submit-container">
                            <input
                                type="submit"
                                class="large-blue-button selenium-login-submit aui-button-primary"
                                value="登录" />
                            <a class="reset-password" href="/account/password/reset/">
                                Forgot your password?
                            </a>
                        </div>


                        <div class="button-container">

                            <div class="signup-link">

                                Need an account?
                                <a href="/account/signup/" id="signup">Sign up</a>.

                            </div>

                        </div>

                        </form>
                    </section>



                </section>



            </section>

        </section>











    </body>
</html>