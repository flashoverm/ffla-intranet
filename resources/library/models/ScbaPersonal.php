<?php

require_once  "User.php";

class ScbaPersonal {
    
    protected ?string $uuid;
    
    protected ?User $user;
    
    protected bool $active;
  
    protected ?string $transponderID;
    
    protected $dateOfBirth;
    
    protected bool $csaTrained;
    
    //last atemschutzstrecke
    //last belastungs-/einsatzübung/heißer einsatz
    //last theorie
    //last csa übung
    //last g26
    protected array $scbaPersonalEvents;
    
    //g26 validity

    //einsatbereit
    
}