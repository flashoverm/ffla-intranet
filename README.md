# intranet

Trello Board for collaboration with feature backlog and bug-tracking:

https://trello.com/b/JHfXwwFF/intranet-feuerwehr-landshut


## Branches

	master 		->	goes in production (via jenkins)

	test    	->	goes on test (via jenkins)

	feature-xyz	->	feature xyz is developed

	bugfix-xyz	->	bugfix xyz is developed

## Rules

Smaller (single commit) features and bugfixes can be fixed directly on the test branch

Bigger features and bugfixes are developed in their on branch

### Release/Merging

feature- and bugfix-branches are merged into test

test is merged into master with a new version number
