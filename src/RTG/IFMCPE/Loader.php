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
    
    public $config;

    public function onEnable() {
        
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
        $this->config = new Config($this->getDataFolder() . "config.yml");
        
    }
    
    public function onJoin(PlayerJoinEvent $e) {
        
        $cfg = $this->config->getAll();
        $p = $e->getPlayer();
        
            foreach($cfg['jcmd'] as $cmd) {
                
                $this->getServer()->dispatchCommand(new \pocketmine\command\ConsoleCommandSender(), str_replace("{player}", $p->getName(), $cmd));
                
            }
        
    }
    
    public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
        
        switch(strtolower($command->getName())) {
            
            case "if":
                
                if(isset($args[0])) {
                    
                    switch($args[0]) {
                        
                        case "all":
                            
                            if(isset($args[1])) {
                                
                                switch($args[1]) {
                                    
                                    case "event":
                                        
                                        if(isset($args[2])) {
                                            
                                            switch($args[2]) {
                                                
                                                case "join":
                                                    
                                                    if(isset($args[3])) {
                                                        
                                                        $cfg = $this->config->getAll();
                                                        $line = implode(" ", array_splice($args, 3));
                                                        
                                                        $this->config->set('jcmd', $line);
                                                        $this->config->save();
                                                        $this->getLogger()->warning("saved!");
                                                         
                                                    }
                                                    else {
                                                        $this->args($sender);
                                                    }
                                                    
                                            }
                                            
                                        }
                                        else {
                                            $this->args($sender);
                                        }
                                        
                                }
                                
                                
                            }
                            else {
                                $this->args($sender);
                            }
                            
                    }
                       
                }
                else {
                    $this->args($sender);
                }
            
        }
        
    }
    
    public function args($p) {
        
        $p->sendMessage(" -- Invalid Args -- ");
        $p->sendMessage("/if [all or player name] event [join] [what to do?]");
        
    }
    
    public function onDisable() {
        $this->getConfig()->save();
    }
    
}