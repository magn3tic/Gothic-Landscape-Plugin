<?php
/**
 * @package  Gothic Plants
 * @author   Jeremy Scott
 * @version  1.0.dev
 * @license  GPL-3.0+
 *
 * @wordpress-plugin
 * Plugin Name: Gothic Landscape Selections
 * Plugin URI: http://gothiclandscape.com/
 * Description: A plugin to power Gothic Landscape of Arizona Landscape Selections Application
 * Author: Jeremy Scott
 * Author URI: http://cactushug.com/
 * Contributors: jeremyescott
 * Stable Tag: 1.0.dev
 * Text Domain: gothic-selections
 * Domain Path: /languages
 * Requires at Least: 5.0
 * Tested Up To: 5.4.rc1
 * Requires PHP: 7.2
 * License:     GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * Gothic Landscape Selections plugin is free software: you can redistribute it and/or modify it under the terms of the
 * GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or any
 * later version.
 *
 * Gothic Landscape Selections plugin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public
 * License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this software. If not, see
 * <http://www.gnu.org/licenses/>.
 */

require_once plugin_dir_path( __FILE__ ) . 'Plugin.php';

$plugin = new \Gothic\Selections\Plugin();

$plugin->instance();
