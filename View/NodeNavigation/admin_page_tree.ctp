<?php

$this->extend('/Common/admin_index');

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb(__d('croogo', 'Content'), array('plugin' => 'nodes', 'controller' => 'nodes', 'action' => 'index'))
	->addCrumb(__d('croogo', $title_for_layout), array('plugin' => 'node_navigation', 'controller' => 'node_navigation', 'action' => 'page_tree'));
?>

<?php $this->start('actions'); ?>
<?php
	echo $this->Croogo->adminAction(
		__d('croogo', 'New Page'),
		array('plugin' => 'nodes', 'controller' => 'nodes', 'action' => 'add', 'page'),
		array('button' => 'success')
	);
?>
<?php $this->end('actions'); ?>

<?php
	if (isset($this->params['named'])) {
		foreach ($this->params['named'] as $nn => $nv) {
			$this->Paginator->options['url'][] = $nn . ':' . $nv;
		}
	}

  echo $this->Form->create(
  	'Node',
  	array(
  		'url' => array('plugin' => 'nodes', 'controller' => 'nodes', 'action' => 'process'),
  		'class' => 'form-inline'
  	)
  );
?>
<table class="table table-striped">
<?php
	$tableHeaders = $this->Html->tableHeaders(array(
		'',
		__d('croogo', 'Id'),
		__d('croogo', 'Title'),
		__d('croogo', 'Status'),
		__d('croogo', 'Actions'),
	));
?>
	<thead>
		<?php echo $tableHeaders; ?>
	</thead>
	<?php
	$rows = array();
	foreach ($nodesTree as $nodeId => $nodeTitle):
		$actions = array();
		$actions[] = $this->Croogo->adminRowAction('', array(
			'action' => 'moveup', $nodeId
			), array(
			'icon' => 'chevron-up',
			'tooltip' => __d('croogo', 'Move up'),
		));
		$actions[] = $this->Croogo->adminRowAction('', array(
			'action' => 'movedown', $nodeId,
			), array(
			'icon' => 'chevron-down',
			'tooltip' => __d('croogo', 'Move down'),
		));
		$actions[] = $this->Croogo->adminRowActions($nodeId);
		$actions[] = $this->Croogo->adminRowAction('', array(
			'plugin' => 'nodes', 'controller' => 'nodes', 'action' => 'edit', $nodeId,
			), array(
			'icon' => 'pencil', 'tooltip' => __d('croogo', 'Edit this page'),
		));
		$actions[] = $this->Croogo->adminRowAction('', '#Node' . $nodeId . 'Id',
			array(
				'icon' => 'trash',
				'tooltip' => __d('croogo', 'Delete this page'),
				'rowAction' => 'delete',
			),
			__d('croogo', 'Are you sure?')
		);
		$actions = $this->Html->div('item-actions', implode(' ', $actions));
		$rows[] = array(
			$this->Form->checkbox('Node.' . $nodeId . '.id'),
			$nodeId,
			$nodeTitle,
			$this->element('admin/toggle', array(
				'id' => $nodeId,
				'status' => $nodesStatus[$nodeId],
			)),
			$actions,
		);
	endforeach;

	echo $this->Html->tableCells($rows);
	?>

</table>
<div class="row-fluid">
	<div id="bulk-action" class="control-group">
		<?php
			echo $this->Form->input('Node.action', array(
				'div' => 'input inline',
				'label' => false,
				'options' => array(
					'publish' => __d('croogo', 'Publish'),
					'unpublish' => __d('croogo', 'Unpublish'),
					'delete' => __d('croogo', 'Delete'),
				),
				'empty' => true,
			));
		?>
		<div class="controls">
			<?php echo $this->Form->end(__d('croogo', 'Submit')); ?>
		</div>
	</div>
</div>