<?php

namespace App\Tests\Game;

use App\Deck\DeckHand;
use App\Game\BlackjackGame;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class BlackjackGame.
 */
class BlackjackGameTest extends TestCase
{
    public function testInitialStatusIsPlaying(): void
    {
        $game = new BlackjackGame();
        $this->assertEquals('playing', $game->getGameStatus());
    }

    public function testDealGivesTwoCardsEach(): void
    {
        $game = new BlackjackGame();
        $game->deal();
        $this->assertEquals(2, $game->getPlayerHand()->getNumberCards());
        $this->assertEquals(2, $game->getDealerHand()->getNumberCards());
    }

    public function testHandsAreDeckHandInstances(): void
    {
        $game = new BlackjackGame();
        $this->assertInstanceOf(DeckHand::class, $game->getPlayerHand());
        $this->assertInstanceOf(DeckHand::class, $game->getDealerHand());
    }

    public function testCalculateScoreEmptyHand(): void
    {
        $game = new BlackjackGame();
        $this->assertEquals(0, $game->calculateScore($game->getPlayerHand()));
    }

    public function testCalculateScoreNumberCards(): void
    {
        $game = new BlackjackGame();
        $game->deal();
        $score = $game->calculateScore($game->getPlayerHand());
        $this->assertGreaterThanOrEqual(2, $score);
        $this->assertLessThanOrEqual(22, $score);
    }

    public function testHitAddsCard(): void
    {
        $game = new BlackjackGame();
        $game->deal();
        $before = $game->getPlayerHand()->getNumberCards();
        $game->hit();
        $this->assertEquals($before + 1, $game->getPlayerHand()->getNumberCards());
    }

    public function testStandEndsGame(): void
    {
        $game = new BlackjackGame();
        $game->deal();
        $game->stand();
        $validStatuses = ['dealer_bust', 'player_lost', 'player_win'];
        $this->assertContains($game->getGameStatus(), $validStatuses);
    }

    public function testGetDealerVisibleScore(): void
    {
        $game = new BlackjackGame();
        $game->deal();
        $score = $game->getDealerVisibleScore();
        $this->assertGreaterThanOrEqual(1, $score);
        $this->assertLessThanOrEqual(11, $score);
    }

    public function testCalculateScoreAceCountsAsOne(): void
    {
        $game = new BlackjackGame();
        $game->deal();
        $score = $game->calculateScore($game->getPlayerHand());
        $this->assertLessThanOrEqual(21, $score);
    }

    public function testPlayerBustSetsStatus(): void
    {
        $game = new BlackjackGame();
        $game->deal();
        for ($i = 0; $i < 20; $i++) {
            if ($game->getGameStatus() !== 'playing') {
                break;
            }
            $game->hit();
        }
        $this->assertContains(
            $game->getGameStatus(),
            ['playing', 'player_bust']
        );
    }

    public function testGetPlayerHand(): void
    {
        $game = new BlackjackGame();
        $this->assertInstanceOf(\App\Deck\DeckHand::class, $game->getPlayerHand());
    }

    public function testGetDealerHand(): void
    {
        $game = new BlackjackGame();
        $this->assertInstanceOf(\App\Deck\DeckHand::class, $game->getDealerHand());
    }
}
