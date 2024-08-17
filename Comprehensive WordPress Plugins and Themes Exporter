<?php
/**
 * This code snippet adds a tool to the WordPress admin area under the "Tools" menu. The tool
 * allows users to select and export a detailed HTML report of all installed plugins and themes,
 * including their active/inactive status. The export also includes additional metadata, such as 
 * the date and time of export, WordPress version, and the total number of themes and plugins exported.
 * This tool is ideal for documenting and sharing your WordPress environment configuration with
 * developers or maintenance or migration purposes.
 * 
 * Add a menu under Tools named "Export Plugins and Themes" with options to export in HTML.
 */

// Add the "Export Plugins and Themes" option to the Tools menu
function export_plugins_and_themes_menu() {
    add_management_page(
        'Export Plugins and Themes',   // Page title
        'Export Plugins and Themes',   // Menu title
        'manage_options',              // Capability required
        'export-plugins-themes',       // Menu slug
        'export_plugins_and_themes_page' // Callback function to display the page content
    );
}
add_action('admin_menu', 'export_plugins_and_themes_menu');

// Callback function to display the export options
function export_plugins_and_themes_page() {
    $plugins = get_plugins();
    $themes = wp_get_themes();
    $active_plugins = get_option('active_plugins', []);

    echo '<div class="wrap">';
    echo '<h1 class="export-title">Export Plugins and Themes</h1>';
    echo '<form method="post" onsubmit="return validateForm()">';

    echo '<p class="export-description">Select the plugins and themes you want to export, then click the button to generate the report.</p>';
    echo '<div class="select-button-container">';
    echo '<a href="#" class="select-all-button" onclick="selectAll()">Select All</a>';
    echo '<a href="#" class="unselect-all-button" onclick="unselectAll()">Unselect All</a>';
    echo '</div>';

    // Theme selection checkboxes
    echo '<h2 class="section-title">Themes</h2>';
    echo '<div class="section-content theme-section">';
    foreach ($themes as $theme_slug => $theme) {
        $is_active = ($theme->get_stylesheet() === get_stylesheet()) ? 'Active' : 'Inactive';
        echo '<label class="checkbox-label"><input type="checkbox" name="selected_themes[]" value="' . esc_attr($theme_slug) . '"> ' . esc_html($theme->get('Name')) . ' <span class="status">(' . $is_active . ')</span></label>';
    }
    echo '</div>';

    // Plugin selection checkboxes
    echo '<h2 class="section-title">Plugins</h2>';
    echo '<div class="section-content plugin-section">';
    foreach ($plugins as $plugin_file => $plugin_data) {
        $is_active = in_array($plugin_file, $active_plugins) ? 'Active' : 'Inactive';
        echo '<label class="checkbox-label"><input type="checkbox" name="selected_plugins[]" value="' . esc_attr($plugin_file) . '"> ' . esc_html($plugin_data['Name']) . ' <span class="status">(' . $is_active . ')</span></label>';
    }
    echo '</div>';

    echo '<div class="submit-button-container">';
    echo '<input type="submit" name="export_plugins_themes_html" class="button-export" value="Export to HTML">';
    echo '</div>';
    echo '</form>';
    echo '</div>';

    echo '<style>
            .wrap {
                font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
                max-width: 800px;
                margin: 0 auto;
                background: #ffffff;
                padding: 20px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
            }
            .export-title {
                font-size: 2.2em;
                color: #333;
                margin-bottom: 10px;
            }
            .export-description {
                font-size: 1.2em;
                color: #666;
                margin-bottom: 20px;
            }
            .select-button-container {
                margin-bottom: 20px;
                display: flex;
                gap: 10px;
            }
            .select-all-button, .unselect-all-button {
                display: inline-block;
                padding: 10px 20px;
                font-size: 1em;
                border-radius: 4px;
                color: #fff !important;
                text-decoration: none;
                cursor: pointer;
                transition: background-color 0.3s;
            }
            .select-all-button {
                background-color: #3498db;
            }
            .select-all-button:hover {
                background-color: #2980b9;
            }
            .unselect-all-button {
                background-color: #e74c3c;
            }
            .unselect-all-button:hover {
                background-color: #c0392b;
            }
            .section-title {
                font-size: 1.5em;
                color: #2c3e50;
                margin-top: 20px;
                margin-bottom: 10px;
                border-bottom: 2px solid #ddd;
                padding-bottom: 5px;
            }
            .section-content {
                margin-left: 10px;
                margin-bottom: 20px;
            }
            .checkbox-label {
                display: block;
                margin: 8px 0;
                font-size: 1.1em;
                color: #444;
                cursor: pointer;
                transition: color 0.3s;
            }
            .checkbox-label:hover {
                color: #3498db;
            }
            .status {
                color: #999;
                font-size: 0.9em;
            }
            .submit-button-container {
                text-align: center;
                margin-top: 20px;
            }
            .button-export {
                background-color: #2ecc71;
                color: #fff;
                padding: 12px 30px;
                font-size: 1.2em;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                transition: background-color 0.3s;
            }
            .button-export:hover {
                background-color: #27ae60;
            }

            /* Hide the white container or any empty div */
            .empty-container, #empty-element {
                display: none !important;
            }
            
            /* Reset padding/margin on the body to avoid unintended spaces */
            body {
                margin: 0;
                padding: 0;
            }
          </style>';

    echo '<script>
            function selectAll() {
                const checkboxes = document.querySelectorAll("input[type=\'checkbox\']");
                checkboxes.forEach(checkbox => checkbox.checked = true);
            }

            function unselectAll() {
                const checkboxes = document.querySelectorAll("input[type=\'checkbox\']");
                checkboxes.forEach(checkbox => checkbox.checked = false);
            }

            function validateForm() {
                var themeCheckboxes = document.querySelectorAll("input[name=\'selected_themes[]\']:checked");
                var pluginCheckboxes = document.querySelectorAll("input[name=\'selected_plugins[]\']:checked");
                if (themeCheckboxes.length === 0 && pluginCheckboxes.length === 0) {
                    alert("Please select at least one theme or plugin to export.");
                    return false;
                }
                return true;
            }
          </script>';

    if (isset($_POST['export_plugins_themes_html'])) {
        export_plugins_and_themes_html();
    }
}

// Function to handle HTML export with selected themes and plugins
function export_plugins_and_themes_html() {
    $selected_themes = !empty($_POST['selected_themes']) ? $_POST['selected_themes'] : [];
    $selected_plugins = !empty($_POST['selected_plugins']) ? $_POST['selected_plugins'] : [];

    if (empty($selected_themes) && empty($selected_plugins)) {
        wp_die('No themes or plugins selected. Please go back and select items to export.');
    }

    $export_date = date('Y-m-d H:i:s');
    $wordpress_version = get_bloginfo('version');
    $total_themes = count($selected_themes);
    $total_plugins = count($selected_plugins);

    header('Content-Type: text/html; charset=utf-8');
    header('Content-Disposition: attachment; filename="plugins-and-themes.html"');

    echo '<!DOCTYPE html>';
    echo '<html><head><title>Plugins and Themes List</title>';
    echo '<style>
            body, html { margin: 0; padding: 0; font-family: "Segoe UI", Arial, sans-serif; background-color: #f8f9fa; color: #333; }
            #wpadminbar, #adminmenuwrap, #adminmenuback, .wrap > h1, .wrap > form, h1.export-title, .error, .export-description { display: none !important; }
            #wpcontent, #wpfooter { margin-left: 0 !important; }  /* Remove any left margin */
            body { margin: 0; padding: 20px; width: calc(100% - 40px); }
            table { border-collapse: collapse; width: 100%; background-color: #ffffff; border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden; table-layout: fixed; } /* Ensure equal column width */
            th, td { padding: 12px 15px; text-align: left; word-wrap: break-word; } /* Allow text to wrap */
            th { background-color: #2980b9; color: white; font-weight: 600; text-transform: uppercase; }
            td { border-bottom: 1px solid #e0e0e0; }
            td:first-child { font-weight: 500; }

            /* Specific column width settings */
            th:nth-child(2), td:nth-child(2) { width: 30%; } /* "Name" column */
            th:nth-child(1), th:nth-child(3), th:nth-child(4), th:nth-child(5), th:nth-child(6),
            td:nth-child(1), td:nth-child(3), td:nth-child(4), td:nth-child(5), td:nth-child(6) { width: 14%; } /* Other columns */

            tr:nth-child(even) { background-color: #f7f7f7; }
            tr:hover { background-color: #eaf2f8; }
            .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #e0e0e0; font-size: 14px; color: #666; }

            /* Hide the .wrap class in the exported HTML */
            .wrap {
                display: none !important;
            }
          </style>';
    echo '</head><body>';

    // Exported content starts here
    if (!empty($selected_themes)) {
        echo '<h1>Themes</h1>';
        echo '<table>';
        echo '<tr><th>Status</th><th>Name</th><th>Version</th><th>Author</th><th>Author URI</th><th>Theme URI</th></tr>';
        foreach ($selected_themes as $theme_slug) {
            $theme = wp_get_theme($theme_slug);
            echo '<tr>';
            echo '<td>' . (($theme->get_stylesheet() === get_stylesheet()) ? 'Active' : 'Inactive') . '</td>';
            echo '<td>' . esc_html($theme->get('Name')) . '</td>';
            echo '<td>' . esc_html($theme->get('Version')) . '</td>';
            echo '<td>' . esc_html(strip_tags($theme->get('Author'))) . '</td>';
            echo '<td>' . esc_html($theme->get('AuthorURI')) . '</td>';
            echo '<td>' . esc_html($theme->get('ThemeURI')) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }

    if (!empty($selected_plugins)) {
        echo '<h1>Plugins</h1>';
        echo '<table>';
        echo '<tr><th>Status</th><th>Name</th><th>Version</th><th>Author</th><th>Author URI</th><th>Plugin URI</th></tr>';
        foreach ($selected_plugins as $plugin_file) {
            $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin_file);
            echo '<tr>';
            echo '<td>' . (is_plugin_active($plugin_file) ? 'Active' : 'Inactive') . '</td>';
            echo '<td>' . esc_html($plugin_data['Name']) . '</td>';
            echo '<td>' . esc_html($plugin_data['Version']) . '</td>';
            echo '<td>' . esc_html(strip_tags($plugin_data['Author'])) . '</td>';
            echo '<td>' . esc_html($plugin_data['AuthorURI']) . '</td>';
            echo '<td>' . esc_html($plugin_data['PluginURI']) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }

    // Add footer with export date and other details
    echo '<div class="footer">';
    echo '<p><strong>Exported on:</strong> ' . esc_html($export_date) . '</p>';
    echo '<p><strong>WordPress Version:</strong> ' . esc_html($wordpress_version) . '</p>';
    echo '<p><strong>Total Themes:</strong> ' . esc_html($total_themes) . '</p>';
    echo '<p><strong>Total Plugins:</strong> ' . esc_html($total_plugins) . '</p>';
    echo '</div>';

    echo '</body></html>';

    exit;
}
?>
