<?php

namespace App\Helper;

use App\Entity\IndicatorValue;
use App\Entity\IndicatorValueHistory;
use App\Entity\User;
use App\Repository\IndicatorValueHistoryRepository;
use App\Manager\IndicatorValueHistoryManager;
use Symfony\Component\Security\Core\Security;

class IndicatorValueHistoryCreate
{
    /**
     * @var IndicatorValueHistoryManager
     */
    private $manager;

    /**
     * @var IndicatorValueHistoryRepository
     */
    private $repository;

    /**
     * @var Security
     */
    private $securityContext;

    public function __construct(IndicatorValueHistoryManager $manager,
                                IndicatorValueHistoryRepository $repository,
                                Security $securityContext)
    {
        $this->manager = $manager;
        $this->repository = $repository;
        $this->securityContext = $securityContext;
    }

    public function createHistory(IndicatorValue $indicatorValue)
    {
        if (null === $this->securityContext->getToken()) {
            return;
        }
        /** @var User $user */
        $user = $this->securityContext->getToken()->getUser();

        if (null == $this->repository->findOneBy(
            [
                'user' => $user,
                'goal' => $indicatorValue->getGoal(),
                'value' => $indicatorValue->getValue(),
                'content' => $indicatorValue->getContent(),
            ]
            )) {
            $ivh = new IndicatorValueHistory();

            $ivh
                ->setUser($user)
                ->setIndicatorValue($indicatorValue)
                ->setGoal($indicatorValue->getGoal())
                ->setValue($indicatorValue->getValue())
                ->setTaux1($indicatorValue->getTaux1())
                ->setTaux2($indicatorValue->getTaux2())
                ->setContent($indicatorValue->getContent())
                ->setAddedAt(new \DateTime());

            $this->manager->save($ivh);
        }
    }
}
