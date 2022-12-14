<?php

# Database connections and variables $conn
include("../Database.php");
$executionStartTime = microtime(true);
$data = [];

# If there's an error connecting the Database
if (mysqli_connect_errno()) {
	$output['status']['code'] = "300";
	$output['status']['name'] = "failure";
	$output['status']['description'] = "Database unnavailable";
	$output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
	$output['data'] = [];
	mysqli_close($conn);
	echo json_encode($output);
	exit;
}

# Query to get all the personnels
$query = 'SELECT 
	p.id, 
	p.lastName, 
	p.firstName, 
	p.jobTitle, 
	p.email, 
	p.img, 
	p.revenue, 
	p.annualSalary, 
	d.name as department, 
	l.name as location 
FROM 
	personnel p 
		LEFT JOIN 
	department d ON (d.id = p.departmentID) 
		LEFT JOIN 
	location l ON (l.id = d.locationID) 
ORDER BY p.lastName, p.firstName, d.name, l.name';
$result = $conn->query($query);

# If there was a problem reading the query
if (!$result) {
	$output['status']['code'] = "400";
	$output['status']['name'] = "executed";
	$output['status']['description'] = "query failed";	
	$output['data'] = [];
	mysqli_close($conn);
	echo json_encode($output); 
	exit;
}

# If there was not a problem reading the query
while ($row = mysqli_fetch_assoc($result)) {
	array_push($data, $row);
}
$output['status']['code'] = "200";
$output['status']['name'] = "ok";
$output['status']['description'] = "success";
$output['status']['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";
$output['data'] = $data;
mysqli_close($conn);
echo json_encode($output);