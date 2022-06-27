<?php

namespace Core;

class TradeAnalytics
{
    const BROKERAGE = 200;

    public function prepareAnalytics($trades, $qty) {
        $analytics = [];
        $overall = [
            "wins" => 0,
            "losses" =>0,
            "long_trades" => 0,
            "long_wins" => 0,
            "long_losses" => 0,
            "short_trades" => 0,
            "short_wins" => 0,
            "short_losses" => 0,
            "total_points" => 0,
            "max_profit" => 0,
            "max_loss" => 0,
            "max_profit_day" => 0,
            "max_loss_day" => 0,
        ];
        $dateWisePNL = [];
        $allTrades = [];
        foreach ($trades as $trade) {
            $allTrades[] = $trade;
            //overall
            if ($trade["points"] >= 5) {
                $overall["wins"] = $overall["wins"] + 1;
            } else {
                $overall["losses"] = $overall["losses"] + 1;
            }
            if($trade["trade_type"] == "long") {
                $overall["long_trades"] = $overall["long_trades"] + 1;
                if ($trade["points"] >= 5) {
                    $overall["long_wins"] = $overall["long_wins"] + 1;
                } else {
                    $overall["long_losses"] = $overall["long_losses"] + 1;
                }
            } else {
                $overall["short_trades"] = $overall["short_trades"] +1;
                if ($trade["points"] >= 5) {
                    $overall["short_wins"] = $overall["short_wins"] + 1;
                } else {
                    $overall["short_losses"] = $overall["short_losses"] + 1;
                }
            }
            $tradePnl = ((int)$trade["points"] * (int)$trade["qty"]) - self::BROKERAGE;
            $tradesPnl[] = $tradePnl;
            $dateWisePNL[$trade["date"]] = isset($dateWisePNL[$trade["date"]]) ? $dateWisePNL[$trade["date"]] + $tradePnl : $tradePnl;
            $overall["max_profit"] = $tradePnl > $overall["max_profit"] ? $tradePnl : $overall["max_profit"];
            $overall["max_loss"] = $tradePnl < $overall["max_loss"] ? $tradePnl : $overall["max_loss"];
            $overall["total_points"] = $overall["total_points"] + (int)$trade["points"];

            $setups = explode(",", $trade["setup"]);
            foreach ($setups as $setup) {
                //add trade
                $analytics[$setup]["trades"][] = $trade;

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
                    $pnl = ((int)$trade["qty"] * (int)$trade["points"]) - self::BROKERAGE;
                    if ($pnl > $analytics[$setup]["analytics"]["final_analytics"]["max_profit_per_trade"]["pnl"]){
                        $analytics[$setup]["analytics"]["final_analytics"]["max_profit_per_trade"]["pnl"] = $pnl;
                        $analytics[$setup]["analytics"]["final_analytics"]["max_profit_per_trade"]["points"] = $trade["points"];
                        $analytics[$setup]["analytics"]["final_analytics"]["max_profit_per_trade"]["date"] = $trade["date"];
                        $analytics[$setup]["analytics"]["final_analytics"]["max_profit_per_trade"]["trade_type"] = $trade["trade_type"];
                        $analytics[$setup]["analytics"]["final_analytics"]["max_profit_per_trade"]["symbol"] = $trade["symbol"];
                    }
                } else {
                    //first trade
                    $analytics[$setup]["analytics"]["final_analytics"]["max_profit_per_trade"]["pnl"] = ((int)$trade["qty"] * (int)$trade["points"]) - self::BROKERAGE;
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
                    $pnl = ((int)$trade["qty"] * (int)$trade["points"]) - self::BROKERAGE;
                    if ($pnl < $analytics[$setup]["analytics"]["final_analytics"]["max_loss_per_trade"]["pnl"]){
                        $analytics[$setup]["analytics"]["final_analytics"]["max_loss_per_trade"]["pnl"] = $pnl;
                        $analytics[$setup]["analytics"]["final_analytics"]["max_loss_per_trade"]["points"] = $trade["points"];
                        $analytics[$setup]["analytics"]["final_analytics"]["max_loss_per_trade"]["date"] = $trade["date"];
                        $analytics[$setup]["analytics"]["final_analytics"]["max_loss_per_trade"]["trade_type"] = $trade["trade_type"];
                        $analytics[$setup]["analytics"]["final_analytics"]["max_loss_per_trade"]["symbol"] = $trade["symbol"];
                    }
                } else {
                    //first trade
                    $analytics[$setup]["analytics"]["final_analytics"]["max_loss_per_trade"]["pnl"] = ((int)$trade["qty"] * (int)$trade["points"]) - self::BROKERAGE;
                    $analytics[$setup]["analytics"]["final_analytics"]["max_loss_per_trade"]["points"] = $trade["points"];
                    $analytics[$setup]["analytics"]["final_analytics"]["max_loss_per_trade"]["date"] = $trade["date"];
                    $analytics[$setup]["analytics"]["final_analytics"]["max_loss_per_trade"]["trade_type"] = $trade["trade_type"];
                    $analytics[$setup]["analytics"]["final_analytics"]["max_loss_per_trade"]["symbol"] = $trade["symbol"];
                }
                //max profit day
                $analyticsPerDay[$trade["date"]] = isset($analyticsPerDay[$trade["date"]]) ?
                    $analyticsPerDay[$trade["date"]] + (((int)$trade["qty"] * (int)$trade["points"]) - self::BROKERAGE) : ((int)$trade["qty"] * (int)$trade["points"]) - self::BROKERAGE;
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
                        $analytics[$setup]["analytics"]["final_analytics"]["pnl"] = $analytics[$setup]["analytics"]["final_analytics"]["pnl"] + (((int)$trade["qty"] * (int)$trade["points"]) - self::BROKERAGE) :
                        (((int)$trade["qty"] * (int)$trade["points"]) - self::BROKERAGE);

                $tradeDates[] = $trade["date"];
                //extra info
                $analytics[$setup]["analytics"]["start_date"] = count($tradeDates) > 1 ? min($tradeDates) : $trade["date"];
                $analytics[$setup]["analytics"]["end_date"] = count($tradeDates) > 1 ? max($tradeDates) : $trade["date"];
                $earlier = new \DateTime($analytics[$setup]["analytics"]["start_date"]);
                $later = new \DateTime($analytics[$setup]["analytics"]["end_date"]);
                $analytics[$setup]["analytics"]["working_days"] = $later->diff($earlier)->format("%a");
            }
        }
        $overall["total_trades"] = count($allTrades);
        $overall["winrate"] = round($overall["wins"] / $overall["total_trades"] * 100, 2);
        $overall["long_winrate"] = round($overall["long_wins"] / $overall["long_trades"] * 100, 2);
        $overall["short_winrate"] = round($overall["short_wins"] / $overall["short_trades"] * 100, 2);
        $overall["final_analytics"]["max_profit_per_trade"]["pnl"] = max($tradesPnl); //$overall["max_profit"];
        $overall["final_analytics"]["max_loss_per_trade"]["pnl"] = min($tradesPnl);//$overall["max_loss"];
        $overall["final_analytics"]["max_profit_per_day"]["profit"] = max($dateWisePNL);
        $overall["final_analytics"]["max_loss_per_day"]["loss"] = min($dateWisePNL);
        $overall["final_analytics"]["pnl"] = ($overall["total_points"] * $qty) - count($allTrades) * self::BROKERAGE;
        $earlier = new \DateTime(min($tradeDates));
        $later = new \DateTime(max($tradeDates));
        $overall["start_date"] = min($tradeDates);
        $overall["end_date"] = max($tradeDates);
        $overall["working_days"] = $later->diff($earlier)->format("%a");
        $result["overall"] = $overall;
        $overall["analytics"] = $overall;
        $overall["trades"] = $allTrades;
        array_unshift($analytics, $overall);
        $result["analysis"] = $analytics;
        $result["all_trades"] = $allTrades;
        return $result;
    }
}