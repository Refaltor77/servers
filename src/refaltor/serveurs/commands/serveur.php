<?php

namespace refaltor\serveurs\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use refaltor\serveurs\events\PlayerListener;

class serveur extends Command
{
    public function __construct()
    {
        parent::__construct("server", "Allows you to select a server");
        $this->setAliases(["serv"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            PlayerListener::sendServeurs($sender);
        }
    }
}