<?php
//---------//
$ex_phpself = explode("/" , $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/" , $ex_phpself);
//---------//
?>

@extends('layouts.brain')

@section('title', '金額修理')

@section('content')
    <div><img src="{{ $public_path }}/img/list.png" id="btn_money_list"></div>

    <div>
        <input type="date" id="basedate">
        <input type="number" id="datenum" value="0">
        <input type="button" id="btn_set" value="set">
    </div>

    <hr>

    <div>
        <div id="div_beforeMoney"></div>

	    <form method="POST" action="{{ url('/money/repairinput') }}" id="form_money_repair">
	        {{ csrf_field() }}
        	<div id="div_dailySpend"></div>
        </form>

        <div id="div_totalSpend"></div>
    </div>

    <div>
        <input type="button" id="btn_input" value="input">
    </div>

    <hr>

    <div id="div_plus">
        <input type="number" id="plusnum" value="0">
        <input type="button" id="btn_plus" value="plus">
    </div>

    <style type="text/css">
    <!--
    #btn_money_list {cursor : pointer; margin : 2px; }
    #datenum {width : 40px ; text-align : right; }
    #beforeMoney {font-weight : bold; }
    #totalSpend {font-weight : bold; }
    #tbl_dailySpend td {border : 1px solid #cccccc; text-align : right; width : 70px; }
    #midashi td {background : #339933; color : #ffffff; text-align : center; }
    .td_sum {background : #ccccff; }
    .td_sagaku {background : #ff9999; }
    .yen_number {width : 40px; text-align : right; }
    #plusnum {width : 40px ; text-align : right; }
    #div_plus {background : #ffffcc; padding : 3px; }
    -->
    </style>

    <script type="text/javascript">
    <!--
    $("#btn_money_list").width(38);
    $("#btn_money_list").click(function (){location.href = "{{ url('/money/index') }}";});

    $("#btn_set").click(function (){
	    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	    $.ajax({
	        url: '{{ $appUrl }}/money/repairsearch',
	        type: 'POST',
	        data: {
	        	_token: CSRF_TOKEN ,
	        	basedate: $("#basedate").val() , 
	        	datenum : $("#datenum").val()
	        },
	        success: function (data) {
	            if (data != ""){
	            	var ex_data = (data).split(";");

					var ex_data_0 = (ex_data[0]).split(":");
	            	$("#div_beforeMoney").html(ex_data_0[0] + " : <span id='beforeMoney'>" + ex_data_0[1] + "</span>");

					/////////////////////////////////////////////////////
					var yenType = "";

	            	var str = "";
	            	str += "<table border='0' cellspacing='2' cellpadding='2' id='tbl_dailySpend'>";

	            	var ex_data_1 = (ex_data[1]).split("/");

	            	var ex_data_1_data = (ex_data_1[0]).split("|");

	            	str += "<tr id='midashi'>";
	            	for (var j=0 ; j<ex_data_1_data.length ; j++){
	            		var ex_data_1_data_item = (ex_data_1_data[j]).split(":");

	            		switch (ex_data_1_data_item[0]){
	        				case "date":
	            				str += "<td>" + ex_data_1_data_item[0] + "</td>";
	            				str += "<td>youbi</td>";
	        					break;

	        				case "sum":
	        				case "sagaku":
	        					str += "<td class='td_" + ex_data_1_data_item[0] + "'>" + ex_data_1_data_item[0] + "</td>";
	        					break;

	        				default:
	        					str += "<td>" + ex_data_1_data_item[0] + "</td>";
	        					yenType += ex_data_1_data_item[0] + "|";
	        					break;
	            		}
	            	}
	            	str += "</tr>";

					var Youbi = ['日' , '月' , '火' , '水' , '木' , '金' , '土'] ;

					var l=0;
	            	for (var i=0 ; i<ex_data_1.length ; i++){
	            		str += "<tr>";

	            		var ex_data_1_data = (ex_data_1[i]).split("|");
	            		for (var j=0 ; j<ex_data_1_data.length ; j++){
	            			var ex_data_1_data_item = (ex_data_1_data[j]).split(":");

	            			switch (ex_data_1_data_item[0]){
	            				case "date":
	            					str += "<td style='text-align : center; width : 80px;'>";
	            					str += ex_data_1_data_item[1];
	            					str += "<input type='hidden' ";
	            					str += "id='" + ex_data_1_data_item[0] + "_" + i + "' ";
	            					str += "name='param[" + i + "][" + ex_data_1_data_item[0] + "]'";
	            					str += "value='" + ex_data_1_data_item[1] + "'>";
	            					str += "</td>";

	            					var today = new Date(ex_data_1_data_item[1]);
	            					var bgColor = "";
	            					switch (parseInt(today.getDay())){
	            						case 0:
	            							bgColor = "background : #ffcccc;";
	            							break;
	            						case 6:
	            							bgColor = "background : #ccccff;";
	            							break;
	            						default:
	            							break;
	            					}
	            					str += "<td style='text-align : center; width : 80px;" + bgColor + "'>";
	            					
									str += Youbi[today.getDay()];
	            					str += "</td>";

	            					break;

	            				case "sum":
	            				case "sagaku":
	            					str += "<td class='td_" + ex_data_1_data_item[0] + "'>";
	            					str += "<span id='span_" + ex_data_1_data_item[0] + "_" + i + "'>" + ex_data_1_data_item[1] + "</span>";
	            					str += "<input type='hidden' ";
	            					str += "id='" + ex_data_1_data_item[0] + "_" + i + "' ";
	            					str += "value='" + ex_data_1_data_item[1] + "'>";
	            					if (ex_data_1_data_item[0] == "sum"){
		            					str += "<div>" + ex_data_1_data_item[1] + "</div>";
		            					l++;
	            					}else{
	            						str += "<div id='change_" + ex_data_1_data_item[0] + "_" + i + "'>0</div>";
	            						str += "<input type='hidden' ";
	            						str += "id='cval_" + ex_data_1_data_item[0] + "_" + i + "' ";
	            						str += "name='param[" + i + "][" + ex_data_1_data_item[0] + "]'>";
	            					}
	            					str += "</td>";
	            					break;

	            				default:
	            					str += "<td>";
	            					str += "<input type='number' ";
	            					str += "id='" + ex_data_1_data_item[0] + "_" + i + "' ";
	            					str += "name='param[" + i + "][" + ex_data_1_data_item[0] + "]'";
	            					str += "value='" + ex_data_1_data_item[1] + "' ";
	            					str += "onblur='javascript:calcNowSum(" + i + ");' ";
	            					str += "class='yen_number'>";
	            					str += "<input type='radio' name='num_plus' onclick='javascript:radioSelect(\"" + ex_data_1_data_item[0] + ";" + i + "\");'>";
	            					str += "</td>";
	            					break;
	            			}
	            		}

						str += "</tr>";
	            	}
	            	str += "</table>";

	            	str += "<input type='hidden' id='yenType' value='" + yenType + "'>";

	            	str += "<input type='hidden' id='sum_num' value='" + l + "'>";

	            	str += "<input type='hidden' id='selected_radio'>";

	            	$("#div_dailySpend").html(str);
					/////////////////////////////////////////////////////

					var ex_data_2 = (ex_data[2]).split(":");
	            	$("#div_totalSpend").html(ex_data_2[0] + " : <span id='totalSpend'>" + ex_data_2[1] + "</span>");
	            }
	        }
	    });
    });

    function calcNowSum(num){
    	var ex_yenType = ($("#yenType").val()).split("|");

    	var sum = $("#sum_" + num).val();

    	var allMoney = 0;
    	for (var i=0 ; i<ex_yenType.length-1 ; i++){
    		var yen = ex_yenType[i].replace("yen_" , "");
    		allMoney += parseInt(yen) * parseInt($("#" + ex_yenType[i] + "_" + num).val());
    	}

    	if (parseInt(sum) != parseInt(allMoney)){
    		$("#span_sum_" + num).html(allMoney);

			var diff = 0;
    		if (parseInt(sum) > parseInt(allMoney)){
    			//総額を小さくした
    			diff = parseInt(sum) - parseInt(allMoney);
    			$("#span_sagaku_" + num).html(parseInt($("#sagaku_" + num).val()) + parseInt(diff));
    		}else if (parseInt(sum) < parseInt(allMoney)){
    			//総額を大きくした
    			diff = parseInt(allMoney) - parseInt(sum);
    			$("#span_sagaku_" + num).html(parseInt($("#sagaku_" + num).val()) - parseInt(diff));
    		}

			var diff2 = 0;
			diff2 = (parseInt(sum) - parseInt(allMoney)) * -1;
    		$("#change_sagaku_" + num).html(diff2);
    		$("#cval_sagaku_" + num).val(diff2);
    	}
    }

    $("#btn_input").click(function (){
    	var div_dailySpend = $("#div_dailySpend").html();
    	if (div_dailySpend == ""){return 0;}

    	var sum_num = $("#sum_num").val();

    	var allSagaku = 0;
    	for (var i=0 ; i<sum_num ; i++){
    		allSagaku += parseInt($("#span_sagaku_" + i).html());
    	}

    	var selected_radio = $("#selected_radio").val();
    	if (selected_radio != ""){
    		var ex_sr = (selected_radio).split(";");
    		var yen = ex_sr[0].replace("yen_" , "");
    		var plusnum = $("#plusnum").val();
    		var sum_num = $("#sum_num").val();
    		var plus = ((parseInt(sum_num) - parseInt(ex_sr[1])) * parseInt(yen) * parseInt(plusnum));
    		allSagaku += plus;
    	}

    	var totalSpend = $("#totalSpend").html();

    	$("#form_money_repair").submit();
    });

    function radioSelect(item){
    	$("#selected_radio").val(item);
    }

    $("#btn_plus").click(function (){
    	var div_dailySpend = $("#div_dailySpend").html();
    	if (div_dailySpend == ""){return 0;}

    	var selected_radio = $("#selected_radio").val();
    	var sum_num = $("#sum_num").val();
    	var ex_sr = (selected_radio).split(";");
    	var plusnum = $("#plusnum").val();
    	for (var i=ex_sr[1] ; i<sum_num ; i++){
    		var val = $("#" + ex_sr[0] + "_" + i).val();
    		$("#" + ex_sr[0] + "_" + i).val(parseInt(val) + parseInt(plusnum));
    		calcNowSum(i);
    	}
    });
    -->
    </script>

@stop
