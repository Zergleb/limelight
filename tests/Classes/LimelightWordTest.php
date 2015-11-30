<?php

namespace Limelight\tests\Classes;

use Limelight\Limelight;
use Limelight\Config\Config;
use Limelight\Tests\TestCase;

class LimelightWordTest extends TestCase
{
    /**
     * @var Limelight\Limelight
     */
    protected static $limelight;

    /**
     * @var Limelight\Classes\LimelightResults
     */
    protected static $results;

    /**
     * Set static limelight on object.
     */
    public static function setUpBeforeClass()
    {
        self::$limelight = new Limelight();

        self::$results = self::$limelight->parse('東京に行って、パスタを食べてしまった。おいしかったです！');
    }

    /**
     * Plugin data can be called by method.
     *
     * @test
     */
    public function it_can_get_plugin_data_by_method_call()
    {
        $romanji = self::$results->findIndex(0)->romanji();

        $this->assertEquals('Tōkyō', $romanji);
    }

    /**
     * Plugin data can be called by property name.
     *
     * @test
     */
    public function it_can_get_plugin_data_by_property_call()
    {
        $romanji = self::$results->findIndex(0)->romanji;

        $this->assertEquals('Tōkyō', $romanji);
    }

    /**
     * It can get properties by property name.
     *
     * @test
     */
    public function it_can_get_property_by_property_name()
    {
        $word = self::$results->findIndex(0)->word;

        $this->assertEquals('東京', $word);
    }

    /**
     * It prints info when printed or echoed.
     *
     * @test
     */
    public function it_can_prints_info_when_printed()
    {
        $results = self::$results;

        ob_start();

        echo $results;

        $output = ob_get_contents();

        ob_end_clean();

        $this->assertContains('東京', $output);
    }

    /**
     * It can get raw mecab data off object.
     *
     * @test
     */
    public function it_can_get_raw_mecab_data()
    {
        $rawMecab = self::$results->findIndex(0)->rawMecab();

        $this->assertEquals('東京', $rawMecab[0]['literal']);
    }

    /**
     * It can get word off object.
     *
     * @test
     */
    public function it_can_get_word()
    {
        $word = self::$results->findIndex(0)->word();

        $this->assertEquals('東京', $word);
    }

    /**
     * It can get lemma off object.
     *
     * @test
     */
    public function it_can_get_lemma()
    {
        $lemma = self::$results->findIndex(0)->lemma();

        $this->assertEquals('東京', $lemma);
    }

    /**
     * It can get reading off object.
     *
     * @test
     */
    public function it_can_get_reading()
    {
        $reading = self::$results->findIndex(0)->reading();

        $this->assertEquals('トウキョウ', $reading);
    }

    /**
     * It can get pronunciation off object.
     *
     * @test
     */
    public function it_can_get_pronunciation()
    {
        $pronunciation = self::$results->findIndex(0)->pronunciation();

        $this->assertEquals('トーキョー', $pronunciation);
    }

    /**
     * It can get partOfSpeech off object.
     *
     * @test
     */
    public function it_can_get_partOfSpeech()
    {
        $partOfSpeech = self::$results->findIndex(0)->partOfSpeech();

        $this->assertEquals('proper noun', $partOfSpeech);
    }

    /**
     * It can get grammar off object.
     *
     * @test
     */
    public function it_can_get_grammar()
    {
        $grammar = self::$results->findIndex(0)->grammar();

        $this->assertEquals(null, $grammar);
    }

    /**
     * It can get plugin data.
     *
     * @test
     */
    public function it_can_get_plugin_data()
    {
        $furigana = self::$results->findIndex(0)->plugin('Furigana');

        $this->AssertEquals('<ruby><rb>東京</rb><rp>(</rp><rt>とうきょう</rt><rp>)</rp></ruby>', $furigana);
    }

    /**
     * It can get convert to hiragana.
     *
     * @test
     */
    public function it_can_convert_to_hiragana()
    {
        $reading = self::$results->findIndex(0)->toHiragana()->reading();

        $this->assertEquals('とうきょう', $reading);
    }

    /**
     * It can get convert to katakana.
     *
     * @test
     */
    public function it_can_convert_to_katakana()
    {
        $pronunciation = self::$results->findIndex(8)->toKatakana()->word();

        $this->assertEquals('オイシカッタ', $pronunciation);
    }

    /**
     * It can get convert to romanji.
     *
     * @test
     */
    public function it_can_convert_to_romanji()
    {
        $pronunciation = self::$results->findIndex(8)->toRomanji()->word();

        $this->assertEquals('oishikatta', $pronunciation);
    }

    /**
     * It can get convert to furigana.
     *
     * @test
     */
    public function it_can_convert_to_furigana()
    {
        $pronunciation = self::$results->findIndex(6)->toFurigana()->lemma();

        $this->assertEquals('<ruby><rb>食</rb><rp>(</rp><rt>た</rt><rp>)</rp></ruby>べる', $pronunciation);
    }

    /**
     * It throws exception when plugin not registered.
     *
     * @test
     * @expectedException Limelight\Exceptions\PluginNotFoundException
     * @expectedExceptionMessage Plugin Romanji not found in config.php
     */
    public function it_throws_exception_when_plugin_not_registered()
    {
        $config = Config::getInstance();

        $config->erase('plugins', 'Romanji');

        $string = self::$results->toRomanji()->words();
    }

    /**
     * It can append to a property.
     *
     * @test
     */
    public function it_can_append_to_property()
    {
        $wordObject = self::$results->findIndex(0);

        $word = $wordObject->word;

        $this->assertEquals('東京', $word);

        $wordObject->appendTo('word', '市');

        $word = $wordObject->word;

        $this->assertEquals('東京市', $word);
    }

    /**
     * It can set partOfSpeech.
     *
     * @test
     */
    public function it_can_set_partOfSpeech()
    {
        $wordObject = self::$results->findIndex(0);

        $partOfSpeech = $wordObject->partOfSpeech;

        $this->assertEquals('proper noun', $partOfSpeech);

        $wordObject->setPartOfSpeech('test');

        $partOfSpeech = $wordObject->partOfSpeech;

        $this->assertEquals('test', $partOfSpeech);
    }

    /**
     * It can set plugin data.
     *
     * @test
     */
    public function it_can_set_plugin_data()
    {
        $wordObject = self::$results->findIndex(0);

        $romanji = $wordObject->romanji;

        $this->assertEquals('Tōkyō', $romanji);

        $wordObject->setPluginData('Romanji', 'test');

        $romanji = $wordObject->romanji;

        $this->assertEquals('test', $romanji);
    }
}