<?php

namespace Lodestone\Parser\Character;

use Lodestone\{
    Entities\Character\Profile,
    Parser\ParserHelper,

    Modules\XIVDB,
    Modules\Benchmark,
    Modules\Logger
};

/**
 * Class Parser
 *
 * @package Lodestone\Parser\Character
 */
class Parser extends ParserHelper
{
    use Hash;
    use TraitProfile;
    use TraitClassJob;
    use TraitAttributes;
    use TraitCollectables;
    use TraitGear;
    use TraitClassJobActive;

    /** @var XIVDB $xivdb */
    protected $xivdb;

    /** @var Profile */
    protected $profile;

    /**
     * Parser constructor.
     *
     * @param int $id
     */
    function __construct(int $id)
    {
        $this->xivdb = new XIVDB();
        $this->profile = new Profile($id);
    }

    /**
     * @return array
     */
    public function parse()
    {
        $this->initialize();

        $started = microtime(true);
        Benchmark::start(__METHOD__,__LINE__);

        // parser stuff (order is important)
        $this->parseProfile();
        $this->parseClassJob();
        $this->parseAttributes();
        $this->parseCollectables();
        $this->parseEquipGear();
        $this->parseActiveClass();

        Benchmark::finish(__METHOD__,__LINE__);
        Logger::write(__CLASS__, __LINE__, sprintf('PARSE DURATION: %s ms', round(microtime(true) - $started, 3)));

        // generate hash
        //$this->hash();

        //print_r($this->profile);
        die;

        return $this->profile->toArray();
    }
}