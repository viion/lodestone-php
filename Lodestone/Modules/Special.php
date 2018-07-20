<?php
namespace Lodestone\Modules;

use Lodestone\Modules\{
    Logging\Logger, Routes
};

trait Special {
    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneBanners()
    {
        $this->url = $this->language.Routes::LODESTONE_BANNERS;
        $this->type = 'Banners';
        return $this->parse();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneNews()
    {
        $this->url = $this->language.Routes::LODESTONE_NEWS;
        $this->type = 'News';
        return $this->parse();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneTopics(int $page = 1)
    {
        $this->url = $this->language.Routes::LODESTONE_TOPICS.'?page='.$page;
        $this->type = 'News';
        return $this->parse();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneNotices(int $page = 1)
    {
        $this->url = $this->language.Routes::LODESTONE_NOTICES.'?page='.$page;
        $this->type = 'Notices';
        return $this->parse();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneMaintenance(int $page = 1)
    {
        $this->url = $this->language.Routes::LODESTONE_MAINTENANCE.'?page='.$page;
        $this->type = 'Notices';
        return $this->parse();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneUpdates(int $page = 1)
    {
        $this->url = $this->language.Routes::LODESTONE_UPDATES.'?page='.$page;
        $this->type = 'Notices';
        return $this->parse();
    }

    /**
     * @test .
     * @return array|bool
     */
    public function getLodestoneStatus(int $page = 1)
    {
        $this->url = $this->language.Routes::LODESTONE_STATUS.'?page='.$page;
        $this->type = 'Notices';
        return $this->parse();
    }

    /**
     * @test .
     * @return array
     */
    public function getWorldStatus()
    {
        $this->url = $this->language.Routes::LODESTONE_WORLD_STATUS;
        $this->type = 'WorldStatus';
        return $this->parse();
    }

    /**
     * @test .
     * @return mixed
     */
    public function getDevBlog()
    {
        $this->url = $this->language.Routes::LODESTONE_DEV_BLOG;
        $this->type = 'DevBlog';
        return $this->parse();
    }

    /**
     * Get params from: http://eu.finalfantasyxiv.com/lodestone/ranking/thefeast/
     *
     * @test .
     * @param bool $season
     * @param array $params
     * @return array
     */
    public function getFeast(int $season = 0, string $dcgroup = '', string $rank_type = 'all')
    {
        if ($season == 0) {
            $season = '';
        }
        $query = $this->queryBuilder([
            'dcgroup' => $dcgroup,
            'rank_type' => $rank_type,
        ]);
        $this->url = sprintf($this->language.Routes::LODESTONE_FEAST, strval($season)).$query;
        $this->type = 'Feast';
        return $this->parse();
    }

    /**
     * Get params from: http://eu.finalfantasyxiv.com/lodestone/ranking/deepdungeon/
     *
     * @test .
     * @param array $params
     * @return array
     */
    public function getDeepDungeon(int $id = 1, string $dcgroup = '', string $solo_party = 'party', string $subtype = '')
    {
        if ($id == 1) {
            $id = '';
        }
        if ($subtype) {
            $solo_party = 'solo';
        }
        $query = $this->queryBuilder([
            'dcgroup' => $dcgroup,
            'solo_party' => $solo_party,
            'subtype' => $subtype,
        ]);
        $this->url = sprintf($this->language.Routes::LODESTONE_DEEP_DUNGEON, strval($id)).$query;
        $this->type = 'DeepDungeon';
        return $this->parse();
    }
}
?>