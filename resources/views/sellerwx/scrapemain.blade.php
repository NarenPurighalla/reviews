<!DOCTYPE html>
<html>
	<head>
	<title>Scraper</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:700' rel='stylesheet' type='text/css'>

		<script type = "text/javascript" src = 'js/date.js'></script>

		<!--bootstrap-->
		<link rel="stylesheet" type="text/css" href="{{ URL::asset('bootstrap/css/bootstrap.min.css') }}">
		<script type="text/javascript" href="{{ URL::asset('bootstrap/js/bootstrap.min.js') }}"></script>

	  	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	  	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	  	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	  	<link rel="stylesheet" href="/resources/demos/style.css">
  	
		<script type="text/javascript">
		Date.toAPIDateString = function(){
		    var date = new Date(this);
		    var timeZoneOffset = date.getTimezoneOffset();
		    date = date.addMins(timeZoneOffset);

		    var day  = date.getDate();
		    var month = date.getMonth()+1;
		    var year = date.getFullYear();

		    var hours  = date.getHours();
		    var mins = date.getMinutes();
		    var seconds = date.getSeconds();

		    if(month < 10)
		        month = '0' + month;
		        
		    if(day < 10)
		        day = '0' + day;
		        
		    var str = year + '-' + month + '-' + day;
		    
		    return str;
		}
			$(document).ready(function() {
				var API_URL = 'scrape/';

				$('#btn').on('click', function() {
					
					var valueOfDate = ($('#datepicker1').val());
					var valueOfDate1 = ($('#datepicker2').val());

					var temp = new Date(valueOfDate);
					var fromDate = temp.toAPIDateString();

					temp = new Date(valueOfDate1);
					var toDate = temp.toAPIDateString();


					var marketplace = $('#marketplace').val();
					var sellerID = $('#sellerID').val();

					if(marketplace === 'Snapdeal') {
						API_URL += 'amz' + '/' + sellerID + '?fromDate=' + fromDate + '&toDate=' + toDate;
					}
					else
					{
						var sellerDetails = sellerID.substr(31);
						var sellername = sellerDetails.split("/");
						var page = sellername[1].split("=");
						var sellerId = page[0].split("?");
						// console.log(sellerId);

						API_URL += 'fk'+ '/' + sellername[0] + '/' + sellerId[0] + '/' + page[1]  + '?fromDate=' + fromDate + '&toDate=' + toDate; 
					}

					var xhr = $.get(API_URL);
					xhr.always(function () {
						API_URL = 'scrape/';
					});

					xhr.done(function(data){
						var output = JSON.stringify(data, null, 4);
						$("#asm").text(output);
						$("#asm").show();
					});

					xhr.fail(function(data){
						alert('failed');
					});
				});
			});
		</script>

		<script type="text/javascript">
	  		$(function() {

	    		$( "#datepicker1" ).datepicker({dateFormat: 'yy-mm-dd'});
	    		$( "#datepicker2" ).datepicker({dateFormat: 'yy-mm-dd'});
				
  			});
  		</script>

	</head>
	<body>
		<div>
			<h1 style="font-family: 'Source Sans Pro';text-align:center;margin-bottom:100px;"></h1>
		</div>
		<div id="scraper">
			<span style="font-family: 'Source Sans Pro', sans-serif;margin-left:300px;font-size:1.2em">MARKETPLACE:</span>
				<select name="marketplace" id="marketplace" style="margin-left:20px;background-color:#ffffff;width:220px;height:30px; ">
					<option value="Amazon">Amazon</option>
					<option value="Flipkart">Flipkart</option>
					<option value="Paytm">Paytm</option>
					<option value="Snapdeal">Snapdeal</option>
				</select><br>
			<div style="margin-top:25px;">
				<span style="font-family: 'Source Sans Pro', sans-serif;margin-left:300px;font-size:1.2em;text-align:justify;">ID/URL:</span>
					<input type="text" id="sellerID" style="margin-left: 85px;" />
					<br /><br />
				<p style="font-family: 'Source Sans Pro', sans-serif;margin-left:300px;font-size:1.2em;text-align:justify;"> From: <input style="margin-left: 98px;" type="text" id="datepicker1"></p>
				<p style="font-family: 'Source Sans Pro', sans-serif;margin-left:300px;font-size:1.2em;text-align:justify;"> To: <input style="margin-left: 121px;" type="text" id="datepicker2"></p>
			</div>
				<button id='btn' class="btn btn-success" style="margin-left:440px">Submit</button>
		</div>
		<pre id="asm" class="hide" style="margin-top:30px;"></pre>
	</body>
</html>