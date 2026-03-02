<?php

namespace App\DataFixtures;

use App\Entity\Player;
use App\Entity\Game;
use App\Entity\Milestone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // --- CREAR UN JUGADOR ESTRELLA ---
        $player1 = new Player();
        $player1->setName('John');
        $player1->setSurname('Bridge');
        $player1->setNumber(10);
        $player1->setPosition('MC');
        $player1->setOverallRating(75);
        $player1->setJoinedAt(new \DateTime('2023-01-01'));
        $player1->setIsLegend(false);
        $player1->setImageUrl('john_bridge.png');
        $player1->setMatchesPlayed(25);
        $player1->setGoals(8);
        $player1->setAssists(12);
        $manager->persist($player1);

        // --- CREAR UNA LEYENDA ---
        $legend = new Player();
        $legend->setName('Arthur');
        $legend->setSurname('Iron');
        $legend->setNumber(9);
        $legend->setPosition('DC');
        $legend->setOverallRating(82);
        $legend->setJoinedAt(new \DateTime('2022-01-01'));
        $legend->setLeftAt(new \DateTime('2023-12-31'));
        $legend->setIsLegend(true);
        $legend->setImageUrl('arthur_iron.png');
        $legend->setMatchesPlayed(50);
        $legend->setGoals(45);
        $legend->setAssists(5);
        $manager->persist($legend);

        // --- CREAR UN PARTIDO (PRÓXIMO) ---
        $game = new Game();
        $game->setOpponentName('Shrewsbury Town');
        $game->setOpponentLogoUrl('shrewsbury_logo.svg');
        $game->setIsHome(true);
        $game->setScheduledAt(new \DateTime('+7 days'));
        $game->setCompetition('EFL League Two');
        // El resultado se queda null porque es próximo
        $manager->persist($game);

        // --- CREAR UN HITO (MILESTONE) ---
        $milestone = new Milestone();
        $milestone->setTitle('El Renacer Industrial');
        $milestone->setDescription('Fundación oficial del Ironbridge FC para el modo carrera 2023.');
        $milestone->setAchievedAt(new \DateTime('2023-08-01'));
        $milestone->setImageUrl('foundation.jpg');
        $manager->persist($milestone);

        // Guardar todo en la DB
        $manager->flush();
    }
}