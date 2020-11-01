<?php

namespace NgLamVN\FireBall;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\plugin\PluginBase;

class FireBall extends PluginBase implements Listener
{
    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        Entity::registerEntity(\NgLamVN\FireBall\entity\FireBall::class, false, ['FireBall', 'minecraft:fireball']);
    }

    /**
     * @param PlayerInteractEvent $event
     * @priority LOW
     */
    public function onTap (PlayerInteractEvent $event)
    {
        if ($event->isCancelled())
        {
            return;
        }
        if ($event->getAction() !== PlayerInteractEvent::RIGHT_CLICK_AIR)
        {
            return;
        }

        $player = $event->getPlayer();
        $item = $event->getItem();

        if ($item->getId() !== Item::FIREBALL)
        {
            return;
        }

        $pos = $player->asVector3();
        $pos->y += $player->getEyeHeight();
        $nbt = Entity::createBaseNBT($pos);
        $entity = Entity::createEntity(Entity::FIREBALL, $player->getLevel(), $nbt, $player);
        $entity->spawnToAll();

        $direction = $player->getDirectionVector();
        $entity->setRotation($player->yaw, $player->pitch);
        $entity->setMotion($direction->multiply(1.5));
    }
}
