<?php 
require_once realpath ( dirname ( __FILE__ ) . "/../bootstrap.php" );

$duplicates = $userDAO->getDuplicateUsersEmail();

foreach ($duplicates as $dublicate){
	
	$users = $userDAO->getUserByEmail($dublicate['email']);
	
	echo "Found duplicate: " . $dublicate['email'] . "\n";
	
	$duplicateUuid = $users[0]->getUuid();
	
	$events = $eventDAO->getEventsWithCreator($duplicateUuid);
	foreach($events as $event){
		$event->setCreator($users[1]);
		$eventDAO->save($event);
		echo "Updated event " . $event->getUuid() . "\n";
	}
	
	$staff = $staffDAO->getStaffByUser($duplicateUuid);
	foreach($staff as $entry){
		$entry->setUser($users[1]);
		$staffDAO->save($entry);
		echo "Updated staff " . $entry->getUuid() . " - Event: " . $entry->getEvent()->getUuid() . "\n";
	}
	
	//datachangerequest
	
	//confirmation
		
	//logbook
	
	foreach($users[0]->getPrivileges() as $privilege){
		if($users[1]->addPrivilege($privilege)){
			echo "Added privilege " . $privilege->getPrivilege() . "\n";
		}
	}
	
	$userDAO->deleteUser($duplicateUuid);
}

