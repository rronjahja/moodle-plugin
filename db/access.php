<?php

/**
 * This file will hold the new capabilities created by the block.
 * These capabilities should also be added to the custom block, so in this case to the file block /db/access.php.
 * If the block is not going to be used in the 'My Moodle page' (ie, your applicable_formats function has 'my' set to false.)
 * then the myaddinstance capability does not need to be given to any user, but it must still be defined here otherwise you will get errors on certain pages.
 *
 */

$capabilities = array(

    'block/testreport:myaddinstance' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'user' => CAP_ALLOW
        ),

        'clonepermissionsfrom' => 'moodle/my:manageblocks'
    ),

    'block/testreport:addinstance' => array(
        'riskbitmask' => RISK_SPAM | RISK_XSS,

        'captype' => 'write',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        ),

        'clonepermissionsfrom' => 'moodle/site:manageblocks'
    ),
);