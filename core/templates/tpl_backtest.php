<?php

use Core\Resource;
$backtest = new Core\Backtest();
$trades = $backtest->getBacktestTrades();
$resource = new Resource();
$setups = $resource->getSetups();
$symbols = $resource->getSymbols();
if (isset($_GET["import_trade"])){
    echo "<div class='alert alert-success'><strong>Success!</strong> Trade Added Successfully.</div>";
    unset($_GET["import_trade"]);
}
$date = date("Y-m-d");;
if (isset($_GET["date"])){
    $date = $_GET["date"];
}
?>

<div class="container mt-3">
    <form name="add-backtest-trade"
          id="add-backtest-trade"
          action="<?php echo $urlInterface->getAddBackTestTradeUrl(); ?>"
          method="POST" enctype="multipart/form-data" class="form-horizontal p-2 border border-primary">
        <div class="row justify-content-md-center">
            <div class="col-sm col-lg-2">
                <label for="date" class="form-label">Date</label><br>
                <input name="date" id="date" type="date" class="form-comtrol datepicker" data-date-format="mm/dd/yyyy" value="<?php echo $date;?>" required>
            </div>
            <div class="col-sm col-lg-2">
                <label for="symbol" class="form-label">Select Symbol</label>
                <select class="form-select" id="symbol" name="symbol" aria-label="Select Symbol" required>
                    <?php foreach ($symbols as $symbol) { ?>
                        <option value="<?php echo $symbol['symbol']; ?>" <?php if ($symbol['symbol'] == "NIFTY") {
                            echo "selected";
                        } ?>>
                            <?php echo $symbol['symbol']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-sm col-lg-3">
                <label for="lots" class="form-label">Lots</label>
                <input type="number" class="form-control" id="lots" name="lots" placeholder="Lots" min="1" value="1" required>
            </div>
        </div>
        <br>
        <div class="row justify-content-md-center">
            <div class="col-sm col-lg-2">
                <div class="form-check">
                    <input type="radio" class="form-check-input" id="trade_type1" name="trade_type" value="long" required>
                    <label class="form-check-label" for="trade_type1">Long</label>
                </div>
                <div class="form-check">
                    <input type="radio" class="form-check-input" id="trade_type2" name="trade_type" value="short" required>
                    <label class="form-check-label" for="trade_type2">Short</label>
                </div>
            </div>
            <div class="col-sm col-lg-2 form-floating ">
                <input type="text" class="form-control" id="points" placeholder="Points" name="points" required>
                <label for="points">Points</label>
            </div>
            <div class="col-sm col-lg-2 form-floating ">
                <input type="text" class="form-control" id="custom_ratio" name="custom_ratio" placeholder="Ratio">
                <label for="custom_ratio" class="form-label">Ratio</label>
            </div>
            <div class="col-sm col-lg-2 form-floating">
                <select class="form-select" aria-label="ratio" id="ratio" name="ratio">
                    <option value="1:2" selected>1:2</option>
                    <option value="1:2.5">1:2.5</option>
                    <option value="1:3">1:3</option>
                    <option value="1:3.5">1:3.5</option>
                    <option value="1:4">1:4</option>
                    <option value="1:4.5">1:4.5</option>
                    <option value="1:5">1:5</option>
                </select>
            </div>
        </div>
        <label for=".setup-check">:: Setup ::</label><br>
        <?php foreach ($setups as $i => $setup) { ?>
            <div class="form-check form-check-inline checkbox-group required">
                <input class="form-check-input"
                       type="checkbox"
                       name="setup[]"
                       id="setup<?php echo $setup['entity_id']; ?>"
                       value="<?php echo $setup['setup_code']; ?>"
                >
                <label class="form-check-label"
                       for="setup<?php echo $setup['entity_id']; ?>"><?php echo $setup['setup']; ?></label>
            </div>
        <?php } ?>
        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="fileToUpload" class="form-label">Screenshot</label>
            <input class="form-control" type="file" name="fileToUpload" id="fileToUpload">
        </div>
        <button type="submit" class="btn btn-primary">Add Trade</button>
    </form>
</div>
<?php
if (count($trades) > 0) {
    ?>
    <br>
    <div class="center">
        <h1 style="text-align: center;">All Trades</h1>
    </div>
    <div class="container-fluid">
        <table id="backtest_tradebook" class="table table-bordered" style="border-collapse: collapse">
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
                $trClass = $trade["points"] > 0 ? "profit-green" : "loss-red";
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
    echo "<div class='center'><h1 style='text-align: center;'>No trades!</h1></div>";
}
?>
<script>
    $(document).ready(function () {
        $('#backtest_tradebook').DataTable({
            "lengthMenu": [ 10, 25, 50, 75, 100 ],
            "pageLength": 50,
            order: [[0, 'desc']]
        });
        var currentTrade = <?php if(isset($currentTrade)){echo json_encode($currentTrade);} else{echo "[]";} ?>;
        $.each(currentTrade, function( key, value ) {
            console.log('caste: ' + key + ' | id: ' +value);
        });
    });
</script>
