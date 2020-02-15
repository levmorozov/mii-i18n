<?php

namespace levmorozov\i18n;

use mii\core\Component;
use mii\core\Exception;

class Simple extends Component
{
    protected string $language;
    protected string $base_path;
    protected array $messages = [];

    public function init(array $config = []) : void {
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }

        if ($this->base_path) {
            $this->base_path = \Mii::resolve($this->base_path);
        } else {
            $this->base_path = \path('app') . '/messages';
        }

        if ($this->language === null) {
            $this->language = \Mii::$app->language;
        }
    }

    private function load() {

        if (!is_file($this->base_path . '/' . $this->language . '.php')) {

            list($lang, $country) = explode('-', $this->language);

            if (!is_file($this->base_path . '/' . $lang . '.php')) {
                throw new Exception();
            } else {
                $this->messages = require($this->base_path . '/' . $lang . '.php');
            }
        } else {
            $this->messages = require($this->base_path . '/' . $this->language . '.php');
        }
    }


    public function translate($string) {
        if (empty($this->messages))
            $this->load();

        return isset($this->messages[$string]) ? $this->messages[$string] : $string;
    }

}

