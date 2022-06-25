<?php

$dailyTradeAnalytics = new \Core\DailyTradeAnalytics();
$result = $dailyTradeAnalytics->getDailyTradeAnalytics();
$analytics = $result["analysis"];
$allTrades = $result["all_trades"];
$type = $result["type"];
?>

<div class="container mt-3" >
    <form name="frm-analytics"
          action="<?php echo $urlInterface->getBaseUrl() . '?&show=trade_analytics&type='.$type;?>"
          method="POST" enctype="multipart/form-data" class="p-5 border border-primary">
        <div class="mb-3">
            <label for="qty" class="form-label">Qty</label>
            <input type="number" class="form-control" id="qty" name="qty" placeholder="qty" min="10" value="<?php echo $result["qty"];?>" required>
        </div>
        <div class="mb-3">
            <label for="from" class="form-label">From</label>
            <input name="from" id="from" type="date" class="datepicker" data-date-format="mm/dd/yyyy" value="<?php echo $result["start_date"];?>">
            <label for="to" class="form-label">To</label>
            <input name="to" id="to" type="date" class="datepicker" data-date-format="mm/dd/yyyy" value="<?php echo $result["end_date"];?>">
        </div>
        <button type="submit" class="btn btn-primary">Show Analytics</button>
    </form>
    <?php foreach ($analytics as $setup => $analysis) {?>
    <br><h1 style="text-align: center;">
            <?php
                $headding = ucwords(str_replace("_", " ",$setup));
                echo $headding;
            ?>
        </h1>
        <?php
            $setupAnalysis = $analysis["analytics"];
            $setupTrades = $analysis["trades"];
            $setupAnalysis["wins"] = isset($setupAnalysis["wins"]) ? $setupAnalysis["wins"] : 0;
            $setupAnalysis["losses"] = isset($setupAnalysis["losses"]) ? $setupAnalysis["losses"] : 0;
            $setupAnalysis["long_wins"] = isset($setupAnalysis["long_wins"]) ? $setupAnalysis["long_wins"] : 0;
            $setupAnalysis["long_losses"] = isset($setupAnalysis["long_losses"]) ? $setupAnalysis["long_losses"] : 0;
            $setupAnalysis["short_wins"] = isset($setupAnalysis["long_wins"]) ? $setupAnalysis["long_wins"] : 0;
            $setupAnalysis["short_losses"] = isset($setupAnalysis["long_losses"]) ? $setupAnalysis["long_losses"] : 0;
            $setupAnalysis["winrate"] = isset($setupAnalysis["winrate"]) ? $setupAnalysis["winrate"] : 0;
        ?>
    <div class="container mt-3">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="bg-info"><?php echo "<h4>Start Date : " . $setupAnalysis["start_date"] . "</h4>"; ?></th>
                    <th class="bg-info"><?php echo "<h4>End Date : " . $setupAnalysis["end_date"] . "</h4>"; ?></th>
                    <th class="bg-info"><?php echo "<h4>Working Days : " . $setupAnalysis["working_days"] . "</h4>"; ?></th>
                </tr>
                <tr>
                    <th class="bg-info"><?php echo "<h4>Total Trades : " . count($setupTrades) . "</h4>"; ?></th>
                    <th class="bg-success"><?php echo "<h4>Wins : " . $setupAnalysis["wins"] . "</h4>"; ?></th>
                    <th class="bg-danger"><?php echo "<h4>Losses : " . $setupAnalysis["losses"] . "</h4>"; ?></th>
                </tr>
                <?php if (isset($setupAnalysis["long_trades"])) { ?>
                <tr>
                    <th class="bg-info"><?php echo "<h4>Long trades : " . $setupAnalysis["long_trades"] . "</h4>"; ?></th>
                    <th class="bg-success"><?php echo "<h4>Long Wins : " . $setupAnalysis["long_wins"] . "</h4>"; ?></th>
                    <th class="bg-danger">
                        <?php echo "<h4>Long Losses : " . $setupAnalysis["long_losses"] . "</h4>"; ?>
                    </th>
                </tr>
                <?php }?>
                <?php if (isset($setupAnalysis["short_trades"])) { ?>
                <tr>
                    <th class="bg-info"><?php echo "<h4>Short Trades : " . $setupAnalysis["short_trades"] . "</h4>"; ?></th>
                    <th class="bg-success"><?php echo "<h4>Short Wins : " . $setupAnalysis["short_wins"] . "</h4>"; ?></th>
                    <th class="bg-danger"><?php echo "<h4>Short Losses : " . $setupAnalysis["short_losses"] . "</h4>"; ?></th>
                </tr>
                <?php }?>
                <tr>
                    <th class="bg-info"><h4>Max Profit / Loss For Per Trade</h4></th>
                    <th class="bg-success"><?php echo "<h4>Max Profit Per Trade : " . $setupAnalysis["final_analytics"]["max_profit_per_trade"]["pnl"] . "</h4>"; ?></th>
                    <th class="bg-danger"><?php echo "<h4>Max Loss Per Trade : " . $setupAnalysis["final_analytics"]["max_loss_per_trade"]["pnl"] . "</h4>"; ?></th>
                </tr>
                <tr>
                    <th class="bg-info"><h4>Final Analytics : </h4></th>
                    <th class="bg-<?php echo $setupAnalysis["final_analytics"]["pnl"]>0 ? 'success' : 'danger';?>">
                        <?php echo "<h4>Win Rate : " . round($setupAnalysis["winrate"],2) . "</h4>"; ?>
                    </th>
                    <th class="bg-<?php echo $setupAnalysis["final_analytics"]["pnl"]>0 ? 'success' : 'danger';?>">
                        <?php echo "<h4>Final PNL : " . $setupAnalysis["final_analytics"]["pnl"] . "</h4>"; ?>
                    </th>
                </tr>
            </thead>
        </table>
    </div>
    <?php }?>
</div>
<br><br>
<div class="center">
    <h1 style="text-align: center;">All Trades</h1>
</div>
<div class="container-fluid">
<table id="tradebook_real" class="table table-bordered" style="border-collapse: collapse">
    <thead>
    <?php
    $columns = array_keys($allTrades[0]);
    echo "<tr>";
    foreach ($columns as $column) {
        echo "<th>";
        echo ucwords(str_replace("_", " ", $column));
        echo "</th>";
    }
    echo "</tr>";
    ?>
    </thead>
    <tbody>
    <?php
    foreach ($allTrades as $trade) {
        $trClass = $trade["points"]>0 ? "profit-green" : "loss-red";
        echo '<tr class="' . $trClass . '" >';
        foreach ($trade as $key => $value) {
            echo "<td>";
            echo $value;
            echo "</td>";
        }
        echo "</tr>";
    }
    ?>
    </tbody>
</table>
</div>

<script>
    $(document).ready(function () {
        $('#tradebook_real').DataTable({
            "lengthMenu": [ 10, 25, 50, 75, 100 ],
            "pageLength": 50,
            order: [[1, 'desc']]
        });
    });
</script>
