<?php

namespace App\Dto;

class ActionSearchDto
{
    private $id;
    private $axeId;
    private $poleId;
    private $thematiqueId;
    private $categoryId;
    private $actionArchiving;
    private $axeEnable;
    private $thematiqueEnable;
    private $poleEnable;
    private $categoryEnable;
    private $search;
    private $thematiqueRef;
    private $categoryRef;
    private $actionRef;
    private $state;

    private $jalonOperator;
    private $jalonFrom;
    private $jalonTo;
    private $jalonNotPresentValider;
    private $states;

    /**
     * @return mixed
     */
    public function getStates()
    {
        return $this->states;
    }

    /**
     * @param mixed $states
     * @return ActionSearchDto
     */
    public function setStates($states)
    {
        $this->states = $states;
        return $this;
    }

    private $jalonNotPresentWriter;
    private $UserWriter;
    private $UserValider;

    public function __construct()
    {
        $this->axeEnable=true;
        $this->poleEnable=true;
        $this->thematiqueEnable=true;
        $this->categoryEnable=true;
        $this->actionArchiving=false;
    }
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return ActionSearchDto
     */
    public function setId($id)
    {
        $this->axeEnable=null;
        $this->poleEnable=null;
        $this->thematiqueEnable=null;
        $this->categoryEnable=null;
        $this->actionArchiving=null;
        $this->id = $id;
        return $this;
    }

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
     * @return mixed
     */
    public function getPoleId()
    {
        return $this->poleId;
    }

    /**
     * @param mixed $poleId
     * @return ActionSearchDto
     */
    public function setPoleId($poleId)
    {
        $this->poleId = $poleId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getThematiqueId()
    {
        return $this->thematiqueId;
    }

    /**
     * @param mixed $thematiqueId
     * @return ActionSearchDto
     */
    public function setThematiqueId($thematiqueId)
    {
        $this->thematiqueId = $thematiqueId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * @param mixed $categoryId
     * @return ActionSearchDto
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
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

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     * @return ActionSearchDto
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
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
     * @return ActionSearchDto
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
     * @return ActionSearchDto
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
     * @return ActionSearchDto
     */
    public function setJalonTo($jalonTo)
    {
        $this->jalonTo = $jalonTo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJalonNotPresentWriter()
    {
        return $this->jalonNotPresentWriter;
    }

    /**
     * @param mixed $jalonNotPresent
     * @return ActionSearchDto
     */
    public function setJalonNotPresentWriter($jalonNotPresentWriter)
    {
        $this->jalonNotPresentWriter = $jalonNotPresentWriter;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJalonNotPresentValider()
    {
        return $this->jalonNotPresentValider;
    }

    /**
     * @param mixed $jalonNotPresent
     * @return ActionSearchDto
     */
    public function setJalonNotPresentValider($jalonNotPresentValider)
    {
        $this->jalonNotPresentValider = $jalonNotPresentValider;
        return $this;
    }
    /**
     * @return string | null
     */
    public function getUserWriter(): ?string
    {
        return $this->UserWriter;
    }

    /**
     * @return ActionSearchDto
     */
    public function setUserWriter(string $UserWriter): ActionSearchDto
    {
        $this->UserWriter = $UserWriter;

        return $this;
    }

    /**
     * @return string | null
     */
    public function getUserValider(): ?string
    {
        return $this->UserValider;
    }

    /**
     * @return ActionSearchDto
     */
    public function setUserValider(string $UserValider): ActionSearchDto
    {
        $this->UserValider = $UserValider;

        return $this;
    }
}