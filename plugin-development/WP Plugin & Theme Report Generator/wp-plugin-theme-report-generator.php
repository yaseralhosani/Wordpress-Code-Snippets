<?php
// Add the "Export Plugins and Themes" option to the Tools menu
function export_plugins_and_themes_menu() {
    add_management_page(
        'Export Plugins and Themes',     // Page title
        'Export Plugins and Themes',     // Menu title
        'manage_options',                // Capability required
        'export-plugins-themes',         // Menu slug
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
    echo '<form method="post" id="export-form" target="_blank" action="' . esc_url(admin_url('admin-post.php')) . '">';

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

    echo '<input type="hidden" name="action" value="export_plugins_themes">';
    echo '<input type="hidden" name="export_format" id="export_format" value="html">';
    echo '<div class="submit-button-container">';
    echo '<button type="button" class="button-export" onclick="submitForm(\'html\')">Export to HTML</button>';
    echo '<button type="button" class="button-export" onclick="submitForm(\'csv\')">Export to CSV</button>';
    echo '</div>';
    echo '</form>';
    echo '</div>';

    echo '<div id="toast" class="toast">Please select themes or plugins to export.</div>';

    // Updated styling for modern UI
    echo '<style>
            .wrap {
                font-family: "Inter", "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
                max-width: 800px;
                margin: 0 auto;
                background: #ffffff;
                padding: 30px;
                box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
                border-radius: 12px;
            }
            .export-title {
                font-size: 2.5em;
                color: #1e293b;
                margin-bottom: 20px;
            }
            .export-description {
                font-size: 1.2em;
                color: #64748b;
                margin-bottom: 25px;
            }
            .select-button-container {
                margin-bottom: 25px;
                display: flex;
                gap: 10px;
                justify-content: flex-start;
            }
            .select-all-button, .unselect-all-button {
                display: inline-block;
                padding: 10px 15px;
                font-size: 1em;
                border-radius: 6px;
                color: #fff !important;
                text-decoration: none;
                cursor: pointer;
                transition: background-color 0.3s, box-shadow 0.3s;
            }
            .select-all-button {
                background-color: #0ea5e9;
            }
            .select-all-button:hover {
                background-color: #0284c7;
            }
            .unselect-all-button {
                background-color: #ef4444;
            }
            .unselect-all-button:hover {
                background-color: #dc2626;
            }
			.select-all-button:focus, .select-all-button:active {
				outline: none;
				box-shadow: 0 0 5px rgba(14, 165, 233, 0.75);
				border-color: #0ea5e9;
			}
			.unselect-all-button:focus, .unselect-all-button:active {
				outline: none;
				box-shadow: 0 0 5px rgba(239, 68, 68, 0.75);
				border-color: #ef4444;
			}
            .section-title {
                font-size: 1.8em;
                color: #1e293b;
                margin-top: 25px;
                margin-bottom: 15px;
                border-bottom: 2px solid #ddd;
                padding-bottom: 8px;
            }
            .section-content {
                margin-left: 15px;
                margin-bottom: 25px;
            }
            .checkbox-label {
                display: block;
                margin: 10px 0;
                font-size: 1.1em;
                color: #475569;
                cursor: pointer;
                transition: color 0.3s;
            }
            .checkbox-label:hover {
                color: #0ea5e9;
            }
            .status {
                color: #94a3b8;
                font-size: 0.9em;
            }
            .submit-button-container {
                text-align: center;
                margin-top: 25px;
                display: flex;
                justify-content: center;
                gap: 15px;
            }
            .button-export {
                background-color: #10b981;
                color: #fff;
                padding: 12px 35px;
                font-size: 1.2em;
                border: none;
                border-radius: 8px;
                cursor: pointer;
                transition: background-color 0.3s;
            }
            .button-export:hover {
                background-color: #059669;
            }

            /* Toast Notification Styles */
            .toast {
                visibility: hidden;
                min-width: 250px;
                background-color: #333;
                color: #fff;
                text-align: center;
                border-radius: 4px;
                padding: 16px;
                position: fixed;
                z-index: 1;
                top: 40px;
                right: 20px;
                font-size: 17px;
                opacity: 0;
            }

            .toast.show {
                visibility: visible;
                opacity: 1;
                transition: opacity 0.5s ease-in-out, visibility 0s linear 0s;
            }

            .toast.hide {
                opacity: 0;
                transition: opacity 0.5s ease-in-out, visibility 0s linear 0.5s;
                visibility: hidden;
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

            function submitForm(format) {
                var themeCheckboxes = document.querySelectorAll("input[name=\'selected_themes[]\']:checked");
                var pluginCheckboxes = document.querySelectorAll("input[name=\'selected_plugins[]\']:checked");
                if (themeCheckboxes.length === 0 && pluginCheckboxes.length === 0) {
                    showToast();
                } else {
                    document.getElementById("export_format").value = format;
                    document.getElementById("export-form").submit();
                }
            }

            function showToast() {
                var toast = document.getElementById("toast");
                toast.classList.remove("hide");
                toast.classList.add("show");
                setTimeout(function(){
                    toast.classList.remove("show");
                    toast.classList.add("hide");
                }, 3000);
            }
          </script>';
}

// Hook for handling the form submission and exporting the data
add_action('admin_post_export_plugins_themes', 'export_plugins_and_themes');

// Function to handle export (HTML, CSV) with selected themes and plugins
function export_plugins_and_themes() {
    $selected_themes = !empty($_POST['selected_themes']) ? $_POST['selected_themes'] : [];
    $selected_plugins = !empty($_POST['selected_plugins']) ? $_POST['selected_plugins'] : [];

    if (empty($selected_themes) && empty($selected_plugins)) {
        wp_die('No themes or plugins selected. Please go back and select items to export.');
    }

    $export_date = date('Y-m-d H:i:s');
    $wordpress_version = get_bloginfo('version');
    $site_url = get_bloginfo('url');
    $site_name = get_bloginfo('name');
    $total_themes = count($selected_themes);
    $total_plugins = count($selected_plugins);

    $format = $_POST['export_format'] ?? 'html';

    if ($format === 'csv') {
        export_plugins_and_themes_csv($selected_themes, $selected_plugins, $export_date, $wordpress_version, $site_url, $site_name);
    } else {
        export_plugins_and_themes_html($selected_themes, $selected_plugins, $export_date, $wordpress_version, $site_url, $site_name);
    }
    exit;
}

// Function to handle HTML export
function export_plugins_and_themes_html($selected_themes, $selected_plugins, $export_date, $wordpress_version, $site_url, $site_name) {
    header('Content-Type: text/html; charset=utf-8');

    echo '<!DOCTYPE html>';
    echo '<html><head><title>Plugins and Themes List</title>';
    echo '<style>
            body, html { margin: 0; padding: 0; font-family: "Inter", Arial, sans-serif; background-color: #f3f4f6; color: #1e293b; }
            body { margin: 0; padding: 20px; width: calc(100% - 40px); }
            h1 { text-align: center; margin-bottom: 30px; font-size: 36px; color: #1d4ed8; }
            h2 { font-size: 24px; color: #0f172a; margin-top: 40px; }
            table { border-collapse: collapse; width: 100%; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); }
            th, td { padding: 15px 20px; text-align: left; }
            th { background-color: #1d4ed8; color: white; font-weight: 600; text-transform: uppercase; font-size: 14px; }
            td { border-bottom: 1px solid #e2e8f0; font-size: 16px; color: #475569; }
            tr:nth-child(even) { background-color: #f1f5f9; }
            tr:hover { background-color: #e2e8f0; }

            /* Adjusting column widths */
            th:nth-child(2), td:nth-child(2) {
                width: 40%; /* The "Name" column gets 40% of the width */
            }
            th:nth-child(1), th:nth-child(3), th:nth-child(4), th:nth-child(5), th:nth-child(6),
            td:nth-child(1), td:nth-child(3), td:nth-child(4), td:nth-child(5), td:nth-child(6) {
                width: 12%; /* The rest of the columns share the remaining width equally */
            }

            .footer { margin-top: 50px; padding-top: 30px; border-top: 1px solid #e2e8f0; font-size: 14px; color: #6b7280; text-align: center; }
          </style>';
    echo '</head><body>';

    echo '<h1>' . esc_html($site_name) . '</h1>';

    echo '<h2>Summary</h2>';
    echo '<p><strong>Total Themes:</strong> ' . esc_html(count($selected_themes)) . '</p>';
    echo '<p><strong>Total Plugins:</strong> ' . esc_html(count($selected_plugins)) . '</p>';
    echo '<p><strong>WordPress Version:</strong> ' . esc_html($wordpress_version) . '</p>';
     echo '<p><strong>Site URL:</strong> <a href="' . esc_url($site_url) . '" target="_blank" rel="noopener">' . esc_html($site_url) . '</a></p>';
    echo '<p><strong>Exported on:</strong> ' . esc_html($export_date) . '</p>';

    if (!empty($selected_themes)) {
        echo '<h2>Themes</h2>';
        echo '<table>';
        echo '<tr><th>Status</th><th>Name</th><th>Version</th><th>Author</th><th>Author URI</th><th>Theme URI</th></tr>';
        foreach ($selected_themes as $theme_slug) {
            $theme = wp_get_theme($theme_slug);
            echo '<tr>';
            echo '<td>' . (($theme->get_stylesheet() === get_stylesheet()) ? 'Active' : 'Inactive') . '</td>';
            echo '<td>' . esc_html($theme->get('Name')) . '</td>';
            echo '<td>' . esc_html($theme->get('Version')) . '</td>';
            echo '<td>' . esc_html(strip_tags($theme->get('Author'))) . '</td>';
            echo '<td><a href="' . esc_url($theme->get('AuthorURI')) . '">' . esc_html($theme->get('AuthorURI')) . '</a></td>';
            echo '<td><a href="' . esc_url($theme->get('ThemeURI')) . '">' . esc_html($theme->get('ThemeURI')) . '</a></td>';
            echo '</tr>';
        }
        echo '</table>';
    }

    if (!empty($selected_plugins)) {
        echo '<h2>Plugins</h2>';
        echo '<table>';
        echo '<tr><th>Status</th><th>Name</th><th>Version</th><th>Author</th><th>Author URI</th><th>Plugin URI</th></tr>';
        foreach ($selected_plugins as $plugin_file) {
            $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin_file);
            echo '<tr>';
            echo '<td>' . (is_plugin_active($plugin_file) ? 'Active' : 'Inactive') . '</td>';
            echo '<td>' . esc_html($plugin_data['Name']) . '</td>';
            echo '<td>' . esc_html($plugin_data['Version']) . '</td>';
            echo '<td>' . esc_html(strip_tags($plugin_data['Author'])) . '</td>';
            echo '<td><a href="' . esc_url($plugin_data['AuthorURI']) . '">' . esc_html($plugin_data['AuthorURI']) . '</a></td>';
            echo '<td><a href="' . esc_url($plugin_data['PluginURI']) . '">' . esc_html($plugin_data['PluginURI']) . '</a></td>';
            echo '</tr>';
        }
        echo '</table>';
    }

    echo '</body></html>';
    exit;
}

// Function to handle CSV export
function export_plugins_and_themes_csv($selected_themes, $selected_plugins, $export_date, $wordpress_version, $site_url, $site_name) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="plugins-themes-export.csv"');

    $output = fopen('php://output', 'w');

    // Add summary metadata to the CSV
    fputcsv($output, ['Summary']);
    fputcsv($output, ['Total Themes', count($selected_themes)]);
    fputcsv($output, ['Total Plugins', count($selected_plugins)]);
    fputcsv($output, ['WordPress Version', $wordpress_version]);
    fputcsv($output, ['Site URL', $site_url]);
    fputcsv($output, ['Exported on', $export_date]);
    fputcsv($output, []);
    fputcsv($output, []);

    // Add header row for themes and plugins
    fputcsv($output, array('Type', 'Status', 'Name', 'Version', 'Author', 'Author URI', 'URI'));

    // Add theme data
    foreach ($selected_themes as $theme_slug) {
        $theme = wp_get_theme($theme_slug);
        fputcsv($output, array(
            'Theme',
            ($theme->get_stylesheet() === get_stylesheet()) ? 'Active' : 'Inactive',
            $theme->get('Name'),
            $theme->get('Version'),
            strip_tags($theme->get('Author')),
            $theme->get('AuthorURI'),
            $theme->get('ThemeURI'),
        ));
    }

    // Add plugin data
    foreach ($selected_plugins as $plugin_file) {
        $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin_file);
        fputcsv($output, array(
            'Plugin',
            is_plugin_active($plugin_file) ? 'Active' : 'Inactive',
            $plugin_data['Name'],
            $plugin_data['Version'],
            strip_tags($plugin_data['Author']),
            $plugin_data['AuthorURI'],
            $plugin_data['PluginURI'],
        ));
    }

    fclose($output);
    exit;
}
?>
