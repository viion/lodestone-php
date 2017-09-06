<?php

namespace Lodestone\Entities\Traits;

use Lodestone\{
    Entities\FreeCompany\FreeCompanySimple,
    Validator\FreeCompanyValidator
};

/**
 * Class FreeCompanyListTrait
 *
 * @package Lodestone\Entities\Traits
 */
trait FreeCompanyListTrait
{
    /**
     * @var array
     */
    protected $freecompanies = [];
    
    /**
     * @return array
     */
    public function getFreeCompanies(): array
    {
        return $this->freecompanies;
    }
    
    /**
     * @param array $freecompanies
     * @return $this
     */
    public function setFreeCompanies(array $freecompanies)
    {
        FreeCompanyValidator::getInstance()
            ->check($freecompanies, 'FreeCompanies')
            ->isInitialized()
            ->isArray()
            ->validate();
        
        $this->freecompanies = $freecompanies;
        
        return $this;
    }
    
    /**
     * @param FreeCompanySimple $character
     * @return $this
     */
    public function addFreeCompany(FreeCompanySimple $character)
    {
        FreeCompanyValidator::getInstance()
            ->check($character, 'FreeCompany')
            ->isInitialized()
            ->isObject()
            ->validate();
        
        $this->freecompanies[] = $character;

        return $this;
    }
}