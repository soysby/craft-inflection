<?php
namespace soysby\inflection\variables;

use soysby\inflection\Inflection;

class InflectionVariable
{
    public function getPluginName()
    {
        return Inflection::$plugin->getPluginName();
    }
}
