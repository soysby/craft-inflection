<?php
namespace soysby\inflection\records;

use craft\db\ActiveRecord;

class Inflection extends ActiveRecord
{
    // Public Methods
    // =========================================================================

    public static function tableName(): string
    {
        return '{{%inflection}}';
    }
}
