<?php
namespace soysby\inflection\components;

use soysby\inflection\models\Inflection as InfModel;
use soysby\inflection\Inflection as InflectionPlugin;

use morphos\Russian\NounDeclension;
use morphos\Russian\NounPluralization;

class Inflection extends \wapmorgan\yii2inflection\Inflection
{
    // Private variables
    // =========================================================================

    private $variations = [
        InfModel::TYPE_DECLENSION => [
            InfModel::SECTION_ABNORMAL_EXCEPTIONS => [NounDeclension::class, 'setAbnormalExceptions'],
            InfModel::SECTION_MASCULINE_WITH_SOFT => [NounDeclension::class, 'setMasculineWithSoft'],
            InfModel::SECTION_MASCULINE_WITH_SOFT_AND_RUN_AWAY_VOWELS => [NounDeclension::class, 'setMasculineWithSoftAndRunAwayVowels'],
            InfModel::SECTION_IMMUTABLE_WORDS => [NounDeclension::class, 'setImmutableWords'],
        ],
        InfModel::TYPE_PLURALIZATION => [
            InfModel::SECTION_ABNORMAL_EXCEPTIONS => [NounPluralization::class, 'setAbnormalExceptions'],
            InfModel::SECTION_NEUTER_EXCEPTIONS => [NounPluralization::class, 'setNeuterExceptions'],
            InfModel::SECTION_GENITIVE_EXCEPTIONS => [NounPluralization::class, 'setGenitiveExceptions'],
            InfModel::SECTION_IMMUTABLE_WORDS => [NounPluralization::class, 'setImmutableWords'],
            InfModel::SECTION_RUN_AWAY_VOWELS_EXCEPTIONS => [NounPluralization::class, 'setRunawayVowelsExceptions'],
        ],
    ];

    // Public Methods
    // =========================================================================

    public function init()
    {
        parent::init();

        $this->setVariations();
    }

    /**
     * Set inflection primary language
     * @param string $lang Language
     */
	public function setLanguage($lang)
	{
		$lang = str_replace('-', '_', $lang);
		parent::setLanguage($lang);
	}

    public function pluralize($countOrWord, $word = null)
    {
        return parent::pluralize($countOrWord, mb_strtolower($word, 'utf-8'));
    }

    public function pluralizes($count, $str = null, $animateness = false, $case = null, $delimeter = ' ')
    {
        $words = explode($delimeter, $str);

        $result = [$count];

        foreach ($words as $word) {
            $word = mb_strtolower($word, 'utf-8');

            switch ($this->language)
            {
                case 'en':
                    $result[] = \morphos\English\NounPluralization::pluralize($count, $word, $animateness, $case);
                    break;
                case 'ru':
                    $result[] = NounPluralization::pluralize($count, $word, $animateness, $case);
                    break;
            }
        }

        return implode($delimeter, $result);
    }

    // Private Methods
    // =========================================================================

	private function setVariations()
    {
        $inflections = InflectionPlugin::$plugin->inflection->getAllItems();

        foreach ($inflections as $inflection) {
            if (!array_key_exists($inflection->type, $this->variations)) {
                continue;
            }

            $sections = $this->variations[$inflection->type];

            if (!array_key_exists($inflection->section, $sections)) {
                continue;
            }

            $section = $sections[$inflection->section];
            $result = $this->getVariationValue($inflection->value);

            call_user_func($section, $result);
        }
    }

    private function getVariationValue(string $str)
    {
        $result = explode(':', $str);

        if (!array_key_exists(1, $result)) {
            return [$str];
        }

        list($name, $values) = $result;

        $variants = array_map('trim', explode(',', $values));

        return [$name => $variants];
    }
}
