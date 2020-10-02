	ALTER TABLE privilege ADD uuid CHAR(36) NOT NULL FIRST; 
	ALTER TABLE privilege ADD is_default BOOLEAN NOT NULL AFTER privilege; 
	
	UPDATE privilege SET uuid = '231C64FA-24F4-CDA4-60FE-B211A364D5AE' WHERE privilege.privilege = 'CHANGEPASSWORD'; 
	UPDATE privilege SET uuid = '6B296269-6280-EAC5-B5F3-4A95C3FA7656' WHERE privilege.privilege = 'ENGINEHYDRANTMANANGER'; 
	UPDATE privilege SET uuid = '9941EE1E-6E61-0656-E72B-18A4EE48633C' WHERE privilege.privilege = 'EVENTADMIN'; 
	UPDATE privilege SET uuid = '26F7145B-826A-F731-4F59-E435B2E94F81' WHERE privilege.privilege = 'EVENTMANAGER'; 
	UPDATE privilege SET uuid = 'C4E19AFC-14CA-9714-B0E6-B1354EC0571C' WHERE privilege.privilege = 'EVENTPARTICIPENT'; 
	UPDATE privilege SET uuid = '10590E6B-FC09-49B3-6A35-53759D10D1FC' WHERE privilege.privilege = 'FFADMINISTRATION'; 
	UPDATE privilege SET uuid = '5873791F-68EC-159D-EF91-3288F02EF1D2' WHERE privilege.privilege = 'FILEADMIN'; 
	UPDATE privilege SET uuid = '2B3DE880-1EB7-C9A1-C533-BD90F773FDBA' WHERE privilege.privilege = 'HYDRANTADMINISTRATOR'; 
	UPDATE privilege SET uuid = 'EE50BFB0-B4B0-2AE2-AAE4-2FB6EE9DA558' WHERE privilege.privilege = 'PORTALADMIN'; 
	
	UPDATE privilege SET is_default = '1' WHERE privilege.privilege = 'EVENTPARTICIPENT'; 
	UPDATE privilege SET is_default = '1' WHERE privilege.privilege = 'CHANGEPASSWORD';
	
	ALTER TABLE privilege ADD UNIQUE(privilege); 
	ALTER TABLE privilege ADD UNIQUE(uuid);
	
	RENAME TABLE user_privilege TO user_privilege2;
	
	CREATE TABLE user_privilege (
		privilege CHARACTER(36) NOT NULL,
		user CHARACTER(36) NOT NULL,
      PRIMARY KEY (privilege, user),
		FOREIGN KEY (privilege) REFERENCES privilege(uuid),
		FOREIGN KEY (user) REFERENCES user(uuid) );
   	
	INSERT INTO user_privilege (user_privilege.privilege, user_privilege.user)
		SELECT privilege.uuid, user_privilege2.user 
		FROM user_privilege2, privilege
		WHERE privilege.privilege = user_privilege2.privilege;
		
	DROP TABLE user_privilege2;
	
	ALTER TABLE privilege DROP PRIMARY KEY, ADD PRIMARY KEY(uuid);