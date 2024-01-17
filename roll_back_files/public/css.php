<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
<link rel="icon" type="image/x-icon" href="/assets/img/<?=$setting->getsiteFavicon();?>">
<link href="\assets\css\loader.css" rel="stylesheet" type="text/css">
<script src="\assets\js\loader.js"></script>
<link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
<link href="\bootstrap\css\bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="\assets\css\plugins.css" rel="stylesheet" type="text/css">
<link href="\plugins\apex\apexcharts.css" rel="stylesheet" type="text/css">
<link href="\assets\css\dashboard\dash_1.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="\assets/css/themify-icons.css">
 <link rel="stylesheet" href="\assets/css/ie7/ie7.css">
 <link rel="stylesheet" href="/assets/demo.css">
 <link rel="stylesheet" href="/assets/cookieconsent.css" media="print" onload="this.media='all'">

 <?php
 if ($session->get('language') == 'ar'):
 ?>
 <link href="\assets\css\rtl.css" rel="stylesheet" type="text/css">
 <?php
 endif;
 ?>
<script>

    var languageJson = <?= $language->getJsonData() ?>;

    function translate(key)
    {
        if (languageJson.hasOwnProperty(key)) {
            return languageJson[key];
        }

        return key;
    }
</script>
