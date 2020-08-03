<?php
declare(strict_types=1);

namespace TheUtopian\p2e_dispel;

use DaPigGuy\PiggyCustomEnchants\CustomEnchantManager;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use TheUtopian\p2e_dispel\command\DispelCommand;

class P2E_Dispel extends PluginBase
{
    private static $instance;

    public function onEnable(){
        self::$instance = $this;
        $this->getServer()->getCommandMap()->register("p2e_dispel", new DispelCommand());
    }

    /**
     * @return mixed
     */
    public static function getInstance(){
        return self::$instance;
    }
}