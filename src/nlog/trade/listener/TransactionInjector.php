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


use nlog\trade\inventory\action\NetworkFakeInventoryAction;
use nlog\trade\inventory\action\NetworkTradeInventoryAction;
use nlog\trade\TradeAPI;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\types\inventory\ContainerIds;
use pocketmine\network\mcpe\protocol\types\inventory\NetworkInventoryAction;
use pocketmine\network\mcpe\protocol\types\inventory\NormalTransactionData;

class TransactionInjector implements Listener {

	public function onDataPacketReceive(DataPacketReceiveEvent $event) {
		if (($pk = $event->getPacket()) instanceof InventoryTransactionPacket) {
			/** @var InventoryTransactionPacket $pk */
			$res = [];
			$c = false;
			foreach ($pk->trData->getActions() as $_ => $action) {
				$after = $action;
				if ($action->windowId === ContainerIds::UI && ($action->inventorySlot === 4 || $action->inventorySlot === 5 || $action->inventorySlot === 50)) {
					if ($action->inventorySlot === 50) {
						if (TradeAPI::getInstance()->isTrading($event->getOrigin()->getPlayer())) {
							$action->inventorySlot = 51;
						} else {
							$res[] = $after;
							continue;
						}
					}

					$after = new NetworkTradeInventoryAction();
					$after->inventorySlot = $action->inventorySlot;
					$after->windowId = $action->windowId;
					$after->newItem = $action->newItem;
					$after->oldItem = $action->oldItem;
					$after->sourceFlags = $action->sourceFlags;
					$after->sourceType = $action->sourceType;

					$c = true;
				}

				if ($action->sourceType === NetworkInventoryAction::SOURCE_TODO && ($action->windowId === -31 || $action->windowId === -30)) {
					$after = new NetworkFakeInventoryAction();
					$after->inventorySlot = $action->inventorySlot;
					$after->windowId = $action->windowId;
					$after->newItem = $action->newItem;
					$after->oldItem = $action->oldItem;
					$after->sourceFlags = $action->sourceFlags;
					$after->sourceType = $action->sourceType;

					$c = true;
				}

				$res[] = $after;
			}

			if ($c) {
				$pk->trData = NormalTransactionData::new($res);
			}
		}
	}

}