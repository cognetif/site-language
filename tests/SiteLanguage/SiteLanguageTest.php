<?php
declare(strict_types=1);

use Cognetif\SiteLanguage\Exceptions\InvalidConfigurationException;
use Cognetif\SiteLanguage\SiteLanguage;
use PHPUnit\Framework\TestCase;

final class SiteLanguageTest extends TestCase
{
    /**@var SiteLanguage */
    private $siteLanguage;

    public function setUp(): void
    {
        $this->siteLanguage = new SiteLanguage();
    }

    /**
     * @param $accepted
     * @param $default
     * @dataProvider invalidParamsDataProvider
     * @throws InvalidConfigurationException
     */
    public function testWillGuardsCatchInvalidPropertyValues($accepted, $default): void
    {
        $this->siteLanguage = new SiteLanguage($accepted, $default);
        $this->expectException(InvalidConfigurationException::class);
        $this->siteLanguage->get();
    }

    /**
     * @param $accepted
     * @param $default
     * @dataProvider validParamsDataProvider
     * @throws InvalidConfigurationException
     */
    public function testWillGuardsLetPassValidPropertyValues($accepted, $default): void
    {
        $this->siteLanguage = new SiteLanguage($accepted, $default);
        $this->siteLanguage->get();
        $this->assertTrue(true);
    }

    /**
     * @param $accepted
     * @param $expected
     * @dataProvider paramAcceptedDataProvider
     */
    public function testCanSetAcceptedPropertyViaConstructor($accepted, $expected): void
    {

        if (is_null($expected)) {
            $this->expectException(TypeError::class);
        }

        $siteLanguage = new SiteLanguage($accepted, null);
        $this->assertEquals($accepted, $siteLanguage->getAccepted());
    }

    /**
     * @param $default
     * @param $expected
     * @dataProvider paramDefaultDataProvider
     */
    public function testCanSetDefaultPropertyViaConstructor($default, $expected): void
    {

        if (is_null($expected)) {
            $this->expectException(TypeError::class);
        }

        $siteLanguage = new SiteLanguage(null, $default);
        $this->assertEquals($default, $siteLanguage->getDefault());
    }

    public function testCanSetPropertiesWithSetters() : void {
        $this->siteLanguage->setAccepted(['de']);
        $this->assertEquals(['de'], $this->siteLanguage->getAccepted());

        $this->siteLanguage->setDefault('ch');
        $this->assertEquals('ch', $this->siteLanguage->getDefault());
    }

    public function testDetectsFirstMatchFromServerLangs(): void
    {
        $this->siteLanguage->setAccepted(['en','fr','de']);
        $this->siteLanguage->setDefault('ch');
        $this->assertEquals('en', $this->siteLanguage->get());
    }


    public function testDetectsMatchesLocale(): void
    {
        $this->siteLanguage->setAccepted(['fr','de']);
        $this->siteLanguage->setDefault('ch');
        $this->assertEquals('fr', $this->siteLanguage->get());
    }

    public function testDetectsMatchesLangInMiddle(): void
    {
        $this->siteLanguage->setAccepted(['de','zu']);
        $this->siteLanguage->setDefault('ch');
        $this->assertEquals('zu', $this->siteLanguage->get());
    }

    public function testNoMatchReturnsDefault(): void
    {
        $this->siteLanguage->setAccepted(['ru','fi']);
        $this->siteLanguage->setDefault('ch');
        $this->assertEquals('ch', $this->siteLanguage->get());
    }

    public function testServerAcceptsIsNullReturnsDefault():void {
            $_SERVER['HTTP_ACCEPT_LANGUAGE'] = null;
        $this->siteLanguage->setAccepted(['de','ch']);
        $this->siteLanguage->setDefault('zu');
        $this->assertEquals('zu', $this->siteLanguage->get());
    }

    /**
     * @return array
     */
    public function paramAcceptedDataProvider()
    {
        return [
            [['en', 'fr'], ['en', 'fr']],
            [['en'], ['en']],
            ['en', null],
            [123, null],
            [false, null],
            [null, null],
        ];
    }

    /**
     * @return array
     */
    public function paramDefaultDataProvider()
    {
        return [
            ['en', 'en'],
            [123, null],
            [false, null],
            [null, null],
        ];
    }

    /**
     * @return array
     */
    public function invalidParamsDataProvider()
    {
        return [
            [[], 'en'],
            ['str', 'en'],
            [123, 'en'],
            [null, 'en'],
            [true, 'en'],
            [false, 'en'],
            [['en'], 123],
            [['en'], ''],
            [['en'], null],
            [['en'], false],
            [['en'], true],
            [['en'], []],
            [['en'], ['fr']],
        ];

    }

    /**
     * @return array
     */
    public function validParamsDataProvider()
    {
        return [
            [['en'], 'fr'],
            [['en','fr'], '123'],
        ];
    }
}