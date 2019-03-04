<?php
namespace soysby\inflection\models;

use soysby\inflection\records\Inflection as InfRecords;

use Craft;
use craft\base\Model;

class Inflection extends Model
{
    // Constants
    // =========================================================================

    const TYPE_DECLENSION                                  = 'declension';
    const TYPE_PLURALIZATION                               = 'pluralization';

    const SECTION_ABNORMAL_EXCEPTIONS                      = 'abnormalExceptions';
    const SECTION_MASCULINE_WITH_SOFT                      = 'masculineWithSoft';
    const SECTION_MASCULINE_WITH_SOFT_AND_RUN_AWAY_VOWELS  = 'masculineWithSoftAndRunAwayVowels';
    const SECTION_IMMUTABLE_WORDS                          = 'immutableWords';
    const SECTION_NEUTER_EXCEPTIONS                        = 'neuterExceptions';
    const SECTION_GENITIVE_EXCEPTIONS                      = 'genitiveExceptions';
    const SECTION_RUN_AWAY_VOWELS_EXCEPTIONS               = 'runawayVowelsExceptions';

    // Public Properties
    // =========================================================================

    public $id;
    public $type;
    public $section;
    public $value;
    public $dateCreated;
    public $dateUpdated;

    // Public Methods
    // =========================================================================

    public function rules()
    {
        $typeRange = [
            self::TYPE_DECLENSION,
            self::TYPE_PLURALIZATION
        ];

        $sectionRange = [
            self::SECTION_ABNORMAL_EXCEPTIONS, self::SECTION_MASCULINE_WITH_SOFT,
            self::SECTION_MASCULINE_WITH_SOFT_AND_RUN_AWAY_VOWELS, self::SECTION_IMMUTABLE_WORDS,
            self::SECTION_NEUTER_EXCEPTIONS, self::SECTION_GENITIVE_EXCEPTIONS, self::SECTION_RUN_AWAY_VOWELS_EXCEPTIONS
        ];

        return [
            [['id'], 'number', 'integerOnly' => true],
            [['type'], 'in', 'range' => $typeRange],
            [['section'], 'in', 'range' => $sectionRange],
            [['value'], 'required'],
            [['value', 'type', 'section'], 'string', 'max' => 255],
        ];
    }

    /**
     * @param InfRecords $record
     *
     * @return Inflection
     */
    public static function createFromRecord(InfRecords $record)
    {
        $model              = new self();
        $model->id          = $record->id;
        $model->type        = $record->type;
        $model->section     = $record->section;
        $model->value       = $record->value;
        $model->dateCreated = $record->dateCreated;
        $model->dateUpdated = $record->dateUpdated;

        return $model;
    }

    public function getTypeList()
    {
        return [
            self::TYPE_DECLENSION    => Craft::t('inflection', 'Declension'),
            self::TYPE_PLURALIZATION => Craft::t('inflection', 'Pluralization'),
        ];
    }

    public function getSectionList()
    {
        return [
            self::SECTION_ABNORMAL_EXCEPTIONS                      => Craft::t('inflection', 'Abnormal exceptions'),
            self::SECTION_MASCULINE_WITH_SOFT                      => Craft::t('inflection', 'Masculine with soft'),
            self::SECTION_MASCULINE_WITH_SOFT_AND_RUN_AWAY_VOWELS  => Craft::t('inflection', 'Masculine with soft and run away vowels'),
            self::SECTION_IMMUTABLE_WORDS                          => Craft::t('inflection', 'Immutable words'),
            self::SECTION_NEUTER_EXCEPTIONS                        => Craft::t('inflection', 'Neuter exceptions'),
            self::SECTION_GENITIVE_EXCEPTIONS                      => Craft::t('inflection', 'Genitive exceptions'),
            self::SECTION_RUN_AWAY_VOWELS_EXCEPTIONS               => Craft::t('inflection', 'Run away vowels exceptions'),
        ];
    }

    public function getType()
    {
        $result = $this->getTypeList();

        return isset($result[$this->type]) ? $result[$this->type] : Craft::t('app', '*unknown*');
    }

    public function getSection()
    {
        $result = $this->getSectionList();

        return isset($result[$this->section]) ? $result[$this->section] : Craft::t('app', '*unknown*');
    }
}