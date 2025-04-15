<?php
/**
 * Compatibility Template Loader for WordPress plugins
 * 
 * A wrapper around Simple_Template_Loader that maintains the same interface as Gamajo_Template_Loader
 * for backward compatibility.
 */

namespace WidgetForEventbriteAPI\Includes;

/**
 * Compatibility Template Loader
 * 
 * Extends Simple_Template_Loader to provide backward compatibility with Gamajo_Template_Loader
 */
class Compat_Template_Loader extends Simple_Template_Loader {
    /**
     * Store template data for the current template
     *
     * @var mixed
     */
    private $current_template_data = null;

    /**
     * Make custom data available to template.
     *
     * This method is kept for backward compatibility with the Gamajo_Template_Loader
     *
     * @param mixed  $data     Custom data for the template.
     * @param string $var_name Optional. Variable under which the custom data is available in the template.
     *                         Default is 'data'.
     *
     * @return Compat_Template_Loader
     */
    public function set_template_data($data, $var_name = 'data') {
        // Store the data for later use in get_template_part
        // If it's an array, convert it to stdClass object for backward compatibility
        if (is_array($data)) {
            $obj_data = new \stdClass();
            foreach ($data as $key => $value) {
                $obj_data->$key = $value;
            }
            $this->current_template_data = $obj_data;
        } else {
            $this->current_template_data = $data;
        }
        
        return $this;
    }

    /**
     * Retrieve a template part with the previously set data.
     *
     * Overrides the parent method to use the data set with set_template_data.
     *
     * @param string $slug Template slug.
     * @param string $name Optional. Template variation name. Default null.
     * @param mixed  $data Optional. Data to pass to template. Default null.
     * @param bool   $load Optional. Whether to load template. Default true.
     *
     * @return string
     */
    public function get_template_part($slug, $name = null, $data = null, $load = true) {
        // If data is null, use the data set with set_template_data
        if ($data === null && $this->current_template_data !== null) {
            $data = $this->current_template_data;
            // Do NOT reset the current_template_data, so it's available for nested calls
            // $this->current_template_data = null;
        }
        
        return parent::get_template_part($slug, $name, $data, $load);
    }

    /**
     * Remove access to custom data in template.
     *
     * This method is kept for backward compatibility with the Gamajo_Template_Loader
     * but does nothing in this implementation.
     *
     * @return Compat_Template_Loader
     */
    public function unset_template_data() {
        $this->current_template_data = null;
        return $this;
    }
}