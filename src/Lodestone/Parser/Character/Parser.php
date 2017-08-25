<?php

namespace Lodestone\Parser\Character;

use Lodestone\{
    Entities\Character\CharacterProfile,
    Parser\Html\ParserHelper,
    Modules\Logging\Benchmark,
    Modules\Logging\Logger
};

/**
 * Class Parser
 *
 * @package Lodestone\Parser\Character
 */
class Parser extends ParserHelper
{
    use TraitProfile;
    use TraitClassJob;
    use TraitAttributes;
    use TraitCollectables;
    use TraitGear;
    use TraitClassJobActive;

    /** @var CharacterProfile */
    protected $profile;

    /**
     * Parser constructor.
     *
     * @param int $id
     */
    function __construct(int $id)
    {
        $this->profile = new CharacterProfile($id);
    }

    /**
     * @return CharacterProfile
     */
    public function parse()
    {
        $this->initialize();

        $started = Benchmark::milliseconds();
        Benchmark::start(__METHOD__,__LINE__);

        // parse stuff (order is important)
        $this->parseProfile();
        $this->parseClassJob();
        $this->parseAttributes();
        $this->parseCollectables();
        $this->parseEquipGear();
        $this->parseActiveClass();

        Benchmark::finish(__METHOD__,__LINE__);
        $finished = Benchmark::milliseconds();
        $duration = $finished - $started;
        Logger::write(__CLASS__, __LINE__, sprintf('PARSE DURATION: %s ms', $duration));

        return $this->profile;
    }
}