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

namespace nlog\trade\merchant;

use pocketmine\entity\Entity;

class TraderProperties{

	/** @var Entity|null */
	public $entity = null; //if it is null, plugin will send fake actor to player.

	/** @var string */
	public $traderName;

	/** @var int */
	public $xp = 0;

	/** @var int */
	public $tradeTier;

	/** @var int */
	public $maxTradeTier;

	/** @var int */
	public $eid; //create/remove fake actor from player

}