<?php

/*
 * TradeAPI, simple to provide Trade UI V2.
 * Copyright (C) 2020  Organic (nnnlog)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace nlog\trade\listener;

use nlog\trade\TradeAPI;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\ActorEventPacket;
use pocketmine\network\mcpe\protocol\ContainerClosePacket;

class InventoryListener implements Listener{

	/** @var TradeAPI */
	private $plugin;

	public function __construct(TradeAPI $plugin){
		$this->plugin = $plugin;
	}

	public function onDataPacketReceive(DataPacketReceiveEvent $event){
		$pk = $event->getPacket();
		if($pk instanceof ContainerClosePacket && $pk->windowId === 255){
			$this->plugin->closeWindow($event->getPlayer(), false);
		}elseif($pk instanceof ActorEventPacket && $pk->event !== ActorEventPacket::COMPLETE_TRADE){
			$this->plugin->doCloseInventory($this->plugin->getInventory($event->getPlayer()));
		}
	}

	public function onPlayerCommandPreprocess(PlayerCommandPreprocessEvent $event){
		$this->plugin->doCloseInventory($this->plugin->getInventory($event->getPlayer()));
	}

	public function onBlockBreak(BlockBreakEvent $event){
		$this->plugin->doCloseInventory($this->plugin->getInventory($event->getPlayer()));
	}

	public function onPlayerDeath(PlayerDeathEvent $event){
		$this->plugin->doCloseInventory($this->plugin->getInventory($event->getPlayer()));
	}

	public function onPlayerJoin(PlayerJoinEvent $event){
		$this->plugin->addInventory($event->getPlayer());
	}

	public function onPlayerQuit(PlayerQuitEvent $event){
		$this->plugin->doCloseInventory($this->plugin->getInventory($event->getPlayer()));
		$this->plugin->removeInventory($event->getPlayer());
	}
}