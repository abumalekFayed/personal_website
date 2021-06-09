<template>

	<div id="print_report_1" class="_invoice">

		<table style="border:none !important;">
			<thead>
				<tr>
					<td style="border:none !important;">
						<div class="header-space"> </div>
					</td>
				</tr>
			</thead>

			<tbody>
				<tr>
					<td style="border:none !important;">
						<div class="content">
							<div dir="rtl" class="custom-page-start">
								<p>{{$moment().format('DD-MM-YYYY')}}</p>
								<p class="print-title">{{title}} </p>
								<slot></slot>
							</div>
						</div>
					</td>
				</tr>
			</tbody>

			<tfoot>
				<tr>
					<td style="border:none !important;">
						<div class="footer-space">&nbsp;</div>
					</td>
				</tr>
			</tfoot>
		</table>

		<div class="header" dir="rtl">
			<!-- <div class="__head_col1">
				<h2 class="__title">{{title}} </h2>
				<h3>التاريخ</h3>
				<p>{{$moment().format('DD-MM-YYYY')}}</p>
			</div>

			<div class="__head_col2">
				<img src="storage/imgs/logo.png" height="150px" width="170px" alt="">

			</div>

			<div class="__head_col1">
				<h3>مصنع الخرسانة الحديث</h3>
				<p>ر/ض 123456675</p>

			</div> -->
		</div>

		<div class="footer" dir="rtl">
			<!-- <p> info@concret.com - جوال ‎+966 17 222 5900 طريق الملك فهد
			</p> -->

		</div>

		<iframe name="print_frame1" width="0" height="0" frameborder="0" src="about:blank" style="background-color:grey;color:red"></iframe>

	</div>
</template>

<script>
export default {
	props: ["title", 'type'],

	computed: {},
	methods: {
		print () {
			let link = document.createElement("link");
			link.href = "/css/print.css"; /**** your CSS file ****/
			link.rel = "stylesheet";
			link.type = "text/css";

			var data = document.getElementById("print_report_1").innerHTML;
			window.frames["print_frame1"].document.title = document.title;
			window.frames["print_frame1"].document.head.appendChild(link);
			setTimeout(() => {
				window.frames["print_frame1"].document.body.innerHTML = data;
				window.frames["print_frame1"].window.focus();
				window.frames["print_frame1"].window.print();
			}, 250);
		},
	},
};
</script>

<style lang="scss" scoped>
._invoice {
	direction: rtl;
	padding: 10px;
	border: solid 1px grey;
	margin: 10px;

	/* background-image: url("storage/imgs/invoice_back.PNG") no-repeat center center fixed;
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover; */
}

.header,
.header-space,
.footer,
.footer-space {
	height: 250px;
}

.header {
	position: fixed;
	top: 0;
}

.footer {
	position: fixed;
	bottom: 0;
}

@page {
	margin: 4mm;
}

@media print {
	.content-block,
	p {
		page-break-before: avoid;
	}

	.header {
		width: 100%;
		position: fixed;
		top: 0;
		display: flex;
		margin: 10px;
		align-items: center;
		justify-content: center;
	}

	.footer {
		width: 100%;
		position: fixed;
		bottom: 0;
		display: flex;
		margin: 10px;
		align-items: center;
		justify-content: center;
	}

	.header,
	.header-space {
		height: 170px;
	}

	.footer,
	.footer-space {
		height: 70px;
	}
}

._invoiceinfo {
	float: left;
}

.__img {
	position: absolute;
	width: 100%;
}

._ptest22 {
	font-size: 46px;
	background-color: rgb(29, 185, 15);
	padding: 20px;
	border: solid 2px green;
}

table,
td,
th {
	border: 1px solid #ddd;
	text-align: left;
}

table {
	border-collapse: collapse;
	width: 100%;
}

th,
td {
	padding: 10px;
}

#table1 th {
	text-align: center;
	background-color: rgb(230, 230, 230);
}

#table2 th {
	text-align: center;
	background-color: rgb(221, 221, 221);
}

#table2 td {
	min-width: 60px;
}

td {
	text-align: center;
}

.__head {
	display: flex;
	margin: 10px;
	align-items: center;
	justify-content: center;
}

.__head_col2 {
	flex: 1;
	margin: 5px;
	text-align: center;
}

.__head_col1 {
	flex: 2;
	margin: 5px;
	text-align: center;
}

.__title {
	font-size: 39px;
}

.__footer {
	background-color: rgb(126, 0, 63);
	text-align: center;
	font-weight: bold;
	color: rgb(255, 255, 255);
	padding: 15px;
}
</style>
