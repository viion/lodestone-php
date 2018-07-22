<?php

namespace Lodestone\Entities\Traits;

use Lodestone\Modules\Validator;

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
     * @index PageCurrent
     */
    public $pageCurrent = 0;
    
    /**
     * @var int
     * @index PageNext
     */
    public $pageNext = 0;
    
    /**
     * @var int
     * @index PagePrevious
     */
    public $pagePrevious = 0;
    
    /**
     * @var int
     * @index PageTotal
     */
    public $pageTotal = 0;
    
    /**
     * @var int
     * @index Total
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
        Validator::getInstance()
            ->check($pageCurrent, 'Current Page')
            ->isNotEmpty()
            ->isNumeric();
        
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
        Validator::getInstance()
            ->check($pageTotal, 'Page Total')
            ->isNotEmpty()
            ->isNumeric();
        
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
        Validator::getInstance()
            ->check($total, 'Total')
            ->isNotEmpty()
            ->isNumeric();
        
        $this->total = $total;
        
        // handle next/prev
        $this->setNextPrevious();
        
        return $this;
    }
}