<?php

/**
 * Routes
 */
  Croogo::hookRoutes('NodeNavigation');

/**
 * Component
 */
	Croogo::hookComponent('*', 'NodeNavigation.NodeNavigation');

/**
 * Helper
 */
	Croogo::hookHelper('*', 'NodeNavigation.NodeNavigation');
	
/**
 * Admin Menu
 *
 */
 	CroogoNav::add('content.children.list.children.node_extras', array(
    'title' => __('Page Tree'),
    'url' => array(
 			'plugin' => 'node_navigation',
 			'controller' => 'node_navigation',
 			'action' => 'page_tree',
 		),
    'access' => array('admin'),
  ));