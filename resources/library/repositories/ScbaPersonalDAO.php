<?php

require_once "BaseDAO.php";

class ScbaPersonalDao extends BaseDAO {
    
    public function __construct(PDO $pdo){
        parent::__construct($pdo, "scbaPersonal");
    }
    
    public function save(ScbaPersonal $scbaPersonal){
        $saved = null;
        if($this->uuidExists($scbaPersonal->getUuid(), $this->tableName)){
            $saved = $this->updateScbaPersonal($scbaPersonal);
        } else {
            $saved = $this->insertScbaPersonal($scbaPersonal);
        }
        if($saved != null){
            return $saved;
        }
        return false;
    }
    
    protected function insertScbaPersonal(ScbaPersonal $scbaPersonal){
        $uuid = $this->generateUuid();
        $scbaPersonal->setUuid($uuid);
        
        $statement = $this->db->prepare("INSERT INTO scbaPersonal
		(uuid, date, start_time, end_time, type, type_other, title, comment, engine, creator, published, staff_confirmation, canceled_by, hash)
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL, ?)");
        
        $result = $statement->execute(array($uuid, $event->getDate(), $event->getStartTime(),
            $event->getEndTime(), $event->getType()->getUuid(), $event->getTypeOther(),
            $event->getTitle(), $event->getComment(), $event->getEngine()->getUuid(),
            $event->getCreator()->getUuid(), $event->getPublished(),
            $event->getStaffConfirmation(), $hash
        ));
        
        if ($result) {
            foreach($event->getStaff() as $staff){
                if( $staff->getEventUuid() == NULL ){
                    $staff->setEventUuid($event->getUuid());
                }
                if( ! $this->staffDAO->save($staff)){
                    return false;
                }
            }
            return $this->getEvent($uuid);
        }
        return false;
        
    }
        
    
}