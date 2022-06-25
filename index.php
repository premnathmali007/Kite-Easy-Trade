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
            $result = $importTrabook->execute();
            if ($result){
                echo "<div class='alert alert-success'>
                        <strong>Success!</strong> Trades Imported Successfully.
                    </div>";
            }
            break;
        case "import_fund_statement":
            $importFundStatement = new Core\ImportFundStatement();
            $result = $importFundStatement->execute();
            if ($result){
                echo "<div class='alert alert-success'>
                        <strong>Success!</strong> Fund Statement Imported Successfully.
                    </div>";
            }
            break;
        case "add_backtest_trade":
            $backtest = new Core\Backtest();
            $result = $backtest->addBackTestTrade();
            $currentTrade = $result["current_trade"];
            if ($result["status"]){
                header("Location: " . $urlInterface->getShowBacktestUrl() . "&import_trade=success&date=" . $_POST["date"]);
            } else {
                $errorMsg = $result["message"];
                echo "<div class='alert alert-danger'>$errorMsg</div>";
            }
            break;
        case "add_daily_trade":
            $dailyTrade = new Core\DailyTrade();
            $result = $dailyTrade->addDailyTrade();
            $currentTrade = $result["current_trade"];
            if ($result["status"]){
                header("Location: " . $urlInterface->getShowDailyTradesUrl() . "&import_trade=success&date=" . $_POST["date"]);
            } else {
                $errorMsg = $result["message"];
                echo "<div class='alert alert-danger'>$errorMsg</div>";
            }
            break;
    }
    switch ($page) {
        case "analytics":
            require_once($app_path_templates . "tpl_analytics.php");
            break;
        case "backtest":
            require_once($app_path_templates . "tpl_backtest.php");
            break;
        case "daily_trades":
            require_once($app_path_templates . "tpl_daily_trades.php");
            break;
        case "daily_trade_analytics":
            require_once($app_path_templates . "tpl_daily_trade_analytics.php");
            break;
        case "import_tradebook":
            require_once($app_path_templates . "tpl_import_tradebook.php");
            break;
        case "import_fund_statement":
            require_once($app_path_templates . "tpl_import_fund_statement.php");
            break;
        default:
            require_once($app_path_templates . "tpl_analytics.php");
    }
    ?>
</body>
<script>
</script>
</html>
