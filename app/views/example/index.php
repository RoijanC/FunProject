<html>
<head>
	<title>This is an example view</title>
</head>
<body>
This is an example.... I want to see some data that has been passed in.
<br>
<br>


<table>
<tr>
<th>
firstName
</th>
<th>
lastName
</th>
<th>
email
</th>
<th>
phone
</th>
<th>
country
</th>
<th>
action
</th>
</tr>
<?php
foreach($data['clients'] as $client){
	echo "<tr><td>$client->firstName</td>";
	echo "<td>$client->lastName</td>";
	echo "<td>$client->email</td>";
	echo "<td>$client->phone</td>";
	echo "<td>$client->country</td>";
	echo "<td><a href='/example/delete/$client->ID'>DELETE!!!!!</a></td></tr>";
}
?>
</table>
<form method="post" action="/example/newClient">
firstName<input type="text" name="firstName" /><br />
lastName<input type="text" name="lastName" /><br />
email<input type="text" name="email" /><br />
phone<input type="text" name="phone" /><br />
country<input type="text" name="country" /><br />
<input type="submit" name="action" 'Save this record' />
</form>



</body>
</html>