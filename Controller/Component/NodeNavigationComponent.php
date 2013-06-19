<?php

App::uses('Component', 'Controller');

/**
 * NodeNavigation Component
 *
 * @category Component
 * @package  Croogo
 * @version  1.0
 * @author   Paul Gardner <paul@webbedit.co.uk>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.webbedit.co.uk
 */
class NodeNavigationComponent extends Component {
  
/**
 * Enabled
 *
 * @var boolean
 * @access public
 */
  public $enabled = true;
   
/**
* Blocks data: contains parsed value of bb-code like strings
*
* @var array
* @access public
*/
 	public $blocksData = array(
 		'nodeArchives' => array(),
 	);
 	  
/**
 * nodeArchives for layout
 *
 * @var string
 * @access public
 */
  public $nodeArchivesForLayout = array();

/**
 * Startup
 *
 * @param object $controller instance of controller
 * @return void
 */
  public function startup(Controller $controller) {
    $this->controller = $controller;
    if (isset($controller->Node)) {
    	$this->Node = $controller->Node;
    } else {
    	$this->Node = ClassRegistry::init('Nodes.Node');
    }
    
    if (!isset($controller->request->params['admin']) && !isset($controller->request->params['requested']) && $this->enabled) {
      $this->processBlocksData($controller->Blocks->blocksForLayout);
      $this->nodeArchives();
    }
  }

/**
 * beforeRender
 *
 * @param object $controller instance of controller
 * @return void
 */
  public function beforeRender(Controller $controller) {
    $this->controller = $controller;
    if (!isset($controller->request->params['admin']) && !isset($controller->request->params['requested']) && $this->enabled) {
    $controller->set(array(
      'nodeNavigation' => $this->nodeNavigation(),
      'nodeArchives_for_layout' => $this->nodeArchivesForLayout,
    ));
    }
  }
  
/**
 * nodeNavigation
 *
 * @param object $controller instance of controller
 * @return void
 */
  public function nodeNavigation() {
    $output = array();
    if($this->controller->request->action == 'view') {
      $submenuParentId = false;
      $submenu = array();
      
      $nodeId = $this->Node->field('id', array('slug'=>$this->controller->request->slug));
      $path = $this->Node->getPath($nodeId, array('id', 'title', 'slug', 'type'));
      $children = $this->Node->children($nodeId, true);
      if(!empty($path) && count($path) > 1) {
        $submenuParentId = $path[1]['Node']['id'];
        if($nodeId == $submenuParentId) {
          $submenu = $children;
        } else {
          $submenu = $this->Node->children($submenuParentId, true);
        }
      }
      
      $output = array(
        'path' => $path,
        'children' => $children,
        'submenu' => array(
          'parentId' => $submenuParentId,
          'links' => $submenu
        )
      );
    }
    return $output;
  }
    
/**
 * nodeArchives
 *
 * @param object $controller instance of controller
 * @return void
 */
  public function nodeArchives() {
    $archive = $this->Node->find('all', array(
      'fields' => array("Node.type, DATE_FORMAT(Node.created, '%Y-%m') AS month", "COUNT(Node.id) AS count"),
      'conditions' => array('Node.type !=' => 'attachment'),
      'group' => array('Node.type, month'),
      'order' => array('Node.type, month'),
      'recursive' => -1
    ));
    foreach($archive AS $typeMonth) {
      $this->nodeArchivesForLayout[$typeMonth['Node']['type']][] = $typeMonth[0];
    }
  }
  
/**
 * Process blocks for bb-code like strings
 * Modified version of CroogoComponent::processBlocksData()
 *
 * @param array $regions (CroogoComponent::blocks_for_layout)
 * @return void
 */
	public function processBlocksData($regions) {
		foreach ($regions as $blocks) {
			foreach ($blocks as $block) {
				$this->blocksData['nodeArchives'] = Set::merge(
					$this->blocksData['nodeArchives'], 
					$this->controller->Blocks->parseString('nodeArchive|na', $block['Block']['body'])
				);
			}
		}
	}
    
}