<template>
	<v-card>
		<v-card-title class="light-blue white--text"> About</v-card-title>

		<v-card-text class="pa-2">
			<h4>Alazhar Dental (Cairo, Boys) web site </h4>
			<ul>
				<li><span> Release date</span> 15/11/2018 </li>
				<li> <span>Planner: </span> Prof.Dr.Tamer Hamza </li>
				<li> <span>Developer :</span> Dr.Abd ElRazek Mahmoud </li>
				<li> <span>Invironment :</span> PHP - JS </li>
				<li> <span> Back-End : </span> laravel framwork 8</li>
				<li> <span> Front-End : </span> vue js framwork 2.6 - vuetify 2.1</li>
				<li> <span> Database :</span>MYSQL</li>
				<hr>
				<!-- <li style="color:blue">Next upgrade will be include:
                    <ol>
                        <li>Protocol generator </li>
                        <li>Reference builder</li>
                        <li>Format checker</li>
                        <li>Support center</li>
                        <li>Publish exams results</li>
                        <li>Requirements acceptance </li>
                    </ol>
                </li> -->
			</ul>
			<hr>
		</v-card-text>
		<v-card-actions>
			For any support please contact 01226461370 - abumalek.fayed@gmail.com
		</v-card-actions>
	</v-card>
</template>

<script>
	export default {};
</script>

<style scoped>
	li span {
		float: left;
		width: 100px;
		font-weight: bold;
		color: rgb(41, 40, 40);
	}
	li {
		margin-top: 10px;
		font-weight: bold;
		color: green;
	}
	ol li {
		color: red;
	}
</style>
