<?php
/**
 * Onyx
 *
 * @link      <http://dei.estg.ipleiria.pt>
 * @copyright Copyright (c) 2013 Cláudio Esperança <cesperanc@gmail.com>, Diogo Serra <2120915@my.ipleiria.pt>
 * @license   This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; If not, see <http://www.gnu.org/licenses/>.
 */
namespace Onyx;

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Onyx\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'services' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/services',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Onyx\Controller',
                        'controller'    => 'Services',
                        'action'        => 'soap',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '[/:action]',
                            'constraints' => array(
                                'controller' => 'Onyx\Controller\Services',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'Onyx\Controller',
                                'controller'    => 'Services',
                                'action'        => 'soap',
                            ),
                        ),
                    ),
                    'wsdl' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => '/soap/wsdl',
                            'constraints' => array(
                                'controller' => 'Onyx\Controller\Services',
                                'action'     => 'wsdl',
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'Onyx\Controller',
                                'controller'    => 'Services',
                                'action'        => 'wsdl',
                            ),
                        ),
                    ),
                    'xsl' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => '/soap/xsl',
                            'constraints' => array(
                                'controller' => 'Onyx\Controller\Services',
                                'action'     => 'xsl',
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'Onyx\Controller',
                                'controller'    => 'Services',
                                'action'        => 'xsl',
                            ),
                        ),
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /onyx/:controller/:action
            'onyx' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/onyx',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Onyx\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        )
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Onyx\Controller\Index' => 'Onyx\Controller\IndexController',
            'Onyx\Controller\Services' => 'Onyx\Controller\ServicesController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'onyx/index/index'        => __DIR__ . '/../view/onyx/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ .'/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    ),
);
