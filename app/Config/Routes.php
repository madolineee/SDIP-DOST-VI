<?php

use App\Controllers\DirectoryController;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('home', 'Home::home');

// DIRECTORY //

$routes->get('/directory/home', to: 'DirectoryController::index');

$routes->get('/directory/regional_offices', 'RegionalOfficeController::regionalOffices');
$routes->get('/directory/regional_offices/create', 'RegionalOfficeController::regionalOfficesCreate');
$routes->post('/directory/regional_offices/store', 'RegionalOfficeController::regionalOfficesStore');
$routes->get('/directory/regional_offices/edit/(:num)', 'RegionalOfficeController::regionalOfficesEdit/$1');
$routes->post('/directory/regional_offices/update/(:num)', 'RegionalOfficeController::regionalOfficesUpdate/$1');
$routes->post('/directory/regional_offices/delete/(:num)', 'RegionalOfficeController::deleteRegionalOffice/$1');

$routes->get('/directory/nga', 'NgaController::nga');
$routes->get('directory/nga/create', 'NgaController::ngaCreate');
$routes->post('/directory/nga/store', 'NgaController::ngaStore');

$routes->get('/directory/academes', 'AcademeController::academes');
$routes->post('/directory/academes/store', 'AcademeController::academesStore');

$routes->get('/directory/lgus', 'LguController::lgu');
$routes->post('/directory/lgus/store', 'LguController::lguStore');

$routes->get('/directory/sucs', 'DirectoryController::suc');
$routes->get('/directory/business_sector', 'NgoController::businessSector');
$routes->post('/directory/business_sector/store', 'NgoController::businessSectorStore');
$routes->get('/directory/wide_contacts', 'WideContactController::wideContacts');


// INSTITUTION //
$routes->get('/institution/home', 'InstitutionController::index');
$routes->get('/institution/create', 'InstitutionController::create_institution');
$routes->post('/institution/store', 'InstitutionController::storeInstitution');
$routes->get('/institution/edit/(:num)', 'InstitutionController::edit/$1');
$routes->post('/institution/update/(:num)', 'InstitutionController::update/$1');
$routes->get('/institution/delete/(:num)', 'InstitutionController::delete/$1');
$routes->get('institution/view/(:num)', 'InstitutionController::view/$1');

$routes->get('/institution/projects/index', 'ProjectsController::projects');
$routes->get('/institution/projects/create', 'ProjectsController::create');
$routes->post('/institution/projects/store', 'ProjectsController::storeProjects');
$routes->get('/institution/projects/edit/(:num)', 'ProjectsController::edit/$1');
$routes->post('/institution/projects/update/(:num)', 'ProjectsController::update/$1');
$routes->get('/institution/projects/delete/(:num)', 'ProjectsController::delete/$1');
$routes->get('institution/projects/view/(:num)', 'ProjectsController::view/$1');


$routes->get('/institution/balik_scientist', 'BalikScientistController::balik_scientist');
$routes->get('/institution/consortium', 'ConsortiumController::consortium');
$routes->get('/institution/ncrp_members', 'NcrpController::ncrp_members');
$routes->get('/institution/research_centers', 'ResearchCentersController::research_centers');
