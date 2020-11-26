<?php

defined ( "LIBRARY_PATH" ) or define ( "LIBRARY_PATH", realpath ( __DIR__ . '/library' ) );

defined ( "MODELS_PATH" ) or define ( "MODELS_PATH", realpath ( LIBRARY_PATH . '/models' ) );
defined ( "CONTROLLER_PATH" ) or define ( "CONTROLLER_PATH", realpath ( LIBRARY_PATH . '/controller' ) );
defined ( "REPOSITORIES_PATH" ) or define ( "REPOSITORIES_PATH", realpath ( LIBRARY_PATH . '/repositories' ) );

defined ( "TEMPLATES_PATH" ) or define ( "TEMPLATES_PATH", realpath ( __DIR__ . '/templates' ) );

?>