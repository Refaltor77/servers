<?php

namespace refaltor\serveurs\events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Player;
use pocketmine\utils\Config;
use refaltor\serveurs\forms\CustomForm;
use refaltor\serveurs\forms\SimpleForm;
use refaltor\serveurs\Serveurs;

class PlayerListener implements Listener
{
    /** @var Serveurs */
    public static $plugin;

    public function __construct($plugin)
    {
        self::$plugin = $plugin;
    }

    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        self::sendServeurs($player);
    }

    public static function sendServeurs(Player $player)
    {
        $data = new Config( self::$plugin->getDataFolder() . "data/data.yml", Config::YAML);
        $form = new SimpleForm(function (Player $player, $datas = null) use ($data){
            if (is_null($datas)) return;
            if (in_array($datas, $data->get("array"))){
                $id = $datas;
                $ip = $data->get("servers")[$id]['ip'];
                $port = $data->get("servers")[$id]["port"];
                $player->transfer($ip, $port);
            }else{
                self::sendAddServer($player);
            }
        });
        if (!empty($data->get("servers"))){
            foreach ($data->get("servers") as $id => $keys){
                $form->addButton("§l{$keys["name"]} §r\n§aClick");
            }
        }
        $form->setTitle("§aServers");
        $form->addButton("§6- §aAdd server ?§6 -");
        $player->sendForm($form);
    }

    public static function sendAddServer(Player $player){
         $dataa = new Config( self::$plugin->getDataFolder() . "data/data.yml", Config::YAML);
         $form = new CustomForm(function (Player $player, $data = null) use ($dataa){
             if (is_null($data)) return;
             $name = $data[0];
             $ip = $data[1];
             $port = $data[2];
             if (empty($dataa->get("servers"))){
                 $id = 0;
             }else  $id = count($dataa->get("servers"));
             $array = $dataa->get("array");
             array_push($array, $id);
             $dataa->remove("array");
             $dataa->set("array", $array);
             $dataa->set("servers", $dataa->get("servers") + ["$id" => ["name" => $name, "ip" => $ip, "port" => $port]]);
             $dataa->save();
             $player->sendMessage("§aServer successfully added !");
         });
         $form->setTitle("§aAdd server");
         $form->addInput("name", "the hive");
         $form->addInput("adresse", "goldrushmc.eu");
         $form->addInput("port", "19132", "19132");
         $player->sendForm($form);
    }
}