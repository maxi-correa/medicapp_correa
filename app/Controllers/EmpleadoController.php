<?php

namespace App\Controllers;

use App\Models\EmpleadoModel;
use App\Controllers\BaseController;
use App\Models\CasoModel;
use App\Models\FamiliarModel;
use CodeIgniter\HTTP\ResponseInterface;
use Carbon\Carbon;

class EmpleadoController extends BaseController
{
    protected $empleadoModel;
    protected $familiarModel;
    protected $casoModel;

    public function __construct()
    {
        $this->empleadoModel = new EmpleadoModel();
        $this->familiarModel = new FamiliarModel();
        $this->casoModel = new CasoModel();
        Carbon::setLocale('es');
    }

    public function empleado()
    {
        $session = session();
        $legajo = $session->get('legajo');
        $casoActivo = $this->casoModel->where('legajo', $legajo)
            ->where('idEstado', '2')
            ->first();

        $casoPendiente = $this->casoModel->where('legajo', $legajo)
            ->where('idEstado', '1')
            ->first();

        if ($casoActivo) {
            $session->set('numeroTramite', $casoActivo['numeroTramite']);
        } elseif ($casoPendiente) {
            $session->set('numeroTramite', $casoPendiente['numeroTramite']);
        }

        $disableRegistrarCaso = ($casoActivo !==  null || $casoPendiente !== null);
        $disableVerCaso = !($casoActivo || $casoPendiente);

        return view('empleado/menu', ['disable_registrar' => $disableRegistrarCaso, 'disable_ver_caso' => $disableVerCaso]);
    }

    public function perfil()
    {
        $session = session();
        $legajo = $session->get('legajo');
        $dataEmpleado = $this->empleadoModel->find($legajo);
        $familia = $this->familiarModel->obtenerFamiliaresDeEmpleado($legajo);
        return view('empleado/perfil', ['empleado' => $dataEmpleado, 'familiares' => $familia]);
    }


    public function vistaFormulario()
    {
        return view('empleado/cambiarContraseña');
    }

    public function modificarContraseña()
    {
        $session = session();
        $empleadoModel = new EmpleadoModel();

        // Obtener el legajo del usuario autenticado
        $legajo = $session->get('legajo');
        $contrasenaActual = $session->get('contrasenia');
        // Obtener la nueva contraseña y confirmación del formulario
        $nuevaContrasena = $this->request->getPost('nueva_contrasena');
        $confirmarContrasena = $this->request->getPost('confirmar_contrasena');
       // Encriptar la nueva contraseña
       $nuevaContrasenaEncriptada = password_hash($nuevaContrasena, PASSWORD_BCRYPT);


        // Verificar que la nueva contraseña no esté vacía
        if (empty($nuevaContrasena)) {
            $session->setFlashdata('error', 'Debe ingresar una nueva contraseña.');
            return redirect()->back(); // Redirige de vuelta al formulario
        }else       // Verifica que la nueva contraseña y la confirmación sean iguales
        
        if ($nuevaContrasena !== $confirmarContrasena) {
            $session->setFlashdata('error', 'Las contraseñas no coinciden.');
            return redirect()->back(); // Redirige de vuelta al formulario
        } else 
        // Verificar si la contraseña actual es correcta
        if (password_verify($nuevaContrasena, $contrasenaActual)) {
            $session->setFlashdata('error', 'La nueva contraseña es la misma que la actual.');
            return redirect()->back(); // Redirige de vuelta al formulario
        }else if ($contrasenaActual !== $nuevaContrasenaEncriptada){
        // Actualizar la contraseña en la base de datos
        if ($empleadoModel->updateContrasena($legajo,  $nuevaContrasenaEncriptada)) {
            echo $legajo;
            echo $nuevaContrasenaEncriptada;
            $session->setFlashdata('success', 'Contraseña actualizada correctamente.');
            return redirect()->to('/perfil'); // Redirige al perfil después de actualizar la contraseña
        } else {
            $session->setFlashdata('error', 'Hubo un problema al actualizar la contraseña.');
            return redirect()->back(); // Redirige de vuelta al formulario
        }
        }

    }
}

