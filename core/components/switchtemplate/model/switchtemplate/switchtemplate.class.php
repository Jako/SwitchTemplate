<?php

/**
 * SwitchTemplate
 *
 * Copyright 2014 by Thomas Jakobi <thomas.jakobi@partout.info>
 *
 * @package switchtemplate
 * @subpackage classfile
 */
class SwitchTemplate
{
    /**
     * A reference to the modX instance
     * @var modX $this ->modx
     */
    public $modx;

    /**
     * A configuration array
     * @var array $config
     */
    public $config;

    /**
     * A configuration array
     * @var boolean $fromCache
     */
    public $fromCache;

    /**
     * SwitchTemplate constructor
     *
     * @param modX $this ->modx A reference to the modX instance.
     * @param array $config An array of configuration options. Optional.
     */
    function __construct(modX $modx, array $config = array())
    {
        $this->modx = &$modx;

        $this->modx->lexicon->load('switchtemplate:default');

        $this->fromCache = false;

        $corePath = realpath($this->modx->getOption('switchtemplate.core_path', $config, $this->modx->getOption('core_path') . 'components/switchtemplate/')) . '/';
        $assetsPath = realpath($this->modx->getOption('switchtemplate.assets_path', $config, $this->modx->getOption('assets_path') . 'components/switchtemplate/')) . '/';
        $assetsUrl = $this->modx->getOption('switchtemplate.assets_url', $config, $this->modx->getOption('assets_url') . 'components/switchtemplate/');

        // Load some default paths for easier management
        $this->config = array(
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
            'processorsPath' => $corePath . 'processors/',
            'templatesPath' => $corePath . 'templates/',
            'hooksPath' => $corePath . 'hooks/',
            'cachePath' => $assetsPath . 'cache/',
            'cacheUrl' => $assetsUrl . 'cache/',
            'jsUrl' => $assetsUrl . 'js/',
            'cssUrl' => $assetsUrl . 'css/',
            'assetsUrl' => $assetsUrl,
            'connectorUrl' => $assetsUrl . 'connector.php',
        );

        // Load parameters
        $this->config = array_merge($this->config, array(
            'mode_key' => $this->modx->getOption('switchtemplate.mode_key', $config, 'mode', true),
            'cache_resource_key' => $this->modx->getOption('switchtemplate.cache_resource_key', $config, $this->modx->getOption(xPDO::OPT_CACHE_KEY, null, 'resource'), true),
            'cache_resource_handler' => $this->modx->getOption('switchtemplate.cache_resource_handler', $config, $this->modx->getOption(xPDO::OPT_CACHE_HANDLER, null, 'xPDOFileCache'), true),
            'cache_resource_expires' => (integer)$this->modx->getOption('switchtemplate.cache_resource_expires', $config, $this->modx->getOption(xPDO::OPT_CACHE_EXPIRES, null, 0), true),
            'max_iterations' => (integer)$this->modx->getOption('parser_max_iterations', null, 10)
        ));

        $this->modx->addPackage('switchtemplate', $this->config['modelPath']);
        $this->modx->lexicon->load('switchtemplate:default');
    }

    /**
     * Switch the current template on the fly
     * @param $templates object of SwitchTemplate configurations
     * @return string The switched and parsed template
     */
    public function switchTemplate($mode, $templates)
    {
        $output = '';

        $include = ($templates->get('include') != '') ? explode(',', $templates->get('include')) : array();
        $exclude = ($templates->get('exclude') != '') ? explode(',', $templates->get('exclude')) : array();

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
        if ($resource->get('cacheable') && $templates->get('cache')) {
            // prepare cache settings
            $cachePageKey = $resource->getCacheKey() . '.' . $mode . '.' . md5(implode('', $this->modx->request->getParameters()));
            $cacheOptions = array(
                xPDO::OPT_CACHE_KEY => $this->config['cache_resource_key'],
                xPDO::OPT_CACHE_HANDLER => $this->config['cache_resource_handler'],
                xPDO::OPT_CACHE_EXPIRES => $this->config['cache_resource_expires'],
            );
            // retreive the output from cache
            if ($this->modx->getCacheManager()) {
                $cachedResource = $this->modx->cacheManager->get($cachePageKey, $cacheOptions);
                if ($cachedResource) {
                    // partial overwrite current resource data with cached data
                    $output = $cachedResource['content'];
                    if (isset($cachedResource['elementCache'])) $this->modx->elementCache= $cachedResource['elementCache'];
                    $resource->setProcessed(true);
                    $fromCache = true;
                }
            }
        }

        // if cache or not set
        if (!$fromCache) {
            switch (strtolower($templates->get('type'))) {
                // get the template chunk
                case 'chunk':
                    if ($chunk = $this->modx->getObject('modChunk', array('name' => $templates->get('template')))) {
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
                        $this->modx->log(xPDO::LOG_LEVEL_ERROR, $this->modx->lexicon('switchtemplate.chunk_not_found', array('name' => $templates->get('template'))), '', 'SwitchTemplate');
                    }
                    break;
                // get the template
                case 'template':
                default:
                    if ($template = $this->modx->getObject('modTemplate', array('templatename' => $templates->get('template')))) {
                        // get the template content
                        $output = $template->get('content');
                    } else {
                        $this->modx->log(xPDO::LOG_LEVEL_ERROR, $this->modx->lexicon('switchtemplate.template_not_found', array('name' => $templates->get('template'))), '', 'SwitchTemplate');
                    }
                    break;
            }
            // parse cacheable elements
            $this->modx->getParser()->processElementTags('', $output, false, false, '[[', ']]', array(), $this->config['max_iterations']);

            if ($resource->get('cacheable') && $templates->get('cache') && $this->modx->getCacheManager() && !empty($output)) {
                // store not empty output in cache
                $cachedResource = array(
                    'output' => $output,
                    'elementCache' => $this->modx->elementCache
                );
                $this->modx->cacheManager->set($cachePageKey, $output, $this->config['cache_resource_expires'], $cacheOptions);
            }
        }
        // parse uncacheable elements
        $this->modx->getParser()->processElementTags('', $output, true, true, '[[', ']]', array(), $this->config['max_iterations']);

        return $output;
    }
}
