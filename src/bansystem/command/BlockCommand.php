<?php

namespace bansystem\command;

use bansystem\Manager;
use bansystem\translation\Translation;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class BlockCommand extends Command {
    
    public function __construct() {
        parent::__construct("block");
        $this->description = "Prevents the given player from running server commands.";
        $this->usageMessage = "/block <name> [reason...]";
        $this->setPermission("bansystem.command.block");
    }
    
    public function execute(CommandSender $sender, $commandLabel, array $args) {
        if ($this->testPermissionSilent($sender)) {
            if (count($args) <= 0) {
                $sender->sendMessage(Translation::translateParams("usage", array($this)));
                return false;
            }
            $blockList = Manager::getNameBlocks();
            $player = $sender->getServer()->getPlayer($args[0]);
            if ($blockList->isBanned($args[0])) {
                $sender->sendMessage(Translation::translate("playerAlreadyBlocked"));
                return false;
            }
            if (count($args) == 1) {
                if ($player != null) {
                    $blockList->addBan($player->getName(), null, null, $sender->getName());
                    $sender->getServer()->broadcastMessage(TextFormat::AQUA . $player->getName() . TextFormat::RED . " has been blocked.");
                    $player->sendMessage(TextFormat::RED . "You have been blocked.");
                } else {
                    $blockList->addBan($args[0], null, null, $sender->getName());
                    $sender->getServer()->broadcastMessage(TextFormat::AQUA . $args[0] . TextFormat::RED . " has been blocked.");
                }
            } else if (count($args) >= 2) {
                $reason = "";
                for ($i = 1; $i < count($args); $i++) {
                    $reason .= $args[$i];
                    $reason .= " ";
                }
                $reason = substr($reason, 0, strlen($reason) - 1);
                if ($player != null) {
                    $blockList->addBan($player->getName(), $reason, null, $sender->getName());
                    $sender->getServer()->broadcastMessage(TextFormat::AQUA . $player->getName() . TextFormat::RED . " has been blocked for " . TextFormat::AQUA . $reason . TextFormat::RED . ".");
                    $player->sendMessage(TextFormat::RED . "You have been blocked for " . TextFormat::AQUA . $reason . TextFormat::RED . ".");
                } else {
                    $blockList->addBan($args[0], $reason, null, $sender->getName());
                    $sender->getServer()->broadcastMessage(TextFormat::AQUA . $args[0] . TextFormat::RED . " has been blocked for " . TextFormat::AQUA . $reason . TextFormat::RED . ".");
                }
            }
        } else {
            $sender->sendMessage(Translation::translate("noPermission"));
        }
        return true;
    }
}