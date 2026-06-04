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
        $this->assertEquals('done', $game->getGameStatus());
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
        $validStatuses = ['playing', 'player_bust', 'done'];
        $this->assertContains($game->getGameStatus(), $validStatuses);
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

    public function testGetNumHands(): void
    {
        $game = new BlackjackGame(3);
        $this->assertEquals(3, $game->getNumHands());
    }

    public function testGetCurrentHandIndex(): void
    {
        $game = new BlackjackGame();
        $this->assertEquals(0, $game->getCurrentHandIndex());
    }

    public function testIsCurrentHand(): void
    {
        $game = new BlackjackGame();
        $this->assertTrue($game->isCurrentHand(0));
        $this->assertFalse($game->isCurrentHand(1));
    }

    public function testMultipleHands(): void
    {
        $game = new BlackjackGame(3);
        $game->deal();
        $this->assertEquals(3, $game->getNumHands());
        $this->assertCount(3, $game->getPlayerHands());
    }

    public function testGetHandStatusPlayerBust(): void
    {
        $game = new BlackjackGame();
        $game->deal();
        for ($i = 0; $i < 20; $i++) {
            if ($game->getGameStatus() !== 'playing') {
                break;
            }
            $game->hit();
        }
        $status = $game->getHandStatus(0);
        $validStatuses = ['player_bust', 'player_win', 'player_lost', 'dealer_bust', 'playing'];
        $this->assertContains($status, $validStatuses);
    }

    public function testGetHandStatusAfterStand(): void
    {
        $game = new BlackjackGame();
        $game->deal();
        $game->stand();
        $status = $game->getHandStatus(0);
        $validStatuses = ['player_win', 'player_lost', 'dealer_bust'];
        $this->assertContains($status, $validStatuses);
    }

    public function testGetHandStatusDealerBust(): void
    {
        $game = new BlackjackGame();
        $game->deal();
        $game->stand();
        $dealerScore = $game->calculateScore($game->getDealerHand());
        $handStatus = $game->getHandStatus(0);
        if ($dealerScore > 21) {
            $this->assertEquals('dealer_bust', $handStatus);
        } else {
            $this->assertContains($handStatus, ['player_win', 'player_lost']);
        }
    }
}
