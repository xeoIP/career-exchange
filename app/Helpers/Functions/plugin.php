<?php

/**
 * @param null $category
 * @param bool $checkInstalled
 * @return array
 */
function plugin_list($category = null, $checkInstalled = false)
{
    $plugins = [];

    // Load all Plugins Services Provider
    $list = \File::glob(config('larapen.core.plugin.path') . '*', GLOB_ONLYDIR);

    if (count($list) > 0) {
        foreach($list as $pluginPath) {
            // Get plugin folder name
            $pluginFolderName = strtolower(last(explode('/', $pluginPath)));

            // Get plugin details
            $plugin = load_plugin($pluginFolderName);
            if (empty($plugin)) {
                continue;
            }

            // Filter for category
            if (!is_null($category) && $plugin->category != $category) {
                continue;
            }

            // Check installed plugins
            try {
                $plugin->installed = call_user_func($plugin->class . '::installed');
            } catch (\Exception $e) {
                continue;
            }

            // Filter for installed plugins
            if ($checkInstalled && $plugin->installed != true) {
                continue;
            }

            $plugins[$plugin->name] = $plugin;
        }
    }

    return $plugins;
}

/**
 * @param null $category
 * @return array
 */
function plugin_installed_list($category = null)
{
    return plugin_list($category, true);
}

/**
 * Get the plugin details
 * @param $name
 * @return null
 */
function load_plugin($name)
{
    try {
        // Get the plugin init data
        $pluginFolderPath = plugin_path($name);
        $pluginData = file_get_contents($pluginFolderPath . '/init.json');
        $pluginData = json_decode($pluginData);

        // Plugin details
        $plugin = [
            'name'          => $pluginData->name,
            'version'       => $pluginData->version,
            'display_name'  => $pluginData->display_name,
            'description'   => $pluginData->description,
            'author'        => $pluginData->author,
            'category'      => $pluginData->category,
            'installed'     => null,
            'has_installer' => (isset($pluginData->has_installer) && $pluginData->has_installer == true) ? true : false,
            'provider'      => plugin_namespace($pluginData->name, ucfirst($pluginData->name) . 'ServiceProvider'),
            'class'         => plugin_namespace($pluginData->name, ucfirst($pluginData->name)),
        ];
        $plugin = \App\Helpers\Arr::toObject($plugin);

    } catch (\Exception $e) {
        $plugin = null;
    }

    return $plugin;
}

/**
 * Get the plugin details (Only if it's installed)
 * @param $name
 * @return null
 */
function load_installed_plugin($name)
{
    $plugin = load_plugin($name);
    if (empty($plugin)) {
        return null;
    }

    if (isset($plugin->has_installer) && $plugin->has_installer) {
        try {
            $installed = call_user_func($plugin->class . '::installed');
            return ($installed) ? $plugin : null;
        } catch (\Exception $e) {
            return null;
        }
    } else {
        return $plugin;
    }
}

/**
 * @param $pluginFolderName
 * @param $localNamespace
 * @return string
 */
function plugin_namespace($pluginFolderName, $localNamespace = null)
{
    if (!is_null($localNamespace)) {
        return config('larapen.core.plugin.namespace') . $pluginFolderName . '\\' . $localNamespace;
    } else {
        return config('larapen.core.plugin.namespace') . $pluginFolderName;
    }
}

/**
 * Get a file of the plugin
 * @param $pluginFolderName
 * @param $localPath
 * @return string
 */
function plugin_path($pluginFolderName, $localPath = null)
{
    return config('larapen.core.plugin.path') . $pluginFolderName . '/' . $localPath;
}

/**
 * Check if plugin exists
 * @param $pluginFolderName
 * @param null $path
 * @return mixed
 */
function plugin_exists($pluginFolderName, $path = null)
{
    $fullPath = config('larapen.core.plugin.path') . $pluginFolderName . '/' . $path;

    return \File::exists($fullPath);
}

/**
 * Get plugins settings values (with HTML)
 * @param $setting
 * @param $out
 * @return mixed
 */
function plugin_setting_value_html($setting, $out)
{
    $plugins = plugin_installed_list();
    if (!empty($plugins)) {
        foreach($plugins as $key => $plugin) {

            $pluginMethodNames = preg_grep('#^get(.+)ValueHtml$#', get_class_methods($plugin->class));

            if (!empty($pluginMethodNames)) {
                foreach($pluginMethodNames as $method) {
                    try {
                        $out = call_user_func($plugin->class . '::' . $method, $setting, $out);
                        return $out;
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }
        }
    }

    return $out;
}

/**
 * Set plugins settings values
 * @param $value
 * @param $setting
 * @return bool|mixed
 */
function plugin_set_setting_value($value, $setting)
{
    $plugins = plugin_installed_list();
    if (!empty($plugins)) {
        foreach($plugins as $key => $plugin) {

            $pluginMethodNames = preg_grep('#^set(.+)Value$#', get_class_methods($plugin->class));

            if (!empty($pluginMethodNames)) {
                foreach($pluginMethodNames as $method) {
                    try {
                        $value = call_user_func($plugin->class . '::' . $method, $value, $setting);
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }
        }
    }

    return $value;
}
