<?php

namespace Lodestone\Tests\Validator;

use PHPUnit\Framework\TestCase;
use Lodestone\{
    Api, Entities\Character\CharacterProfile, Validator\Exceptions\HttpNotFoundValidationException
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
        $this->commonValidCharacter(14261785, 'Annie Brooks', 'Jenova');
        $this->commonValidCharacter(14096803, 'Aerena Suroo', 'Jenova');
        $this->commonValidCharacter(12252236, 'Jade Rain', 'Jenova');
    }

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
        self::assertNotEmpty($character->getActiveClassJob()->getName());
        self::assertTrue(is_numeric($character->getActiveClassJob()->getLevel()));
    }

    /**
     * Test has no free company
     *
     * This does have the potential to fail if the character
     * joins a free company. Carefully select a character which
     * looks to have been offline for a very long time.
     */
    public function testValidCharacterWithNoFreeCompany()
    {
        $api = new Api();
        $id = 8;

        /** @var CharacterProfile $character */
        $character = $api->getCharacter($id);

        // basic
        self::assertEquals($character->getId(), $id);
        self::assertEquals($character->getName(), 'Tamago Explosion');
        self::assertEquals($character->getServer(), 'Aegis');

        // should have no free company
        self::assertTrue(!$character->getFreecompany());
    }

    /**
     * Test has no grand company
     *
     * This does have the potential to fail if the character
     * joins a free company. Carefully select a character which
     * looks to have been offline for a very long time.
     */
    public function testValidCharacterWithNoGrandCompany()
    {
        $api = new Api();
        $id = 12933634;

        /** @var CharacterProfile $character */
        $character = $api->getCharacter($id);

        // basic
        self::assertEquals($character->getId(), $id);
        self::assertEquals($character->getName(), "A' A'");
        self::assertEquals($character->getServer(), 'Anima');

        // should have no free company
        self::assertTrue(!$character->getFreecompany());

        // should have no grand company
        self::assertTrue(!$character->getGrandcompany());
    }

    /**
     * Test 404, this ID is likely to always 404
     * because it is very low and new characters alway
     * get a higher number
     */
    public function testCharacterNotFound()
    {
        $api = new Api();
        $id = 3;

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