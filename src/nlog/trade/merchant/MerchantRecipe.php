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


use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;

class MerchantRecipe {

	/**
	 * @param  CompoundTag          $tag
	 * @param  string               $k
	 * @param  Item|int|float|null  $v
	 * @param  int|float            $minValue
	 *
	 * @return CompoundTag
	 */
	private static function add(CompoundTag $tag, string $k, $v, $minValue = 0): void {
		if (is_int($v)) {
			if ($v > $minValue) {
				$tag->setInt($k, $v);
			}
		}

		if (is_float($v)) {
			if ($v > $minValue) {
				$tag->setInt($k, $v);
			}
		}

		if ($v instanceof Item) {
			$tag->setTag($k, $v->nbtSerialize(-1));
		}
	}

	/** @var Item */
	private $buyA;

	/** @var Item */
	private $sell;

	/** @var Item|null */
	private $buyB = null;

	/** @var int */
	private $maxUses = 999;

	/** @var int */
	private $tier = -1;

	/** @var int */
	private $buyCountA = -1;

	/** @var int */
	private $buyCountB = -1;

	/** @var int */
	private $uses = -1; //TODO

	/** @var int */
	private $rewardExp = -1; //TODO

	/** @var int */
	private $demand = -1; //TODO

	/** @var int */
	private $traderExp = -1; //TODO

	/** @var float */
	private $priceMultiplierA = -1.0, $priceMultiplierB = -1.0; //TODO

	public function __construct(Item $buyA, Item $sell, ?Item $buyB = null, int $tier = -1, int $buyCountA = -1, int $buyCountB = -1, int $maxUses = 999) {
		$this->buyA = $buyA;
		$this->sell = $sell;

		$this->buyB = $buyB;
		$this->tier = $tier;
		$this->buyCountA = $buyCountA;
		$this->buyCountB = $buyCountB;
		$this->maxUses = $maxUses;
	}

	/**
	 * @param  Item  $buyA
	 */
	public function setBuyA(Item $buyA): void {
		$this->buyA = $buyA;
	}

	/**
	 * @param  Item|null  $buyB
	 */
	public function setBuyB(?Item $buyB): void {
		$this->buyB = $buyB;
	}

	/**
	 * @param  Item  $sell
	 */
	public function setSell(Item $sell): void {
		$this->sell = $sell;
	}

	/**
	 * @param  int  $tier
	 */
	public function setTier(int $tier): void {
		$this->tier = $tier;
	}

	/**
	 * @param  int  $buyCountA
	 */
	public function setBuyCountA(int $buyCountA): void {
		$this->buyCountA = $buyCountA;
	}

	/**
	 * @param  int  $buyCountB
	 */
	public function setBuyCountB(int $buyCountB): void {
		$this->buyCountB = $buyCountB;
	}

	/**
	 * @param  int  $maxUses
	 */
	public function setMaxUses(int $maxUses): void {
		$this->maxUses = $maxUses;
	}

	public function toNBT(): CompoundTag {
		$tag = CompoundTag::create();

		self::add($tag, "buyA", $this->buyA);
		self::add($tag, "sell", $this->sell);
		self::add($tag, "buyB", $this->buyB);
		self::add($tag, "tier", $this->tier, -1);
		self::add($tag, "buyCountA", $this->buyCountA);
		self::add($tag, "buyCountB", $this->buyCountB);
		self::add($tag, "uses", max($this->uses, 0), -1);
		self::add($tag, "rewardExp", max($this->rewardExp, 0));
		self::add($tag, "demand", max($this->demand, 0));
		self::add($tag, "traderExp", max($this->traderExp, 0));
		self::add($tag, "priceMultiplierA", max($this->priceMultiplierA, 0.0));
		self::add($tag, "priceMultiplierB", max($this->priceMultiplierB, 0.0));

		return $tag;
	}

}