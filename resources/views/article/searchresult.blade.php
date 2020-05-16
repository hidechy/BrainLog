<?php
//---------//
$ex_phpself = explode("/" , $_SERVER['PHP_SELF']);
array_pop($ex_phpself);
$public_path = implode("/" , $ex_phpself);
//---------//
?>

@extends('layouts.brain')

@section('title', '検索結果')

@section('content')
	<meta name="viewport" content="width=device-width; initial-scale=1.0; user-scalable=yes; maximum-scale=1.0;">

	<table style="margin:5px;"><tr>
	<td>
	<img src="{{ $public_path }}/img/calender.png" id="btn_article_index">
	</td>
	</tr></table>

	<form method="POST" action="{{ url('/article/datejump') }}" id="form_article_datejump">
	{{ csrf_field() }}
	<input type="hidden" name="jumpdate" id="jumpdate">
	</form>

	@foreach ($data as $word=>$v)
		<div class="midashiDiv">{{ $word }}（<?php echo count($v); ?>）</div>

		@foreach ($v as $lineno=>$line)
			<div class="div_article">
			<div class="div_date">
			<?php
			$ansDate = $line['year'] . "-" . $line['month'] . "-" . $line['day'];
			?>
			<span class="linkBlock" onclick="javascript:pagejump('{{ $ansDate }}');">■</span>
			{{ $ansDate }}

<?php
$weekday = ['日', '月', '火', '水', '木', '金', '土'];
echo "（" . $weekday[date('w', strtotime($ansDate))] . "）";
?>

			<input type="checkbox" id="merge_chk_{{ $lineno }}" value="{{ $ansDate }}:{{ $line['num'] }}">
			</div>

			<?php
			$ex_article = explode("\n" , trim($line['article']));
			?>
			@foreach ($ex_article as $v)
				@if (preg_match("/^http/" , trim($v)))
					<div><a href="{!! trim($v) !!}" target="_blank">

					<?php
					echo strtr(trim($v) , [$searchword => '<span class="hitword">' . $searchword . '</span>']);
					?>

					</a></div>
				@else

					<div>
					<?php
					echo strtr(trim($v) , [$searchword => '<span class="hitword">' . $searchword . '</span>']);
					?>
					</div>

				@endif
			@endforeach
			</div>
		@endforeach
	@endforeach

	<form method="POST" action="{{ url('/article/articlemerge') }}" id="form_article_merge">
	{{ csrf_field() }}
	<input type="date" id="merge_date" name="merge_date">
	<div style="display:none;">
	<textarea id="ta_merge_data" name="merge_data"></textarea>
	</div>
	</form>

	<button id="btn_article_merge">同一日付に移動</button>

	<hr>

	<button id="btn_article_delete">削除する</button>

	<br><br><br>

	<style type="text/css">
	<!--
	#btn_article_index {cursor : pointer; margin : 2px; }
	.midashiDiv {background : #339933; color : #ffffff; font-weight : bold; padding : 5px; }
	.div_article {border : 1px solid #cccccc; margin : 10px; padding : 5px; }
	.div_date {background : #ccffcc; }
	.linkBlock {font-size : 18px; cursor : pointer; color : #339933; }

	.hitword {color: #ff0000; font-weight: bold;}
	-->
	</style>

	<script type="text/javascript">
	<!--
	function pagejump(date){
	$("#jumpdate").val(date);
	$("#form_article_datejump").submit();
	}

	$("#btn_article_index").width(38);
	$("#btn_article_index").click(function (){location.href = "{{ url('/article/index') }}";});

	$("#btn_article_merge").click(function (){

		if ($("#merge_date").val() == ""){
			window.alert("移動日未選択");
			return false;
		}

		let mergedata = [];
		let j=0;
		for (let i=0 ; i<100 ; i++){
			if (document.getElementById('merge_chk_' + i) != undefined){
				if ($("#merge_chk_" + i + ":checked").val()){
					mergedata[j] = $("#merge_chk_" + i).val();
					j++;
				}
			}
		}

		if (mergedata.length > 0){
			$("#ta_merge_data").val(mergedata.join('/'));

			$("#form_article_merge").submit();
		}else{
			window.alert("移動データ未選択");
			return false;
		}
	});



	$("#btn_article_delete").click(function (){

		let mergedata = [];
		let j=0;
		for (let i=0 ; i<100 ; i++){
			if (document.getElementById('merge_chk_' + i) != undefined){
				if ($("#merge_chk_" + i + ":checked").val()){
					mergedata[j] = $("#merge_chk_" + i).val();
					j++;
				}
			}
		}

		if (mergedata.length > 0){
			$("#ta_merge_data").val(mergedata.join('/'));

	        $("#form_article_merge").attr("action", "{{ url('/article/articledelete') }}");
			$("#form_article_merge").submit();
		}else{
			window.alert("移動データ未選択");
			return false;
		}
	});
	-->
	</script>

@stop
