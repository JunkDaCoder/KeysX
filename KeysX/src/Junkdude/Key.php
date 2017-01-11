<?php
namespace Junkdude;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\item\Item;
use pocketmine\block\Block;
use pocketmine\command\{Command, CommandSender};
use pocketmine\utils\TextFormat as TF;
use pocketmine\event\Listener;
use pocketmine\Server;
use pocketmine\nbt\tag\ByteTag;
    use pocketmine\nbt\tag\CompoundTag;
    use pocketmine\nbt\tag\DoubleTag;
    use pocketmine\nbt\tag\FloatTag;
    use pocketmine\nbt\tag\IntTag;
    use pocketmine\nbt\tag\ListTag;
    use pocketmine\nbt\tag\ShortTag;
    use pocketmine\nbt\tag\StringTag;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\nbt\NBT;
use pocketmine\level\Level;
use pocketmine\level\sound;
use pocketmine\math\Vector3;
use pocketmine\level\Position;
use pocketmine\level\particle\FlameParticle;
use pocketmine\level\sound\ExplodeSound;
use pocketmine\item\enchantment\Enchantment;
class Key extends PluginBase implements Listener {

    public static $data, $crate = [];
    public static $prefix = TF::GREEN.'KeysX '.TF::GOLD;

    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getPluginManager()->registerEvents(new KeyListener($this), $this);
    }
   
    /**
    * Gives $player a key.
    */
    public static function giveKey(Player $player) {
        $key = Item::get(399, 0, 1);
        $keyTag = new CompoundTag('', []);
        $keyTag->Key = new IntTag('Key', 1);       
        $keyTag->display = new CompoundTag("display", [
        "Name" => new StringTag("Name", TF::GOLD."Key".TF::GREEN."\nTap on the ground to redeem!")
         ]);
        $keyTag->ench = new ListTag("ench", []);
        $key->setCompoundTag($keyTag);
        $player->getInventory()->addItem($key);
    }

    /**
    * Checks whether $key is a crate key.
    * @return bool.
    */
    public static function isKey(Item $key) : bool {
        return $key->getNamedTagEntry('Key') !== null ? true : false;
    }

    public static function redeemKey(Player $player) {
        $level = $player->getLevel();
        $player->sendMessage(TF::BOLD . TF::GREEN . "Key Redeemed!");
        $count1 = rand(1, 10);
        $enchlvl1 = rand(1, 2);
        $dpick = Item::get(Item::DIAMOND_PICKAXE, 0 , 1);
        switch (mt_rand(1, 3)) {
            case 1:
            $player->sendMessage(TF::BOLD . TF::GREEN . "You got " . $count1 . " Diamonds!");
            $player->getInventory()->addItem(Item::get(Item::DIAMOND, 0 , $count1));
            case 2:
            $player->sendMessage(TF::BOLD . TF::GREEN . "Wow you got an Effeciancy " . $enchlvl1 . " Diamond Pickaxe!");
            $player->getInventory()->addItem(Item::get(Item::DIAMOND_PICKAXE, 0 , 1));
            $dpick->addEnchantment(Enchantment::getEnchantment(15)->setLevel($enchlvl1));
            case 3:
            return;
        }
    }

    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
        if (isset($args[0])) {
            switch (strtolower($args[0])) {
                case 'givekey':
                    if (isset($args[1])) {
                        if (($player = $this->getServer()->getPlayer($args[1])) instanceof Player) {
                            if ($player === $sender) {
                                $sender->sendMessage(self::$prefix.'You gave yourself a crate key!');
                            } else {
                                $sender->sendMessage(self::$prefix.'You gave '.$player->getName().' a crate key');
                                $player->sendMessage(self::$prefix.'You received a crate key from '.$sender->getName());
                            }
                            self::giveKey($player);
                        } else {
                            $sender->sendMessage(TF::RED.'Player is offline.');
                        }
                    } else {
                        $sender->sendMessage(self::$prefix.'You gave yourself a crate key!');
                        self::giveKey($sender);
                    }
                    break;
                }
            }
        }
    }