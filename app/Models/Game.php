<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Game extends Model
{
    public function addPlayer($nickname): array
    {
        $players = $this->players()->get();

        if($players->count() >= 3) {
            return ['success' => false, 'message' => 'Too many players'];
        }

        foreach ($players as $player) {
            if($player->nickname === $nickname) {
                return ['success' => false, 'message' => 'Player with this nickname already exist in game'];
            }
        }

        if($this->players()->create(['nickname' => $nickname])) {
            return ['success' => true, 'message' => 'Player join game'];
        }
        return ['success' => false, 'message' => 'Something want wrong'];
    }

    public function players(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Player::class,'game_id');
    }

    public function gesture($nickname, $gesture): array
    {
        $countChoose = 0;
        foreach ($this->players as $player){
            if($player->gesture !== null) {
                $countChoose++;
            }
        }

        foreach($this->players as $player) {
            if($player->nickname === $nickname) {
                if($player->gesture === null) {
                    try{
                        $player->gesture = $gesture;
                        DB::beginTransaction();
                        if($player->save()) {
                            if($this->checkChoosedAll()) {
                                $this->isOver = true;
                                $this->callculateWinner();
                                $this->save();
                            }
                            DB::commit();
                            return ['success' => true, 'message' => 'Successfully add choose'];
                        }else{
                            return ['success' => false, 'message' => 'Something want wrong'];
                        }
                    }catch (\Exception $exception){
                        DB::rollBack();
                    }
                }else{
                    return ['success' => false, 'message' => 'Choose already exist'];
                }
            }
        }
        return ['success' => false, 'message' => 'Player with this nickname in this game not found'];
    }

    public function getInfo()
    {
        $players = $this->players;

    }

    public function checkChoosedAll(): bool
    {
        foreach ($this->players()->get() as $player){
            if($player->gesture == null) {
                return false;
            }
        }
        return true;
    }
    public function callculateWinner()
    {
        $players = $this->players;
        if(count($players) == 3){
            $playerOne = $players[0];
            $playerTwo = $players[1];
            $playerThree = $players[2];

        }
    }
}
