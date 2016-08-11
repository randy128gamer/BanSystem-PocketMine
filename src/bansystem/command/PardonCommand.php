<?php

namespace bansystem\command;

use bansystem\translation\Translation;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class PardonCommand extends Command {
    
    public function __construct() {
        parent::__construct("pardon");
        $this->description = "Allows the given players to use this server.";
        $this->usageMessage = "/pardon <player>";
        $this->setPermission("bansystem.command.pardon");
    }
    
    public function execute(CommandSender $sender, $commandLabel, array $args) {
        if ($this->testPermissionSilent($sender)) {
            if (count($args) <= 0) {
                $sender->sendMessage(Translation::translateParams("usage", array($this)));
                return false;
            }
            $banList = $sender->getServer()->getNameBans();
            if (!$banList->isBanned($args[0])) {
                $sender->sendMessage(Translation::translate("playerNotBanned"));
                return false;
            }
            $banList->remove($args[0]);
            $sender->getServer()->broadcastMessage(TextFormat::AQUA . $args[0] . TextFormat::GREEN . " has been unbanned.");
        } else {
            $sender->sendMessage(Translation::translate("noPermission"));
        }
        return true;
    }
}