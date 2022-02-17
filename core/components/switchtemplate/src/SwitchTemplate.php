<?php
/**
 * SwitchTemplate
 *
 * Copyright 2014-2022 by Thomas Jakobi <office@treehillstudio.com>
 *
 * @package switchtemplate
 * @subpackage classfile
 */

namespace TreehillStudio\SwitchTemplate;

use modX;
use SwitchtemplateSettings;
use TreehillStudio\SwitchTemplate\Output\SwitchTemplateOutput;
use xPDO;

/**
 * class SwitchTemplate
 */
class SwitchTemplate
{
    /**
     * A reference to the modX instance
     * @var modX $modx
     */
    public $modx;

    /**
     * The namespace
     * @var string $namespace
     */
    public $namespace = 'switchtemplate';

    /**
     * The package name
     * @var string $packageName
     */
    public $packageName = 'SwitchTemplate';

    /**
     * The version
     * @var string $version
     */
    public $version = '1.3.1';

    /**
     * The class options
     * @var array $options
     */
    public $options = [];

    /**
     * The debug info
     * @var array $debugInfo
     */
    public $debugInfo = [];

    /**
     * From cache status
     * @var bool
     */
    public $fromCache;

    /**
     * SwitchTemplate constructor
     *
     * @param modX $modx A reference to the modX instance.
     * @param array $options An array of options. Optional.
     */
    public function __construct(modX &$modx, $options = [])
    {
        $this->modx =& $modx;
        $this->namespace = $this->getOption('namespace', $options, $this->namespace);

        $corePath = $this->getOption('core_path', $options, $this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/' . $this->namespace . '/');
        $assetsPath = $this->getOption('assets_path', $options, $this->modx->getOption('assets_path', null, MODX_ASSETS_PATH) . 'components/' . $this->namespace . '/');
        $assetsUrl = $this->getOption('assets_url', $options, $this->modx->getOption('assets_url', null, MODX_ASSETS_URL) . 'components/' . $this->namespace . '/');
        $modxversion = $this->modx->getVersionData();

        // Load some default paths for easier management
        $this->options = array_merge([
            'namespace' => $this->namespace,
            'version' => $this->version,
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'vendorPath' => $corePath . 'vendor/',
            'chunksPath' => $corePath . 'elements/chunks/',
            'pagesPath' => $corePath . 'elements/pages/',
            'snippetsPath' => $corePath . 'elements/snippets/',
            'pluginsPath' => $corePath . 'elements/plugins/',
            'controllersPath' => $corePath . 'controllers/',
            'processorsPath' => $corePath . 'processors/',
            'templatesPath' => $corePath . 'templates/',
            'cachePath' => $this->modx->getOption('core_path') . 'cache/',
            'assetsPath' => $assetsPath,
            'assetsUrl' => $assetsUrl,
            'jsUrl' => $assetsUrl . 'js/',
            'cssUrl' => $assetsUrl . 'css/',
            'imagesUrl' => $assetsUrl . 'images/',
            'connectorUrl' => $assetsUrl . 'connector.php'
        ], $options);

        // Add default options
        $this->options = array_merge($this->options, [
            'cache_resource_expires' => intval($this->getOption('cache_resource_expires', $options, $this->modx->getOption(xPDO::OPT_CACHE_EXPIRES, null, 0))),
            'cache_resource_handler' => $this->getOption('cache_resource_handler', $options, $this->modx->getOption(xPDO::OPT_CACHE_HANDLER, null, 'xPDOFileCache')),
            'cache_resource_key' => $this->getOption('cache_resource_key', $options, $this->modx->getOption(xPDO::OPT_CACHE_KEY, null, 'resource')),
            'debug' => (bool)$this->getOption('debug', $options, false),
            'modxversion' => $modxversion['version'],
            'is_admin' => $this->modx->user && ($modx->hasPermission('settings') || $modx->hasPermission($this->namespace . '_settings')),
            'max_iterations' => intval($this->modx->getOption('parser_max_iterations', null, 10)),
            'mode_key' => $this->getOption('mode_key', $options, 'mode'),
            'mode_tv' => $this->getOption('mode_tv', $options, ''),
            'show_debug' => isset($_REQUEST['switchtemplate-debug']) && $_REQUEST['switchtemplate-debug'] == '1' && $this->getOption('allow_debug_info', null, false)
        ]);

        $this->modx->addPackage($this->namespace, $this->getOption('modelPath'));
        $lexicon = $this->modx->getService('lexicon', 'modLexicon');
        $lexicon->load($this->namespace . ':default');

        // Autoload composer classes
        require $this->getOption('vendorPath') . 'autoload.php';
    }

    /**
     * Get a local configuration option or a namespaced system setting by key.
     *
     * @param string $key The option key to search for.
     * @param array $options An array of options that override local options.
     * @param mixed $default The default value returned if the option is not found locally or as a
     * namespaced system setting; by default this value is null.
     * @return mixed The option value or the default value specified.
     */
    public function getOption($key, $options = [], $default = null)
    {
        $option = $default;
        if (!empty($key) && is_string($key)) {
            if ($options != null && array_key_exists($key, $options)) {
                $option = $options[$key];
            } elseif (array_key_exists($key, $this->options)) {
                $option = $this->options[$key];
            } elseif (array_key_exists("$this->namespace.$key", $this->modx->config)) {
                $option = $this->modx->getOption("$this->namespace.$key");
            }
        }
        return $option;
    }

    /**
     * Switch the current template on the fly
     *
     * @param SwitchtemplateSettings $setting A Switchtemplate setting
     * @return string The switched and parsed template
     */
    public function switchTemplate($setting)
    {
        $include = ($setting->get('include') != '') ? explode(',', $setting->get('include')) : [];
        $exclude = ($setting->get('exclude') != '') ? explode(',', $setting->get('exclude')) : [];

        // Get the current resource
        if (!$this->modx->resource) {
            // Prevent infinite loop in OnLoadWebPageCache
            $this->fromCache = true;

            $this->modx->resource = $this->modx->request->getResource($this->modx->resourceMethod, $this->modx->resourceIdentifier);
        }
        $resource = &$this->modx->resource;
        $resourceId = $resource->get('id');
        $resource->_output = '';

        if ($this->getOption('show_debug')) {
            $this->debugInfo[] = '# Resource ID: ' . $resourceId;
            $this->debugInfo[] = '# Used SwitchTemplate Setting: ' . print_r($setting->toArray(), true);
        }

        // Stop, if resource id is not in include list or in exclude list
        if ((count($include) && (!in_array($resourceId, $include))) ||
            (count($exclude) && (in_array($resourceId, $exclude)))
        ) {
            if ($this->getOption('show_debug')) {
                exit('<pre>' . implode("\n\n", $this->debugInfo));
            }
            return null;
        }
        // Stop, if mode name is not in mode_tv list value
        $tVName = $this->getOption('mode_tv');
        $tvValue = ($tVName) ? $resource->getTVValue($tVName) : '';
        $modes = explode(',', $tvValue);
        if ($tVName && ($tvValue == '' || !in_array($setting->get('key'), $modes))) {
            if ($this->getOption('show_debug')) {
                exit('<pre>' . implode("\n\n", $this->debugInfo));
            }
            return null;
        }
        // Stop if the template can't be switched/parsed
        if (is_null($this->parseTemplate($setting))) {
            if ($this->getOption('show_debug')) {
                exit('<pre>' . implode("\n\n", $this->debugInfo));
            }
            return null;
        }

        if ($this->getOption('show_debug')) {
            exit('<pre>' . implode("\n\n", $this->debugInfo));
        }

        return $resource->_output;
    }

    /**
     * Get variable template name
     *
     * @param string $name
     * @param string $type
     * @return mixed
     */
    private function getName($name, $type = 'chunk')
    {
        $resource = &$this->modx->resource;
        $chunk = $this->modx->newObject('modChunk', ['name' => '{tmp}-' . uniqid()]);
        $chunk->setCacheable(false);
        $tmpname = $chunk->process($resource->toArray(), $name);
        switch ($type) {
            case 'template':
                if ($this->modx->getObject('modTemplate', ['templatename' => $tmpname])) {
                    $name = $tmpname;
                }
                break;
            case 'chunk':
            default:
                if ($this->modx->getObject('modChunk', ['name' => $tmpname])) {
                    $name = $tmpname;
                }
                break;
        }
        // Strip not processed placeholder
        $name = $this->stripPlaceholder($name);

        return $name;
    }

    /**
     * Strip MODX placeholder
     *
     * @param string $string
     * @return string
     */
    private function stripPlaceholder($string)
    {
        $re = '%\[\[(?:(?!\[\[).)+?\]\]%ms';
        while (preg_match($re, $string)) {
            $string = preg_replace($re, '', $string);
        }
        return $string;
    }

    /**
     * Set timing placeholder
     */
    private function timingPlaceholder()
    {
        $resource =& $this->modx->resource;
        $totalTime = (microtime(true) - $this->modx->startTime);
        $queryTime = $this->modx->queryTime;
        $queries = isset ($this->modx->executedQueries) ? $this->modx->executedQueries : 0;
        $phpTime = $totalTime - $queryTime;
        $queryTime = sprintf("%2.4f s", $queryTime);
        $totalTime = sprintf("%2.4f s", $totalTime);
        $phpTime = sprintf("%2.4f s", $phpTime);
        $source = $this->modx->resourceGenerated ? "database" : "cache";
        $resource->_output = str_replace("[^q^]", $queries, $resource->_output);
        $resource->_output = str_replace("[^qt^]", $queryTime, $resource->_output);
        $resource->_output = str_replace("[^p^]", $phpTime, $resource->_output);
        $resource->_output = str_replace("[^t^]", $totalTime, $resource->_output);
        $resource->_output = str_replace("[^s^]", $source, $resource->_output);
    }

    /**
     * Register scripts in the output
     */
    public function registerScripts()
    {
        $resource =& $this->modx->resource;
        // Insert Startup jscripts & CSS scripts into template - template must have a </head> tag
        if (($js = $this->modx->getRegisteredClientStartupScripts()) && (strpos($resource->_output, '</head>') !== false)) {
            $resource->_output = preg_replace("/(<\/head>)/i", $js . "\n\\1", $resource->_output, 1);
        }

        // Insert jscripts & html block into template - template must have a </body> tag
        if (($js = $this->modx->getRegisteredClientScripts()) && (strpos($resource->_output, '</body>') !== false)) {
            $resource->_output = preg_replace("/(<\/body>)/i", $js . "\n\\1", $resource->_output, 1);
        }
    }

    /**
     * Parse the output template
     *
     * @param SwitchtemplateSettings $setting A Switchtemplate setting
     * @return null|object
     */
    private function parseTemplate($setting)
    {
        $resource =& $this->modx->resource;

        $this->modx->resourceGenerated = true;
        // Prepare cache settings
        $cachePageKey = $resource->getCacheKey() . '_' . $setting->get('key');
        $cacheOptions = [
            xPDO::OPT_CACHE_KEY => $this->getOption('cache_resource_key'),
            xPDO::OPT_CACHE_HANDLER => $this->getOption('cache_resource_handler'),
            xPDO::OPT_CACHE_EXPIRES => $this->getOption('cache_resource_expires'),
        ];
        // If resource is cacheable and cache is enabled for this setting
        if ($resource->get('cacheable') && $setting->get('cache')) {
            // Retreive the output from cache
            $this->getResourceCache($cachePageKey, $cacheOptions);
        }

        if (!$templateName = $setting->get('template')) {
            /** @var \modTemplate $template */
            $template = $this->modx->getObject('modTemplate', ['id' => $resource->get('template')]);
            if ($template) {
                $templateName = $template->get('templatename') . ' ' . $setting->get('name');
                if ($this->getOption('show_debug')) {
                    $this->debugInfo[] = '# Automatic selected template: ' . $templateName;
                }
            } else {
                // Fallback to normal resource
                $message = $this->modx->lexicon('switchtemplate.err_template_invalid');
                if ($this->getOption('debug')) {
                    $this->modx->log(xPDO::LOG_LEVEL_ERROR, $message, '', 'SwitchTemplate');
                }
                if ($this->getOption('show_debug')) {
                    $this->debugInfo[] = $message;
                }
                $resource = $this->modx->request->getResource('id', $resource->get('id'));
                return null;
            }
        }

        // If resource was not cached
        if ($this->modx->resourceGenerated) {
            switch (strtolower($setting->get('type'))) {
                // Get the template chunk
                case 'chunk':
                    // Set variable chunkname
                    $chunkname = $this->getName($templateName, 'chunk');

                    if ($chunk = $this->modx->getObject('modChunk', ['name' => $chunkname])) {
                        // Prepare current resource as placeholder for templated output
                        $ph = $resource->toArray();

                        // Prepare current resource template variable values as placeholder for templated output
                        /** @var \modTemplateVar[] $tvs */
                        $tvs = $resource->getMany('TemplateVars');
                        foreach ($tvs as $tv) {
                            $tvName = $tv->get('name');
                            $tv->getValue($resource->get('id'));
                            $ph[$tvName] = $tv->renderOutput($resource->get('id'));
                            $this->modx->setPlaceholder($tvName, $ph[$tvName]);
                        }
                        $chunk->setCacheable(false);
                        $resource->_output = $chunk->process($ph);
                    } else {
                        // Fallback to normal resource
                        $message = $this->modx->lexicon('switchtemplate.err_chunk_nf', ['name' => $templateName]);
                        if ($this->getOption('debug')) {
                            $this->modx->log(xPDO::LOG_LEVEL_ERROR, $message, '', 'SwitchTemplate');
                        }
                        if ($this->getOption('show_debug')) {
                            $this->debugInfo[] = '# ' . $message;
                        }
                        $resource = $this->modx->request->getResource('id', $resource->get('id'));
                        return null;
                    }
                    break;
                // Get the template
                case 'template':
                default:
                    // Set variable templatename
                    $templatename = $this->getName($templateName, 'template');

                    if ($template = $this->modx->getObject('modTemplate', ['templatename' => $templatename])) {
                        // Get the template content
                        $resource->_output = $template->process();
                    } else {
                        // Fallback to normal resource
                        $message = $this->modx->lexicon('switchtemplate.err_template_nf', ['name' => $templateName]);
                        if ($this->getOption('debug')) {
                            $this->modx->log(xPDO::LOG_LEVEL_ERROR, $message, '', 'SwitchTemplate');
                        }
                        if ($this->getOption('show_debug')) {
                            $this->debugInfo[] = $message;
                        }
                        $resource = $this->modx->request->getResource('id', $resource->get('id'));
                        return null;
                    }
                    break;
            }
            // Parse cacheable elements
            $this->modx->getParser()->processElementTags('', $resource->_output, false, false, '[[', ']]', [], $this->getOption('max_iterations'));

            $this->modx->invokeEvent('OnSwitchTemplateParsed', [
                'output' => & $resource->_output,
                'mode' => $setting->get('key'),
                'setting' => $setting,
                'uncached' => true
            ]);

            $this->modx->beforeRender();

            $this->modx->invokeEvent('OnWebPagePrerender');

            $this->timingPlaceholder();

            if ($resource->get('cacheable') && $setting->get('cache') && $this->modx->getCacheManager() && !empty($resource->_output)) {
                $this->setResourceCache($cachePageKey, $cacheOptions);
            }
        }
        // Parse uncacheable elements
        $this->modx->getParser()->processElementTags('', $resource->_output, true, true, '[[', ']]', [], $this->getOption('max_iterations'));

        $outputType = ($setting->get('output')) ? $setting->get('output') : 'html';
        $className = 'TreehillStudio\SwitchTemplate\Output\SwitchTemplate' . ucfirst($outputType);
        if (class_exists($className)) {
            /** @var SwitchTemplateOutput $handler */
            $handler = new $className($this->modx, $this->options);
            $handler->run($resource, $setting);
        } else {
            if ($this->getOption('debug')) {
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Output type not set.', '', 'SwitchtTemplate');
            }
        }

        return $resource;
    }

    /**
     * Get the resource cache
     *
     * @param string $cachePageKey
     * @param array $cacheOptions
     */
    private function getResourceCache($cachePageKey, $cacheOptions)
    {
        if ($this->modx->getCacheManager()) {
            $cachedResource = $this->modx->cacheManager->get($cachePageKey, $cacheOptions);
            if ($cachedResource) {
                // Partial overwrite current resource data with cached data
                $this->modx->resource->fromArray($cachedResource['resource'], '', true, true, true);
                $this->modx->resource->_output = $cachedResource['resource']['content'];
                if (isset($cachedResource['elementCache'])) {
                    $this->modx->elementCache = $cachedResource['elementCache'];
                }
                if (isset($cachedResource['sourceCache'])) {
                    $this->modx->sourceCache = $cachedResource['sourceCache'];
                }
                if (isset($cachedResource['_jscripts'])) {
                    $this->modx->jscripts = $cachedResource['resource']['_sjscripts'];
                }
                if (isset($cachedResource['_sjscripts'])) {
                    $this->modx->sjscripts = $cachedResource['resource']['_sjscripts'];
                }
                if (isset($cachedResource['_loadedjscripts'])) {
                    $this->modx->loadedjscripts = $cachedResource['resource']['_loadedjscripts'];
                }
                $this->modx->resourceGenerated = false;
            }
        }
    }

    /**
     * Set the resource cache
     *
     * @param string $cachePageKey
     * @param array $cacheOptions
     */
    private function setResourceCache($cachePageKey, $cacheOptions)
    {
        // Store not empty output in cache
        $cachedResource['resource'] = $this->modx->resource->toArray();
        $cachedResource['resource']['content'] = $this->modx->resource->_output;
        if (!empty($this->modx->elementCache)) {
            $cachedResource['elementCache'] = $this->modx->elementCache;
        }
        if (!empty($this->modx->sourceCache)) {
            $cachedResource['sourceCache'] = $this->modx->sourceCache;
        }
        if (!empty($this->modx->sjscripts)) {
            $cachedResource['resource']['_sjscripts'] = $this->modx->sjscripts;
        }
        if (!empty($this->modx->jscripts)) {
            $cachedResource['resource']['_jscripts'] = $this->modx->jscripts;
        }
        if (!empty($this->modx->loadedjscripts)) {
            $cachedResource['resource']['_loadedjscripts'] = $this->modx->loadedjscripts;
        }
        $this->modx->cacheManager->set($cachePageKey, $cachedResource, $this->getOption('cache_resource_expires'), $cacheOptions);
    }
}
