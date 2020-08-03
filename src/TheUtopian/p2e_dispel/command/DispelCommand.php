<?php
declare(strict_types=1);

namespace TheUtopian\p2e_dispel\command;


use DaPigGuy\PiggyCustomEnchants\CustomEnchantManager;
use DaPigGuy\PiggyCustomEnchants\enchants\CustomEnchantIds;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use TheUtopian\p2e_dispel\P2E_Dispel;

class DispelCommand extends Command implements PluginIdentifiableCommand{

    private $notAllowed = [CustomEnchantIds::AUTOREPAIR, CustomEnchantIds::SOULBOUND];

    public function __construct(){
        parent::__construct("dispel", "Remove spells from items", "/dispel <enchantName>");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){
        if (!$sender instanceof Player) {
            $sender->sendMessage("§cThis command can be used only in game!");
            return true;
        }
        if (!isset($args[0])) {
            $sender->sendMessage("§o§l§c/dispel (enchantment)");
            return true;
        }

        $testEnchant = CustomEnchantManager::getEnchantmentByName($args[0]);

        if ($testEnchant === null) {
            $sender->sendMessage("§o§l§cInvalid enchantment " . $args[0] . "!");
            return true;
        }

        if (in_array($testEnchant->getId(), $this->notAllowed)) {
            $sender->sendMessage("§o§l§cThat Enchant cannot be Dispelled!");
            return true;
        }

        $item = $sender->getInventory()->getItemInHand();
        $currentEnchant = $item->getEnchantment($testEnchant->getId());

        if ($currentEnchant === null) {
            $sender->sendMessage("§o§l§cItem does not have specified enchantment!");
            return true;
        }

        $book = ItemFactory::get(Item::ENCHANTED_BOOK);
        $book->addEnchantment($currentEnchant);
        $inventory = $sender->getInventory();
        if(!$inventory->canAddItem($book)){
            $sender->sendMessage("Not enough room in your inventory!");
            return true;
        }

        $item->removeEnchantment($testEnchant->getId());
        $inventory->setItemInHand($item);
        $inventory->addItem($book);
        $sender->sendMessage("§o§l§aEnchantment successfully removed.");
        return true;
    }

    public function getPlugin() : Plugin{
        return P2E_Dispel::getInstance();
    }

}