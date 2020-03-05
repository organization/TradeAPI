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

namespace nlog\trade\inventory;


use pocketmine\inventory\BaseInventory;
use pocketmine\player\Player;

class PlayerTradeInventory extends BaseInventory {
	/** @var Player */
	protected $holder;

	public function __construct(Player $holder) {
		$this->holder = $holder;
		parent::__construct(3);
	}

	/**
	 * @return Player
	 */
	public function getHolder() {
		return $this->holder;
	}
}