<?php
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
include('mpdf/mpdf.php');
include('config.php');
include('index.php'); ?>
<div class="container">
Invoice generated Successfully!
</div>

<?php
$summaryHTML = '
	<center>Consolidated Report</center>
	<table border="1">
		<tr>
			<th>Customer Name</th><th>Customer ID</th><th>Billed Amount</th><th>Total Usage</th>
		</tr>';

//$cust_id=$_GET['customerid'];
$month=$_GET['month'];


$select = "select a.first_name,a.last_name, a.customer_id,a.meter_id,a.email_id,a.phone_number,a.first_name, b.address_line_1,b.address_line_2,b.city, b.state,b.pin,c.name,c.tan,c.website,c.tarif_id,c.Biller_id,c.legalmsg,c.logo,cr.symbol,t.rate_of_interest from customer as a join address as b on a.address_id = b.address_id join biller_info as c on a.biller_id = c.Biller_id join currency cr on c.currency_id = cr.currency_id join tax t on t.tax_id = c.tax_id";
$res = mysqli_query($link,$select);
while($rows = mysqli_fetch_array($res)){
$invoice_num = mt_rand(100000,999999); 

$cust_id = $rows['customer_id'];
$bill_period_query = "select start_time , end_time from cycle cy where cy.cycle_id = ( select c.cycle_id from customer c where c.customer_id = ".$cust_id." ) and cy.month=".$month;
$bill_interval = mysqli_query($link,$bill_period_query);
$time=mysqli_fetch_array($bill_interval);
$usage_query="select c.customer_id, m.meter_id, max(m.end_unit) end_unit, min(m.start_unit) start_unit, max(m.end_unit) - min(m.start_unit) total_usage from customer c, meter_usage m where c.meter_id =m.meter_id and c.customer_id=".$cust_id ; 
$usage_query.=" and m.start_time >= '".$time['start_time']."' and m.end_time <= '".$time['end_time']."'  group by customer_id";
$usage_result=mysqli_query($link,$usage_query);
$usage_data=mysqli_fetch_array($usage_result);



//$tier_query="select start_unit, end_unit, rate_per_unit from tier ti , tariff ta where ta.tier_id=ti.tier_id and ta.tariff_id =".$rows['tarif_id']." order by end_unit asc"; 
$tier_query="select start_unit, end_unit, rate_per_unit from tier ti , tariff ta where ta.tier_id=ti.tier_id and ta.tariff_id =".$rows['tarif_id']." order by end_unit asc"; 
$tier_result=mysqli_query($link,$tier_query);

$tiers = array();
$i=0;
while ($tier = mysqli_fetch_array($tier_result)) {
	$tiers[] = $tier;
	$i++;
}
$prev_tier_end = 0;
$total_amount = 0;
$tier_index = 0;
$amount = 0;
$total_usage_unit = $usage_data['total_usage'];
while($total_usage_unit > 0){
$next_tier_end = $tiers[$tier_index]['end_unit'];
$tier_interval = $next_tier_end - $prev_tier_end; 
if($tier_interval > $total_usage_unit){
	$amount = $amount + $total_usage_unit * $tiers[$tier_index]['rate_per_unit'];
	break;
}else{

	$amount = $amount + $tier_interval * $tiers[$tier_index]['rate_per_unit'];
}

$total_usage_unit = $total_usage_unit - $tier_interval;
$prev_tier_end = $tiers[$tier_index]['end_unit'];
$tier_index++;
}

//calculate tax
$pre_tax_amt = $amount;
$tax_amt = $amount * ($rows['rate_of_interest'] / 100);
$total_amount = $pre_tax_amt + $tax_amt;

$html = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
	<META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html">
	<TITLE></TITLE>
	<meta charset="utf-8">
	<STYLE TYPE="text/css">
	<!--
		@page { margin: 0.79in }
		P { margin-bottom: 0.08in }
		H1 { margin-bottom: 0.08in }
		H1.western { font-family: "Arial", sans-serif; font-size: 16pt }
		H1.cjk { font-family: "Microsoft YaHei"; font-size: 16pt }
		H1.ctl { font-family: "Mangal"; font-size: 16pt }
		TD P { margin-bottom: 0in }
		TH P { margin-bottom: 0in }
		A:link { so-language: zxx }
		.dataTable th, .dataTable td { border-width: 1px; border-color:#eeeeee; border-style: solid}
	-->
	</STYLE>
</HEAD>
<BODY LANG="en-US" DIR="LTR">
<img style="float:left;" alt="" src="images/'.$rows['logo'].'">
<H1 CLASS="western" ALIGN=CENTER><FONT FACE="Arial, sans-serif">'.$rows['name'].'</FONT></H1>
<P ALIGN=CENTER><BR><BR>
</P>
<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<COL WIDTH=128*>
	<COL WIDTH=128*>
	<TR VALIGN=TOP>
		<TD WIDTH=50%>
			<P ALIGN=LEFT><FONT FACE="Arial, sans-serif">'.$rows['first_name'].' '.$rows['last_name'].'</FONT></P>
		</TD>
		<TD WIDTH=50%>
			<P ALIGN=RIGHT><FONT FACE="Arial, sans-serif">Customer id:
			'.$rows['customer_id'].'</FONT></P>
		</TD>
	</TR>
	<TR VALIGN=TOP>
		<TD WIDTH=50%>
			<P ALIGN=LEFT><FONT FACE="Arial, sans-serif">'.$rows['address_line_1'].'</FONT></P>
		</TD>
		<TD WIDTH=50%>
			<P ALIGN=RIGHT><FONT FACE="Arial, sans-serif">Meter Id:
			'.$rows['meter_id'].'</FONT></P>
		</TD>
	</TR>
	<TR VALIGN=TOP>
		<TD WIDTH=50%>
			<P ALIGN=LEFT><FONT FACE="Arial, sans-serif">'.$rows['address_line_2'].'</FONT></P>
		</TD>
		<TD WIDTH=50%>
			<P ALIGN=RIGHT><FONT FACE="Arial, sans-serif">Email Id:'.$rows['email_id'].'</FONT></P>
		</TD>
	</TR>
	<TR VALIGN=TOP>
		<TD WIDTH=50%>
			<P ALIGN=LEFT><FONT FACE="Arial, sans-serif">'.$rows['city'].', '.$rows['state'].'</FONT></P>
		</TD>
		<TD WIDTH=50%>
			<P ALIGN=RIGHT><FONT FACE="Arial, sans-serif">Phone: '.$rows['phone_number'].'</FONT></P>
		</TD>
	</TR>
</TABLE>
<P ALIGN=LEFT><BR><BR>
</P>
<P ALIGN=LEFT><FONT FACE="Arial, sans-serif"><B>Bill Details</B></FONT></P>
<TABLE WIDTH=100% style="border:solid 2px #000000;" class="dataTable" CELLPADDING=4 CELLSPACING=0>
	<COL WIDTH=43*>
	<COL WIDTH=43*>
	<COL WIDTH=43*>
	<COL WIDTH=43*>
	<COL WIDTH=43*>
	<COL WIDTH=43*>
	<THEAD>
		<TR VALIGN=TOP>
			<TH WIDTH=17%>
				<P><FONT FACE="Arial, sans-serif">Start Date</FONT></P>
			</TH>
			<TH WIDTH=17%>
				<P><FONT FACE="Arial, sans-serif">End Date</FONT></P>
			</TH>
			<TH WIDTH=17%>
				<P><FONT FACE="Arial, sans-serif">Start Unit</FONT></P>
			</TH>
			<TH WIDTH=17%>
				<P><FONT FACE="Arial, sans-serif">End Unit</FONT></P>
			</TH>
			<TH WIDTH=17%>
				<P><FONT FACE="Arial, sans-serif">Total Consumption</FONT></P>
			</TH>
			<TH WIDTH=17%>
				<P><FONT FACE="Arial, sans-serif">Amount Due</FONT></P>
			</TH>
		</TR>
	</THEAD>
	<TBODY>
	<TR VALIGN=TOP>	
 			<TD WIDTH=17%>
				<P ALIGN=LEFT><FONT FACE="Arial, sans-serif">'.$time['start_time'].'</FONT></P>
			</TD>
			<TD WIDTH=17%>
				<P ALIGN=LEFT><FONT FACE="Arial, sans-serif">'.$time['end_time'].'</FONT></P>
			</TD>
			<TD WIDTH=17%>
				<P ALIGN=LEFT><FONT FACE="Arial, sans-serif">'.$usage_data['start_unit'].'</FONT></P>
			</TD>
			<TD WIDTH=17%>
				<P ALIGN=LEFT><FONT FACE="Arial, sans-serif">'.$usage_data['end_unit'].'</FONT></P>
			</TD>
			<TD WIDTH=17%>
				<P ALIGN=LEFT><FONT FACE="Arial, sans-serif">'.$usage_data['total_usage'].'</FONT></P>
			</TD>
			<TD WIDTH=17%>
				<P ALIGN=LEFT><FONT FACE="Arial, sans-serif">'.$rows['symbol'].' '.round($total_amount,2).'</FONT></P>
			</TD>
		</TR>
	</TBODY>
</TABLE>
<P ALIGN=LEFT><BR><BR>
</P>
<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<COL WIDTH=128*>
	<COL WIDTH=128*>
	<TR VALIGN=TOP>
		<TD WIDTH=50%>
			<P ALIGN=LEFT><BR><BR>
			</P>
			<P ALIGN=LEFT><FONT FACE="Arial, sans-serif"><B>This Month Charges</B></FONT></P>
		</TD>
		<TD WIDTH=50%>
			<P ALIGN=LEFT><BR>
			</P>
		</TD>
	</TR>
	<TR VALIGN=TOP>
		<TD WIDTH=50%>
			<P ALIGN=LEFT><BR><BR>
			</P>
			<TABLE WIDTH=332 BORDER=0 CELLPADDING=10 CELLSPACING=0>
				<COL WIDTH=168>
				<COL WIDTH=165>
				<TR VALIGN=TOP>
					<TD WIDTH=168 HEIGHT=25 BGCOLOR="#cccccc">
						<P ALIGN=LEFT><FONT FACE="Arial, sans-serif">Usage Charge</FONT></P>
					</TD>
					<TD WIDTH=165 BGCOLOR="#cccccc">
						<P ALIGN=LEFT><FONT FACE="Arial, sans-serif">'.$rows['symbol'].' '.$pre_tax_amt.'</FONT></P>
					</TD>
				</TR>
				<TR VALIGN=TOP>
					<TD WIDTH=168 HEIGHT=36 BGCOLOR="#cccccc">
						<P ALIGN=LEFT><FONT FACE="Arial, sans-serif">Tax @ '.$rows['rate_of_interest'].'%</FONT></P>
					</TD>
					<TD WIDTH=165 BGCOLOR="#cccccc">
						<P ALIGN=LEFT><FONT FACE="Arial, sans-serif">'.$rows['symbol'].' '.$tax_amt.'</FONT></P>
					</TD>
				</TR>
				<TR VALIGN=TOP>
					<TD WIDTH=168 HEIGHT=31 BGCOLOR="#cccccc">
						<P ALIGN=LEFT><FONT FACE="Arial, sans-serif"><B>Total</B></FONT></P>
					</TD>
					<TD WIDTH=165 BGCOLOR="#cccccc">
						<P ALIGN=LEFT><FONT FACE="Arial, sans-serif"><B>'.$rows['symbol'].' '.round($total_amount,2).'</B></FONT></P>
					</TD>
				</TR>
			</TABLE>
			<P ALIGN=LEFT><BR><BR>
			</P>
		</TD>
		<TD WIDTH=50% style="padding-left:20px;">
			<P ALIGN=LEFT>                  <FONT FACE="Arial, sans-serif"><B>Tariff
			Tier Information</B></FONT></P>
			<P ALIGN=LEFT><BR>
			</P>
			<TABLE WIDTH=100% style="border:solid 2px #000;" class="dataTable" CELLPADDING=4 CELLSPACING=0>
				<COL WIDTH=85*>
				<COL WIDTH=85*>
				<COL WIDTH=85*>
				<THEAD>
					<TR VALIGN=TOP>
						<TH WIDTH=33%>
							<P><FONT FACE="Arial, sans-serif">Tier Start</FONT></P>
						</TH>
						<TH WIDTH=33%>
							<P><FONT FACE="Arial, sans-serif">Tier End</FONT></P>
						</TH>
						<TH WIDTH=33%>
							<P><FONT FACE="Arial, sans-serif">Rate</FONT></P>
						</TH>
					</TR>
				</THEAD>
				<TBODY>';
					foreach($tiers as $val){
					$html.='<TR VALIGN=TOP>
						<TD WIDTH=33% SDVAL="0" SDNUM="1033;">
							<P ALIGN=CENTER><FONT FACE="Arial, sans-serif">'.$val['start_unit'].'</FONT></P>
						</TD>
						<TD WIDTH=33% SDVAL="300" SDNUM="1033;">
							<P ALIGN=CENTER><FONT FACE="Arial, sans-serif">'.$val['end_unit'].'</FONT></P>
						</TD>
						<TD WIDTH=33%>
							<P ALIGN=CENTER><FONT FACE="Arial, sans-serif">'.$rows['symbol'].' '.$val['rate_per_unit'].'</FONT></P>
						</TD>
					</TR>';
				}
				$html.='
				</TBODY>
			</TABLE>
			<P ALIGN=LEFT><BR>
			</P>
		</TD>
	</TR>
</TABLE>

<P ><SPAN STYLE="float:left; font-weight: normal">Service
TAX Number</SPAN><B>:'.$rows['tan'].'</B><SPAN STYLE="float:right; font-weight: normal">Reach
us at<B>: '.$rows['website'].'</B></SPAN></P>
<br/>
<p style="text-align:center; font-weight:bold;">Additional Notes</p>
<hr/>
<p>'.$rows['legalmsg'].'</p>
</BODY>
</HTML>
';

$mpdf=new mPDF('c','A4','','',15,20,15,15,16,13); 

$mpdf->SetDisplayMode('fullpage');
$mpdf->allow_charset_conversion=true;
$mpdf->WriteHTML($html,2);
//$mpdf->Output('my.pdf','F');
$months = array('Jan','Feb','Mar','Apr','May','Jun','July','Aug','Sep','Oct','Nov','Dec');
$month_name = $months[$month-1] ;

if (!file_exists($rows['Biller_id'])) {
	mkdir($rows['Biller_id']);
	if(!$file_exist[$month_name]){
		mkdir($rows['Biller_id'].'/'.$month_name);
	}
	chmod($rows['Biller_id'], 0777);
	chmod($rows['Biller_id'].'/'.$month_name, 0777);
}
$mpdf->Output($rows['Biller_id'].'/'.$month_name.'/'.$rows['meter_id'].'_'.$rows['first_name'].'.pdf','F');

$summaryHTML .= '
		<tr>
			<td>'.$rows['first_name'].' '.$rows['last_name'].'</td>
			<td>'.$rows['customer_id'].'</td>
			<td>'.$total_amount.'</td>
			<td>'.$usage_data['total_usage'].'</td>
		</tr>';	

}
//cho $summaryHTML;
$summaryHTML .= '</table>';
$mpfd = '';
$mpdf=new mPDF('c','A4','','',32,25,27,25,16,13); 

$mpdf->SetDisplayMode('fullpage');

$mpdf->WriteHTML($summaryHTML,2);
$mpdf->Output('summary.pdf','F');

?>