<?php
/**
 * Plugin Name: Teste
 * Description: Plugin simples para aprender a fazer Plugins. Pong feito seguindo um tutorial para aprender a usar Phaser.
 * Author: LJoão 
 */

if (!defined('ABSPATH')) {
   die;
}

include 'enqueues.php';
include 'FormWidget.php';

class TestePlugin
{
   function __construct()
   {
      add_action('init', array($this, 'custom_post_type'));
   }

   function activate()
   {
      // generated a CPT
      $this->custom_post_type();
      // flush rewrite rules
      flush_rewrite_rules();
   }

   function deactivate()
   {
      // flush rewrite rules
      flush_rewrite_rules();
   }

   function custom_post_type()
   {
      register_post_type(
         'book',
         [
            'labels' =>
            [
               'name' => 'Frases',
               'singular_name' => 'Frase'
            ],
            'public' => true,
            'has_archive' => true
         ]
      );
   }
}

if (class_exists('TestePlugin')) {
   $testePlugin = new TestePlugin();
}

add_filter('page_template', 'book_page_template');

function book_page_template($page_template)
{
   if (is_page('sample-page')) {
      $page_template = dirname(__FILE__) . '/single-book.php';
   }
   return $page_template;
}


//activation
register_activation_hook(__FILE__, array($testePlugin, 'activate'));

//deactivation
register_deactivation_hook(__FILE__, array($testePlugin, 'deactivate'));

//Checks if the widget is active, if not it does not enquen the scripts 
add_action('template_redirect', 'page_load_game');
function page_load_game()
{
   if (is_404()) {
      if (is_active_widget(false, false, 'widget_teste', true)) {
         //enqueues scripts needed for the game
         add_action('wp_enqueue_scripts', 'load_scripts');
      }
   }
}
