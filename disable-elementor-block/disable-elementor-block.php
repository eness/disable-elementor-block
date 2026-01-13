<?php
/*
Plugin Name: Disable Elementor Block
Plugin URI: https://wordpress.org/plugins/disable-elementor-block
Description: Temporarily disable Elementor elements (removes from DOM on frontend, visible in Editor).
Version: 1.0
Requires at least: 5.8
Requires PHP: 7.3
Author: enes sönmez
Author URI: https://enes.dev
License: GPLv3
Text Domain: disable-elementor-block
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Disable_Elementor_Block {

    const CONTROL_NAME = 'eed_disable_element';

    public function __construct() {
        add_action( 'elementor/init', [ $this, 'init' ] );
    }

    public function init() {
        // Check if Elementor is loaded
        if ( ! did_action( 'elementor/loaded' ) ) {
            return;
        }

        /**
         * 1. REGISTER CONTROLS (HOOKS)
         */

        // 1. WIDGETS ("Blocks")
        // FIXED: Widgets don't have 'section_advanced'. We hook into '_section_responsive' instead.
        // This puts the control inside the Advanced Tab, after the Responsive settings.
        add_action( 'elementor/element/common/_section_responsive/after_section_end', [ $this, 'register_controls' ], 10, 2 );

        // 2. SECTIONS
        // Sections use 'section_advanced'
        add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'register_controls' ], 10, 2 );

        // 3. COLUMNS
        // Columns use 'section_advanced'
        add_action( 'elementor/element/column/section_advanced/after_section_end', [ $this, 'register_controls' ], 10, 2 );

        // 4. CONTAINERS (Flexbox/Grid)
        // We hook into '_section_responsive' to keep it consistent in the Advanced Tab.
        add_action( 'elementor/element/container/_section_responsive/after_section_end', [ $this, 'register_controls' ], 10, 2 );


        /**
         * 2. RENDER FILTERS (REMOVE FROM DOM)
         */
        $element_types = [ 'widget', 'section', 'column', 'container' ];
        foreach ( $element_types as $type ) {
            add_filter( "elementor/frontend/{$type}/should_render", [ $this, 'should_render_logic' ], 10, 2 );
        }

        /**
         * 3. EDITOR VISUALIZATION
         */
        add_action( 'elementor/frontend/before_render', [ $this, 'add_editor_classes' ] );
        add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_editor_styles' ] );
    }

    /**
     * Add the Switcher Control
     * @param object $element The Elementor element.
     * @param array  $args    Arguments passed by the hook.
     */
    public function register_controls( $element, $args ) {

        $element->start_controls_section(
            'section_eed_disable',
            [
                'label' => esc_html__( 'Visibility / Disabler', 'disable-elementor-block' ),
                'tab'   => \Elementor\Controls_Manager::TAB_ADVANCED,
            ]
        );

        $element->add_control(
            self::CONTROL_NAME,
            [
                'label'        => esc_html__( 'Disable this Element?', 'disable-elementor-block' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'disable-elementor-block' ),
                'label_off'    => esc_html__( 'No', 'disable-elementor-block' ),
                'return_value' => 'yes',
                'default'      => '',
                'description'  => esc_html__( 'If Yes: Element is removed from DOM on frontend but remains editable here.', 'disable-elementor-block' ),
                'frontend_available' => true,
            ]
        );

        $element->add_control(
            'eed_disable_note',
            [
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw'  => '<div style="background: #ffe6e6; border-left: 4px solid #d9534f; padding: 10px; font-size: 12px; color: #a94442;">
                <strong>DISABLED:</strong> This element will NOT be output in the source code of the live site.
             </div>',
                'content_classes' => 'elementor-descriptor',
                'condition' => [
                    self::CONTROL_NAME => 'yes',
                ],
            ]
        );

        $element->end_controls_section();
    }

    /**
     * Logic: Prevent HTML output on Frontend
     */
    public function should_render_logic( $should_render, $element ) {
        // If it's already false, do nothing
        if ( false === $should_render ) {
            return false;
        }

        // ALWAYS show in Editor mode (so you can edit/re-enable it)
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
            return true;
        }

        $settings = $element->get_settings_for_display();

        if ( ! empty( $settings[ self::CONTROL_NAME ] ) && 'yes' === $settings[ self::CONTROL_NAME ] ) {
            return false;
        }

        return $should_render;
    }

    /**
     * Logic: Add CSS class in Editor mode
     */
    public function add_editor_classes( $element ) {
        // Only run in editor
        if ( ! \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
            return;
        }

        $settings = $element->get_settings_for_display();

        if ( ! empty( $settings[ self::CONTROL_NAME ] ) && 'yes' === $settings[ self::CONTROL_NAME ] ) {
            $element->add_render_attribute( '_wrapper', 'class', 'eed-disabled-element' );
        }
    }

    /**
     * Editor CSS
     */
    public function enqueue_editor_styles() {
        $css = "
          .elementor-editor-active .eed-disabled-element {
             opacity: 0.4 !important;
             background-image: repeating-linear-gradient(
                45deg,
                transparent,
                transparent 10px,
                rgba(231, 76, 60, 0.05) 10px,
                rgba(231, 76, 60, 0.05) 20px
             ) !important;
             outline: 2px dashed #e74c3c !important;
             outline-offset: -2px;
             position: relative !important;
             transition: opacity 0.3s;
          }

          .elementor-editor-active .eed-disabled-element::before {
             content: 'DISABLED / HIDDEN';
             display: block;
             position: absolute;
             top: 0;
             left: 0;
             background: #e74c3c;
             color: white;
             font-size: 10px;
             font-weight: bold;
             padding: 2px 6px;
             z-index: 9999;
             pointer-events: none;
          }
          
          .elementor-editor-active .eed-disabled-element:hover {
             opacity: 0.8 !important;
          }
       ";

        wp_add_inline_style( 'elementor-editor', $css );
    }
}

new Disable_Elementor_Block();
