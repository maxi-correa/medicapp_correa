<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EmpleadoModel;
use App\Models\MedicoAuditorModel;
use App\Models\TipoCategoriaModel;
use App\Models\EmpleadoCategoriaModel;
use App\Models\CasoModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Session\Session;

class Home extends BaseController
{
    protected $empleadoModel;
    protected $medicoModel;
    protected $tipoCategoriaModel;
    protected $empleadoCategoriaModel;
    protected $casoModel;

    public function __construct()
    {
        $this->empleadoModel = new EmpleadoModel();
        $this->medicoModel = new MedicoAuditorModel();
        $this->tipoCategoriaModel = new TipoCategoriaModel();
        $this->empleadoCategoriaModel = new EmpleadoCategoriaModel();
        $this->casoModel = new CasoModel();
    }

    private function generateRedirectURL($rol)
    {
        switch ($rol) {
            case 'Empleado Común':
                return base_url('menu-empleado');
            case 'Admin. Medicina Laboral':
                return base_url('caso');
            case 'Medico':
                return base_url('turnos/listar');
            default:
                return base_url();
        }
    }

    private function generateMenu($rol)
    {
        $menu = [];
        switch ($rol) {
            case 'Admin. Medicina Laboral':
                $menu[] = ['label' => 'Inicio', 'url' => base_url('/caso')];
                $menu[] = ['label' => 'Turnos', 'url' => base_url('turnos/listar')];
                $menu[] = ['label' => 'Medicos', 'url' => base_url('medicos')];
                $menu[] = ['label' => 'Reportes', 'url' => base_url('reportes')];
                break;
            case 'Empleado Común':
                $menu[] = ['label' => 'Inicio', 'url' => base_url('menu-empleado')];
                $menu[] = ['label' => 'Perfil', 'url' => base_url('perfil')];
                break;
            case 'Medico':
                $menu[] = ['label' => 'Inicio', 'url' => base_url('turnos/listar')];
                $menu[] = ['label' => 'Notificar Ausencia', 'url' => base_url('construccion')];
                break;
        }
        return $menu;
    }

    public function index()
    {
        $mensaje = session('mensaje');
        return view('/page/auth/login', ['mensaje' => $mensaje]);
    }

    public function login()
    {
        $legajo_matricula = $this->request->getPost('input');
        $password = $this->request->getPost('contrasenia');

        log_message('debug', 'Legajo: ' . $legajo_matricula . ' Contraseña: ' . $password);

        if (ctype_digit($legajo_matricula)) {
            $empleado = $this->empleadoModel->obtenerEmpleado($legajo_matricula);

            $casoActivo = $this->casoModel->obtenerCasoActivo($legajo_matricula);

            if ($empleado && password_verify($password, $empleado['contrasenia'])) {
                $session = session();
                $session->set([
                    'legajo' => $empleado['legajo'],
                    'nombre' => $empleado['nombre'],
                    'apellido' => $empleado['apellido'],
                    'rol' => $empleado['rol'],
                    'menu' => $this->generateMenu($empleado['rol']),
                    'isLoggedIn' => true,
                ]);

                if ($casoActivo) {
                    $session->set([
                        'numeroTramite' => $casoActivo
                    ]);
                }
                return redirect()->to($this->generateRedirectURL($empleado['rol']));
            }
        } elseif (preg_match('/^M\d+$/', $legajo_matricula)) {
            $medico = $this->medicoModel->obtenerMedico($legajo_matricula);
            log_message('debug', 'Contraseña ingresada: ' . $password);
            log_message('debug', 'Hash almacenado: ' . $medico['contrasenia']);
            // Validar si el médico está habilitado
            if ($medico && $medico['habilitado'] != 1) {
                return redirect()->to('')
                    ->with('mensaje', 'Este médico no está habilitado para acceder al sistema.')
                    ->with('mensaje_tipo', 'error');
            }
            if ($medico && password_verify($password, $medico['contrasenia'])) {
                $session = session();
                $session->set([
                    'matricula' => $medico['matricula'],
                    'nombre' => $medico['nombre'],
                    'apellido' => $medico['apellido'],
                    'rol' => 'Medico',
                    'menu' => $this->generateMenu('Medico'),
                    'isLoggedIn' => true
                ]);
                return redirect()->to($this->generateRedirectURL('Medico'));
            }
        } else {
            return redirect()->to('')
                ->with('mensaje', 'Datos inválidos, por favor intente nuevamente.')
                ->with('mensaje_tipo', 'error');
        }
        return redirect()->to('')
            ->with('mensaje', 'Datos inválidos, por favor intente nuevamente.')
            ->with('mensaje_tipo', 'error');
    }

    public function salir()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('');
    }
    public function registro()
    {
        $mensaje = session('mensaje');
        return view('/page/auth/register', ['mensaje' => $mensaje]);
    }

    public function registro_ingreso()
    {
        $legajo = $this->request->getPost('legajo');
        $email = $this->request->getPost('email');
        $pass = $this->request->getPost('contrasenia');

        $empleadoExistente = $this->empleadoModel->obtenerEmpleadoPorLegajoYCorreo($legajo, $email);

        if (!$empleadoExistente) {
            return redirect()->back()
                ->with('mensaje', 'No formas parte de la empresa.')
                ->with('mensaje_tipo', 'error');;
        }

        if (!empty($empleadoExistente['contrasenia'])) {
            return redirect()->back()
                ->with('mensaje', 'El empleado ya tiene una cuenta registrada.')
                ->with('mensaje_tipo', 'info');;
        }

        if ($empleadoExistente['rol'] == 'Empleado Común') {
            $categorias = $this->tipoCategoriaModel->findAll();
            foreach ($categorias as $categoria) {
                $data = [
                    'legajo' => $legajo,
                    'idCategoria' => $categoria['idCategoria'],
                    'diasDisponibles' => $categoria['limitedias'],
                ];
                $this->empleadoCategoriaModel->insert($data);
            }
        }
        $empleadoExistente['contrasenia'] = password_hash($pass, PASSWORD_DEFAULT);
        $this->empleadoModel->update($legajo, $empleadoExistente);

        return redirect()->to('')
            ->with('mensaje', 'Empleado registrado con éxito.')
            ->with('mensaje_tipo', 'success');
    }

    public function errorView()
    {
        return view('errors/error_view');
    }

    public function construccion()
    {
        return view('errors/construccion');
    }
}
