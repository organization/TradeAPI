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


use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;

class MerchantRecipeList {

	/** @var MerchantRecipe[] */
	private $recipes = [];

	/** @var bool */
	private $changed = true;

	/** @var ListTag */
	private $nbt;

	public function __construct(MerchantRecipe ...$recipes) {
		foreach ($recipes as $recipe) {
			$this->push($recipe);
		}
		$this->nbt = new ListTag();
	}

	public function push(MerchantRecipe $recipe): int {
		$this->recipes[] = clone $recipe;
		$this->changed = true;

		return count($this->recipes) - 1;
	}

	public function pop(): ?MerchantRecipe {
		if (count($this->recipes)) {
			$this->changed = true;
			return array_pop($this->recipes);
		}
		return null;
	}

	public function toNBT(bool $cache = true): ListTag {
		if (!$cache or $this->changed) {
			$this->nbt = new ListTag(array_map(function (MerchantRecipe $recipe): CompoundTag {
				return $recipe->toNBT();
			}, $this->recipes));

			$this->changed = false;
		}

		return $this->nbt;
	}

}