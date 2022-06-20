<?php

$analyticsObj = new Core\Analytics();
$analytics = $analyticsObj->getAnalytics();
$trades = $analytics["trades"];

if (count($trades)>0) {

?>

<div class="container mt-3" >
    <form name="frm-analytics"
          action="<?php echo $urlInterface->getBaseUrl() . '?&show=analytics';?>"
          method="POST" enctype="multipart/form-data" class="p-5 border border-primary">
        <div class="mb-3">
            <label for="from" class="form-label">From</label>
            <input name="from" id="from" type="date" class="datepicker" data-date-format="mm/dd/yyyy" value="<?php echo $analytics["start_date"];?>">
            <label for="to" class="form-label">To</label>
            <input name="to" id="to" type="date" class="datepicker" data-date-format="mm/dd/yyyy" value="<?php echo $analytics["end_date"];?>">
        </div>
        <button type="submit" class="btn btn-primary">Show Analytics</button>
    </form>
    <br><h1 style="text-align: center;">Trade analytics</h1>
    <div class="container mt-3">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="bg-info"><?php echo "<h4>Start Date : " . $analytics["start_date"] . "</h4>"; ?></th>
                    <th class="bg-info"><?php echo "<h4>End Date : " . $analytics["end_date"] . "</h4>"; ?></th>
                    <th class="bg-info"><?php echo "<h4>Working Days : " . $analytics["working_days"] . "</h4>"; ?></th>
                </tr>
                <tr>
                    <th class="bg-info"><h4>Trade time</h4></th>
                    <th class="bg-info"><?php echo "<h4>Min Time Taken Trade : " . $analytics["min_time"] . "</h4>"; ?></th>
                    <th class="bg-info"><?php echo "<h4>Max Time Taken Trade : " . $analytics["max_time"] . "</h4>"; ?></th>
                </tr>
                <tr>
                    <th class="bg-info"><?php echo "<h4>Total Trades : " . count($trades) . "</h4>"; ?></th>
                    <th class="bg-success"><?php echo "<h4>Wins : " . $analytics["total_wins"] . "</h4>"; ?></th>
                    <th class="bg-danger"><?php echo "<h4>Losses : " . $analytics["total_losses"] . "</h4>"; ?></th>
                </tr>
                <tr>
                    <th class="bg-info"><?php echo "<h4>Long trades : " . $analytics["number_of_long_trades"] . "</h4>"; ?></th>
                    <th class="bg-success"><?php echo "<h4>Long Wins : " . $analytics["long_wins"] . "</h4>"; ?></th>
                    <th class="bg-danger">
                        <?php echo "<h4>Long Losses : " . $analytics["long_losses"] . "</h4>"; ?>
                    </th>
                </tr>
                <tr>
                    <th class="bg-info"><?php echo "<h4>Short Trades : " . $analytics["number_of_short_trades"] . "</h4>"; ?></th>
                    <th class="bg-success"><?php echo "<h4>Short Wins : " . $analytics["short_wins"] . "</h4>"; ?></th>
                    <th class="bg-danger"><?php echo "<h4>Short Losses : " . $analytics["short_losses"] . "</h4>"; ?></th>
                </tr>
                <tr>
                    <th class="bg-info"><h4>Max Profit / Loss For Per Trade</h4></th>
                    <th class="bg-success"><?php echo "<h4>Max Profit Per Trade : " . $analytics["max_profit"] . "</h4>"; ?></th>
                    <th class="bg-danger"><?php echo "<h4>Max Loss Per Trade : " . $analytics["max_loss"] . "</h4>"; ?></th>
                </tr>
                <tr>
                    <th class="bg-info"><h4>Final Analytics : </h4></th>
                    <th class="bg-<?php echo $analytics["total_pnl"]>0 ? 'success' : 'danger';?>">
                        <?php echo "<h4>Win Rate : " . $analytics["win_rate"] . "</h4>"; ?>
                    </th>
                    <th class="bg-<?php echo $analytics["total_pnl"]>0 ? 'success' : 'danger';?>">
                        <?php echo "<h4>Final PNL : " . $analytics["total_pnl"] . "</h4>"; ?>
                    </th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<br><br>
<div class="center">
    <h1 style="text-align: center;">All Trades</h1>
</div>
<div class="container-fluid">
<table id="tradebook_real" class="table table-bordered" style="border-collapse: collapse">
    <thead>
    <?php
    $columns = array_keys($trades[0]);
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
    foreach ($trades as $trade) {
        $trClass = $trade["pnl"]>0 ? "profit-green" : "loss-red";
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
<?php } else {
    echo "No trades!";
}
?>

<script>
    $(document).ready(function () {
        $('#tradebook_real').DataTable({
            "lengthMenu": [ 10, 25, 50, 75, 100 ],
            "pageLength": 50
        });
    });
</script>
