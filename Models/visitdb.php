<?php
class VisitDB {

public static function CreateVisit($VISIT) {
    $db = Database::getDB();

    $userID = $VISIT->getUserID();
    $locationID = $VISIT->getLocationID();
    $startTime = date("Y-m-d h:i:s");

    $query = 'INSERT INTO visit
              (UserID, LocationId, StartTime)
              VALUES
              ( :userid, :locationid,:starttime)';

    $statement = $db->prepare($query);
    $statement->bindValue(':userid', $userID);
    $statement->bindValue(':locationid', $locationID);
    $statement->bindValue(':starttime', $startTime);
    $statement->execute();
    $statement->closeCursor();
}

public static function RetrieveVisit($VISIT) {
      $db = Database::getDB();

      $query = 'SELECT *
			          FROM visit
                WHERE visit.VisitId = :visitid';

      $statement = $db->prepare($query);
      $statement->bindValue(":visitid", $VISIT->getVisitID());
      $statement->execute();
      $row = $statement->fetch();
      $statement->closeCursor();

      if ($row != false) {
          $visit = new Visit(
      							   $row['UserID'],
      							   $row['LocationId'],
                       $row['VisitId'],
                       $row['StartTime'],
                       $row['EndTime'] );
		     return $visit;
	   } else
		   return null;
}

public static function UpdateVisit($VISIT) {
  $db = Database::getDB();

  $query = 'UPDATE visit
            SET LocationId = :locationid, EndTime = :endtime
		        WHERE visit.VisitId = :visitid';

  $statement = $db->prepare($query);
  $statement->bindValue(":locationid", $VISIT->getLocationID());
	$statement->bindValue(":endtime", $VISIT->getEndTime());
	$statement->bindValue(":visitid", $VISIT->getVisitID());
  $statement->execute();
  $statement->closeCursor();
}

public static function DeleteVisit($VISIT) {
    $db = Database::getDB();

    $query = 'DELETE FROM visit
		          WHERE visit.VisitId = :visitid';

    $statement = $db->prepare($query);
  	$statement->bindValue(":visitid", $VISIT->getVisitID());
    $statement->execute();
    $statement->closeCursor();
  }
}