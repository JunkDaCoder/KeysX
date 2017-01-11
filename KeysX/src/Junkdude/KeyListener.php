<?php
namespace Junkdude;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\nbt\tag\ByteTag;
    use pocketmine\nbt\tag\CompoundTag;
    use pocketmine\nbt\tag\DoubleTag;
    use pocketmine\nbt\tag\FloatTag;
    use pocketmine\nbt\tag\IntTag;
    use pocketmine\nbt\tag\ListTag;
    use pocketmine\nbt\tag\ShortTag;
    use pocketmine\nbt\tag\StringTag;

class KeyListener implements Listener {

    public function __construct(Key $plugin) {
        $this->plugin = $plugin;
    }

    public function onInteract(PlayerInteractEvent $event) {
        $item = $event->getItem();
        $held = $event->getPlayer()->getInventory()->getItemInHand();
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $slot = $player->getInventory()->getHeldItemSlot();
        if (Key::isKey($item)) {
            Key::RedeemKey($player);
            $player->getInventory()->remove($held);
            }
        }
    }
