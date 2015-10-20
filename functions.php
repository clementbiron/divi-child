<?php

	//Ajouter la CSS de Divi à notre child theme
	add_action( 'wp_enqueue_scripts', 'divichild_enqueue_assets' );
	function divichild_enqueue_assets() {
		wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
	}

	//Supprimer les CPT crées par Divi
	add_action('init','divichild_unregister_divi_post_type',11);
	function divichild_unregister_divi_post_type(){
		$cpts = array('project');
		global $wp_post_types;
		foreach ($cpts as $cpt){
			if (isset($wp_post_types[$cpt])){
				unset($wp_post_types[$cpt]);
			}
		}
	}

	//Supprimer les custom tax crées par Divi
	add_action('init', 'divichild_unregister_divi_taxonomy',11);
	function divichild_unregister_divi_taxonomy(){
		$taxs = array('project_tag','project_category');
		global $wp_taxonomies;
		foreach ($taxs as $tax){
			if (taxonomy_exists($tax)){
        		unset($wp_taxonomies[$tax]);
			}
		}		
	}

	//Ajouter le Divi Builder pour nos CPT
	add_filter('et_builder_post_types', 'divichild_add_post_types');
	function divichild_add_post_types($post_types) {
		foreach(get_post_types() as $pt) {
			if (!in_array($pt, $post_types) and post_type_supports($pt, 'editor')) {
				$post_types[] = $pt;
			}
		} 
		return $post_types;
	}

	//Ajouter les metabox Divi pour nos CPT
	add_action('add_meta_boxes', 'divichild_add_meta_boxes');
	function divichild_add_meta_boxes() {
		foreach(get_post_types() as $pt) {
			if (post_type_supports($pt, 'editor')) {
				add_meta_box('et_settings_meta_box', __('Divi Custom Post Settings', 'Divi'), 'et_single_settings_meta_box', $pt, 'side', 'high');
			}
		} 
	}