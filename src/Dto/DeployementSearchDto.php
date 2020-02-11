<?php

namespace App\Dto;

class DeployementSearchDto
{

    private $UserWriter;
    private $indicatorEnable;
    private $indicatorValueEnable;
    private $corbeilleEnable;
    private $jalonOperator;
    private $jalonFrom;
    private $jalonTo;
    private $jalonNotPresent;
    private $search;
    private $organismeId;
    private $searchDate;

    /**
     * @return mixed
     */
    public function getSearchDate()
    {
        return $this->searchDate;
    }

    /**
     * @param mixed $searchDate
     * @return DeployementSearchDto
     */
    public function setSearchDate($searchDate)
    {
        $this->searchDate = $searchDate;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getOrganismeId()
    {
        return $this->organismeId;
    }

    /**
     * @param mixed $organismeId
     * @return DeployementSearchDto
     */
    public function setOrganismeId($organismeId)
    {
        $this->organismeId = $organismeId;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * @param mixed $search
     * @return DeployementSearchDto
     */
    public function setSearch($search)
    {
        $this->search = $search;

        $this->searchDate();

        return $this;
    }

    private function searchDate()
    {
        if (!empty($this->search)) {
            $d = $this->validateDate($this->search);
            if (!empty($d)) {
                $this->setSearchDate($d);
                $this->search = null;
            }
        }
    }
    function validateDate($date)
    {
        if (mb_substr_count($this->search, '/') == 2) {
            $d = explode('/', $date);
            return
                (strlen($d[2]) == 2 ? '20' . $d[2]:$d[2])
                . '-' .
                (strlen($d[1]) == 2 ? $d[1] : '0' . $d[1])
                . '-' .
                (strlen($d[0]) == 2 ? $d[0] : '0' . $d[0]);
        }
        return null;
    }

    /**
     * @return mixed
     */
    public function getJalonOperator()
    {
        return $this->jalonOperator;
    }

    /**
     * @param mixed $jalonOperator
     * @return DeployementSearchDto
     */
    public function setJalonOperator($jalonOperator)
    {
        $this->jalonOperator = $jalonOperator;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJalonFrom()
    {
        return $this->jalonFrom;
    }

    /**
     * @param mixed $jalonFrom
     * @return DeployementSearchDto
     */
    public function setJalonFrom($jalonFrom)
    {
        $this->jalonFrom = $jalonFrom;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJalonTo()
    {
        return $this->jalonTo;
    }

    /**
     * @param mixed $jalonTo
     * @return DeployementSearchDto
     */
    public function setJalonTo($jalonTo)
    {
        $this->jalonTo = $jalonTo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJalonNotPresent()
    {
        return $this->jalonNotPresent;
    }

    /**
     * @param mixed $jalonNotPresent
     * @return DeployementSearchDto
     */
    public function setJalonNotPresent($jalonNotPresent)
    {
        $this->jalonNotPresent = $jalonNotPresent;
        return $this;
    }




    public function __construct(ActionSearchDto $actionSearchDto)
    {
        $this->actionSearchDto=$actionSearchDto;
        $this->indicatorEnable=true;
        $this->indicatorValueEnable=true;
        $this->corbeilleEnable=true;
    }

    /**
     * @return string | null
     */
    public function getUserWriter(): ?string
    {
        return $this->UserWriter;
    }

    /**
     * @return DeployementSearchDto
     */
    public function setUserWriter(string $UserWriter): DeployementSearchDto
    {
        $this->UserWriter = $UserWriter;

        return $this;
    }

    /**
     * @return bool
     */
    public function isIndicatorEnable(): bool
    {
        return $this->indicatorEnable;
    }

    /**
     * @return DeployementSearchDto
     */
    public function setIndicatorEnable(bool $indicatorEnable): DeployementSearchDto
    {
        $this->indicatorEnable = $indicatorEnable;

        return $this;
    }

    /**
     * @return bool
     */
    public function isIndicatorValueEnable(): bool
    {
        return $this->indicatorValueEnable;
    }

    /**
     * @return DeployementSearchDto
     */
    public function setIndicatorValueEnable(bool $indicatorValueEnable): DeployementSearchDto
    {
        $this->indicatorValueEnable = $indicatorValueEnable;

        return $this;
    }


    /**
     * @return mixed
     */
    public function isCorbeilleEnable()
    {
        return $this->corbeilleEnable;
    }

    /**
     * @param mixed $corbeilleEnable
     * @return DeployementSearchDto
     */
    public function setCorbeilleEnable($corbeilleEnable)
    {
        $this->corbeilleEnable = $corbeilleEnable;
        return $this;
    }
}
