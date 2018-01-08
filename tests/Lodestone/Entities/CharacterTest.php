<?php

namespace Lodestone\Tests\Validator;

use PHPUnit\Framework\TestCase;
use Lodestone\{
    Api, Entities\Character\CharacterProfile, Validator\Exceptions\HttpNotFoundValidationException, Validator\Exceptions\ValidationException
};

/**
 * Class CharacterTest
 * @package Lodestone\Tests\Validator
 */
class CharacterTest extends TestCase
{
    /**
     * Test a valid character (mine!),
     * very unlikely for any of this to change.
     */
    public function testValidCharacter()
    {
        $this->commonValidCharacter(730968, 'Premium Virtue', 'Phoenix');
        $this->commonValidCharacter(12252236, 'Jade Rain', 'Jenova');
        $this->commonValidCharacter(14261785, 'Snow Locaine', 'Jenova');
        $this->commonValidCharacter(14096803, 'Aerena Suroo', 'Jenova');
        $this->commonValidCharacter(12252236, 'Jade Rain', 'Jenova');
    }

    /**
     * Common method for ValidCharacter
     *
     * @param $id
     * @param $name
     * @param $server
     */
    private function commonValidCharacter($id, $name, $server)
    {
        $api = new Api();

        /** @var CharacterProfile $character */
        $character = $api->getCharacter($id);

        // basic
        self::assertEquals($character->getId(), $id);
        self::assertEquals($character->getName(), $name);
        self::assertEquals($character->getServer(), $server);

        // ensure some stuff always exists
        self::assertNotEmpty($character->getAvatar());
        self::assertNotEmpty($character->getPortrait());
        self::assertNotEmpty($character->getGuardian());
        self::assertNotEmpty($character->getCity());
        self::assertNotEmpty($character->getClan());
        self::assertNotEmpty($character->getGender());

        // active job
        self::assertNotEmpty($character->getActiveClassJob()->getClassName());
        self::assertTrue(is_numeric($character->getActiveClassJob()->getLevel()));
    }

    //
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    //

    /**
     * Test has no free company
     *
     * This does have the potential to fail if the character
     * joins a free company. Carefully select a character which
     * looks to have been offline for a very long time.
     */
    public function testValidCharacterWithNoFreeCompany()
    {
        $this->commonValidCharacterWithNoFreeCompany(3783177, 'Damasco Burks', 'Tiamat');
        $this->commonValidCharacterWithNoFreeCompany(4268543, 'Glacier Arrest', 'Masamune');
        $this->commonValidCharacterWithNoFreeCompany(12367201, 'A\'gnayax Bhen', 'Carbuncle');
        $this->commonValidCharacterWithNoFreeCompany(15725574, 'A\'hadi Okoye', 'Fenrir');
    }

    /**
     * Common method for ValidCharacterWithNoFreeCompany
     *
     * @param $id
     * @param $name
     * @param $server
     */
    private function commonValidCharacterWithNoFreeCompany($id, $name, $server)
    {
        $api = new Api();

        /** @var CharacterProfile $character */
        $character = $api->getCharacter($id);

        // basic
        self::assertEquals($character->getId(), $id);
        self::assertEquals($character->getName(), $name);
        self::assertEquals($character->getServer(), $server);

        // should have no free company
        self::assertTrue(!$character->getFreecompany());
    }

    //
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    //

    /**
     * Test has no grand company
     *
     * This does have the potential to fail if the character
     * joins a free company. Carefully select a character which
     * looks to have been offline for a very long time.
     */
    public function testValidCharacterWithNoGrandCompany()
    {
        $this->commonValidCharacterWithNoGrandCompany(12933634, "A' A'", 'Anima');
    }

    /**
     * Common method for ValidCharacterWithNoGrandCompany
     *
     * @param $id
     * @param $name
     * @param $server
     */
    private function commonValidCharacterWithNoGrandCompany($id, $name, $server)
    {
        $api = new Api();

        /** @var CharacterProfile $character */
        $character = $api->getCharacter($id);

        // basic
        self::assertEquals($character->getId(), $id);
        self::assertEquals($character->getName(), $name);
        self::assertEquals($character->getServer(), $server);

        // should have no free company
        self::assertTrue(!$character->getFreecompany());

        // should have no grand company
        self::assertTrue(!$character->getGrandcompany());
    }

    //
    // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    //

    /**
     * Test 404, this ID is likely to always 404
     * because it is very low and new characters alway
     * get a higher number
     */
    public function testCharacterNotFound()
    {
        $this->commonCharacterNotFound(3);
    }
    
    /**
     * Common method for NotFound
     *
     * @param $id
     * @throws HttpNotFoundValidationException
     */
    private function commonCharacterNotFound($id)
    {
        $api = new Api();

        // expect HttpNotFound to be thrown
        self::expectException(HttpNotFoundValidationException::class);

        try {
            /** @var CharacterProfile $character */
            $character = $api->getCharacter($id);
        } catch (HttpNotFoundValidationException $ex) {
            throw $ex;
        }
    }
}
