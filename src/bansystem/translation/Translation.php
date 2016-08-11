<?php

namespace bansystem\translation;

use bansystem\exception\TranslationFailedException;
use InvalidArgumentException;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat;

class Translation {
    
    public static function translate(string $translation) : string {
        switch ($translation) {
            case "noPermission":
                return TextFormat::RED . "You don't have enough access to do that.";
            case "playerNotFound":
                return TextFormat::GOLD . "Player is not online.";
            case "playerAlreadyBanned":
                return TextFormat::GOLD . "Player is already banned.";
            case "ipAlreadyBanned":
                return TextFormat::GOLD . "Player is already IP banned.";
            case "ipNotBanned":
                return TextFormat::GOLD . "IP address is not banned.";
            case "ipAlreadyMuted":
                return TextFormat::GOLD . "IP address is already muted.";
            case "playerNotBanned":
                return TextFormat::GOLD . "Player is not banned.";
            case "playerAlreadyMuted":
                return TextFormat::GOLD . "Player is already muted.";
            case "playerNotMuted":
                return TextFormat::GOLD . "Player is not muted.";
            case "ipNotMuted":
                return TextFormat::GOLD . "IP address is not muted.";
            case "playerAlreadyBlocked":
                return TextFormat::GOLD . "Player is already blocked.";
            case "playerNotBlocked":
                return TextFormat::GOLD . "Player is not blocked.";
            case "ipAlreadyBlocked":
                return TextFormat::GOLD . "IP address is already blocked.";
            case "ipNotBlocked":
                return TextFormat::GOLD . "IP address is not blocked.";
            default:
                throw new TranslationFailedException("Failed to translate.");
        }
    }
    
    public static function translateParams(string $translation, array $parameters) : string {
        if (empty($parameters)) {
            throw new InvalidArgumentException("Parameter is empty.");
        }
        switch ($translation) {
            case "usage":
                $command = $parameters[0];
                if ($command instanceof Command) {
                    return TextFormat::DARK_GREEN . "Usage: " . TextFormat::GREEN . $command->getUsage();
                } else {
                    throw new InvalidArgumentException("Parameter index 0 must be the type of Command.");
                }
        }
    }
}