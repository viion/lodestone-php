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
     * @index FreeCompanies
     */
    protected $freeCompanies = [];
    
    /**
     * @return array
     */
    public function getFreeCompanies(): array
    {
        return $this->freeCompanies;
    }
    
    /**
     * @param array $freeCompanies
     * @return $this
     */
    public function setFreeCompanies(array $freeCompanies)
    {
        FreeCompanyValidator::getInstance()
            ->check($freeCompanies, 'FreeCompanies')
            ->isInitialized()
            ->isArray();
        
        $this->freeCompanies = $freeCompanies;
        
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
            ->isObject();
        
        $this->freeCompanies[] = $character;

        return $this;
    }
}
