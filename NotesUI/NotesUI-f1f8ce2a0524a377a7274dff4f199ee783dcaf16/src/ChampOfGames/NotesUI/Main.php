<?php


namespace ChampOfGames\NotesUI;

use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener
{
    public  $files = array();
    public $files2 = array();
    public $fnames = array();
    public function openNoteUI($player)
    {
        $form = new SimpleForm(function (Player $player, int $data = null) {

            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    $this->openNewNoteUI($player);
                    break;
                    case 1:
                        $this->delNotesUI($player);
                    break;
                case 2:
                    $this->openNotesUI($player);
                    break;
            }
        });

        $form->setTitle("Notes");
        $form->setContent("Entekhab Konid Che Kari Anjam Midahid.");
        $form->addButton("§2Sakht Yek Note");
        $form->addButton("§4Sakht Yek Note");
        $form->addButton("§bNote Haye Shoma");
        $form->addButton("Bastan");
        $form->sendToPlayer($player);
        return $form;
    }
    public function openNewNoteUI($player)
    {
        $form = new CustomForm(function (Player $player, array $data = null) {

            if (isset($data[0])) {

                if (!file_exists($this->getDataFolder() .  $player->getName() . "/")) {
                    @mkdir($this->getDataFolder() .  $player->getName() . "/");
                }
                $note = fopen($this->getDataFolder() . $player->getName() . "/" . $data[0] . ".txt", "w");
                fwrite($note, $data[1]);
                fclose($note);
            } else {
                $this->openNoteUI($player);
            }
        });

        $form->setTitle("Notes");
        $form->addInput("Nam :");
        $form->addInput("Matn Shoma :");
        $form->sendToPlayer($player);
        return $form;
    }
    public function openNotesUI($player)
    {

        $fname = array();
        foreach (glob($this->getDataFolder() . $player->getName() . "/*.txt") as $file) {
            $this->files[] = $file;
            $name = basename($file, ".txt") . PHP_EOL;
            $fname[] = $name;
        }
        $form = new CustomForm(function (Player $player, array $data = null) {




            $nform = new SimpleForm(function (Player $player, int $data) {
            });
            $nform->setTitle("Notes");
            $nform->setContent($file = file_get_contents($this->files[$data[0]], FILE_USE_INCLUDE_PATH));
            $nform->addButton("Bastan");
            $nform->sendToPlayer($player);
            return $nform;
        });


        $form->setTitle("Notes");
        $form->addDropdown("Note Haye Shoma:", $fname);
        $form->sendToPlayer($player);
        return $form;
    }

    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool
    {
        if ($cmd->getName() === "notesui") {
            if ($sender instanceof Player) {
                if ($sender->hasPermission("notesui.use")) {
                    $this->openNoteUI($sender);
                }
            }
        }
        return true;
    }

    public function delNote($note){
        unlink($note);
    }


    public function delNotesUI($player)
    {

        foreach (glob($this->getDataFolder() . $player->getName() . "/*.txt") as $file) {
            $this->files2[] = $file;
            $name = basename($file, ".txt") . PHP_EOL;
            $this->fnames[] = $name;
        }
        $form = new CustomForm(function (Player $player, array $data = null) {
$this->delNote($this->files2[$data[0]]);


        });


        $form->setTitle("Notes");
        $form->addDropdown("Note Haye Shoma:", $this->fnames);
        $form->sendToPlayer($player);
        return $form;
    }
}
