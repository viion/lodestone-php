<?php

namespace Lodestone\Entities\Traits;

use Lodestone\{
    Entities\FreeCompany\FreeCompanySimple,
    Modules\Validator
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
        Validator::getInstance()
            ->check($freeCompanies, 'FreeCompanies')
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
        Validator::getInstance()
            ->check($character, 'FreeCompany')
            ->isObject();
        
        $this->freeCompanies[] = $character;

        return $this;
    }
}
