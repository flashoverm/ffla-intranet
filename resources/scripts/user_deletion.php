<?php

require_once realpath ( dirname ( __FILE__ ) . "/../bootstrap.php" );

//TODO implement 

$email = $argv[1];

$user = get_user_by_email($email);

echo "Name:   \t" . $user->firstname . " " . $user->lastname . "\n";
echo "E-Mail: \t" . $user->email . "\n";
echo "\n";

//logbook entries
	
//get events (staff)

//get reports (creator)



//confirmations
//delete

//get privileges of user
//delete
