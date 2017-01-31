<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types = 1);

namespace pocketmine\block;

use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\math\AxisAlignedBB;

class BlockType{

	/** @var \SplFixedArray */
	public static $list = null;

	public static function init(){
		self::$list = new \SplFixedArray(256);
		//TODO: init blocks
	}

	protected $uniqueName;
	protected $translationKey;
	protected $fallbackName;

	protected $id;
	protected $meta;

	protected $solid = true;
	protected $transparent = false;
	protected $hardness = 1;
	protected $blastResistance = 5;

	/** @var AxisAlignedBB */
	protected $boundingBox;

	public function __construct(int $id, int $meta = 0){
		$this->id = $id;
		$this->meta = $meta;
	}

	/**
	 * Returns the block type's unique name as per the /give command.
	 *
	 * @return string
	 */
	public function getUniqueName() : string{
		return $this->uniqueName;
	}

	/**
	 * Returns a translation key for the block type's name which can be sent to clients.
	 *
	 * @return string
	 */
	public function getNameTranslationKey() : string{
		return $this->translationKey;
	}

	/**
	 * Returns the fallback (English) name of this block type.
	 *
	 * @return string
	 */
	public function getFallbackName() : string{
		return $this->fallbackName;
	}

	/**
	 * Returns the block type ID (its position in the block type registry)
	 *
	 * @return int
	 */
	final public function getId() : int{
		return $this->id;
	}

	/**
	 * Returns the block type meta
	 *
	 * @return int
	 */
	final public function getDamage() : int{
		return $this->meta;
	}

	/**
	 * Returns whether objects will collide with this block type's bounding box.
	 *
	 * @return bool
	 */
	public function isSolid() : bool{
		return $this->solid;
	}

	public function setSolid(bool $solid = true){
		$this->solid = $solid;
	}

	/**
	 * Returns whether light will pass through any part of this block type.
	 *
	 * @return bool
	 */
	public function isTransparent() : bool{
		return $this->transparent;
	}

	public function setTransparent(bool $transparent = true){
		$this->transparent = $transparent;
	}

	/**
	 * Returns the hardness value of this block type. Used to calculate block break times. If the block is indestructible, this may return -1.
	 *
	 * @return float
	 */
	public function getHardness() : float{
		return $this->hardness;
	}

	public function setHardness(float $hardness){
		$this->hardness = $hardness;
	}

	/**
	 * Returns this block type's blast resistance.
	 *
	 * @return float
	 */
	public function getBlastResistance() : float{
		return $this->blastResistance;
	}

	public function setBlastResistance(float $resistance){
		$this->blastResistance = $resistance;
	}

	/**
	 * Returns the block type's bounding box relative to 0 0 0. May vary based on meta.
	 *
	 * @return AxisAlignedBB|null
	 */
	public function getBoundingBox(){
		return $this->boundingBox;
	}

	/**
	 * Returns the block type's bounding box relative to the specified position.
	 *
	 * @param Position $pos The position is also used for things like stairs whose bounding boxes vary based on surroundings.
	 *
	 * @return AxisAlignedBB|null
	 */
	public function getBoundingBoxRelativeTo(Position $pos){
		return $this->boundingBox->offset($pos->x, $pos->y, $pos->z);
	}

	/**
	 * Performs actions when an entity taps the block at the specified position (right-click).
	 *
	 * @param Position $blockPos The position of the block clicked
	 * @param Item     $item The Item used to perform the interaction
	 * @param int      $face The side of the block clicked on
	 * @param float    $fx The X position (between 0 and 1) in the block targeted.
	 * @param float    $fy The Y position (between 0 and 1) in the block targeted.
	 * @param float    $fz The Z position (between 0 and 1) in the block targeted.
	 * @param Entity   $source the source of the interaction.
	 *
	 * @return bool if any changes were made.
	 */
	public function onInteract(Position $blockPos, Item $item, int $face, float $fx, float $fy, float $fz, Entity $source = null) : bool{
		return false;
	}

	/**
	 * Performs actions when an entity tries to break the block at the specified position (left-click).
	 *
	 * @param Position $blockPos
	 * @param Item     $item
	 * @param int      $face The side of the block clicked on
	 * @param float    $fx The X position (between 0 and 1) in the block targeted.
	 * @param float    $fy The Y position (between 0 and 1) in the block targeted.
	 * @param float    $fz The Z position (between 0 and 1) in the block targeted.
	 * @param Entity   $source the entity trying to break the block.
	 *
	 * @return bool if any changes were made.
	 */
	public function onStartBreak(Position $blockPos, Item $item, int $face, float $fx, float $fy, float $fz, Entity $source = null) : bool{
		return false;
	}

	/**
	 * Performs actions when the block at the specified position is broken.
	 *
	 * @param Position    $blockPos
	 * @param Item|null   $item
	 * @param Entity|null $source
	 *
	 * @return bool if any changes were made.
	 */
	public function onBreak(Position $blockPos, Item $item = null, Entity $source = null) : bool{
		return $blockPos->getLevel()->setBlockType($blockPos, BlockType::get(BlockType::AIR));
	}

	/**
	 * Performs actions when a neighbour block causes the block at $pos to be updated.
	 *
	 * @param Position $blockPos
	 * @param Position $source the neighbour block which triggered the update
	 *
	 * @return bool if any changes were made.
	 */
	public function onNeighbourUpdate(Position $blockPos, Position $source) : bool{
		return false;
	}

	/**
	 * Performs actions when the block at $pos is updated at random.
	 *
	 * @param Position $blockPos
	 *
	 * @return bool if any changes were made.
	 */
	public function onRandomUpdate(Position $blockPos) : bool{
		return false;
	}
}