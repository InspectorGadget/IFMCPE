<?php

/* 
 * Copyright (C) 2017 RTG
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace RTG\IFMCPE;

/* Essentials */
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\utils\TextFormat as TF;
use pocketmine\utils\Config;
use pocketmine\event\player\PlayerJoinEvent;

class Loader extends PluginBase implements Listener {
    
    public function onEnable() {
        
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
        
        $cfg = $this->getConfig()->getAll();
        
    }
    
    public function onJoin(PlayerJoinEvent $e) {
        
        $p = $e->getPlayer();
        
            foreach($cfg["jcmd"] as $cmd) {
                
                $this->getServer()->dispatchCommand(new \pocketmine\command\ConsoleCommandSender(), str_replace("{player}", $e->getPlayer(), $cmd));
                
            }
        
    }
    
    public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
        
        switch (strtolower($command->getName())) {
            
            case "if":
                
                if(!$sender->hasPermission("ifmcpe.command")) {
                    $sender->sendMessage(TF::RED . "You have no permission to use this command!");
                }
                
                if(!isset($args[1])) {
                    $sender->sendMessage($this->onHelp($sender));
                }
                
                if($args[1] === "{player}") {
                    
                    if($args[2] === "event") {
                        
                        if($args[3] === "join") {
                            
                            $line = $args[3];
                            
                            $this->getConfig()->set("jcmd", $line);
                            $this->getConfig()->save();
                            
                        }
                        else {
                            $this->onHelp($sender);
                        }

                    }
                    else {
                        $this->onHelp($sender);
                    }
                    
                }
                else {
                    $this->onHelp($sender);
                }
                
        }
        
    }
    
    public function onHelp($p) {
        
        $p->sendMessage(TF::YELLOW . "-- HELP --");
        
    }
    
    public function onDisable() {
        $this->getConfig()->save();
    }
    
}