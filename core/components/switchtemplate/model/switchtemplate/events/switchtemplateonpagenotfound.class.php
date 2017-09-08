<?php

/**
 * @package switchtemplate
 * @subpackage plugin
 */
class SwitchTemplateOnPageNotFound extends SwitchTemplatePlugin
{
    public function run()
    {
        if ($this->modx->context->get('key') !== 'mgr') {
            $requestUri = trim(strtok($_SERVER['REQUEST_URI'], '?'), '/');
            $requestName = substr($requestUri, 0, $this->strrposn($requestUri, '.', $this->switchtemplate->getOption('extension_dots', array(), 2)));
            $requestExtension = substr($requestUri, $this->strrposn($requestUri, '.', $this->switchtemplate->getOption('extension_dots', array(), 2)));

            if ($requestExtension &&
                $this->modx->getCount('SwitchtemplateSettings', array(
                    'extension' => $requestExtension
                ))
            ) {
                /** @var modContentType $htmlContentType */
                $htmlContentType = $this->modx->getObject('modContentType', array('name' => 'HTML'));
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
                    $setting = $this->modx->getObject('SwitchtemplateSettings', array(
                        'extension' => $requestExtension
                    ));
                    $_REQUEST[$modeKey] = $setting->get('key');

                    $this->modx->sendForward($id);
                }
            }
        }
        return;
    }

    private function strrposn($haystack, $needle, $num = 1, $offset = 0)
    {
        $x = 0;
        $len = strlen($haystack);
        for ($i = 0; $i < $num; $i++) {
            $x = strrpos($haystack, $needle, -$offset);
            if ($x === false) {
                return false;
            }
            $offset = $len - $x + strlen($needle);
        }
        return $x;
    }
}
