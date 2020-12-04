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

namespace nlog\trade\inventory\action;

use nlog\trade\TradeAPI;
use pocketmine\inventory\transaction\action\InventoryAction;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\network\mcpe\protocol\types\ContainerIds;
use pocketmine\network\mcpe\protocol\types\NetworkInventoryAction;
use pocketmine\Player;
use UnexpectedValueException;

class NetworkTradeInventoryAction extends NetworkInventoryAction{
	public function createInventoryAction(Player $player) : ?InventoryAction{
		if($this->oldItem->equalsExact($this->newItem)){
			//filter out useless noise in 1.13
			return null;
		}
		switch($this->sourceType){
			case self::SOURCE_CONTAINER:
				$window = null;
				$slot = $this->inventorySlot;
				if($this->windowId === ContainerIds::UI and ($slot === 4 || $slot === 5)){
					$window = TradeAPI::getInventory($player);
					$slot -= 4;
				}
				if($this->windowId === ContainerIds::UI and $slot === 51){
					$window = TradeAPI::getInventory($player);
					$slot -= 49;
				}
				if($window !== null){
					return new SlotChangeAction($window, $slot, $this->oldItem, $this->newItem);
				}

				throw new UnexpectedValueException("No open container with window ID $this->windowId");
			default:
				throw new UnexpectedValueException("Unknown inventory source type $this->sourceType");
		}
	}
}