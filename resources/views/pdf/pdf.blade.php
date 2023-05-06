<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Client Invoice</title>

    <style>
    .clearfix:after {
	  content: "";
	  display: table;
	  clear: both;
	}

	a {
	  color: #5D6975;
	  text-decoration: underline;
	}

	body {
	  position: relative;
	  width: 21cm;
	  height: 29.7cm;
	  margin: 0 auto;
	  color: #001028;
	  background: #FFFFFF;
	  font-family: Arial, sans-serif;
	  font-size: 12px;
	  font-family: Arial;
	}

	header {
	  padding: 10px 0;
	  margin-bottom: 30px;
	}

	#logo {
		width: 90%;
		text-align: center;
		margin-bottom: 10px;
	}

	#logo img {
		width: 600px;
	}

	h1 {
	  border-top: 1px solid  #5D6975;
	  border-bottom: 1px solid  #5D6975;
	  color: #5D6975;
	  font-size: 2.4em;
	  line-height: 1.4em;
	  font-weight: normal;
	  text-align: center;
	  margin: 0 0 20px 0;
	  background: url(dimension.png);
	}

	#project {
	  float: left;
	}

	#project span {
	  color: #5D6975;
	  text-align: right;
	  width: 52px;
	  margin-right: 10px;
	  display: inline-block;
	  font-size: 0.8em;
	}

	#company {
	  float: right;
	  text-align: right;
	  /* border: 1px solid black; */
	}

	#project div,
	#company div {
	  white-space: nowrap;
	}

	table {
	  width: 90%;
	  border-collapse: collapse;
	  border-spacing: 0;
	  margin-bottom: 20px;
	}

	table tr:nth-child(2n-1) td {
	  background: #F5F5F5;
	}

	table th,
	table td {
	  text-align: center;
	}

	table th {
	  padding: 5px 20px;
	  color: #5D6975;
	  border-bottom: 1px solid #C1CED9;
	  white-space: nowrap;
	  font-weight: normal;
	}

	table .service,
	table .desc {
	  text-align: left;
	}

	table td {
	  padding: 20px;
	  text-align: right;
	}

	table td.service,
	table td.desc {
		vertical-align: top;
	}

	table td.unit, table td.qty, table td.total {
		font-size: 1.2em;
	}

	table td.grand {
		border-top: 1px solid #5D6975;;
	}

	#notices .notice {
		color: #5D6975;
		font-size: 1.2em;
	}

	footer {
		color: #5D6975;
		width: 100%;
		height: 30px;
		position: absolute;
		bottom: 0;
		border-top: 1px solid #C1CED9;
		padding: 8px 0;
		text-align: center;
	}
</style>

</head>
	<body>
		<header class="clearfix">
			
			<div id="logo">
				<img src="{{ asset($image) }}">
			</div>

			<div style="width: 90%;">
				<h2>INVOICE#: {{$invoiceNumber}}</h2>
			</div>

			<div style="width: 90%;">
				<div id="company" class="clearfix">
					<div>{{$user->companyname}}</div>
					<div>{{$user->Name}}  {{$user->Surname}}</div>
					<div>{{$user->address}}</div>
					<div>{{$user->Mobile}}</div>
					<div><a href="mailto:{{$user->Email}}">{{$user->Email}}</a></div>
					<div>{{$user->vat}}</div>
				</div>

				<div>
					<div id="project">
						<div><span>COMPANY</span> {{$client->companyname}}</div>
						<div><span>Name</span> {{$client->name}} {{$client->surname}}</div>
						<div><span>ADDRESS</span> {{$client->address}}</div>
						<div><span>EMAIL</span> <a href="mailto:{{$client->email}}">{{$client->email}}</a></div>
						<div><span>MOBILE</span> {{ $client->mobile }}</div>
						<div><span>DATE</span> {{ $date }}</div>
					</div>
				</div>
			</div>
		</header>
		<main>
			<table>
				<thead>
					<tr>
						<th class="service" colspan="2" style="width: 60%;">DESCRIPTION</th>
						<th style="width: 15%;">PRICE</th>
						<th style="width: 5%;">QTY</th>
						<th style="width: 20%;">TOTAL</th>
					</tr>
				</thead>
				<tbody>

					@foreach ($invoicelines as $item)

						<tr>
							<td class="service" colspan="2">{{ $item->product_name }}</td>
							<td class="unit">{{ $currency }} {{ $item->unitprice }}</td>
							<td class="qty">{{ $item->quantity }}</td>
							<td class="total">{{ $currency }} {{ $item->linetotal }}</td>
						</tr>

					@endforeach

					<tr>
						<td colspan="4" class="grand total">GRAND TOTAL</td>
						<td class="grand total">{{ $currency }} {{ $invoiceTotal }}</td>
					</tr>
				</tbody>
			</table>
			<div id="notices">
				<div>NOTICE:</div>
				<div class="notice">Banking details:</div>
				<div class="notice">{{$user->BankingDetails}}</div>

			</div>
		</main>
		<footer>
			Invoice was created on the Datanav NavBilling and is valid without the signature and seal.
		</footer>
	</body>
</html>