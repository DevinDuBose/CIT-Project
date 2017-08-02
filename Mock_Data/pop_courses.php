<?php

//include 'C:\wamp\www\CIT_Remote_Monitoring_App\App\Models\db.php';
include($_SERVER["DOCUMENT_ROOT"] . "/CIT_Remote_Monitoring_App/App/Models/db.php");
$lines = file("zsrsecl_cit.txt");
$db = Database::getDb();
foreach($lines as $line)
{
		
    $trim_line = preg_replace('/"\s+"/', '""', $line);
	echo $trim_line . "<br/>";
	$parts = explode('""', $trim_line);
	$output = array();
	foreach ($parts as $part)
	{
		$trim_part = preg_replace('/"/', '', $part);
		echo $trim_part . "<br />";
			
		if ( $trim_part != "")
		{
			array_push($output, $trim_part);			
		}
		
	}

	$courseNum = $output[2] . " " . $output[3];
	$courseName = $output[4];
	$leadInstructor = $output[5];
	
	
	
	
	$query1 = 'SELECT CourseNumber FROM Course WHERE CourseNumber = :coursenum';
	$statement = $db->prepare($query1);
	$statement->bindValue(':coursenum', $courseNum);
	$statement->execute();
	$rows = $statement->fetchAll();
	$statement->closeCursor();
	
	$query2 = 'SELECT UserID FROM AppUser WHERE LNumber = :leadinstructor';
	$statement = $db->prepare($query2);
	$statement->bindValue(':leadinstructor', $leadInstructor);
	$statement->execute();
	$rows2 = $statement->fetch();
	$statement->closeCursor();
	$userID = $rows2[0];
	echo $userID . "<br/>";
	
	
	echo count($rows) . "<br/>";
	
	if (count($rows) == 0)
	{
	
		$query3 = 'INSERT INTO COURSE(CourseName, CourseNumber, LeadInstructorId)
							 VALUES (:coursename, :coursenum, :leadinstructor)';
	
		$statement = $db->prepare($query3);
		$statement->bindValue(':coursenum', $courseNum);
		$statement->bindValue(':coursename', $courseName);
		$statement->bindValue(':leadinstructor', $userID);
		$statement->execute();
		$statement->closeCursor();
	}
	/*
	else
	{
		if (strpos($email, 'lanecc.edu'))
		{
			$query3 = 'UPDATE appuser SET EmailAddress = :email WHERE LNumber = :lnum';
			$statement = $db->prepare($query3);
			$statement->bindValue(':lnum', $lnum);
			$statement->bindValue(':email', $email);
			$statement->execute();
			$statement->closeCursor();
			
		}
	}
		
	*/		
	
}
?>