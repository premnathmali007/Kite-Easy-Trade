<nav class="navbar navbar-expand-sm navbar-dark bg-primary fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo $urlInterface->getHomeUrl();?>">Kite Analysis</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="<?php echo $urlInterface->getShowAnalyticsUrl();?>">Analytics</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $urlInterface->getShowTradebookUrl();?>">Tradebook</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $urlInterface->getShowBacktestUrl();?>">Backtest</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $urlInterface->getShowDailyTradesUrl();?>">Daily Trades</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $urlInterface->getImportTradebookUrl();?>">Import Tradebook</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $urlInterface->getImportFundStatementUrl();?>">Import Fund Statement</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div style="margin-top: 80px;"></div>
<div style="margin-top: 80px;"></div>