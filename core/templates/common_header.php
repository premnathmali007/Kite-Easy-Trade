<?php
//Header file
?>
<head>
    <title>Kite Analytics</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Latest compiled and minified CSS -->
    <link href="<?php echo $app_path_css . 'bootstrap.min.css'; ?>" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo $app_path_css . 'datatables.min.css'; ?>"/>

    <!-- Latest compiled JavaScript -->
    <script src="<?php echo $app_path_js . 'bootstrap.bundle.min.js'; ?>"></script>
    <script src="<?php echo $app_path_js . 'jquery-3.6.0.min.js'; ?>"></script>
    <script type="text/javascript" src="<?php echo $app_path_js . 'datatables.min.js'; ?>"></script>
</head>
<style>
    /*.center {*/
    /*    text-align: center;*/
    /*    border: 3px solid green;*/
    /*}*/
    /*.container{*/
    /*    max-width: none !important;*/
    /*    width: 100%;*/
    /*    margin: 5px 5px 5px 5px;*/
    /*}*/
    .nav-item.active a {
        color:white !important;
        font-weight: bolder;
    }
    .loss-red {
        background-color: #b16464 !important;
    }
    .profit-green {
        background-color: #5cb85c !important;
    }
</style>
<?php
require_once("tpl_navbar.php");
?>