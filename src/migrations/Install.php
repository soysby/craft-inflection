<?php

namespace soysby\inflection\migrations;

use Craft;
use craft\db\Migration;

/**
 * Install migration.
 */
class Install extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTables();
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->removeTables();
    }

    public function createTables()
    {
        $this->createTable('{{%inflection}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string()->notNull(),
            'section' => $this->string()->notNull(),
            'value' => $this->string()->notNull(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
        ]);
    }

    public function removeTables()
    {
        $this->dropTableIfExists('{{%inflection}}');
    }
}
