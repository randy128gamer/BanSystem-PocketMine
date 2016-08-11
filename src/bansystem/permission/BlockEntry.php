<?php

namespace bansystem\permission;

use pocketmine\permission\BanEntry;

class BlockEntry extends BanEntry {
    
    public function __construct($name) {
        parent::__construct($name);
        $this->setReason("Blocked by an operator.");
    }
}