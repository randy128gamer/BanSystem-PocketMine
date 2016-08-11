<?php

namespace bansystem\permission;

use DateTime;
use InvalidArgumentException;
use pocketmine\permission\BanEntry;
use pocketmine\permission\BanList;

class MuteList extends BanList {
    
    public function add(BanEntry $entry) {
        if ($entry instanceof MuteEntry) {
            throw new InvalidArgumentException();
        }
        parent::add($entry);
    }
    
    /**
     * 
     * @param string $target
     * @param string $reason
     * @param DateTime $expires
     * @param string $source
     */
    public function addBan($target, $reason = null, $expires = null, $source = null) {
        $entry = new MuteEntry($target);
        $entry->setReason($reason != null ? $reason : $entry->getReason());
        $entry->setExpires($expires);
        $entry->setSource($source != null ? $source : $entry->getSource());
        parent::addBan($entry->getName(), $entry->getReason(), $entry->getExpires(), $entry->getSource());
    }
}