<?php
/**
 * This file holds version information for the plugin. It contains a number of properties, which are used during the plugin installation and upgrade process.
 * It allows to make sure the plugin is compatible with the given Moodle site, as well as spotting whether an upgrade is needed.
 * The object being used here is always called $plugin. You do not need to create this object yourself within the version file.
 *
 * NOTE: Do not include a closing "?>" tag. This is is intentional.
 *
 *
 *  PROPERTY:
 *      $plugin->component: Required string. It is used during the installation and upgrade process for diagnostics and validation purposes
 *                          to make sure the plugin code has been deployed to the correct location within the Moodle code tree.
 *      $plugin->version: Required integer. This is the version number of the plugin. The format is partially date based with the form YYYYMMDDXX
 *                        where XX is an incremental counter for the given year (YYYY), month (MM) and date (DD) of the plugin version's release.
 *                        Every new plugin version must have this number increased in this file, which is detected by Moodle core and the upgrade process is triggered.
 *
 *      $plugin->required: Recommended integer. Specifies the minimum version number of Moodle core that this plugin requires.
 *                          It is not possible to install it to earlier Moodle version. Moodle core's version number is
 *                          defined in the file version.php located in Moodle root directory, in the $version variable.
 */

$plugin->component = 'block_testreport';  // Recommended since 2.0.2 (MDL-26035). Required since 3.0 (MDL-48494)
$plugin->version = 2020061500;        // The current plugin version (Date: YYYYMMDDXX)
$plugin->requires = 2020060900;  // YYYYMMDDHH (This is the release version for Moodle 2.0)