<?php

namespace Core;

class TradeAnalytics
{
    public function prepareAnalytics($trades, $qty) {
        $analytics = [];
        $allTrades = [];
        foreach ($trades as $trade) {
            $allTrades[] = $trade;
            $setups = explode(",", $trade["setup"]);
            foreach ($setups as $setup) {
                //add trade
                $analytics[$setup]["trades"][] = $trade;
                if ($trade["points"]<2){
                    $trade["qty"]=$qty + 3;
                } else {
                    $trade["qty"]=$qty - 3;
                }
                //add analytics
                if ((int)$trade["points"] >= 5) {
                    $analytics[$setup]["analytics"]["wins"] =
                        isset($analytics[$setup]["analytics"]) && isset($analytics[$setup]["analytics"]["wins"]) ?
                            $analytics[$setup]["analytics"]["wins"]+1 : 1;
                } else {
                    $analytics[$setup]["analytics"]["losses"] =
                        isset($analytics[$setup]["analytics"]) && isset($analytics[$setup]["analytics"]["losses"]) ?
                            $analytics[$setup]["analytics"]["losses"]+1 : 1;
                }
                //long/short
                if ($trade["trade_type"]=="long") {
                    //long +
                    $analytics[$setup]["analytics"]["long_trades"] =
                        isset($analytics[$setup]["analytics"]) && isset($analytics[$setup]["analytics"]["long_trades"]) ?
                            $analytics[$setup]["analytics"]["long_trades"]+1 : 1;
                    //long win/loss
                    if ((int)$trade["points"] >= 5) {
                        $analytics[$setup]["analytics"]["long_wins"] =
                            isset($analytics[$setup]["analytics"]) && isset($analytics[$setup]["analytics"]["long_wins"]) ?
                                $analytics[$setup]["analytics"]["long_wins"]+1 : 1;
                    } else {
                        $analytics[$setup]["analytics"]["long_losses"] =
                            isset($analytics[$setup]["analytics"]) && isset($analytics[$setup]["analytics"]["long_losses"]) ?
                                $analytics[$setup]["analytics"]["long_losses"]+1 : 1;
                    }
                } else {
                    $analytics[$setup]["analytics"]["short_trades"] =
                        isset($analytics[$setup]["analytics"]) && isset($analytics[$setup]["analytics"]["short_trades"]) ?
                            $analytics[$setup]["analytics"]["short_trades"]+1 : 1;
                    if ((int)$trade["points"] >= 5) {
                        $analytics[$setup]["analytics"]["short_wins"] =
                            isset($analytics[$setup]["analytics"]) && isset($analytics[$setup]["analytics"]["short_wins"]) ?
                                $analytics[$setup]["analytics"]["short_wins"]+1 : 1;
                    } else {
                        $analytics[$setup]["analytics"]["short_losses"] =
                            isset($analytics[$setup]["analytics"]) && isset($analytics[$setup]["analytics"]["short_losses"]) ?
                                $analytics[$setup]["analytics"]["short_losses"]+1 : 1;
                    }
                }
                //winrate
                if(
                    isset($analytics[$setup]["analytics"]) &&
                    isset($analytics[$setup]["analytics"]["wins"]) &&
                    isset($analytics[$setup]["analytics"]["losses"])
                ){
                    $analytics[$setup]["analytics"]["winrate"] =
                        $analytics[$setup]["analytics"]["wins"] / ($analytics[$setup]["analytics"]["wins"] + $analytics[$setup]["analytics"]["losses"]) *100;
                }
                //long winrate
                if(
                    isset($analytics[$setup]["analytics"]) &&
                    isset($analytics[$setup]["analytics"]["long_wins"]) &&
                    isset($analytics[$setup]["analytics"]["long_losses"])
                ){
                    $analytics[$setup]["analytics"]["long_winrate"] =
                        $analytics[$setup]["analytics"]["long_wins"] / ($analytics[$setup]["analytics"]["long_wins"] + $analytics[$setup]["analytics"]["long_losses"]) *100;
                }
                //short winrate
                if(
                    isset($analytics[$setup]["analytics"]) &&
                    isset($analytics[$setup]["analytics"]["short_wins"]) &&
                    isset($analytics[$setup]["analytics"]["short_losses"])
                ){
                    $analytics[$setup]["analytics"]["short_winrate"] =
                        $analytics[$setup]["analytics"]["short_wins"] / ($analytics[$setup]["analytics"]["short_wins"] + $analytics[$setup]["analytics"]["short_losses"]) *100;
                }

                //final analytics
                //max profit per trade
                if (
                    isset($analytics[$setup]["analytics"]) &&
                    isset($analytics[$setup]["analytics"]["final_analytics"]) &&
                    isset($analytics[$setup]["analytics"]["final_analytics"]["max_profit_per_trade"]) &&
                    isset($analytics[$setup]["analytics"]["final_analytics"]["max_profit_per_trade"]["pnl"])
                ){
                    $pnl = (int)$trade["qty"] * (int)$trade["points"];
                    if ($pnl > $analytics[$setup]["analytics"]["final_analytics"]["max_profit_per_trade"]["pnl"]){
                        $analytics[$setup]["analytics"]["final_analytics"]["max_profit_per_trade"]["pnl"] = $pnl;
                        $analytics[$setup]["analytics"]["final_analytics"]["max_profit_per_trade"]["points"] = $trade["points"];
                        $analytics[$setup]["analytics"]["final_analytics"]["max_profit_per_trade"]["date"] = $trade["date"];
                        $analytics[$setup]["analytics"]["final_analytics"]["max_profit_per_trade"]["trade_type"] = $trade["trade_type"];
                        $analytics[$setup]["analytics"]["final_analytics"]["max_profit_per_trade"]["symbol"] = $trade["symbol"];
                    }
                } else {
                    //first trade
                    $analytics[$setup]["analytics"]["final_analytics"]["max_profit_per_trade"]["pnl"] = (int)$trade["qty"] * (int)$trade["points"];
                    $analytics[$setup]["analytics"]["final_analytics"]["max_profit_per_trade"]["points"] = $trade["points"];
                    $analytics[$setup]["analytics"]["final_analytics"]["max_profit_per_trade"]["date"] = $trade["date"];
                    $analytics[$setup]["analytics"]["final_analytics"]["max_profit_per_trade"]["trade_type"] = $trade["trade_type"];
                    $analytics[$setup]["analytics"]["final_analytics"]["max_profit_per_trade"]["symbol"] = $trade["symbol"];
                }
                //max loss per trade
                if (
                    isset($analytics[$setup]["analytics"]) &&
                    isset($analytics[$setup]["analytics"]["final_analytics"]) &&
                    isset($analytics[$setup]["analytics"]["final_analytics"]["max_loss_per_trade"]) &&
                    isset($analytics[$setup]["analytics"]["final_analytics"]["max_loss_per_trade"]["pnl"])
                ){
                    $pnl = (int)$trade["qty"] * (int)$trade["points"];
                    if ($pnl < $analytics[$setup]["analytics"]["final_analytics"]["max_loss_per_trade"]["pnl"]){
                        $analytics[$setup]["analytics"]["final_analytics"]["max_loss_per_trade"]["pnl"] = $pnl;
                        $analytics[$setup]["analytics"]["final_analytics"]["max_loss_per_trade"]["points"] = $trade["points"];
                        $analytics[$setup]["analytics"]["final_analytics"]["max_loss_per_trade"]["date"] = $trade["date"];
                        $analytics[$setup]["analytics"]["final_analytics"]["max_loss_per_trade"]["trade_type"] = $trade["trade_type"];
                        $analytics[$setup]["analytics"]["final_analytics"]["max_loss_per_trade"]["symbol"] = $trade["symbol"];
                    }
                } else {
                    //first trade
                    $analytics[$setup]["analytics"]["final_analytics"]["max_loss_per_trade"]["pnl"] = (int)$trade["qty"] * (int)$trade["points"];
                    $analytics[$setup]["analytics"]["final_analytics"]["max_loss_per_trade"]["points"] = $trade["points"];
                    $analytics[$setup]["analytics"]["final_analytics"]["max_loss_per_trade"]["date"] = $trade["date"];
                    $analytics[$setup]["analytics"]["final_analytics"]["max_loss_per_trade"]["trade_type"] = $trade["trade_type"];
                    $analytics[$setup]["analytics"]["final_analytics"]["max_loss_per_trade"]["symbol"] = $trade["symbol"];
                }
                //max profit day
                $analyticsPerDay[$trade["date"]] = isset($analyticsPerDay[$trade["date"]]) ?
                    $analyticsPerDay[$trade["date"]] + ((int)$trade["qty"] * (int)$trade["points"]) : (int)$trade["qty"] * (int)$trade["points"];
                $analytics[$setup]["analytics"]["final_analytics"]["max_profit_per_day"]["profit"] = max($analyticsPerDay);
                $analytics[$setup]["analytics"]["final_analytics"]["max_profit_per_day"]["date"] = array_keys($analyticsPerDay, max($analyticsPerDay))[0];
                $analytics[$setup]["analytics"]["final_analytics"]["max_loss_per_day"]["loss"] = min($analyticsPerDay);
                $analytics[$setup]["analytics"]["final_analytics"]["max_loss_per_day"]["date"] = array_keys($analyticsPerDay, min($analyticsPerDay))[0];

                //total trades
                $analytics[$setup]["analytics"]["total_trades"] = count($analytics[$setup]["trades"]);
                //final pnl
                $analytics[$setup]["analytics"]["final_analytics"]["pnl"] =
                    isset($analytics[$setup]["analytics"]["final_analytics"]) &&
                    isset($analytics[$setup]["analytics"]["final_analytics"]["pnl"]) ?
                        $analytics[$setup]["analytics"]["final_analytics"]["pnl"] = $analytics[$setup]["analytics"]["final_analytics"]["pnl"] + ((int)$trade["qty"] * (int)$trade["points"]) :
                        ((int)$trade["qty"] * (int)$trade["points"]);

                $tradeDates[] = $trade["date"];
                //extra info
                $analytics[$setup]["analytics"]["start_date"] = count($tradeDates) > 1 ? min($tradeDates) : $trade["date"];
                $analytics[$setup]["analytics"]["end_date"] = count($tradeDates) > 1 ? max($tradeDates) : $trade["date"];
                $earlier = new \DateTime($analytics[$setup]["analytics"]["start_date"]);
                $later = new \DateTime($analytics[$setup]["analytics"]["end_date"]);
                $analytics[$setup]["analytics"]["working_days"] = $later->diff($earlier)->format("%a");
            }
        }
        $result["analysis"] = $analytics;
        $result["all_trades"] = $allTrades;
        return $result;
    }
}