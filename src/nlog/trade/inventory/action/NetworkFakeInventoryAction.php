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


use nlog\trade\inventory\FakeInventory;
use pocketmine\inventory\transaction\action\InventoryAction;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\network\mcpe\protocol\types\inventory\NetworkInventoryAction;
use pocketmine\player\Player;
use UnexpectedValueException;

class NetworkFakeInventoryAction extends NetworkInventoryAction {
	public function createInventoryAction(Player $player): ?InventoryAction {
		if ($this->oldItem->equalsExact($this->newItem)) {
			//filter out useless noise in 1.13
			return null;
		}
		switch ($this->sourceType) {
			case self::SOURCE_TODO:
				if ($this->windowId === -30) { //fake inventory -> real inventory (slot 50)
					return new SlotChangeAction(new FakeInventory($this->oldItem), $this->inventorySlot, $this->oldItem, $this->newItem);
				}
				if ($this->windowId === -31) { //real item -> fake inventory
					return new SlotChangeAction(new FakeInventory($this->oldItem), $this->inventorySlot, $this->oldItem, $this->newItem);
				}
				throw new UnexpectedValueException("No open container with window ID $this->windowId");
			default:
				throw new UnexpectedValueException("Unknown inventory source type $this->sourceType");
		}
	}
}