<?php

/**
 * Simple Template Loader for WordPress plugins
 * 
 * A simple template loader that doesn't use $wp_query for data passing
 */
namespace WidgetForEventbriteAPI\Includes;

/**
 * Simple Template Loader
 * 
 * Loads template files with fallback through child theme > parent theme > plugin
 */
class Simple_Template_Loader {
    /**
     * Prefix for filter names.
     *
     * @var string
     */
    protected $filter_prefix = 'widget-for-eventbrite-api';

    /**
     * Directory name where custom templates for this plugin should be found in the theme.
     *
     * @var string
     */
    protected $theme_template_directory = 'widget-for-eventbrite-api';

    /**
     * Reference to the root directory path of this plugin.
     *
     * @var string
     */
    protected $plugin_directory = WIDGET_FOR_EVENTBRITE_API_PLUGIN_DIR;

    /**
     * Directory name where templates are found in this plugin.
     *
     * @var string
     */
    protected $plugin_template_directory = 'templates';

    /**
     * Internal cache for template paths
     *
     * @var array
     */
    private $template_path_cache = array();

    /**
     * Constructor - sets up template paths
     */
    public function __construct() {
        /**
         * @var \Freemius $wfea_fs Object for freemius.
         */
        global $wfea_fs;
        add_filter( $this->filter_prefix . '_template_paths', function ( $file_paths ) {
            if ( isset( $file_paths[1] ) ) {
                $file_paths[2] = trailingslashit( $file_paths[1] ) . 'parts';
                $file_paths[3] = trailingslashit( $file_paths[1] ) . 'loops';
                $file_paths[4] = trailingslashit( $file_paths[1] ) . 'scripts';
            }
            $file_paths[11] = trailingslashit( $file_paths[10] ) . 'parts';
            $file_paths[12] = trailingslashit( $file_paths[10] ) . 'loops';
            $file_paths[13] = trailingslashit( $file_paths[10] ) . 'scripts';
            $file_paths[20] = trailingslashit( dirname( WIDGET_FOR_EVENTBRITE_API_PLUGINS_TOP_DIR ) ) . 'widget-for-eventbrite-api';
            $file_paths[21] = trailingslashit( dirname( WIDGET_FOR_EVENTBRITE_API_PLUGINS_TOP_DIR ) ) . 'widget-for-eventbrite-api/parts';
            $file_paths[22] = trailingslashit( dirname( WIDGET_FOR_EVENTBRITE_API_PLUGINS_TOP_DIR ) ) . 'widget-for-eventbrite-api/loops';
            $file_paths[23] = trailingslashit( dirname( WIDGET_FOR_EVENTBRITE_API_PLUGINS_TOP_DIR ) ) . 'widget-for-eventbrite-api/scripts';
            global $wfea_fs;
            $file_paths[] = WIDGET_FOR_EVENTBRITE_API_PLUGIN_DIR . 'templates__free';
            $file_paths[] = WIDGET_FOR_EVENTBRITE_API_PLUGIN_DIR . 'templates__free/parts';
            $file_paths[] = WIDGET_FOR_EVENTBRITE_API_PLUGIN_DIR . 'templates__free/loops';
            $file_paths[] = WIDGET_FOR_EVENTBRITE_API_PLUGIN_DIR . 'templates__free/scripts';
            ksort( $file_paths );
            return $file_paths;
        }, 0 );
        add_filter(
            $this->filter_prefix . '_get_template_part',
            function ( $templates, $slug, $name ) {
                /**
                 * @var \Freemius $wfea_fs Object for freemius.
                 */
                global $wfea_fs;
                // also convert new format to legacy format to cover custom template
                if ( 'layout_widget' == $slug ) {
                    array_unshift( $templates, 'widget.php' );
                }
                return $templates;
            },
            0,
            3
        );
    }

    /**
     * Global template data that can be used across nested template calls
     *
     * @var mixed
     */
    private static $current_template_data = null;

    /**
     * Retrieve a template part.
     *
     * @param string $slug Template slug.
     * @param string $name Optional. Template variation name. Default null.
     * @param mixed  $data Optional. Data to pass to template (array or object). Default null to use current template data.
     * @param bool   $load Optional. Whether to load template. Default true.
     *
     * @return string
     */
    public function get_template_part(
        $slug,
        $name = null,
        $data = null,
        $load = true
    ) {
        // Execute code for this part.
        do_action( 'get_template_part_' . $slug, $slug, $name );
        do_action( $this->filter_prefix . '_get_template_part_' . $slug, $slug, $name );
        // Get files names of templates, for given slug and name.
        $templates = $this->get_template_file_names( $slug, $name );
        // If data is provided, store it for nested template calls
        if ( $data !== null ) {
            self::$current_template_data = $data;
        } else {
            if ( self::$current_template_data !== null ) {
                // Use the current template data if no data is provided
                $data = self::$current_template_data;
            } else {
                // Default to empty array if no data is provided and no current template data exists
                $data = array();
            }
        }
        // Return the part that is found.
        return $this->locate_template(
            $templates,
            $data,
            $load,
            false
        );
    }

    /**
     * Given a slug and optional name, create the file names of templates.
     *
     * @param string $slug Template slug.
     * @param string $name Template variation name.
     *
     * @return array
     */
    protected function get_template_file_names( $slug, $name ) {
        $templates = array();
        if ( isset( $name ) ) {
            $templates[] = $slug . '-' . $name . '.php';
        }
        $templates[] = $slug . '.php';
        /**
         * Allow template choices to be filtered.
         *
         * The resulting array should be in the order of most specific first, to least specific last.
         * e.g. 0 => recipe-instructions.php, 1 => recipe.php
         *
         * @param array  $templates Names of template files that should be looked for, for given slug and name.
         * @param string $slug      Template slug.
         * @param string $name      Template variation name.
         */
        return apply_filters(
            $this->filter_prefix . '_get_template_part',
            $templates,
            $slug,
            $name
        );
    }

    /**
     * Retrieve the name of the highest priority template file that exists.
     *
     * Searches in the STYLESHEETPATH before TEMPLATEPATH so that themes which
     * inherit from a parent theme can just overload one file. If the template is
     * not found in either of those, it looks in the theme-compat folder last.
     *
     * @param string|array $template_names Template file(s) to search for, in order.
     * @param array        $data           Optional. Data to pass to template. Default empty array.
     * @param bool         $load           If true the template file will be loaded if it is found.
     * @param bool         $require_once   Whether to require_once or require. Default true.
     *                                     Has no effect if $load is false.
     *
     * @return string The template filename if one is located.
     */
    public function locate_template(
        $template_names,
        $data = array(),
        $load = false,
        $require_once = true
    ) {
        // Use $template_names as a cache key - either first element of array or the variable itself if it's a string
        $cache_key = ( is_array( $template_names ) ? $template_names[0] : $template_names );
        // If the key is in the cache array, we've already located this file.
        if ( isset( $this->template_path_cache[$cache_key] ) ) {
            $located = $this->template_path_cache[$cache_key];
        } else {
            // No file found yet.
            $located = false;
            // Remove empty entries.
            $template_names = array_filter( (array) $template_names );
            $template_paths = $this->get_template_paths();
            // Try to find a template file.
            foreach ( $template_names as $template_name ) {
                // Trim off any slashes from the template name.
                $template_name = ltrim( $template_name, '/' );
                // Try locating this template file by looping through the template paths.
                foreach ( $template_paths as $template_path ) {
                    if ( file_exists( $template_path . $template_name ) ) {
                        $located = $template_path . $template_name;
                        // Store the template path in the cache
                        $this->template_path_cache[$cache_key] = $located;
                        break 2;
                    }
                }
            }
        }
        if ( $load && $located ) {
            // Handle data based on its type
            if ( is_array( $data ) && !empty( $data ) ) {
                // First, create a $data object for backward compatibility with templates
                // that expect to access properties via $data->property
                $data_obj = new \stdClass();
                foreach ( $data as $key => $value ) {
                    $data_obj->{$key} = $value;
                }
                $data = $data_obj;
                // Also extract variables for templates that expect individual variables
                extract( (array) $data, EXTR_SKIP );
            } elseif ( is_object( $data ) ) {
                // If it's an object, make it available as $data
                // This ensures backward compatibility with templates that use $data->property
                // Also extract variables for templates that expect individual variables
                extract( (array) $data, EXTR_SKIP );
            }
            if ( $require_once ) {
                require_once $located;
            } else {
                require $located;
            }
        }
        return $located;
    }

    /**
     * Return a list of paths to check for template locations.
     *
     * Default is to check in a child theme (if relevant) before a parent theme, so that themes which inherit from a
     * parent theme can just overload one file. If the template is not found in either of those, it looks in the
     * theme-compat folder last.
     *
     * @return array
     */
    protected function get_template_paths() {
        $theme_directory = trailingslashit( $this->theme_template_directory );
        $file_paths = array(
            10  => trailingslashit( get_template_directory() ) . $theme_directory,
            100 => $this->get_templates_dir(),
        );
        // Only add this conditionally, so non-child themes don't redundantly check active theme twice.
        if ( is_child_theme() ) {
            $file_paths[1] = trailingslashit( get_stylesheet_directory() ) . $theme_directory;
        }
        /**
         * Allow ordered list of template paths to be amended.
         *
         * @param array $var Default is directory in child theme at index 1, parent theme at 10, and plugin at 100.
         */
        $file_paths = apply_filters( $this->filter_prefix . '_template_paths', $file_paths );
        // Sort the file paths based on priority.
        ksort( $file_paths, SORT_NUMERIC );
        return array_map( 'trailingslashit', $file_paths );
    }

    /**
     * Return the path to the templates directory in this plugin.
     *
     * @return string
     */
    protected function get_templates_dir() {
        return trailingslashit( $this->plugin_directory ) . $this->plugin_template_directory;
    }

    /**
     * Get file paths for debugging
     *
     * @return array
     */
    public function get_file_paths() {
        return $this->get_template_paths();
    }

}
