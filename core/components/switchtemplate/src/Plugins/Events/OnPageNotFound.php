<?php
/**
 * @package switchtemplate
 * @subpackage plugin
 */

namespace TreehillStudio\SwitchTemplate\Plugins\Events;

use modContentType;
use SwitchtemplateSettings;
use TreehillStudio\SwitchTemplate\Plugins\Plugin;

class OnPageNotFound extends Plugin
{
    /**
     * {@inheritDoc}
     * @return mixed|void
     */
    public function process()
    {
        if ($this->modx->context->get('key') !== 'mgr') {
            $requestUri = $_REQUEST[$this->modx->getOption('request_param_alias', null, 'q')];
            $requestName = substr($requestUri, 0, $this->strrposn($requestUri, '.', $this->switchtemplate->getOption('extension_dots', [], 2)));
            $requestExtension = substr($requestUri, $this->strrposn($requestUri, '.', $this->switchtemplate->getOption('extension_dots', [], 2)));

            if ($requestExtension &&
                $this->modx->getCount('SwitchtemplateSettings', [
                    'extension' => $requestExtension
                ])
            ) {
                /** @var modContentType $htmlContentType */
                $htmlContentType = $this->modx->getObject('modContentType', ['name' => 'HTML']);
                $htmlExtension = ($htmlContentType) ? $htmlContentType->get('file_extensions') : '.html';

                if (isset($this->modx->aliasMap[$requestName . $htmlExtension])) {
                    $id = $this->modx->aliasMap[$requestName . $htmlExtension];
                } elseif (isset($this->modx->aliasMap[$requestName . '/'])) {
                    $id = $this->modx->aliasMap[$requestName . '/'];
                } elseif ($requestName == 'index') {
                    $id = $this->modx->getOption('site_start');
                } else {
                    $id = 0;
                }
                if ($id) {
                    $modeKey = $this->switchtemplate->getOption('mode_key');
                    /** @var SwitchtemplateSettings $setting */
                    $setting = $this->modx->getObject('SwitchtemplateSettings', [
                        'extension' => $requestExtension
                    ]);
                    $_REQUEST[$modeKey] = $setting->get('key');

                    $this->switchtemplate->debugInfo = ['# Extension based template switch triggered with the extension "' . $requestExtension . '"'];

                    $this->modx->sendForward($id);
                }
            }
        }
    }

    /**
     * Get the position of the nth needle in the haystack from right 
     * 
     * @param $haystack
     * @param $needle
     * @param int $nth
     * @param int $offset
     * @return false|int
     */
    private function strrposn($haystack, $needle, $nth = 1, $offset = 0)
    {
        $x = 0;
        $len = strlen($haystack);
        for ($i = 0; $i < $nth; $i++) {
            $x = ($len >= $offset) ? strrpos($haystack, $needle, -$offset) : false;
            if ($x === false) {
                return false;
            }
            $offset = $len - $x + strlen($needle);
        }
        return $x;
    }
}
