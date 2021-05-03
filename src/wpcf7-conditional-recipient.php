<?php
/**
 * Plugin Name: Contact Form 7 : Conditional Recipient
 * Version: 1.0
 * Author: WascarDev
 * Author URI: https://www.wascardev.com/
 **/

function wpcf7cr_select_form($name, $values, $current_value = "")
{
    echo '<select name="' . $name . '">';

    foreach ($values as $id => $label) {
        echo '<option value="' . $id . '" ' . (($current_value === $id) ? 'selected' : '') . '>' . $label . '</option>';
    }

    echo '</select>';
}

function wpcf7cr_save_contact_form(WPCF7_ContactForm $contact_form, $args, $context)
{
    if (isset($_POST['wpcf7cr_recipiants'])) {
        update_post_meta($contact_form->id(), 'wpcf7cr', $_POST['wpcf7cr_recipiants']);
    }
}

add_action('wpcf7_save_contact_form', 'wpcf7cr_save_contact_form', 10, 3);

function wpcf7cr_display_panel(WPCF7_ContactForm $contact_form)
{
    echo '<h2>' . _e("Destinaire Dynamique") . '</h2>';
    echo '<div class="conditional-recipient-list"></div>';
}

function wpcf7cr_panels($panels)
{
    $panels['conditional-recipient'] = array(
        'title' => __('Destinaire Dynamique'),
        'callback' => 'wpcf7cr_display_panel');

    return $panels;
}

add_filter('wpcf7_editor_panels', 'wpcf7cr_panels');

function wpcf7cr_admin_script()
{
    wp_enqueue_style('wpcf7cr-admin', plugin_dir_url(__FILE__) . '/assets/css/admin.css', array(), '1.0');

    wp_enqueue_script('ejs', 'https://cdn.jsdelivr.net/npm/ejs@3.1.6/ejs.min.js', array(), 'v3.1.6');
    wp_enqueue_script('wpcf7cr-js', plugin_dir_url(__FILE__) . '/assets/js/admin.js', array('ejs'), 'v1.0');

    wp_localize_script('wpcf7cr-js', 'WPCF7CR', array(
        'pluginsUrl' => plugin_dir_url(__FILE__),
        'ajax' => admin_url('admin-ajax.php')
    ));
}

add_action('admin_enqueue_scripts', 'wpcf7cr_admin_script');

add_action('wp_ajax_wpcf7cr_settings', 'wpcf7cr_ajax_form_metadata');
add_action('wp_ajax_nopriv_wpcf7cr_settings', 'wpcf7cr_ajax_form_metadata');

function wpcf7cr_ajax_form_metadata()
{
    if (isset($_POST['post_id'])) {
        $meta = get_post_meta($_POST['post_id'], 'wpcf7cr', true);
        if ($meta != false) {
            wp_send_json_success(array('recipients' => $meta));
            return;
        }
    }

    wp_send_json_success(array('recipients' => array(array('emails' => array(''), 'or_structures' => array(array(array("field" => '', 'operator' => 'equals', 'value' => '')))))));
}

function wpcf7cr_found_recipient($meta)
{
    foreach ($meta as $index => $recipient_data) {
        foreach ($recipient_data['or_structures'] as $or_structure) {

            $valid = true;

            foreach ($or_structure as $and_structure) {
                if ($and_structure['operator'] === 'equals') {
                    if (!isset($_POST[$and_structure['field']]) || $_POST[$and_structure['field']] !== $and_structure['value']) {
                        $valid = false;
                    }
                } else if ($and_structure['operator'] === 'noequals') {
                    if (!isset($_POST[$and_structure['field']]) || $_POST[$and_structure['field']] === $and_structure['value']) {
                        $valid = false;
                    }
                }
            }

            if ($valid)
                return $recipient_data['emails'];
        }

    }

    return array();
}

function wpcf7cr_before_send_mail_function(WPCF7_ContactForm $contact_form, $abort, $submission)
{
    $meta = get_post_meta($contact_form->id(), 'wpcf7cr', true);
    if ($meta) {
        $mails = wpcf7cr_found_recipient($meta);
        if (!empty($mails)) {
            $properties = $contact_form->get_properties();
            $properties['mail']['recipient'] = array_shift($mails);
            if (!empty($mails)) {
                $properties['mail']['additional_headers'] .= "\nCc : " . implode(', ', $mails);
            }
            $contact_form->set_properties($properties);
        }
    }

    return $contact_form;
}

add_filter('wpcf7_before_send_mail', 'wpcf7cr_before_send_mail_function', 10, 3);
