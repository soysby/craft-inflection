<?php
/**
 * Inflection plugin for Craft CMS 3.x
 *
 * Inflection with craftcms
 *
 * @copyright Copyright (c) 2019 Soys
 */

namespace soysby\inflection;

use soysby\inflection\base\PluginTrait;
use soysby\inflection\variables\InflectionVariable;

use Craft;
use craft\events\RegisterUrlRulesEvent;
use craft\web\UrlManager;
use craft\helpers\UrlHelper;
use craft\web\twig\variables\CraftVariable;

use yii\base\Event;

class Inflection extends \craft\base\Plugin
{
    // Public Properties
    // =========================================================================

    public $schemaVersion = '1.0.0';
    public $hasCpSettings = true;

    // Traits
    // =========================================================================

    use PluginTrait;

    // Public Methods
    // =========================================================================

    public function init()
    {
        parent::init();

        self::$plugin = $this;

        $this->_setAppComponents();
        $this->_setPluginComponents();
        $this->_registerCpRoutes();
        $this->_registerVariables();
    }

    public function getPluginName()
    {
        return Craft::t('inflection', $this->name);
    }

    public function getSettingsResponse()
    {
        Craft::$app->getResponse()->redirect(UrlHelper::cpUrl('inflection'));
    }

    // Private Methods
    // =========================================================================

    private function _registerCpRoutes()
    {
        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES, function(RegisterUrlRulesEvent $event) {
            $event->rules = array_merge($event->rules, [
                'inflection' => 'inflection/default/index',
                'inflection/create' => 'inflection/default/edit',
                'inflection/edit/<inflectionId:\d+>' => 'inflection/default/edit',
            ]);
        });
    }

    private function _registerVariables()
    {
        Event::on(CraftVariable::class, CraftVariable::EVENT_INIT, function(Event $event) {
            $event->sender->set('inflection', InflectionVariable::class);
        });
    }
}
