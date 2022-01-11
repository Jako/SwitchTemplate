<?php
/**
 * @package switchtemplate
 * @subpackage plugin
 */

namespace TreehillStudio\SwitchTemplate\Output;

use Exception;
use Lullabot\AMP\AMP;
use Lullabot\AMP\Validate\Scope;
use modContentType;
use modResource;
use SwitchtemplateSettings;
use xPDO;

class SwitchTemplateAmp extends SwitchTemplateOutput
{
    /**
     * @param modResource $resource
     * @param SwitchtemplateSettings $setting
     */
    public function run(&$resource, &$setting)
    {
        // Add size image tags
        $this->fixImageTags($resource->_output);

        // Relative URLs to absolute
        $resource->_output = preg_replace(
            '~(href|src)=(["\'])(?!#)(?!https?://)/?([^\2]*?)\2~i',
            '$1=$2' . $this->modx->getOption('site_url') . '$3$2',
            $resource->_output
        );

        if ($extension = $setting->get('extension')) {
            /** @var modContentType $htmlContentType */
            $htmlContentType = $this->modx->getObject('modContentType', ['name' => 'HTML']);
            $htmlExtension = ($htmlContentType) ? $htmlContentType->get('file_extensions') : '.html';

            // Replace extension
            $resource->_output = preg_replace(
                '~(href|src)=(["\'])([^\2]*?)' . preg_quote($htmlExtension, '~') . '(#?[^\2]*?)\2~i',
                '$1=$2$3' . $extension . '$4$2',
                $resource->_output
            );
            // Replace site url with amp version
            $resource->_output = preg_replace(
                '~(href|src)=(["\'])' . preg_quote($this->modx->getOption('site_url'), '~') . '(#[^\2]*?)?\2~i',
                '$1=$2' . $this->modx->getOption('site_url') . 'index' . $extension . '$3$2',
                $resource->_output
            );
        }

        // Set canonical link
        $url = '<link rel="canonical" href="' . $this->modx->makeUrl($resource->get('id'), '', '', 'full') . '" />';
        $resource->_output = preg_replace('/(\s+)(<title>.*?<\/title>)/i', '$1$2$1' . $url, $resource->_output);

        $amp = new AMP();
        try {
            $amp->loadHtml($resource->_output, [
                'scope' => Scope::HTML_SCOPE
            ]);
            $resource->_output = $amp->convertToAmpHtml();
        } catch (Exception $e) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Error converting to AMP: ' . $e->getMessage());
        }
    }

    /**
     * @param string $source
     */
    private function fixImageTags(&$source)
    {
        $imagetags = [];
        preg_match_all('/<img.*?src=([",\'])(.*?)\1.*?[^>]+>/i', $source, $imagetags);

        foreach ($imagetags[2] as $i => $imagename) {
            $imagetag = trim($imagetags[0][$i]);

            if (substr($imagename, 0, 7) != 'http://' && substr($imagename, 0, 8) != 'https://') {
                if ($dimensions = @getimagesize($this->modx->getOption('base_path') . urldecode(ltrim($imagename, '/')))) {

                    preg_match_all('/width=([",\'])([0-9%]+?)\1/i', $imagetag, $widths);
                    if (isset($widths[2][0])) {
                        $width = $widths[2][0];
                    } else {
                        preg_match_all('/style=".*?width:\s*([0-9%]+?)px/i', $imagetag, $widths);
                        $width = $widths[1][0] ?? false;
                    }
                    if ($width && substr($width, -1, 1) == '%') {
                        $percentWidth = substr($width, 0, -1);
                    } else {
                        $percentWidth = false;
                    }

                    preg_match_all('/height=([",\'])([0-9%]+?)\1/i', $imagetag, $heights);
                    if (isset($heights[2][0])) {
                        $height = $heights[2][0];
                    } else {
                        preg_match_all('/style=[",\'].*?height:\s*([0-9]+?)px/i', $imagetag, $heights);
                        $height = $heights[1][0] ?? false;
                    }

                    if ($height && substr($height, -1, 1) == '%') {
                        $percentHeight = substr($height, 0, -1);
                    } else {
                        $percentHeight = false;
                    }

                    if ($percentWidth && !$percentHeight) {
                        $width = round($dimensions[0] * $percentWidth / 100);
                        $height = ($height) ?: round($dimensions[1] * $percentWidth / 100);
                    } elseif (!$percentWidth && $percentHeight) {
                        $width = ($width) ?: round($dimensions[0] * $percentHeight / 100);
                        $height = round($dimensions[1] * $percentHeight / 100);
                    } elseif ($percentWidth && $percentHeight) {
                        $width = round($dimensions[0] * $percentWidth / 100);
                        $height = round($dimensions[1] * $percentHeight / 100);
                    } else {
                        $width = $dimensions[0];
                        $height = $dimensions[1];
                    }

                    $newtag = preg_replace('/(style=([",\']).*?)(width|height):\s*[0-9%]+?px;?.*?\2/i', '$1$2', $imagetag);
                    $newtag = preg_replace('/(width|height)=([",\'])([0-9%]+?)\2/i', '', $newtag);
                    $newtag = trim(substr($newtag, 0, -1)) . ' width="' . $width . '" height="' . $height . '"' . substr($newtag, -1, 1);
                    $newtag = str_replace('  ', ' ', $newtag);

                    $source = str_replace($imagetag, $newtag, $source);
                }
            }
        }
    }
}
