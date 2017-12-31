<?php

namespace bansystem\command;

use bansystem\Manager;
use bansystem\translation\Translation;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class BlockIPCommand extends Command {
    
    public function __construct() {
        parent::__construct("block-ip");
        $this->description = "Prevents the given IP address from running server commands.";
        $this->usageMessage = "/block-ip <player | address> [reason...]";
        $this->setPermission("bansystem.command.blockip");
    }
    
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if ($this->testPermissionSilent($sender)) {
            if (count($args) <= 0) {
                $sender->sendMessage(Translation::translateParams("usage", array($this)));
                return false;
            }
            $player = $sender->getServer()->getPlayer($args[0]);
            $ip = filter_var($args[0], FILTER_VALIDATE_IP);
            $blockList = Manager::getIPBlocks();
            if ($blockList->isBanned($args[0])) {
                $sender->sendMessage(Translation::translate("ipAlreadyBlocked"));
                return false;
            }
            if (count($args) == 1) {
                if ($ip != null) {
                    $blockList->addBan($ip, null, null, $sender->getName());
                    foreach ($sender->getServer()->getOnlinePlayers() as $player) {
                        if ($player->getAddress() == $ip) {
                            $player->sendMessage(TextFormat::RED . "You have been IP blocked.");
                        }
                    }
                    $sender->getServer()->broadcastMessage(TextFormat::RED . "Address " . TextFormat::AQUA . $ip . TextFormat::RED . " has been blocked.");
                } else {
                    if ($player != null) {
                        $blockList->addBan($player->getAddress(), null, null, $sender->getName());
                        $sender->getServer()->broadcastMessage(TextFormat::AQUA . $player->getName() . TextFormat::RED . " has been IP blocked.");
                        $player->sendMessage(TextFormat::RED . "You have been IP blocked.");
                    } else {
                        $sender->sendMessage(Translation::translate("playerNotFound"));
                    }
                }
            } else if (count($args) >= 2) {
                $reason = "";
                for ($i = 1; $i < count($args); $i++) {
                    $reason .= $args[$i];
                    $reason .= " ";
                }
                $reason = substr($reason, 0, strlen($reason) - 1);
                if ($ip != null) {
                    $blockList->addBan($ip, $reason, null, $sender->getName());
                    foreach ($sender->getServer()->getOnlinePlayers() as $player) {
                        if ($player->getAddress() == $ip) {
                            $player->sendMessage(TextFormat::RED . "You have been IP blocked for " . TextFormat::AQUA . $reason . TextFormat::RED . ".");
                        }
                    }
                    $sender->getServer()->broadcastMessage(TextFormat::RED . "Address " . TextFormat::AQUA . $ip . TextFormat::RED . " has been blocked for " . TextFormat::AQUA . $reason . TextFormat::RED . ".");
                } else {
                    if ($player != null) {
                        $blockList->addBan($player->getAddress(), $reason, null, $sender->getName());
                        $sender->getServer()->broadcastMessage(TextFormat::AQUA . $player->getName() . TextFormat::RED . " has been IP blocked for " . TextFormat::AQUA . $reason . TextFormat::RED . ".");
                        $player->sendMessage(TextFormat::RED . "You have been IP blocked for " . TextFormat::AQUA . $reason . TextFormat::RED . ".");
                    } else {
                        $sender->sendMessage(Translation::translate("playerNotFound"));
                    }
                }
            }
        } else {
            $sender->sendMessage(Translation::translate("noPermission"));
        }
        return true;
    }
}