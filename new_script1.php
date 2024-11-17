<?php
//получает год через пользовательский ввод
$year_input = readline("Введите год:");

//конечная дата в виде строки в определенном формате
$to_date_str = "$year_input-01-01";

//начальная дата
$from = new DateTime('2000-01-01');

//конечная дата
$to   = new DateTime($to_date_str);

//период дат
$period = new DatePeriod($from, new DateInterval('P1M'), $to);

//период дат в виде массива
$all_dates = iterator_to_array($period);

//функция собирает массив с датами первых пятниц каждого месяца
function get_target_dates($all_dates) {
    $target_dates = array();
    foreach($all_dates as $dt) {
        if($dt->format('D') != 'Fri') {
            $day = $dt->format('d');
            $month = $dt->format('m');
            $year = $dt->format('Y');
            if ($dt->format('D') == 'Mon') {
                $day = $day + 4;
            }

            elseif ($dt->format('D') == 'Tue') {
                $day = $day + 3;
            }

            elseif ($dt->format('D') == 'Wed') {
                $day = $day + 2;
            }

            elseif ($dt->format('D') == 'Thu') {
                $day = $day + 1;
            }

            elseif ($dt->format('D') == 'Sat') {
                $day = $day + 6;
            }

            elseif ($dt->format('D') == 'Sun') {
                $day = $day + 5;
            }

            $t_dt = new DateTime("$year-$month-$day");

            if($t_dt->format('d') % 2 == 0) {
                $promo = [$t_dt, 'стулья'];
            }
            else {
                $promo = [$t_dt, 'столы'];
            }
            array_push($target_dates, $promo);
        }
        else {
            if($dt->format('d') % 2 == 0) {
            $promo = [$dt, 'стулья'];
            }
            else {
            $promo = [$dt, 'столы'];
            }
            array_push($target_dates, $promo);
        }
    }
    return $target_dates;
}

//массив с датами первых пятниц каждого месяца
$target_dates = get_target_dates($all_dates);

//функция переопределяет акции на столы и стулья
function redefine_promo($target_dates) {
    $first_year = $target_dates[0][0]->format('Y');
    $amount_chair = 0;
    $amount_table = 0;
    $promo_on_product = '';
    $cnt = 0;
    $cnt_while = 0;

    for ($i=1; $i<count($target_dates)-1; $i++) {
        if($first_year != $target_dates[$i][0]->format('Y')) {

            for ($j=0; $j<$i; $j++) {
                if($target_dates[$j][1] == 'стулья') {
                    $amount_chair += 1;
                }
                else {
                    $amount_table += 1;
                }
            }
            if ($amount_chair > $amount_table) {
                $promo_on_product = 'столы';
            }
            else {
                $promo_on_product = 'стулья';
            }
            $cnt = abs($amount_chair - $amount_table);
            while ($cnt>=0 and $i+$cnt_while < count($target_dates)) {
                if($target_dates[$i+$cnt_while][1] != $promo_on_product) {
                    $target_dates[$i+$cnt_while][1] = $promo_on_product;
                    $cnt -= 1;
                }
                $cnt_while += 1;
            }
            $cnt_while = 0;
            $amount_chair = 0;
            $amount_table = 0;
            $first_year = $target_dates[$i][0]->format('Y');
        };
    }
}

redefine_promo($target_dates);

//функция выводит на экран даты акций на столы
function show_dates_of_promo($target_dates) {
    $months_arr = array();
    $months_arr['Jan'] = 'янв';
    $months_arr['Feb'] = 'фев';
    $months_arr['Mar'] = 'мар';
    $months_arr['Apr'] = 'апр';
    $months_arr['May'] = 'май';
    $months_arr['Jun'] = 'июн';
    $months_arr['Jul'] = 'июл';
    $months_arr['Aug'] = 'авг';
    $months_arr['Sep'] = 'сен';
    $months_arr['Oct'] = 'окт';
    $months_arr['Nov'] = 'ноя';
    $months_arr['Dec'] = 'дек';
    for ($i=0; $i<count($target_dates)-1; $i++) {
        $day = $target_dates[$i][0]->format('d');
        $month = $months_arr[$target_dates[$i][0]->format('M')];
        $year = $target_dates[$i][0]->format('Y');
        if($target_dates[$i][1] == 'столы') {
            echo "$day-е $month. $year\n";
        }
    }
}

show_dates_of_promo($target_dates);
