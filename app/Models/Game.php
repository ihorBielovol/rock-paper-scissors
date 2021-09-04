<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Game extends Model
{
    const ROCK = 'rock';
    const PAPER = 'paper';
    const SCISSORS = 'scissors';

    private const BEATS = [
        self::ROCK => self::SCISSORS,
        self::SCISSORS => self::PAPER,
        self::PAPER => self::ROCK,
    ];

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
                            $this->callculateWinner();

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
        $data = [
            'players' => $players,
            'isOver' => $this->is_over,
            'result' => $this->result,
        ];
        return $data;
    }

    public function checkChoosedAll(): bool
    {
        $player = $this->players()->get();
        if ($player->count() === 3){
            foreach ($this->players()->get() as $player){
                if($player->gesture == null) {
                    return false;
                }
            }
            $this->is_over = true;
            $this->save();
            return true;
        }
        return false;
    }
    public function callculateWinner():void
    {
        if($this->checkChoosedAll()){
            $players = $this->players;
            $choosed = [
                self::ROCK => 0,
                self::PAPER => 0,
                self::SCISSORS => 0,
            ];
            foreach ($players as $player) {
                $choosed[$player->gesture] = $choosed[$player->gesture]++;
            }

            if(($choosed[self::ROCK] === 1 && $choosed[self::PAPER] === 1 && $choosed[self::SCISSORS] === 1) || array_search(3, $choosed) !== false) {
                $this->result = 'draw';
            }else{
                $first = array_search(2, $choosed);
                $second = array_search(1, $choosed);
                $win = self::BEATS[$first] === $second ? $first : $second;
                $winner = [];
                foreach ($players as $player) {
                    if($player->gesture === $win) {
                        $winner[] = $player->nickname;
                    }
                }
                $this->result = 'winner'. (count($winner) > 1 ? 's' : '').': ' . implode($winner);
            }
            $this->save();
        }
    }
}
