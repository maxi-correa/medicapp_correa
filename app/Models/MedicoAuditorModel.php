<?php

namespace App\Models;

use CodeIgniter\Model;
use PhpParser\Node\Expr\Cast\String_;

class MedicoAuditorModel extends Model
{
    protected $table            = 'medicos_auditores';
    protected $primaryKey       = 'matricula';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nombre', 'apellido', 'dni', 'fechaNacimiento', 'gmail', 'telefono', 'especialidad', 'contrasenia'];

    public function hashPassword(array $data): array
    {
        foreach ($data as &$empleado) {
            if (!empty($empleado['contrasenia'])) {
                $empleado['contrasenia'] = password_hash($empleado['contrasenia'], PASSWORD_BCRYPT);
            }
        }
        return $data;
    }

    public function hashPasswordMedico($medico)
    {
        if (!empty($medico['contrasenia'])) {
            $medico['contrasenia'] = password_hash($medico['contrasenia'], PASSWORD_BCRYPT);
        }
        return $medico;
    }

    public function crearMedico(array $data)
    {
        if (!empty($data['contrasenia'])) {
            $data['contrasenia'] = password_hash($data['contrasenia'], PASSWORD_BCRYPT);
        }
        return $this->insert($data);
    }


    public function obtenerMedico($matricula)
    {
        return $this->where('matricula', $matricula)->first();
    }

    public function obtenerMedicos()
    {
        return $this->findAll();
    }

    // Método para obtener los horarios de un médico por su matrícula
    // Método para obtener los horarios de un médico, combinando ambas tablas
    public function obtenerHorariosPorMatricula($matricula)
    {
        return $this->db->table('horarios')
            ->select('horarios.diaSemana, horarios.horaInicio, horarios.horaFin, horarios.duracion, 
                                  medicos_auditores.nombre, medicos_auditores.apellido, medicos_auditores.especialidad')
            ->join('medicos_auditores', 'horarios.matricula = medicos_auditores.matricula')
            ->where('horarios.matricula', $matricula)
            ->orderBy('horarios.diaSemana', 'ASC')
            ->get()
            ->getResultArray(); // Devuelve un array asociativo
    }

    public function obtenerHorarioMedico($matricula){
        $sql = $this->db->query("SELECT * FROM `horarios` WHERE matricula = '$matricula'");
        return $sql->getResult();
    }

    public function obtenerMedicosHabilitados(){
        $sql = $this->db->query("SELECT * FROM medicos_auditores ma WHERE ma.habilitado = 1");
        return $sql->getResultArray();
    }


    public function deshabilitarMedico($matricula){
        $sql = "UPDATE medicos_auditores SET habilitado = 0 WHERE matricula = ?";
        return $this->db->query($sql, [$matricula]); 
    }

    public function habilitarMedico($matricula){
        $sql = "UPDATE medicos_auditores SET habilitado = 1 WHERE matricula = ?";
        return $this->db->query($sql, [$matricula]); 
    }


}
