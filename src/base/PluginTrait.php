<?php
namespace soysby\inflection\base;

use Craft;

trait PluginTrait
{
    // Static Properties
    // =========================================================================
    public static $plugin;

    // Private Methods
    // =========================================================================

    private function _setPluginComponents()
    {
        $this->setComponents([
            'inflection' => \soysby\inflection\services\Inflection::class,
        ]);
    }

    private function _setAppComponents()
    {
        Craft::$app->setComponents([
            'inflection' => \soysby\inflection\components\Inflection::class,
        ]);
    }
}