<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Character;
use App\Services\ActionResolver;

/**
 * @Route("/battle")
 */
class BattleController extends AbstractController
{
    private $actionResolver;

    public function __construct(ActionResolver $actionResolver)
    {
        $this->actionResolver = $actionResolver;
    }

    /**
     * @Route("/", name="battle_test")
     */
    public function test(): Response
    {
        $legolas = new Character();
        $legolas->setHealthPoint(1600);
        $legolas->setName('Legolas');
        $legolas->setDefense('60');
        $legolas->setStrength('60');

        $gimli = new Character();
        $gimli->setHealthPoint(2000);
        $gimli->setName('Gimli');
        $gimli->setDefense('70');
        $gimli->setStrength('50');

        $runs = $this->runBattle($legolas, $gimli);

        return $this->render('battle/test.html.twig', [
            'gimli'   => $gimli,
            'legolas' => $legolas,
            'runs'    => $runs,
        ]);
    }

    /**
     * @param Character $legolas
     * @param Character $gimli
     *
     * @return array
     */
    protected function runBattle(Character $gimli, Character $legolas): array
    {
        $attacks = [];

        while (!$gimli->hasGivenUp() && !$legolas->hasGivenUp()) {
            $attacks[] = $this->runAttack($legolas, $gimli);

            if (!$gimli->hasGivenUp()) {
                $attacks[] = $this->runAttack($gimli, $legolas);
            }
        }

        return $attacks;
    }

    protected function runAttack(Character $attacker, Character $defender): array
    {
        $damage = 0;
        while ($damage < 0) {
            $damage = $this->actionResolver->attack($attacker, $defender);
        }

        if ($damage > 0) {
            $defender->getHit($damage);
        }

        return [
            'attacker'     => $attacker->getName(),
            'defender'     => $defender->getName(),
            'damage'       => $damage,
            'attackerWins' => $defender->hasGivenUp(),
        ];
    }
}
