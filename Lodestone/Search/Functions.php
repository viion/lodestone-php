<?php
namespace Lodestone\Search;

use Lodestone\Modules\{
    Logging\Logger, Http\Routes, Http\HttpRequest
};

trait Functions {
    /**
     * @test Premium Virtue,Phoenix
     * @param $name
     * @param bool $server
     * @param bool $page
     * @return SearchCharacter
     */
    public function searchCharacter(string $name = '', string $server = '', string $classjob = '', string $race_tribe = '', string $gcid = '', string $blog_lang = '', string $order = '', int $page = 1)
    {
        $query = $this->queryBuilder([
            'q' => str_ireplace(' ', '+', $name),
            'worldname' => $server,
            'classjob' => $classjob,
            'race_tribe' => $race_tribe,
            'gcid' => $gcid,
            'blog_lang' => $blog_lang,
            'order' => $order,
            'page' => $page,
        ]);
        $this->url = sprintf($this->language.Routes::LODESTONE_CHARACTERS_SEARCH_URL.$query);
        $this->type = 'searchCharacter';
        return $this->parse();
    }

    /**
     * @test Equilibrium,Phoenix
     * @param $name
     * @param bool $server
     * @param bool $page
     * @return SearchFreeCompany
     */
    public function searchFreeCompany(string $name = '', string $server = '', int $character_count = 0, string $activities = '', string $roles = '', string $activetime = '', string $join = '', string $house = '', string $gcid = '', string $order = '', int $page = 1)
    {
        $query = $this->queryBuilder([
            'q' => str_ireplace(' ', '+', $name),
            'worldname' => $server,
            'character_count' => $character_count,
            'activities' => $activities,
            'roles' => $roles,
            'activetime' => $activetime,
            'join' => $join,
            'house' => $house,
            'gcid' => $gcid,
            'order' => $order,
            'page' => $page,
        ]);
        $this->url = sprintf($this->language.Routes::LODESTONE_FREECOMPANY_SEARCH_URL.$query);
        $this->type = 'searchFreeCompany';
        return $this->parse();
    }

    /**
     * @test Monster Hunt
     * @param $name
     * @param $server
     * @param $page
     * @return SearchLinkshell
     */
    public function searchLinkshell(string $name = '', string $server = '', int $character_count = 0, string $order = '', int $page = 1)
    {
        $query = $this->queryBuilder([
            'q' => str_ireplace(' ', '+', $name),
            'worldname' => $server,
            'character_count' => $character_count,
            'order' => $order,
            'page' => $page,
        ]);
        $this->url = sprintf($this->language.Routes::LODESTONE_LINKSHELL_SEARCH_URL.$query);
        $this->type = 'searchLinkshell';
        return $this->parse();
    }
    
    /**
     * @test Ankora
     * @param $name
     * @param $server
     * @param $page
     * @return SearchPvPTeam
     */
    public function searchPvPTeam(string $name = '', string $server = '', string $order = '', int $page = 1)
    {
        $query = $this->queryBuilder([
            'q' => str_ireplace(' ', '+', $name),
            'worldname' => $server,
            'order' => $order,
            'page' => $page,
        ]);
        $this->url = sprintf($this->language.Routes::LODESTONE_PVPTEAM_SEARCH_URL.$query);
        $this->type = 'searchPvPTeam';
        return $this->parse();
    }
}
?>