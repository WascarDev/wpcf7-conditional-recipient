<?php
/**
 * Plugin Name: Contact Form 7 : Conditional Recipient
 * Version: 1.0
 * Author: Adrien Jussak - Agence Magic Web
 * Author URI: https://agence.magicweb.fr/
 **/

function wpcf7cr_select_form($name, $values, $current_value = "") {
    echo '<select name="' . $name . '">';

    foreach ($values as $id => $label) {
        echo '<option value="'. $id .'" ' . (($current_value === $id) ? 'selected' : '') . '>' . $label . '</option>';
    }

    echo '</select>';
}

function wpcf7cr_display_panel(WPCF7_ContactForm $contact_form)
{
    $args = array('recipiants' => array(0 => array('email' => '', 'or_structures' => array(0 => array('and_structures' => array(0 => array('field' => '', 'operator' => 'equals', 'value' => '')))))));

    include 'template-parts/conditional-recipient-panel.php';
}

function wpcf7cr_panels($panels)
{
    $panels['conditional-recipient'] = array(
        'title' => __('Conditional Recipient'),
        'callback' => 'wpcf7cr_display_panel');

    return $panels;
}

add_filter('wpcf7_editor_panels', 'wpcf7cr_panels');

function wpcf7cr_admin_script() {
    wp_enqueue_style('wpcf7cr-admin', plugin_dir_url( __FILE__ ) . '/assets/css/admin.css', array(), '1.0');

    wp_enqueue_script('ejs', 'https://cdn.jsdelivr.net/npm/ejs@3.1.6/ejs.min.js', array(), 'v3.1.6');
    wp_enqueue_script('wpcf7cr-js', plugin_dir_url( __FILE__ ) . '/assets/js/admin.js', array('ejs'), 'v1.0');

    wp_localize_script('wpcf7cr-js', 'WPCF7CR', array(
        'pluginsUrl' => plugin_dir_url( __FILE__ ),
    ));
}

add_action('admin_enqueue_scripts', 'wpcf7cr_admin_script');
