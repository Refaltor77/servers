<?php

namespace refaltor\serveurs;

use pocketmine\plugin\PluginBase;
use refaltor\serveurs\commands\serveur;
use refaltor\serveurs\events\PlayerListener;

class Serveurs extends PluginBase
{

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents(new PlayerListener($this), $this);
        @mkdir($this->getDataFolder() . "data/");
        $this->saveResource("data/data.yml");
        $this->getServer()->getCommandMap()->register("server", new serveur());
    }

}