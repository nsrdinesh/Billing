<?php
include('config.php');
include('index.php');

?>
	<script>
		$("#reportGen").live("click",function(){
				var value = $("#selectReport").val();
				window.location.href = "report.php?month="+value;
		});
	</script>
			<div class="container" style="padding:20px;">
			<span>Generate Report</span>
			<select id="selectReport">
				<option value="1">January</option>
				<option value="2">February</option>
				<option value="3">March</option>
				<option value="4">Apilr</option>
				<option value="5">May</option>
				<option value="6">June</option>
				<option value="7">July</option>
				<option value="8">August</option>
				<option value="9">September</option>
				<option value="10">October</option>
				<option value="11">November</option>
				<option value="12">December</option>
			</select>
			<input type="button" value="Generate" id="reportGen">
		</div>