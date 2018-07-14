<?php

namespace Lodestone\Parser;

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
class Character extends ParserHelper
{
    use Character\TraitProfile;
    use Character\TraitClassJob;
    use Character\TraitAttributes;
    use Character\TraitCollectables;
    use Character\TraitGear;
    use Character\TraitClassJobActive;
    
    /**
     * Parser constructor.
     *
     * @param int $id
     */
    function __construct(int $id, string $html)
    {
        $this->results = new CharacterProfile($id);
        $this->html = $html;
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
    }
}