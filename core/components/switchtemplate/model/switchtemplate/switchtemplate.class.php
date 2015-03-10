<?php

/**
 * SwitchTemplate
 *
 * Copyright 2014-2015 by Thomas Jakobi <thomas.jakobi@partout.info>
 *
 * @package switchtemplate
 * @subpackage classfile
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
     * The class options
     * @var array $options
     */
    public $options = array();

    /**
     * A configuration array
     * @var boolean $fromCache
     */
    public $fromCache;

    /**
     * SwitchTemplate constructor
     *
     * @param modX $modx A reference to the modX instance.
     * @param array $options An array of options. Optional.
     */
    function __construct(modX &$modx, array $options = array())
    {
        $this->modx = &$modx;

        $this->modx->lexicon->load('switchtemplate:default');

        $corePath = $this->getOption('core_path', $options, $this->modx->getOption('core_path') . 'components/switchtemplate/');
        $assetsPath = $this->getOption('assets_path', $options, $this->modx->getOption('assets_path') . 'components/switchtemplate/');
        $assetsUrl = $this->getOption('assets_url', $options, $this->modx->getOption('assets_url') . 'components/switchtemplate/');

        $this->fromCache = false;

        // Load some default paths for easier management
        $this->options = array_merge(array(
            'namespace' => $this->namespace,
            'assetsPath' => $assetsPath,
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
            'imagesUrl' => $assetsUrl . 'images/',
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'chunksPath' => $corePath . 'elements/chunks/',
            'pagesPath' => $corePath . 'elements/pages/',
            'snippetsPath' => $corePath . 'elements/snippets/',
            'pluginsPath' => $corePath . 'elements/plugins/',
            'processorsPath' => $corePath . 'processors/',
            'templatesPath' => $corePath . 'templates/',
            'cachePath' => $this->modx->getOption('core_path') . 'cache/',
            'connectorUrl' => $assetsUrl . 'connector.php'
        ), $options);

        // Load parameters
        $this->options = array_merge($this->options, array(
            'mode_key' => $this->getOption('mode_key', $options, 'mode', true),
            'cache_resource_key' => $this->getOption('cache_resource_key', $options, $this->modx->getOption(xPDO::OPT_CACHE_KEY, null, 'resource'), true),
            'cache_resource_handler' => $this->getOption('cache_resource_handler', $options, $this->modx->getOption(xPDO::OPT_CACHE_HANDLER, null, 'xPDOFileCache'), true),
            'cache_resource_expires' => (integer)$this->getOption('cache_resource_expires', $options, $this->modx->getOption(xPDO::OPT_CACHE_EXPIRES, null, 0), true),
            'max_iterations' => (integer)$this->modx->getOption('parser_max_iterations', null, 10)
        ));

        $this->modx->addPackage('switchtemplate', $this->getOption('modelPath'));
        $this->modx->lexicon->load('switchtemplate:default');
    }

    /**
     * Get a local configuration option or a namespaced system setting by key.
     *
     * @param string $key The option key to search for.
     * @param array $options An array of options that override local options.
     * @param mixed $default The default value returned, if the option is not found locally or as a namespaced system setting.
     * @param bool $skipEmpty If true: use default value if option value is empty.
     * @return mixed The option value or the default value specified.
     */
    public function getOption($key, $options = array(), $default = null, $skipEmpty = false)
    {
        $option = $default;
        if (!empty($key) && is_string($key)) {
            if ($options !== null && array_key_exists($key, $options) && !($skipEmpty && empty($options[$key]))) {
                $option = $options[$key];
            } elseif (array_key_exists($key, $this->options) && !($skipEmpty && empty($options[$key]))) {
                $option = $this->options[$key];
            } elseif (array_key_exists("{$this->namespace}.{$key}", $this->modx->config)) {
                $option = $this->modx->getOption("{$this->namespace}.{$key}", null, $default, $skipEmpty);
            }
        }
        return $option;
    }

    /**
     * Switch the current template on the fly
     * @param string $mode request value used as cache identifier
     * @param SwitchtemplateSettings $setting A Switchtemplate setting
     * @return string The switched and parsed template
     */
    public function switchTemplate($mode, $setting)
    {
        $output = '';

        $include = ($setting->get('include') != '') ? explode(',', $setting->get('include')) : array();
        $exclude = ($setting->get('exclude') != '') ? explode(',', $setting->get('exclude')) : array();

        // get the current resource
        if (!$this->modx->resource) {
            $this->fromCache = true;
            $this->modx->resource = $this->modx->request->getResource($this->modx->resourceMethod, $this->modx->resourceIdentifier);
        }
        $resource = &$this->modx->resource;
        $resourceId = $this->modx->resource->get('id');

        // stop, if resource id is not in include list or in exclude list
        if ((count($include) && (!in_array($resourceId, $include))) ||
            (count($exclude) && (in_array($resourceId, $exclude)))
        ) {
            return null;
        }

        $fromCache = false;
        // if resource is cacheable and cache is enabled for this setting
        if ($resource->get('cacheable') && $setting->get('cache')) {
            // prepare cache settings
            $cachePageKey = $resource->getCacheKey() . '.' . $mode . '.' . md5(implode('', $this->modx->request->getParameters()));
            $cacheOptions = array(
                xPDO::OPT_CACHE_KEY => $this->getOption('cache_resource_key'),
                xPDO::OPT_CACHE_HANDLER => $this->getOption('cache_resource_handler'),
                xPDO::OPT_CACHE_EXPIRES => $this->getOption('cache_resource_expires'),
            );
            // retreive the output from cache
            if ($this->modx->getCacheManager()) {
                $cachedResource = $this->modx->cacheManager->get($cachePageKey, $cacheOptions);
                if ($cachedResource) {
                    // partial overwrite current resource data with cached data
                    $resource->fromArray($cachedResource['resource'], '', true, true, true);
                    $output = $cachedResource['resource']['content'];
                    if (isset($cachedResource['elementCache'])) {
                        $this->modx->elementCache = $cachedResource['elementCache'];
                    }
                    if (isset($cachedResource['sourceCache'])) {
                        $this->modx->sourceCache = $cachedResource['sourceCache'];
                    }
                    if($cachedResource['_jscripts']) {
                        $this->modx->jscripts = $cachedResource['resource']['_sjscripts'];
                    }
                    if($cachedResource['_sjscripts']) {
                        $this->modx->sjscripts = $cachedResource['resource']['_sjscripts'];
                    }
                    if($cachedResource['_loadedjscripts']) {
                        $this->modx->loadedjscripts = $cachedResource['resource']['_loadedjscripts'];
                    }
                    $fromCache = true;
                }
            }
        }

        // if cache not set
        if (!$fromCache) {
            switch (strtolower($setting->get('type'))) {
                // get the template chunk
                case 'chunk':
                    $chunkname = $setting->get('template');

                    // set variable chunkname
                    if ((strpos($chunkname, '[[') !== false)) {
                        $chunk = $this->modx->newObject('modChunk', array('name' => '{tmp}-' . uniqid()));
                        $chunk->setCacheable(false);
                        $tmpname = $chunk->process($resource->toArray(), $chunkname);
                        if ($this->modx->getObject('modChunk', array('name' => $tmpname))) {
                            $chunkname = $tmpname;
                        } else {
                            // Strip not processed placeholder
                            $re = '%\[\[(?:(?!\[\[).)+?\]\]%ms';
                            while (preg_match($re, $chunkname)) {
                                $chunkname = preg_replace($re, '', $chunkname);
                            }
                        }
                    }

                    if ($chunk = $this->modx->getObject('modChunk', array('name' => $chunkname))) {
                        // prepare current resource as placeholder for templated output
                        $ph = $resource->toArray();

                        // prepare current resource template variable values as placeholder for templated output
                        $tvs = $resource->getMany('TemplateVars');
                        foreach ($tvs as $tv) {
                            $tvName = $tv->get('name');
                            $tv->getValue($this->modx->resourceIdentifier);
                            $ph[$tvName] = $tv->renderOutput($this->modx->resourceIdentifier);
                            $this->modx->setPlaceholder($tvName, $ph[$tvName]);
                        }
                        $chunk->setCacheable(false);
                        $output = $chunk->process($ph);
                    } else {
                        $this->modx->log(xPDO::LOG_LEVEL_ERROR, $this->modx->lexicon('switchtemplate.chunk_not_found', array('name' => $setting->get('template'))), '', 'SwitchTemplate');
                    }
                    break;
                // get the template
                case 'template':
                default:
                    if ($template = $this->modx->getObject('modTemplate', array('templatename' => $setting->get('template')))) {
                        // get the template content
                        $output = $template->get('content');
                    } else {
                        $this->modx->log(xPDO::LOG_LEVEL_ERROR, $this->modx->lexicon('switchtemplate.template_not_found', array('name' => $setting->get('template'))), '', 'SwitchTemplate');
                    }
                    break;
            }
            // parse cacheable elements
            $this->modx->getParser()->processElementTags('', $output, false, false, '[[', ']]', array(), $this->getOption('max_iterations'));

            if ($resource->get('cacheable') && $setting->get('cache') && $this->modx->getCacheManager() && !empty($output)) {
                // store not empty output in cache
                $cachedResource['resource'] = $resource->toArray();
                $cachedResource['resource']['content'] = $output;
                if (!empty($this->modx->elementCache)) {
                    $cachedResource['elementCache'] = $this->modx->elementCache;
                }
                if (!empty($this->modx->sourceCache)) {
                    $cachedResource['sourceCache'] = $this->modx->sourceCache;
                }
                if (!empty($resource->_sjscripts)) {
                    $cachedResource['resource']['_sjscripts']= $resource->_sjscripts;
                }
                if (!empty($resource->_jscripts)) {
                    $cachedResource['resource']['_jscripts']= $resource->_jscripts;
                }
                if (!empty($resource->_loadedjscripts)) {
                    $cachedResource['resource']['_loadedjscripts']= $resource->_loadedjscripts;
                }
                $this->modx->cacheManager->set($cachePageKey, $cachedResource, $this->getOption('cache_resource_expires'), $cacheOptions);
            }
        }
        // parse uncacheable elements
        $this->modx->getParser()->processElementTags('', $output, true, true, '[[', ']]', array(), $this->getOption('max_iterations'));

        return $output;
    }
}
