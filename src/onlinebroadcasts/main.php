<?php

namespace onlinebroadcasts;


use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use onlinebroadcasts\sendBroadcast;

class main extends PluginBase
{
    public $c;
    public function onEnable()
    {
        @mkdir($this->getDataFolder());
        $this->c = new Config($this->getDataFolder()."config.yml",Config::YAML,[
            "api" => "https://daltontastic.net/broadcasts.txt",
            "time" => 500,
            "prefix" => "Broadcast > ",
            "send_popup" => false,
            "send_tip" => false,
            "send_msg" => true,
            "saved_broadcasts" => []
        ]);
        $this->c->save();
        $this->updateBroadcasts();
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new sendBroadcast($this),$this->c->getAll()["time"]);
    }


    public function updateBroadcasts(){ //updates SavedBroadcasts from Online API
        $c = $this->c->getAll();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $c["api"]);
        $result = curl_exec($ch);
        curl_close($ch);
        $c["saved_broadcasts"] = [];
        foreach (preg_split('/\s*\R\s*/m', trim($result), NULL, PREG_SPLIT_NO_EMPTY) as $broadcast){
            $c["saved_broadcasts"][] = $broadcast;
        }
        $this->c->setAll($c);
        $this->c->save();
    }


}
