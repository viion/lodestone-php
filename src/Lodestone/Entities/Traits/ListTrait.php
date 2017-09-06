<?php

namespace Lodestone\Entities\Traits;

use Lodestone\Validator\BaseValidator;

/**
 * Class ListTrait
 *
 * Common trait for lodestone list pages
 *
 * @package Lodestone\Entities\Traits
 */
trait ListTrait
{
    /**
     * @var int
     */
    protected $pageCurrent = 0;
    
    /**
     * @var int
     */
    protected $pageNext = 0;
    
    /**
     * @var int
     */
    protected $pagePrevious = 0;
    
    /**
     * @var int
     */
    protected $pageTotal = 0;
    
    /**
     * @var int
     */
    protected $total = 0;
    
    /**
     * Set next/previous pages
     *
     * @return $this
     */
    public function setNextPrevious()
    {
        if (!$this->pageCurrent || !$this->pageTotal) {
            return $this;
        }
        
        // set next page
        $this->pageNext = ($this->pageCurrent == $this->pageTotal) ? $this->pageCurrent : $this->pageCurrent + 1;
        
        // set total page
        $this->pagePrevious = ($this->pageCurrent > 1) ? $this->pageCurrent - 1 : 1;
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getPageCurrent(): int
    {
        return $this->pageCurrent;
    }
    
    /**
     * @param int $pageCurrent
     * @return $this
     */
    public function setPageCurrent(int $pageCurrent)
    {
        BaseValidator::getInstance()
            ->check($pageCurrent, 'Current Page')
            ->isInitialized()
            ->isNotEmpty()
            ->isNumeric()
            ->validate();
        
        $this->pageCurrent = $pageCurrent;
        
        // handle next/prev
        $this->setNextPrevious();
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getPageTotal(): int
    {
        return $this->pageTotal;
    }
    
    /**
     * @param int $pageTotal
     * @return $this
     */
    public function setPageTotal(int $pageTotal)
    {
        BaseValidator::getInstance()
            ->check($pageTotal, 'Page Total')
            ->isInitialized()
            ->isNotEmpty()
            ->isNumeric()
            ->validate();
        
        $this->pageTotal = $pageTotal;
        
        // handle next/prev
        $this->setNextPrevious();
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }
    
    /**
     * @param int $total
     * @return $this
     */
    public function setTotal(int $total)
    {
        BaseValidator::getInstance()
            ->check($total, 'Total')
            ->isInitialized()
            ->isNotEmpty()
            ->isNumeric()
            ->validate();
        
        $this->total = $total;
        
        // handle next/prev
        $this->setNextPrevious();
        
        return $this;
    }
}