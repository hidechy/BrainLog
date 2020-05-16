<?php
//---------//
$ex_phpself = explode("/" , $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/" , $ex_phpself);
//---------//
?>

@extends('layouts.brain')

@section('title', '銀行口座入力')

@section('content')
    <div><img src="{{ $public_path }}/img/list.png" id="btn_money_list"></div>

    <table border="0" cellspacing="2" cellpadding="2" id="tbl_money_summary">
        @foreach($data as $lineno=>$line)
            <?php
            list($year , $month , $monthend , $end , $salary) = explode("|" , $line);
            if ($month == 1){$spendTotal = [];}
            ?>

            <tr>
                <td style="width : 40px; text-align : center;">{{ $year }}</td>
                <td style="width : 40px; text-align : center;">{{ $month }}</td>
                <td style="width : 40px; text-align : center;">{{ $monthend }}</td>
                <td>{!! number_format($end) !!}</td>

                <?php
                $spe = $spend[$lineno];
                $bgColor = ($spe > 0) ? "background : #ccccff" : "";
                ?>
                <td style="{{ $bgColor }}">{!! number_format($spe) !!}</td>

                <td>{!! number_format($salary) !!}</td>

                <?php
                $spend__ = ($spe - $salary);
                $spendTotal[] = ($spend__ * -1);
                ?>
                <td>{!! number_format($spend__ * -1) !!}</td>

                <?php
                $_credit = (isset($credit[$year][$month])) ? $credit[$year][$month] : 0;
                ?>
                <td>{!! number_format($_credit) !!}</td>

                <?php
                $everyday = (($spend__ * -1) - $_credit);
                ?>
                <td>{!! number_format($everyday) !!}</td>

                <?php
                $everyday_average = floor($everyday / $monthend);
                ?>
                <td>{!! number_format($everyday_average) !!}</td>
            </tr>

            @if ($month == 12)
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                    <?php
                    echo number_format(array_sum($spendTotal));
                    ?>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td style="background : #339933; color : #ffffff; text-align : center;">year</td>
                    <td style="background : #339933; color : #ffffff; text-align : center;">month</td>
                    <td style="background : #339933; color : #ffffff; text-align : center;">monthend</td>
                    <td style="background : #339933; color : #ffffff; text-align : center;">end</td>
                    <td style="background : #339933; color : #ffffff; text-align : center;">total</td>
                    <td style="background : #339933; color : #ffffff; text-align : center;">salary</td>
                    <td style="background : #339933; color : #ffffff; text-align : center;">spend</td>
                    <td style="background : #339933; color : #ffffff; text-align : center;">credit</td>
                    <td style="background : #339933; color : #ffffff; text-align : center;">everyday</td>
                    <td style="background : #339933; color : #ffffff; text-align : center;">average</td>
                </tr>
            @endif
        @endforeach
    </table>

    <div><br><br><br></div>

    <style type="text/css">
    <!--
    #tbl_money_summary td {border : 1px solid #cccccc; width : 80px; text-align : right;}
    #btn_money_list {cursor : pointer; margin : 2px; }
    -->
    </style>

    <script type="text/javascript">
    <!--
    $("#btn_money_list").width(38);
    $("#btn_money_list").click(function (){location.href = "{{ url('/money/index') }}";});
    -->
    </script>

@stop
