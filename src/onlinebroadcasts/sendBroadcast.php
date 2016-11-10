<?php

namespace onlinebroadcasts;


use pocketmine\scheduler\PluginTask;

class sendBroadcast extends PluginTask
{
    public $main;
    public function __construct(main $owner)
    {
        parent::__construct($owner);
        $this->main = $owner;
    }

    public function onRun($currentTick)
    {
        $c = $this->main->c->getAll();
        $b = $c["saved_broadcasts"][array_rand($c["saved_broadcasts"])];
        if ($c["send_msg"] == true){
            $this->main->getServer()->broadcastMessage($c["prefix"].$b);
        }
        if ($c["send_tip"] == true){
            foreach ($this->main->getServer()->getOnlinePlayers() as $player){
                $player->sendTip($c["prefix"].$b);
            }
        }
        if ($c["send_popup"] == true){
            foreach ($this->main->getServer()->getOnlinePlayers() as $player){
                $player->sendPopup($c["prefix"].$b);
            }
        }
    }
}