<?php

/*
Plugin Name: WordPress Contact Forms 7 OR validation
Description: Добавляет валидацию по нескольким полям. Добавьте ** вместо * если нужно чтобы хоть одно из полей должно быть заполнено. (Будьте внимательны, единичное поле ** не противоречит отправке)
Plugin URI: https://github.com/nikolays93/contact-form-7-or-validation.git
Version: 1.0
Author: NikolayS93
Author URI: https://vk.com/nikolays_93
Author EMAIL: nikolayS93@ya.ru
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/**
 * Проверяет вверен ли один из полей
 *
 * @param  WPCF7_Validation $result @see wpcf7/validation.php
 * @param  stdObj           $tags   @see wpcf7/submisson.php
 * @return WPCF7_Validation
 */
function wpcf7_or_validation($result, $tags) {
    $submission = WPCF7_Submission::get_instance();
    $posted_data = $submission->get_posted_data();

    // $contact_form_id = $submission->contact_form->id();
    // if( $contact_form_id !== '26' ) {
    //     return $result;
    // }

    $maybe_required = array();
    foreach ($tags as $tag) {
        if( '**' == substr( $tag->type, -2 ) && empty($posted_data[ $tag->name ]) ) {
            $maybe_required[] = $tag;
        }
    }

    $msg = __( 'Одно из этих полей должно быть заполнено.' );
    if( count($maybe_required) > 1 ) {
        foreach ($maybe_required as $required) {
            $result->invalidate( $required, $msg );
        }
    }

    return $result;
}
add_filter( 'wpcf7_validate', 'wpcf7_or_validation', 10, 2 );

function custom_wpcf7_or_validation() {
    wpcf7_add_form_tag(
        array( 'text**', 'email**', 'url**', 'tel**' ),
        'wpcf7_text_form_tag_handler', array( 'name-attr' => true ) );

    wpcf7_add_form_tag( array( 'checkbox**' ),
        'wpcf7_checkbox_form_tag_handler',
        array(
            'name-attr' => true,
            'selectable-values' => true,
            'multiple-controls-container' => true,
            )
        );

    wpcf7_add_form_tag( array( 'date**' ),
        'wpcf7_date_form_tag_handler', array( 'name-attr' => true ) );

    wpcf7_add_form_tag( array( 'file**' ),
        'wpcf7_file_form_tag_handler', array( 'name-attr' => true ) );

    wpcf7_add_form_tag( array( 'number**', 'range**' ),
        'wpcf7_number_form_tag_handler', array( 'name-attr' => true ) );

    wpcf7_add_form_tag( array( 'select**' ),
        'wpcf7_select_form_tag_handler',
        array(
            'name-attr' => true,
            'selectable-values' => true,
            )
        );

    wpcf7_add_form_tag( array( 'textarea**' ),
        'wpcf7_textarea_form_tag_handler', array( 'name-attr' => true ) );
}
add_action( 'wpcf7_init', 'custom_wpcf7_or_validation' );
