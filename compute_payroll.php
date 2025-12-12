<?php
function computePayroll(
    $daily_rate, 
    $days_worked, 
    $ot_hours, 
    $night_hours, 
    $regular_holiday,
    $special_holiday,
    $allowance,
    $cash_advance
) {

    $hourly_rate = $daily_rate / 8;

    // BASIC PAY
    $basic_pay = $daily_rate * $days_worked;

    // OVERTIME (125%)
    $overtime_pay = $hourly_rate * 1.25 * $ot_hours;

    // NIGHT DIFFERENTIAL (10%)
    $night_diff = $hourly_rate * 0.10 * $night_hours;

    // HOLIDAY PAY
    $regular_holiday_pay = $regular_holiday * ($daily_rate * 2);
    $special_holiday_pay = $special_holiday * ($daily_rate * 1.30);

    // GROSS
    $gross_income = $basic_pay + $overtime_pay + $night_diff 
                    + $regular_holiday_pay + $special_holiday_pay 
                    + $allowance;

    // DEDUCTIONS
    $sss = $gross_income * 0.045;  
    $philhealth = $gross_income * 0.025;
    $pagibig = 100;                

    $total_deductions = $sss + $philhealth + $pagibig + $cash_advance;

    // NET PAY
    $net_pay = $gross_income - $total_deductions;

    return [
        "basic_pay" => $basic_pay,
        "overtime" => $overtime_pay,
        "night_diff" => $night_diff,
        "holiday_pay" => $regular_holiday_pay + $special_holiday_pay,
        "gross_income" => $gross_income,
        "deductions" => $total_deductions,
        "net_pay" => $net_pay
    ];
}
?>
