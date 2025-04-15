<?php
/**
 * How to use the Simple Template Loader
 * This file is for documentation only - not used in the plugin
 */

namespace WidgetForEventbriteAPI\Includes;

// 1. First, include the appropriate template loader class
// For new code:
use WidgetForEventbriteAPI\Includes\Simple_Template_Loader;
// For backward compatibility with existing code:
use WidgetForEventbriteAPI\Includes\Compat_Template_Loader;

// 2. Create an instance
// For new code:
$template_loader = new Simple_Template_Loader();

// OR for backward compatibility with existing code (maintains set_template_data method):
$template_loader = new Compat_Template_Loader();

// 3. Prepare your data to pass to the template
// Method 1 (for Simple_Template_Loader): Pass data directly to get_template_part
$template_data = array(
    'template_loader' => $template_loader,
    'events'          => $events,
    'args'            => $atts,
    'template'        => strtolower( $theme->template ),
    'plugin_name'     => $this->plugin_name,
    'utilities'       => $this->utilities,
    'unique_id'       => uniqid(),
    'instance'        => $wfea_instance_counter,
    'event'           => new stdClass(),
);

// 4a. For Simple_Template_Loader: Get the template part, passing the data as the third parameter
$template_found = $template_loader->get_template_part('layout_name', null, $template_data);

// Method 2 (for Compat_Template_Loader): Set data first, then call get_template_part
// This maintains backward compatibility with code that uses Gamajo_Template_Loader
$compat_loader = new Compat_Template_Loader();
$compat_loader->set_template_data(
    array(
        'template_loader' => $compat_loader,
        'events'          => $events,
        'args'            => $atts,
        'template'        => strtolower( $theme->template ),
        'plugin_name'     => $this->plugin_name,
        'utilities'       => $this->utilities,
        'unique_id'       => uniqid(),
        'instance'        => $wfea_instance_counter,
        'event'           => new stdClass(),
    )
);

// 4b. For Compat_Template_Loader: Get the template part without passing data
// The loader will use the data set with set_template_data
$template_found = $compat_loader->get_template_part('layout_name');

/**
 * Examples of updating Frontend class:
 * 
 * 1. Replace this:
 *    $template_loader = new Compat_Template_Loader();
 *    $template_loader->set_template_data(
 *        array(
 *            'template_loader' => $template_loader,
 *            'events'          => $events,
 *            'args'            => $atts,
 *            'template'        => strtolower( $theme->template ),
 *            'plugin_name'     => $this->plugin_name,
 *            'utilities'       => $this->utilities,
 *            'unique_id'       => uniqid(),
 *            'instance'        => $wfea_instance_counter,
 *            'event'           => new stdClass(),
 *        )
 *    );
 *    $template_found = $template_loader->get_template_part('layout_' . $atts['layout']);
 * 
 * 2. With ONE of these options:
 * 
 *    // Option 1: Easiest way - use the compatibility loader
 *    // This maintains the same interface but uses the new implementation internally
 *    $template_loader = new Compat_Template_Loader();
 *    $template_loader->set_template_data(
 *        array(
 *            'template_loader' => $template_loader,
 *            'events'          => $events,
 *            'args'            => $atts,
 *            'template'        => strtolower( $theme->template ),
 *            'plugin_name'     => $this->plugin_name,
 *            'utilities'       => $this->utilities,
 *            'unique_id'       => uniqid(),
 *            'instance'        => $wfea_instance_counter,
 *            'event'           => new stdClass(),
 *        )
 *    );
 *    $template_found = $template_loader->get_template_part('layout_' . $atts['layout']);
 *
 *    // Option 2: Pass as array (extracts to variables in template)
 *    $template_loader = new Simple_Template_Loader();
 *    $template_data = array(
 *        'template_loader' => $template_loader,
 *        'events'          => $events,
 *        'args'            => $atts,
 *        'template'        => strtolower( $theme->template ),
 *        'plugin_name'     => $this->plugin_name,
 *        'utilities'       => $this->utilities,
 *        'unique_id'       => uniqid(),
 *        'instance'        => $wfea_instance_counter,
 *        'event'           => new stdClass(),
 *    );
 *    $template_found = $template_loader->get_template_part('layout_' . $atts['layout'], null, $template_data);
 *
 *    // Option 3: Pass as object (access as $data->property in template)
 *    $template_loader = new Simple_Template_Loader();
 *    $data = new stdClass();
 *    $data->template_loader = $template_loader;
 *    $data->events = $events;
 *    $data->args = $atts;
 *    $data->template = strtolower( $theme->template );
 *    $data->plugin_name = $this->plugin_name;
 *    $data->utilities = $this->utilities;
 *    $data->unique_id = uniqid();
 *    $data->instance = $wfea_instance_counter;
 *    $data->event = new stdClass();
 *    $template_found = $template_loader->get_template_part('layout_' . $atts['layout'], null, $data);
 *
 * Examples of updating Widget class:
 *
 * 1. Replace this:
 *    $template_loader = new Compat_Template_Loader();
 *    $template_loader->set_template_data( array(
 *        'template_loader' => $template_loader,
 *        'events'          => $events,
 *        'args'            => $instance,
 *        'template'        => strtolower( $theme->template ),
 *        'plugin_name'     => 'widget-for-eventbrite-api',
 *        'utilities'       => $this->utilities,
 *        'unique_id'       => uniqid(),
 *        'instance'        => $wfea_instance_counter,
 *        'event'           => new stdClass(),
 *    ) );
 *    $template_loader->get_template_part( $template );
 *
 * 2. With this:
 *    $template_loader = new Simple_Template_Loader();
 *    $template_data = array(
 *        'template_loader' => $template_loader,
 *        'events'          => $events,
 *        'args'            => $instance,
 *        'template'        => strtolower( $theme->template ),
 *        'plugin_name'     => 'widget-for-eventbrite-api',
 *        'utilities'       => $this->utilities,
 *        'unique_id'       => uniqid(),
 *        'instance'        => $wfea_instance_counter,
 *        'event'           => new stdClass(),
 *    );
 *    $template_loader->get_template_part( $template, null, $template_data );
 */