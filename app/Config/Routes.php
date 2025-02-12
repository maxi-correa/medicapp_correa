<?php

use App\Controllers\EmpleadoController;
use App\Models\EmpleadoModel;
use CodeIgniter\Debug\Toolbar\Collectors\Routes;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
#RUTAS DE TODOS LOS ROLES
// =====================================================
// TODOS
// =====================================================
$routes->get('/', 'Home::index');
$routes->post('/login', 'Home::login');
$routes->get('/salir', 'Home::salir');
$routes->get('/registro', 'Home::registro');
$routes->post('/registro', 'Home::registro_ingreso');
$routes->get('error', 'Home::errorView');
$routes->get('construccion', 'Home::construccion');
$routes->get('verificar-turnos-caducados', 'TurnoController::verificarTurnosCaducados');
$routes->get('verificar-casos-caducados', 'CasoController::verificarCasos');
$routes->get('medicos/informacion/(:segment)/(:any)/vacio','MedicoAuditorController::mostrarFormularioHorario/$1/$2');
$routes->get('medicos/informacion/(:segment)/(:any)','MedicoAuditorController::mostrarInformacionDia/$1/$2');
$routes->get('medicos/informacion/(:segment)','MedicoAuditorController::mostrarInformacion/$1');
$routes->post('medicos/informacion/(:segment)/(:segment)/guardar', 'MedicoAuditorController::guardarHorario/$1/$2');


// =====================================================
// ADMINISTRADOR
// =====================================================
$routes->group('', ['filter' => 'AdministradorFilter'], function ($routes) {
    
    $routes->get('guardarNumeroTramite/(:num)', 'CasoController::guardarNumeroTramite/$1');
    #INICIO 
    $routes->get('/menu-administrador', 'EmpleadoController::administrador');
    #.................................................Registrar turnos
    $routes->get('/registrar-turno', 'TurnoController::creoTurno');
    $routes->post('/validar', 'TurnoController::validar');
    #.................................................Turnos
    $routes->post('turnos/mostrar-turnos', 'TurnoController::mostrarTurnos');
    $routes->get('/mostrarHorario/(:segment)', 'TurnoController::mostrarTurnos/$1');
    $routes->post('registrar-turno/dia', 'TurnoController::obtenerDiaDeFecha');
    #.................................................Listar Casos
    $routes->get('/caso', 'CasoController::index');
    #.................................................visualizar caso 
    $routes->get('visualizarCasoA', 'CasoController::mostrarUnCasoAdmin');
    
    #..............................................Medicos Auditores 
    $routes->get('/medicos', 'MedicoAuditorController::listar');
    $routes->get('/medicos/crear', 'MedicoAuditorController::crear');
    $routes->post('medicos/guardar', 'MedicoAuditorController::guardar');
    $routes->get('/medicos/deshabilitar/(:any)', 'MedicoAuditorController::verMedico/$1');
    $routes->post('/medicos/deshabilitarTurnos/(:segment)/(:num)', 'MedicoAuditorController::deshabilitarTurnos/$1/$2');
    $routes->get('/medicos/deshabilitarTurnos/(:segment)/(:num)', 'MedicoAuditorController::deshabilitarTurnos/$1/$2');
    $routes->get('/medicos/habilitarMedico/(:any)', 'MedicoAuditorController::habilitarMedico/$1');
    $routes->post('/medicos/deshabilitarMedico/(:any)', 'MedicoAuditorController::deshabilitarMedico/$1');
    $routes->get('/medicos/deshabilitarMedico/(:any)', 'MedicoAuditorController::deshabilitarMedico/$1');
    $routes->post('/medicos/deshabilitarMedicoTemporal', 'MedicoAuditorController::deshabilitarMedicoTemporalmente');
    //$routes->post('/medicos/deshabilitar/(:any)', 'MedicoAuditorController::mostrarInformacion/$1');
    #.................................................asignar estado 
    $routes->get('/certificado/(:any)/(:any)', 'CertificadoController::verImagen/$1/$2');
    $routes->post('/certificado/update', 'CertificadoController::asignarEstadoCertificado');
    #.................................................Generar reportes
    $routes->get('/reportes', 'ReporteController::reportes');
    $routes->get('/reportes/certificados-emitidos', 'ReporteController::reporteTorta');

    //REPORTE CERTIFICADOS
    $routes->get('reportes/certificados','ReporteController::reporteCertificados');

    $routes->get('reportes/certificadosPeriodo', 'ReporteController::certificadosPeriodo');

    $routes->get('reportes/ausentismo/(:any)/(:any)', 'ReporteController::reporteAusentismo/$1/$2');
    $routes->post('reportes/exportar', 'ReporteController::exportar');
    $routes->post('reportes/exportar2', 'ReporteController::exportar2');
});

// =====================================================
// EMPLEADO
// =====================================================
$routes->group('', ['filter' => 'EmpleadoFilter'], function ($routes) {
    $routes->get('/menu-empleado', 'EmpleadoController::empleado');
    #.................................................Perfil / historial 
    $routes->get('/perfil', 'EmpleadoController::perfil');
    $routes->get('/perfil/historial','CasoController::historial');
    $routes->get('/perfil/modificarcontrasena' , 'EmpleadoController::vistaFormulario');
    $routes->post('/perfil/actualizarContrasena' , 'EmpleadoController::modificarContraseña');
    #.................................................Registrar Caso
    $routes->get('/registrar-caso', 'CasoController::registrarCaso');
    $routes->post('/registrar-caso', 'CasoController::validar');
    #.................................................Ver caso 
    $routes->get('visualizarCasoE', 'CasoController::mostrarUnCasoEmpleado');
    #..............................................Certificado
    $routes->get('certificado', 'CertificadoController::formulario');
    $routes->post('certificado', 'CertificadoController::validar_formulario');
    $routes->get('certificado/(:num)', 'CertificadoController::verImagen/$1');
    $routes->get('/buscarMedico/(:num)', 'CertificadoController::buscarMedico/$1');
    $routes->get('/verificardias/(:num)/(:num)', 'CertificadoController::verificarDias/$1/$2');
});

// =====================================================
// MEDICO AUDITOR
// =====================================================
$routes->group('', ['filter' => 'MedicoFilter'], function ($routes) {
    $routes->get('/menu-medico', 'MedicoAuditorController::index1');

    #.................................................visualizar caso 
    $routes->get('visualizarCasoM', 'CasoController::mostrarUnCasoMedico');
    
    /* $routes->get('ver-turnoMed', 'CasoController::mostrarUnCaso'); */

    #.................................................Genera observacion
    $routes->get('/seguimiento', 'SeguimientoController::index');
    $routes->post('/seguimiento', 'SeguimientoController::validar');


    $routes->get('guardarIdTurno/(:num)', 'TurnoController::guardarIdTurno/$1');
});



#.................................................Listar Turnos
$routes->get('/turnos/listar', 'TurnoController::listar');
$routes->get('turnos/listar/(:num)', 'TurnoController::listar/$1');
$routes->get('/download/(:any)/(:any)', 'CertificadoController::descargarCertificado/$1/$2');

#..................................Ver horarios medico auditor
$routes->get('misHorarios', 'MedicoAuditorController::obtenerHorariosMedico');





