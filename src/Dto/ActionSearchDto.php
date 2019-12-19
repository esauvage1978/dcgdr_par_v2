<?php

namespace App\Dto;

class ActionSearchDto
{
    private $axe_id;
    private $action_archiving;
    private $axe_enable;
    private $thematique_enable;
    private $pole_enable;
    private $category_enable;

    public function __construct()
    {
        $this->axe_enable=true;
        $this->pole_enable=true;
        $this->thematique_enable=true;
        $this->category_enable=true;
        $this->action_archiving=false;
    }

    /**
     * @return mixed
     */
    public function getAxeId()
    {
        return $this->axe_id;
    }

    /**
     * @param mixed $axe_id
     * @return actionSearchDto
     */
    public function setAxeId($axe_id)
    {
        $this->axe_id = $axe_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getActionArchiving()
    {
        return $this->action_archiving;
    }

    /**
     * @param mixed $action_archiving
     * @return actionSearchDto
     */
    public function setActionArchiving($action_archiving)
    {
        $this->action_archiving = $action_archiving;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAxeEnable()
    {
        return $this->axe_enable;
    }

    /**
     * @param mixed $axe_enable
     * @return actionSearchDto
     */
    public function setAxeEnable($axe_enable)
    {
        $this->axe_enable = $axe_enable;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getThematiqueEnable()
    {
        return $this->thematique_enable;
    }

    /**
     * @param mixed $thematique_enable
     * @return actionSearchDto
     */
    public function setThematiqueEnable($thematique_enable)
    {
        $this->thematique_enable = $thematique_enable;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPoleEnable()
    {
        return $this->pole_enable;
    }

    /**
     * @param mixed $pole_enable
     * @return actionSearchDto
     */
    public function setPoleEnable($pole_enable)
    {
        $this->pole_enable = $pole_enable;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategoryEnable()
    {
        return $this->category_enable;
    }

    /**
     * @param mixed $category_enable
     * @return actionSearchDto
     */
    public function setCategoryEnable($category_enable)
    {
        $this->category_enable = $category_enable;
        return $this;
    }


}