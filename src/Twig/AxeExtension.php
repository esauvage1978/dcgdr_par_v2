<?php


namespace App\Twig;


use App\Dto\ActionSearchDto;
use App\Entity\Axe;
use App\Repository\ActionRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AxeExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('nbrActionForAxe', [$this, 'nbrActionForAxe']),
        ];
    }

    /**
     * @var ActionSearchDto
     */
    private $actionSearchDto;

    /**
     * @var ActionRepository
     */
    private $actionRepository;

    public function __construct(
        ActionSearchDto $actionSearchDto,
        ActionRepository $actionRepository
    ) {
        $this->actionSearchDto=$actionSearchDto;
        $this->actionRepository=$actionRepository;
    }

    public function nbrActionForAxe(Axe $axe)
    {
        $this->actionSearchDto->setAxeId($axe->getId());
        return count($this->actionRepository->findAllForDto(
           $this->actionSearchDto
        ));
    }

}