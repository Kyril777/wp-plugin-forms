<?php

/**
* Plugin Name: Form Plugin
**/

  // Add functions to the header and the footer.
  function admin_menu_option()
  {
    add_menu_page('Header & Footer Scripts', 'Site Scripts', 'manage_options', 'admin-menu', 'scripts_page', '', 200);
  }

  add_action('admin_menu', 'admin_menu_option');


  function scripts_page()
  {

    if(array_key_exists('submit_scripts_update', $_POST))
    {
      update_option('header_scripts', $_POST['header_scripts']);
      update_option('footer_scripts', $_POST['footer_scripts']);

      ?>
      <div id="setting-error-settings-updated" class="updated settings-error notice is-dismissible">
        <strong>Setting saved.</strong>
      </div>
      <?php


    }

    $header_scripts = get_option('header_scripts', 'none');
    $footer_scripts = get_option('footer_scripts', 'none');

    ?>
    <div class="wrap">
      <h2>Update Script</h2>
      <form method="POST" action="">
        <label for="header_scripts">Header Scripts</label>
        <textarea name="header_scripts" class="large-text"><?php print $header_scripts; ?></textarea>
        <label for="footer_scripts">Footer Scripts</label>
        <textarea name="footer_scripts" class="large-text"><?php print $footer_scripts; ?></textarea>
        <input type="submit" name="submit_scripts_update" class="button button-primary" value="UPDATE">
      </form>
    </div>
    <?php
  }

  function display_header_scripts(){
    $header_scripts = get_option('header_scripts', 'none');
    print $header_scripts;
  }
  add_action('wp_head', 'display_header_scripts');

  function display_footer_scripts(){
    $footer_scripts = get_option('footer_scripts', 'none');
    print $footer_scripts;
  }
  add_action('wp_footer', 'display_footer_scripts');


  function fmp_display_form()
  {
    /* Content variables. */
    $content = '';

    $content .= '<form method="post" action="http://localhost/formplugin/index.php/thank-you" />';

      $content .= '<input type="text" name="full_name" placeholder="Place your full name." />';
      $content .= '<br/>';

      $content .= '<input type="text" name="email_address" placeholder="Place your email address." />';
      $content .= '<br/>';

      $content .= '<input type="text" name="topic" placeholder="Your topic." />';
      $content .= '<br/>';

      $content .= '<textarea name="message" placeholder="So what\'s on your mind?"></textarea>';
      $content .= '<br/>';

      $content .= '<input type="submit" name="fmp_submit_form" value="SUBMIT" />';

    $content .= '</form>';

    return $content;
  }
  add_shortcode('fmp_contact_form', 'fmp_display_form');

  function set_html_content_type()
  {
    return 'text/html';
  }

  function fmp_form_capture()
  {
    if(array_key_exists('fmp_submit_form', $_POST))
    {
      $to = "email@formplugin.com";
      $subject = "Site Form Submission";
      $body = '';

      $body .= 'Name: '.$_POST['full_name'].' <br/> ';
      $body .= 'Email: '.$_POST['email_address'].' <br/> ';
      $body .= 'Topic: '.$_POST['topic'].' <br/> ';
      $body .= 'Message: '.$_POST['message'].' <br/> ';


      add_filter('wp_mail_content_type', 'set_html_content_type');
      wp_mail($to,$subject,$body);
      remove_filter('wp_mail_content_type', 'set_html_content_type' );
    }
  }
  add_action('wp_head', 'fmp_form_capture');
