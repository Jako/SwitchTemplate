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
            $requestUri = (string)($_REQUEST[$this->modx->getOption('request_param_alias', null, 'q')] ?? '');
            if ($requestUri === '') {
                return;
            }

            $setting = $this->getSettingByExtension($requestUri);
            if ($setting) {
                $requestExtension = $setting->get('extension');
                $requestName = substr($requestUri, 0, -strlen($requestExtension));

                /** @var modContentType $htmlContentType */
                $htmlContentType = $this->modx->getObject('modContentType', ['name' => 'HTML']);
                $htmlExtension = ($htmlContentType) ? $htmlContentType->get('file_extensions') : '.html';

                if ($requestName === 'index') {
                    $id = $this->modx->getOption('site_start');
                } else {
                    $id = $this->modx->findResource($requestName . $htmlExtension);
                    if (!$id) {
                        $id = $this->modx->findResource($requestName . '/');
                    }
                }
                if ($id) {
                    $modeKey = $this->switchtemplate->getOption('mode_key');
                    $_REQUEST[$modeKey] = $setting->get('key');

                    $this->switchtemplate->debugInfo = ['# Extension based template switch triggered with the extension "' . $requestExtension . '"'];

                    $this->modx->sendForward($id);
                }
            }
        }
    }

    /**
     * Get the SwitchTemplate setting with the longest extension the request URI ends with
     *
     * @param string $requestUri
     * @return SwitchtemplateSettings|null
     */
    private function getSettingByExtension($requestUri)
    {
        $c = $this->modx->newQuery('SwitchtemplateSettings');
        $c->where(['extension:!=' => '']);
        /** @var SwitchtemplateSettings[] $settings */
        $settings = $this->modx->getIterator('SwitchtemplateSettings', $c);

        $match = null;
        $matchLength = 0;
        foreach ($settings as $setting) {
            $extension = $setting->get('extension');
            $length = strlen($extension);
            if ($length > $matchLength && $length < strlen($requestUri) &&
                substr_compare($requestUri, $extension, -$length) === 0
            ) {
                $match = $setting;
                $matchLength = $length;
            }
        }
        return $match;
    }
}
