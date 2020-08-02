<?php
declare(strict_types=1);

namespace TheUtopian\p2e_dispel;

use DaPigGuy\PiggyCustomEnchants\CustomEnchantManager;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;

class P2E_Dispel extends PluginBase
{
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage("§cThis command can be used only in game!");
            return true;
        }
        if (!isset($args[0])) {
            $sender->sendMessage("§o§l§c/dispel (enchantment)");
            return true;
        }
        switch ($args[0]) {
            case "dispel":
                $enchant = CustomEnchantManager::getEnchantmentByName($args[0]);
                $item = $sender->getInventory()->getItemInHand();
                $nope = [118, 108];
                if ($enchant === null) {
                    $sender->sendMessage("§o§l§cInvalid enchantment!");
                    return true;
                }
                if ($item->getEnchantment($enchant->getId()) === null) {
                    $sender->sendMessage("§o§l§cItem does not have specified enchantment!");
                    return true;
                }
                if ($item->getEnchantment($enchant->getId()) === $nope) {
                    $sender->sendMessage("§o§l§cThat Enchant cannot be Dispelled!");
                    return true;
                }
                $item->removeEnchantment($enchant->getId());
                if (count($item->getEnchantments()) === 0) $item->removeEnchantments();
                $sender->sendMessage("§o§l§aEnchantment successfully removed.");
                $sender->getInventory()->setItemInHand($item);
                return true;
        }

        return true;
    }
}