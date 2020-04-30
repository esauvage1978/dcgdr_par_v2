<?php

namespace App\Helper;

use App\Entity\IndicatorValue;
use App\Entity\IndicatorValueHistory;
use App\Entity\User;
use App\Manager\IndicatorValueHistoryManager;
use App\Repository\IndicatorValueHistoryRepository;
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
        if (!$indicatorValue->getId()) {
            return;
        }
        /** @var User $user */
        $user = $this->securityContext->getToken()->getUser();

        if (!$this->entryPresente($indicatorValue, $user)) {
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

    private function entryPresente(IndicatorValue $indicatorValue, User $user)
    {


        $ivh = $this->repository->getLastEntry($indicatorValue->getId());

        if (empty($ivh)) {
            return false;
        }

        if (
            $ivh->getUser()->getId() == $user->getId() &&
            $ivh->getGoal() == $indicatorValue->getGoal() &&
            $ivh->getValue() == $indicatorValue->getValue() &&
            $ivh->getContent() == $indicatorValue->getContent() &&
            $ivh->getIndicatorValue()->getId() == $indicatorValue->getId()
        ) {
            return true;
        }
        return false;
    }
}
