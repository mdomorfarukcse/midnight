<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
<link rel="icon" type="image/x-icon" href="/assets/img/<?=$setting->getSiteFavicon();?>">
<link href="<?= SITE_URL ?>\assets\css\loader.css" rel="stylesheet" type="text/css">
<script src="<?= SITE_URL ?>\assets\js\loader.js"></script>
<link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
<link href="\bootstrap\css\bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="<?= SITE_URL ?>\assets\css\plugins.css" rel="stylesheet" type="text/css">
<link href="<?= SITE_URL ?>\plugins\apex\apexcharts.css" rel="stylesheet" type="text/css">
<link href="<?= SITE_URL ?>\assets\css\dashboard\dash_1.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?= SITE_URL ?>\assets/css/themify-icons.css">
<link rel="stylesheet" href="<?= SITE_URL ?>\assets/css/ie7/ie7.css">

<script>

    var languageJson = <?= $language->getJsonData() ?>;

    function translate(key) {
        if (languageJson.hasOwnProperty(key)) {
            return languageJson[key];
        }

        return key;
    }

</script>
