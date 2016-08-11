<?php

namespace bansystem\command;

use bansystem\Manager;
use bansystem\translation\Translation;
use bansystem\util\date\Countdown;
use DateTime;
use InvalidArgumentException;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class TempMuteCommand extends Command {
    
    public function __construct() {
        parent::__construct("tempmute");
        $this->description = "Temporarily prevents the player from sending chat message.";
        $this->usageMessage = "/tempmute <player> <timeFormat> [reason...]";
        $this->setPermission("bansystem.command.tempmute");
    }
    
    public function execute(CommandSender $sender, $commandLabel, array $args) {
        if ($this->testPermissionSilent($sender)) {
            if (count($args) <= 1) {
                $sender->sendMessage(Translation::translateParams("usage", array($this)));
                return false;
            }
            $muteList = Manager::getNameMutes();
            $player = $sender->getServer()->getPlayer($args[0]);
            try {
                $expiry = new Countdown($args[1]);
                $expiryToString = Countdown::expirationTimerToString($expiry->getDate(), new DateTime());
                if ($muteList->isBanned($args[0])) {
                    $sender->sendMessage(Translation::translate("playerAlreadyMuted"));
                    return false;
                }
                if (count($args) == 2) {
                    if ($player != null) {
                        $muteList->addBan($player->getName(), $expiry->getDate(), null, $sender->getName());
                        $sender->getServer()->broadcastMessage(TextFormat::AQUA . $player->getName() . TextFormat::RED . " has been muted until " . TextFormat::AQUA . $expiryToString . TextFormat::RED . ".");
                        $player->sendMessage(TextFormat::RED . "You have been muted until " . TextFormat::AQUA . $expiryToString . TextFormat::RED . ".");
                    } else {
                        $muteList->addBan($args[0], $expiry->getDate(), null, $sender->getName());
                        $sender->getServer()->broadcastMessage(TextFormat::AQUA . $args[0] . TextFormat::RED . " has been muted until " . TextFormat::AQUA . $expiryToString . TextFormat::RED . ".");
                    }
                } else if (count($args) >= 3) {
                    $reason = "";
                    for ($i = 2; $i < count($args); $i++) {
                        $reason .= $args[$i];
                        $reason .= " ";
                    }
                    $reason = substr($reason, 0, strlen($reason) - 1);
                    if ($player != null) {
                        $muteList->addBan($player->getName(), $reason, $expiry->getDate(), $sender->getName());
                        $sender->getServer()->broadcastMessage(TextFormat::AQUA . $player->getName() . TextFormat::RED . " has been muted for " . TextFormat::AQUA . $reason . " until " . TextFormat::AQUA . $expiryToString . TextFormat::RED . ".");
                        $player->sendMessage(TextFormat::RED . "You have been muted for " . TextFormat::AQUA . $reason . TextFormat::REDd . " until " . TextFormat::AQUA . $expiryToString . TextFormat::RED . ".");
                    } else {
                        $muteList->addBan($args[0], $reason, $expiry->getDate(), $sender->getName());
                        $sender->getServer()->broadcastMessage(TextFormat::AQUA . $args[0] . TextFormat::RED . " has been muted for " . TextFormat::AQUA . $reason . TextFormat::RED . " until " . TextFormat::AQUA . $expiryToString . TextFormat::RED . ".");
                    }
                }
            } catch (InvalidArgumentException $ex) {
                $sender->sendMessage(TextFormat::RED . $ex->getMessage());
            }
        } else {
            $sender->sendMessage(Translation::translate("noPermission"));
        }
        return true;
    }
}