<?php
/**
 * NodeNavigation Plugin Activation
 * Activation class for NodeNavigation plugin.
 *
 * @package  Croogo
 * @version  1.5
 * @author   Paul Gardner <paul@webbedit.co.uk>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.webbedit.co.uk
 */
class NodeNavigationActivation {

/**
 * onActivate will be called if this returns true
 *
 * @param  object $controller Controller
 * @return boolean
 */
  public function beforeActivation(&$controller) {
    return true;
  }

/**
 * Called after activating the plugin in ExtensionsPluginsController::admin_toggle()
 *
 * @param object $controller Controller
 * @return void
 */
  public function onActivation(&$controller) {
    $controller->Croogo->addAco('NodeNavigation');
    $controller->Croogo->addAco('NodeNavigation/archive', array('public')); 
  }

/**
 * onDeactivate will be called if this returns true
 *
 * @param  object $controller Controller
 * @return boolean
 */
  public function beforeDeactivation(&$controller) {
    return true;
  }

/**
 * Called after deactivating the plugin in ExtensionsPluginsController::admin_toggle()
 *
 * @param object $controller Controller
 * @return void
 */
  public function onDeactivation(&$controller) {
    $controller->Croogo->removeAco('NodeNavigation');
	}
		
/**
 * Schema
 *
 * @param string sql action
 * @return void
 * @access protected
 */
	protected function _schema($action = 'create') {
		App::uses('File', 'Utility');
		App::import('Model', 'CakeSchema', false);
		App::import('Model', 'ConnectionManager');
		$db = ConnectionManager::getDataSource('default');
		if(!$db->isConnected()) {
			$this->Session->setFlash(__('Could not connect to database.'), 'default', array('class' => 'error'));
		} else {
			CakePlugin::load('NodeExtras'); //is there a better way to do this?
			$schema =& new CakeSchema(array('name'=>'nodeExtras', 'plugin'=>'NodeExtras'));
			$schema = $schema->load();
			foreach($schema->tables as $table => $fields) {
			  if($action == 'create') {
			  	$sql = $db->createSchema($schema, $table);
			  } else {
  			  $sql = $db->dropSchema($schema, $table);
			  }
				$db->execute($sql);
			}
		}
	}
  
}