<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Simple invoice html template</title>
</head>
<body>

<style>
	@import "https://fonts.googleapis.com/css?family=Open+Sans:400,400i,600,600i,700";
	html,body,div,span,applet,object,iframe,h1,h2,h3,h4,h5,h6,p,blockquote,pre,a,abbr,acronym,address,big,cite,code,del,dfn,em,img,ins,kbd,q,s,samp,small,strike,strong,sub,sup,tt,var,b,u,i,center,dl,dt,dd,ol,ul,li,fieldset,form,label,legend,table,caption,tbody,tfoot,thead,tr,th,td,article,aside,canvas,details,embed,figure,figcaption,footer,header,hgroup,menu,nav,output,ruby,section,total,time,mark,audio,video{margin:0;padding:0;border:0;font-size:100%;font:inherit;vertical-align:baseline}article,aside,details,figcaption,figure,footer,header,hgroup,menu,nav,section{display:block}body{line-height:1}ol,ul{list-style:none}blockquote,q{quotes:none}blockquote:before,blockquote:after,q:before,q:after{content:'';content:none}table{border-collapse:collapse;border-spacing:0}body{height:840px;width:592px;margin:auto;font-family:'Open Sans',sans-serif;font-size:12px}strong{font-weight:700}#container{position:relative;padding:4%}#header{height:80px}#header > #reference{float:right;text-align:right}#header > #reference h3{margin:0}#header > #reference h4{margin:0;font-size:85%;font-weight:600}#header > #reference p{margin:0;margin-top:2%;font-size:85%}#header > #logo{width:50%;float:left}#fromto{height:160px}#fromto > #from,#fromto > #to{width:45%;min-height:90px;margin-top:30px;font-size:85%;padding:1.5%;line-height:120%}#fromto > #from{float:left;width:45%;background:#efefef;margin-top:30px;font-size:85%;padding:1.5%}#fromto > #to{float:right;border:solid grey 1px}#items{margin-top:10px}#items > p{font-weight:700;text-align:right;margin-bottom:1%;font-size:65%}#items > table{width:100%;font-size:85%;border:solid grey 1px}#items > table th:first-child{text-align:left}#items > table th{font-weight:400;padding:1px 4px}#items > table td{padding:1px 4px}#items > table th:nth-child(2),#items > table th:nth-child(4){width:45px}#items > table th:nth-child(3){width:60px}#items > table th:nth-child(5){width:80px}#items > table tr td:not(:first-child){text-align:right;padding-right:1%}#items table td{border-right:solid grey 1px}#items table tr td{padding-top:3px;padding-bottom:3px;height:10px}#items table tr:nth-child(1){border:solid grey 1px}#items table tr th{border-right:solid grey 1px;padding:3px}#items table tr:nth-child(2) > td{padding-top:8px}#summary{height:170px;margin-top:30px}#summary #note{float:left}#summary #note h4{font-size:10px;font-weight:600;font-style:italic;margin-bottom:4px}#summary #note p{font-size:10px;font-style:italic}#summary #total table{font-size:85%;width:260px;float:right}#summary #total table td{padding:3px 4px}#summary #total table tr td:last-child{text-align:right}#summary #total table tr:nth-child(3){background:#efefef;font-weight:600}#footer{margin:auto;position:absolute;left:4%;bottom:4%;right:4%;border-top:solid grey 1px}#footer p{margin-top:1%;font-size:65%;line-height:140%;text-align:center}

    /* Add background color to #to div */
    #to {
        background-color: #F5D9C4;  /* This is the color you requested */
        padding: 10px;
    }

    /* Increase font size in #reference div */
    #reference h2 {
        font-size: 24px;  /* Increase font size for the heading */
    }

    #reference h3 {
        font-size: 18px;  /* Increase font size for the subheading */
    }

    /* Set background color for the table header */
    #items table th {
        background-color: #F5D9C4;
        font-weight: bold;
        padding: 10px;
    }

    #items table th:nth-child(1), #items table td:nth-child(1) {
        width: 5%;  /* Lebar kolom NO lebih sempit */
    }
    
    #items table th:nth-child(2), #items table td:nth-child(2) {
        width: 40%;  /* Lebar kolom DESCRIPTION lebih lebar */
    }

    #items table th:nth-child(3), #items table td:nth-child(3) {
        width: 15%;  /* Lebar kolom UNIT */
    }

    #items table th:nth-child(4), #items table td:nth-child(4) {
        width: 20%;  /* Lebar kolom PRICE */
    }

    #items table th:nth-child(5), #items table td:nth-child(5) {
        width: 20%;  /* Lebar kolom AMOUNT */
    }

</style>

<div id="container">
	<div id="header">
		<div id="logo">
			<img src="{{ public_path('storage/dwiprintingsize.png') }}" alt="Logo">
		</div>
		<div id="reference">
			<h2><strong>INVOICE</strong></h2>
			<h3>Date : {{ $formattedDate }}</h3>
			@if ($invoice->no_invoice_update != null)
				<h3>No Invoice : {{ $invoice->no_invoice_update }}</h3>
			@else
				<h3>No Invoice : {{ $invoice->no_invoice }}</h3>
			@endif
		</div>
	</div>


	<div id="fromto">
		<div id="from">
			<p>
				<strong>DWI PRINTING</strong><br>
				JL Ir. H. Juanda No.26,  Nagasari, <br>
                Kec. Karawang Barat, Karawang, Jawa Barat <br><br>
				Telephone.:  08957-7094-3200 <br>
				Email:  dwiprinting2016@gmail.com 
			</p>
		</div>
		<div id="to">
			<p>
				<strong>CUSTOMER</strong><br><br>
				{{ $invoice->customer_name }}
			</p>
		</div>
	</div>

	<div id="items">
		<p>Daftar Barang</p>
		<table>
			<tr>
				<th>NO</th>
				<th>DESCRIPTION</th>
				<th>UNIT</th>
				<th>PRICE</th>
				<th>AMOUNT</th>
			</tr>
            @foreach ($items as $barang)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $barang['list_item'] }}</td>
                    <td>{{ $barang['unit'] }}</td>
                    <td>{{ $barang['price'] }}</td>
                    <td>{{ $barang['amount'] }}</td>
                </tr>
            @endforeach
			
			
		</table>
	</div>

	<div id="summary">
		<div id="note">
			<h4>Note :</h4>
            @if ($invoice->status == 'FULL PAYMENT')
                <p>Terima kasih atas pembayaran Anda. <br> Invoice ini sudah lunas.</p>
            @else
                <p>Terima kasih atas pembayaran Anda. <br> Invoice ini belum lunas. Silahkan hubungi nomor yang tertera</p>
            @endif
			
		</div>
		<div id="total">
			<table border="1">
				<tr>
					<td>SUBTOTAL</td>
					<td>Rp. {{ $subtotal }}</td>
				</tr>
				<tr>
					<td>DP</td>
					<td>Rp. {{ $down_payment }} </td>
				</tr>
                @if ($invoice->status == 'DOWN PAYMENT')
                    <tr>
                        <td>TOTAL DUE</td>
                        <td>Rp. {{ $totalDue }}</td>
                    </tr>
                @else 
                <tr>
                    <td> </td>
                    <td>FULL PAYMENT</td>
                </tr>
                @endif
				
			</table>
		</div>
	</div>

	<div id="footer">
		<p>DWI PRINTING  - Alamat: Jl. Ir. H. Juanda No. 26, Nagasari, Kec.                                                                                  
            Karawang Bar., Karawang, Jawa Barat <br> - No. HP: 08957-7094-3200 <br>
			Email -  dwiprinting2016@gmail.com </p>
	</div>
</div>

</body>
</html>
