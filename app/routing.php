<?php
/**
 * This file hold all routes definitions.
 *
 * PHP version 7
 *
 * @author   WCS <contact@wildcodeschool.fr>
 *
 * @link     https://github.com/WildCodeSchool/simple-mvc
 */

$routes = [
    'Admin' => [ // Controller
        ['login', '/login', ['POST', 'GET']],  // action, url, method
        ['admin', '/admin', ['POST', 'GET']],
        ['logout', '/admin/logout', 'GET'],
        ['concerts', '/admin/concerts', ['POST', 'GET']],
        ['benevolContentUpdated', '/admin/logged', 'POST'],
        ['adminBenevol', '/admin/benevole', ['POST', 'GET']],
        ['adminInfos', '/admin/infos', ['POST', 'GET']],
        ['adminArtist', '/admin/artistes', ['POST', 'GET']],
    ],

    'User' => [
        ['index', '/', 'GET'],
        ['concerts', '/concerts', 'GET'],
        ['artists', '/artistes', 'GET'],
        ['infos', '/infos', 'GET'],
        ['benevol', '/benevole', 'GET'],
        ['insertedBenevol', '/benevole', 'POST'],
        ['billetterie', '/billetterie', 'GET'],
    ]
];
