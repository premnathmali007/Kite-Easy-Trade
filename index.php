<html lang="en">
<?php
//init config
require_once("config.php");
//include header
include_once($app_path_templates . "common_header.php");
?>
<body>
    <?php
    //include body
    //show analytics
    require_once($app_path_templates . "tpl_common_actions.php");
    $page = isset($_GET["show"]) && $_GET["show"] ? $_GET["show"] : "";
    $action = isset($_GET["action"]) && $_GET["action"] ? $_GET["action"] : "";
    switch ($action) {
        case "import_tradebook":
            $importTrabook = new Core\ImportTradebook();
            $importTrabook->execute();
            break;
    }
    switch ($page) {
        case "analytics":
            require_once($app_path_templates . "tpl_analytics.php");
            break;
        case "import_tradebook":
            require_once($app_path_templates . "tpl_import_tradebook.php");
            break;
        default:
            require_once($app_path_templates . "tpl_analytics.php");
    }
    ?>
</body>
<script>
</script>
</html>
