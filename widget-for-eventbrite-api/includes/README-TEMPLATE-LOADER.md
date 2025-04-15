# Template Loader for WordPress 6.8 Compatibility

## Overview

This implementation addresses compatibility issues with the Gamajo Template Loader in WordPress 6.8. WordPress 6.8 has changed how `$wp_query->query_vars` works, which affects the Gamajo Template Loader's ability to store and retrieve custom data for templates.

## Files Included

1. `class-simple-template-loader.php` - A new template loader that doesn't rely on `$wp_query->query_vars` for data passing
2. `class-compat-template-loader.php` - A compatibility layer that maintains the same interface as Gamajo_Template_Loader
3. `class-simple-template-loader-usage.php` - Documentation and examples for using the template loaders

## Migration Options

### Option 1: Use the Compatibility Loader (Easiest)

For minimal code changes, replace:

```php
$template_loader = new Compat_Template_Loader();
```

with:

```php
$template_loader = new Compat_Template_Loader();
```

This maintains the same interface but uses the new implementation internally.

### Option 2: Switch to Direct Data Passing

For more explicit data handling:

```php
$template_loader = new Simple_Template_Loader();
$template_data = array(
    'template_loader' => $template_loader,
    'events'          => $events,
    'args'            => $atts,
    // ... other data
);
$template_loader->get_template_part('template_name', null, $template_data);
```

## Template Compatibility

The Simple Template Loader supports both:

1. Array data (extracted to variables in the template)
2. Object data (accessible as `$data->property` in templates)

This maintains compatibility with existing templates that expect variables from Gamajo's extraction method or access properties of a `$data` object.

### Nested Template Support

A key feature of this implementation is support for nested template calls:

- Data is maintained across nested template calls automatically
- Templates can call other templates using `$data->template_loader->get_template_part()` without needing to pass data again
- This allows template hierarchies to work correctly, with all levels having access to the data

## Implementation Details

- `Simple_Template_Loader` passes data directly to templates without using `$wp_query->query_vars`
- `Compat_Template_Loader` provides the same interface as Gamajo_Template_Loader but uses the new implementation internally
- Both loaders maintain the same template path resolution logic for child theme > parent theme > plugin fallbacks

## Performance Benefits

The new implementation is more efficient as it:

1. Doesn't modify global WordPress state
2. Avoids potential conflicts with other plugins that might also use `$wp_query->query_vars`
3. Provides a cleaner data passing mechanism