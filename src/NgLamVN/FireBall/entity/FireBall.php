<?php

namespace NgLamVN\FireBall\entity;

use pocketmine\block\Block;
use pocketmine\entity\projectile\Throwable;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\level\particle\HugeExplodeParticle;
use pocketmine\level\Position;
use pocketmine\math\RayTraceResult;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\level\Explosion;
use pocketmine\Player;

class FireBall extends Throwable
{

    const NETWORK_ID = self::FIREBALL;

    public $width = 0.50;
    public $height = 0.50;

    protected $gravity = 0;


    public function getName()
    {
        return "Fire Ball";
    }

    public function onHitBlock(Block $blockHit, RayTraceResult $hitResult): void
    {
        parent::onHitBlock($blockHit, $hitResult); // TODO: Change the autogenerated stub
        $this->getLevel()->addParticle(new HugeExplodeParticle($this->asVector3())); //TODO: FAKE EXPLOSIVE.
    }

    public function onUpdate(int $currentTick): bool
    {
        if ($this->closed) {
            return false;
        }

        $this->timings->startTiming();
        $this->updateMovement();
        $hasUpdate = parent::onUpdate($currentTick);

        if ($this->ticksLived > 1200 or $this->isCollided)
        {
            $this->flagForDespawn();
            $hasUpdate = true;
        }

        $this->timings->stopTiming();

        return $hasUpdate;
    }
}
