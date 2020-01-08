<?php

namespace App\Dto;

class ActionSearchDto
{
    private $axeId;
    private $actionArchiving;
    private $axeEnable;
    private $thematiqueEnable;
    private $poleEnable;
    private $categoryEnable;
    private $search;
    private $thematiqueRef;
    private $categoryRef;
    private $actionRef;

    /**
     * @return mixed
     */
    public function getAxeId()
    {
        return $this->axeId;
    }

    /**
     * @param mixed $axeId
     * @return ActionSearchDto
     */
    public function setAxeId($axeId)
    {
        $this->axeId = $axeId;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActionArchiving(): bool
    {
        return $this->actionArchiving;
    }

    /**
     * @param bool $actionArchiving
     * @return ActionSearchDto
     */
    public function setActionArchiving(bool $actionArchiving): ActionSearchDto
    {
        $this->actionArchiving = $actionArchiving;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAxeEnable(): bool
    {
        return $this->axeEnable;
    }

    /**
     * @param bool $axeEnable
     * @return ActionSearchDto
     */
    public function setAxeEnable(bool $axeEnable): ActionSearchDto
    {
        $this->axeEnable = $axeEnable;
        return $this;
    }

    /**
     * @return bool
     */
    public function isThematiqueEnable(): bool
    {
        return $this->thematiqueEnable;
    }

    /**
     * @param bool $thematiqueEnable
     * @return ActionSearchDto
     */
    public function setThematiqueEnable(bool $thematiqueEnable): ActionSearchDto
    {
        $this->thematiqueEnable = $thematiqueEnable;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPoleEnable(): bool
    {
        return $this->poleEnable;
    }

    /**
     * @param bool $poleEnable
     * @return ActionSearchDto
     */
    public function setPoleEnable(bool $poleEnable): ActionSearchDto
    {
        $this->poleEnable = $poleEnable;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCategoryEnable(): bool
    {
        return $this->categoryEnable;
    }

    /**
     * @param bool $categoryEnable
     * @return ActionSearchDto
     */
    public function setCategoryEnable(bool $categoryEnable): ActionSearchDto
    {
        $this->categoryEnable = $categoryEnable;
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
     * @return ActionSearchDto
     */
    public function setSearch($search)
    {
        $this->search = $search;

        $this->SearchReference();
        return $this;
    }

    private function SearchReference()
    {
        if (mb_substr_count($this->search,'-')==2) {
            $temp=explode('-',$this->search);
            $this->setThematiqueRef($temp[0]);
            $this->setCategoryRef($temp[1]);
            $this->setActionRef($temp[2]);
            $this->search=null;
        }
    }

    /**
     * @return mixed
     */
    public function getThematiqueRef()
    {
        return $this->thematiqueRef;
    }

    /**
     * @param mixed $thematiqueRef
     * @return ActionSearchDto
     */
    public function setThematiqueRef($thematiqueRef)
    {
        $this->thematiqueRef = $thematiqueRef;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategoryRef()
    {
        return $this->categoryRef;
    }

    /**
     * @param mixed $categoryRef
     * @return ActionSearchDto
     */
    public function setCategoryRef($categoryRef)
    {
        $this->categoryRef = $categoryRef;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getActionRef()
    {
        return $this->actionRef;
    }

    /**
     * @param mixed $actionRef
     * @return ActionSearchDto
     */
    public function setActionRef($actionRef)
    {
        $this->actionRef = $actionRef;
        return $this;
    }





    public function __construct()
    {
        $this->axeEnable=true;
        $this->poleEnable=true;
        $this->thematiqueEnable=true;
        $this->categoryEnable=true;
        $this->actionArchiving=false;
    }


}