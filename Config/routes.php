<?php

CroogoRouter::connect('/blog/archive/*', array('plugin' => 'node_navigation', 'controller' => 'node_navigation', 'action' => 'archive', 'type' => 'blog'));
