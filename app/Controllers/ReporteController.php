<?php

namespace App\Controllers; //Ubicación donde está el controlador

use App\Models\MedicoModel;
use App\Models\Reporte_model;
use App\Models\CertificadoModel;
use App\Models\MedicoTratanteModel;
use App\Models\EmpleadoModel;
use CodeIgniter\Controller;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use SebastianBergmann\CodeCoverage\Report\Xml\Report;

class ReporteController extends BaseController
{
    protected $medicoModel;
    protected $certificadoModel;
    protected $empleadoModel;

    public function __construct()
    {
        $this->medicoModel = new MedicoTratanteModel();
        $this->certificadoModel = new CertificadoModel();
        $this->empleadoModel = new EmpleadoModel();
    }

    public function reporteTorta()
    {
        $medicos = $this->medicoModel->findAll();
        $medicosConCertificados = [];

        foreach ($medicos as $medico) {
            $cantidadCertificaddos = $this->certificadoModel->countByMatricula($medico['matricula']);
            $medicosConCertificados[] = [
                'nombre' => $medico['matricula'] . ' | ' . $medico['apellido'] . ' ' . $medico['nombre'],
                'cantidad' => $cantidadCertificaddos
            ];
        }

        usort($medicosConCertificados, function ($a, $b) {
            return $b['cantidad'] - $a['cantidad'];
        });

        $topMedicos = array_slice($medicosConCertificados, 0, 6);
        $otrosMedicos = array_slice($medicosConCertificados, 6);
        $topNombres = array_column($topMedicos, 'nombre');
        $topCantidades = array_column($topMedicos, 'cantidad');
        return view('administrador/reporte',  [
            'nombres' => $topNombres,
            'cantidades' => $topCantidades,
            'otrosMedicos' => $otrosMedicos
        ]);
    }

    public function reportes()
    {
        return view('administrador/reportes');
    }

    public function index()
    {
        $data = $this->bajarCaso();
        return view('page/home/reportes', $data);
    }


    public function configPhp()
    { //Function para verificar que la biblioteca funciona está instalada y funcionando
        if (class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            echo "PhpSpreadsheet está disponible.";
        } else {
            echo "PhpSpreadsheet no está instalado correctamente.";
        }
    }

    public function generarReporte()
    {

        $phpExcel = new Spreadsheet();

        $phpExcel->getProperties()->setCreator("MIRKO");

        $hoja = $phpExcel->getActiveSheet(); //Defino la hoja en la que voy a trabajar

        //$hoja->setCellValue('A1', 'IMG'); //celda-fila , valor
        $hoja->mergeCells('D2:G2');
        $hoja->setCellValue('D2', 'GESTIÓN DE MEDICINA LABORAL');
        $hoja->getStyle('D2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $hoja->getStyle('D2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $hoja->getStyle('D2')->getFont()->setSize(20);
        $hoja->getStyle('D2')->getFont()->setName('Arial');


        //Defino el rango de celdas
        $rango = 'B2:D2';

        // Modificar el color de fondo y de texto de la celda B1
        $hoja->getStyle($rango)->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'a9a9a9'], // Fondo Gris
            ],
            'font' => [
                'color' => ['rgb' => '000000'], // Texto azul
            ]
        ]);

        //Cabecera de la tabla configuración de estilos
        $hoja->getStyle('B5:F5')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '49564c'], // 
            ],
            'font' => [
                'color' => ['rgb' => 'f8f9fa'], // 
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Centrar horizontalmente
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, // Centrar verticalmente
            ]
        ]);


        //Configuración de estilos de la tabla
        $styleArray = [
            'borders' => [
                'allborders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000']
                ]
            ]

        ];

        //Definición de tabla
        $hoja->setCellValue('B5', 'LEGAJO');
        $hoja->getColumnDimension('B')->setWidth(20);
        $hoja->setCellValue('C5', 'SECTOR');
        $hoja->getColumnDimension('C')->setWidth(20);
        $hoja->setCellValue('D5', 'NOMBRE');
        $hoja->getColumnDimension('D')->setWidth(20);
        $hoja->setCellValue('E5', 'APELLIDO');
        $hoja->getColumnDimension('E')->setWidth(20);
        $hoja->setCellValue('F5', 'CANTIDAD DE AUSENCIA');
        $hoja->getColumnDimension('F')->setWidth(25);

        // Agregar una imagen
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo de la empresa');
        $drawing->setPath(FCPATH . 'assets\img\logo.png'); // Ruta de la imagen en el servidor // FCPATH Apunta a la carpeta public
        $drawing->setHeight(100); // Altura de la imagen
        $drawing->setCoordinates('C2'); // Celda donde aparecerá la imagen
        $drawing->setWorksheet($phpExcel->getActiveSheet());

        //Cambio el alto de la celda
        $hoja->getRowDimension(2)->setRowHeight(80); // Establece la altura de la fila 1 a 40 puntos


        //Generar dinamicamente la tabla con los datos de la consulta

        //Contador
        $i = 6;
        //Consulta
        $datos = $this->bajarCaso();

        /*
        foreach($datos as $dato){
            $hoja->setCellValue('B'.$i, $dato->legajo);
            $hoja->getColumnDimension('B')->setWidth(20);
            $hoja->setCellValue('C'.$i, $dato->nombre);
            $hoja->getColumnDimension('C')->setWidth(20);
            $hoja->setCellValue('D'.$i, $dato->apellido);
            $hoja->getColumnDimension('D')->setWidth(20);
            $hoja->setCellValue('E'.$i, $dato->sector);
            $hoja->getColumnDimension('E')->setWidth(20);
            $hoja->setCellValue('F'.$i, $dato->fechaDesde);
            $hoja->getColumnDimension('F')->setWidth(25);
            $hoja->setCellValue('G'.$i, $dato->fechaHasta);
            $hoja->getColumnDimension('G')->setWidth(25);
            $hoja->setCellValue('H'.$i, $dato->Cantidaddeausencias);
            $hoja->getColumnDimension('H')->setWidth(25);
            $i++;
        }*/


        // Crear el archivo Excel y guardarlo
        $writer = new Xlsx($phpExcel);
        $fileName = 'pruebaReporte.xlsx';

        // Para forzar la descarga en el navegador
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function bajarCaso()
    {

        $caso = new Reporte_model();
        $casos = $caso->getReporteAusencias();
        if (empty($casos)) {
            echo "No se encontraron casos";
            return;
        }

        $data['casos'] = $casos;
        return $data;
    }

    public function obtenerCasosPorFechas()
    {
        $fechaInicio = $this->request->getGet('fechaInicio');
        $fechaFin = $this->request->getGet('fechaFin');

        // Asegúrate de validar y sanitizar las fechas
        if (!$fechaInicio || !$fechaFin) {
            return $this->response->setJSON([]);
        }

        // Llama a tu modelo para obtener los casos
        $model = new Reporte_model();
        $casos = $model->obtenerCasosPorFechas($fechaInicio, $fechaFin);

        return $this->response->setJSON($casos);
    }

    public function pruebaObtenerCasos()
    {
        $model = new Reporte_model();

        // Establece fechas de prueba
        $fechaInicio = '2024-04-05'; // Ajusta según lo que necesites
        $fechaFin = '2025-12-31'; // Ajusta según lo que necesites

        // Llama a la función del modelo
        $resultados = $model->obtenerCasosPorFechas($fechaInicio, $fechaFin);

        // Devuelve los resultados en formato JSON
        return $this->response->setJSON($resultados);
    }

    public function reporteCertificados()
    {
        $data['medicos'] = $this->medicoModel->getMedicosTratantes();
        return view('administrador/reporteCertificados', $data);
    }

    //Reporte de certificados por periodo

    public function certificadosPeriodo()
    {
        // Obtener los parámetros de la solicitud AJAX
        $fechaDesde = $this->request->getVar('fechaDesde');
        $fechaHasta = $this->request->getVar('fechaHasta');
        $medico = $this->request->getVar('medico');

        // Validar que los tres parámetros estén presentes
        if (empty($fechaDesde) || empty($fechaHasta) || empty($medico)) {
            // Si falta algún parámetro, devolver un mensaje de error
            return $this->response->setJSON(['error' => 'Debe proporcionar los tres parámetros: fechas y médico.']);
        }

        // Crear una consulta para obtener los certificados con los filtros
        $certificados = $this->medicoModel->getCertificadosEmitidos($fechaDesde, $fechaHasta, $medico);

        // Verificar si se encontraron certificados
        if (empty($certificados)) {
            return $this->response->setJSON(['error' => 'No se encontraron certificados para los parámetros proporcionados.']);
        }

        // Retornar los datos como JSON
        return $this->response->setJSON($certificados);
    }

    // reporte ausentismo

    public function reporteAusentismo($fechaHoy, $fechaMesDespues)
    {

        $datos['reporteAusentismo'] = $this->empleadoModel->obtenerAusentismos($fechaHoy, $fechaMesDespues);
        $datos['fechaHoy'] = $fechaHoy;
        $datos['fechaMesDespues'] = $fechaMesDespues;

        return view('administrador/reporteAusentismos', $datos);
    }


    public function exportar()
    {
        $fechaDesde = $this->request->getPost('fechaDesde');
        $fechaHasta = $this->request->getPost('fechaHasta');
        $dirigido = $this->request->getPost('dirigido');
        $datos = $this->empleadoModel->obtenerAusentismos($fechaDesde, $fechaHasta);
        $datosSectores = $this->empleadoModel->obtenerSectoresAusencias($fechaDesde, $fechaHasta);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        // ##############CONFIGURACIONES INICIALES############## //

        //TAMAÑO DE LAS CELDAS
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(23);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(23);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(13);

        $spreadsheet->getDefaultStyle()
            ->getFont()
            ->setName('Relaway')
            ->setSize(12);

        // ############## IMAGEN ##############//

        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath(base_url('assets/img/logo.png'));
        $drawing->setHeight(80);
        $drawing->setCoordinates('D1');
        $drawing->setWorksheet($spreadsheet->getActiveSheet());

        $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(60);
        $spreadsheet->getActiveSheet()->setCellValue('A1', "REPORTES");
        $spreadsheet->getActiveSheet()->mergeCells("A1:H1");
        $spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setSize(25);



        $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $spreadsheet->getActiveSheet()->getStyle('A1:H1')->getFill()->setFillType(Fill::FILL_SOLID);
        $spreadsheet->getActiveSheet()->getStyle('A1:H1')->getFill()->getStartColor()->setRGB('e4e3df');
        // ############################//


        // TITULO
        $fechaFormateadaDesde = new \DateTime($fechaDesde);
        $fechaFormateadaDesde = $fechaFormateadaDesde->format('d/m/Y');

        $fechaFormateadaHasta = new \DateTime($fechaHasta);
        $fechaFormateadaHasta = $fechaFormateadaHasta->format('d/m/Y');

        $spreadsheet->getActiveSheet()
            ->setCellValue('A2', "AUSENTISMOS DESDE " . $fechaFormateadaDesde . " HASTA " . $fechaFormateadaHasta);

        $spreadsheet->getActiveSheet()->mergeCells("A2:H2");
        $spreadsheet->getActiveSheet()->getStyle('A2')->getFont()->setSize(23);
        $spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getRowDimension('2')->setRowHeight(50);
        $spreadsheet->getActiveSheet()->getStyle('A2')->getFill()->setFillType(Fill::FILL_SOLID);
        $spreadsheet->getActiveSheet()->getStyle('A2')->getFill()->getStartColor()->setRGB('254b5e');
        $spreadsheet->getActiveSheet()->getStyle('A2')->getFont()->getColor()->setRGB('FFFFFF');

        //FECHA DE CREACION
        $fechaHoy = date('d/m/Y');
        $spreadsheet->getActiveSheet()
            ->setCellValue('A4', "Fecha de creación " . $fechaHoy);
        $spreadsheet->getActiveSheet()->mergeCells("A4:D4");

        //DIRIGIDO A
        if (isset($dirigido)) {
            $spreadsheet->getActiveSheet()
                ->setCellValue('A5', "Dirigido a: " . $dirigido);
            $spreadsheet->getActiveSheet()->mergeCells("A5:D5");
            $spreadsheet->getActiveSheet()->getStyle('A5')->getFill()->getStartColor()->setRGB('e4e3df');
        }



        // ##############ENCABEZADO DE LA TABLA DE AUSENTISMOS##############//

        // COLORES DEL ENCABEZADO DE LA TABLA
        $tablaEncabezado = [
            'font' => [
                'color' => [
                    'rgb' => '000000'
                ],
                'size' => 15,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '9bb2bd'
                ]
            ]
        ];


        // BORDE DE LA TABLA
        $bordeEstilo = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '9bb2bd'],
                ],
            ],
        ];

        // COLUMNAS DE LA TABLA
        $sheet->setCellValue('A7', 'LEGAJO');
        $sheet->setCellValue('B7', 'SECTOR');
        $sheet->setCellValue('C7', 'NOMBRE');
        $sheet->setCellValue('D7', 'APELLIDO');
        $sheet->setCellValue('E7', 'CANTIDAD DE AUSENCIAS');
        $spreadsheet->getActiveSheet()->getStyle('A7:E7')->applyFromArray($tablaEncabezado);

        // DATOS DE LA TABLA
        $row = 8;
        foreach ($datos as $unDato) {
            $sheet->setCellValue('A' . $row, $unDato->legajo);
            $sheet->setCellValue('B' . $row, $unDato->sector);
            $sheet->setCellValue('C' . $row, $unDato->nombre);
            $sheet->setCellValue('D' . $row, $unDato->apellido);
            $sheet->setCellValue('E' . $row, $unDato->ausencias);
            $row++;
        }
        $sheet->getStyle('A7:E' . ($row - 1))->applyFromArray($bordeEstilo);


        // ##############ENCABEZADO DE LA TABLA DE SECTORES##############//

        // COLORES DEL ENCABEZADO DE LA TABLA
        $tablaEncabezadoSectores = [
            'font' => [
                'color' => [
                    'rgb' => 'FFFFFF'
                ],
                'size' => 15,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '937aa2'
                ]
            ]
        ];

        // BORDE DE LA TABLA
        $bordeEstiloSectores = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '937aa2'],
                ],
            ],
        ];

        // COLUMNAS DE LA TABLA
        $sheet->setCellValue('G7', 'SECTOR');
        $sheet->setCellValue('H7', 'AUSENCIAS');

        // DATOS DE LA TABLA
        $row = 8;
        foreach ($datosSectores as $unDato) {
            $sheet->setCellValue('G' . $row, $unDato->sector);
            $sheet->setCellValue('H' . $row, $unDato->ausencias);
            $row++;
        }

        $sheet->getStyle('G7:H' . ($row - 1))->applyFromArray($bordeEstiloSectores);
        $spreadsheet->getActiveSheet()->getStyle('G7:H7')->applyFromArray($tablaEncabezadoSectores);



        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="reporte.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function exportar2()
    {
        $fechaDesde = $this->request->getPost('fechaDesde');
        $fechaHasta = $this->request->getPost('fechaHasta');
        $matricula = $this->request->getPost('option');
        $datos = $this->empleadoModel->obtenerAusentismos($fechaDesde, $fechaHasta);
        $datosSectores = $this->empleadoModel->obtenerSectoresAusencias($fechaDesde, $fechaHasta);
        $medico = $this->medicoModel->obtenerMedicoPorMatricula($matricula);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        // ##############CONFIGURACIONES INICIALES############## //

        //TAMAÑO DE LAS CELDAS
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(23);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(23);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(13);

        $spreadsheet->getDefaultStyle()
            ->getFont()
            ->setName('Relaway')
            ->setSize(12);

        // ############## IMAGEN ##############//

        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath(base_url('assets/img/logo.png'));
        $drawing->setHeight(80);
        $drawing->setCoordinates('D1');
        $drawing->setWorksheet($spreadsheet->getActiveSheet());

        $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(60);
        $spreadsheet->getActiveSheet()->setCellValue('A1', "REPORTES");
        $spreadsheet->getActiveSheet()->mergeCells("A1:H1");
        $spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setSize(25);



        $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $spreadsheet->getActiveSheet()->getStyle('A1:H1')->getFill()->setFillType(Fill::FILL_SOLID);
        $spreadsheet->getActiveSheet()->getStyle('A1:H1')->getFill()->getStartColor()->setRGB('e4e3df');
        // ############################//


        // TITULO
        $fechaFormateadaDesde = new \DateTime($fechaDesde);
        $fechaFormateadaDesde = $fechaFormateadaDesde->format('d/m/Y');

        $fechaFormateadaHasta = new \DateTime($fechaHasta);
        $fechaFormateadaHasta = $fechaFormateadaHasta->format('d/m/Y');

        $spreadsheet->getActiveSheet()
            ->setCellValue('A2', "CERTIFICADOS EMITIDOS DESDE " . $fechaFormateadaDesde . " HASTA " . $fechaFormateadaHasta);

        $spreadsheet->getActiveSheet()->mergeCells("A2:H2");
        $spreadsheet->getActiveSheet()->getStyle('A2')->getFont()->setSize(23);
        $spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getRowDimension('2')->setRowHeight(50);
        $spreadsheet->getActiveSheet()->getStyle('A2')->getFill()->setFillType(Fill::FILL_SOLID);
        $spreadsheet->getActiveSheet()->getStyle('A2')->getFill()->getStartColor()->setRGB('254b5e');
        $spreadsheet->getActiveSheet()->getStyle('A2')->getFont()->getColor()->setRGB('FFFFFF');

        //FECHA DE CREACION
        $fechaHoy = date('d/m/Y');
        $spreadsheet->getActiveSheet()
            ->setCellValue('A4', "Fecha de creación " . $fechaHoy);
        $spreadsheet->getActiveSheet()->mergeCells("A4:D4");

        $spreadsheet->getActiveSheet()
                ->setCellValue('A12', $matricula);

        // Dirigido al médico
        if ($medico) {
            $spreadsheet->getActiveSheet()
                ->setCellValue('A5', "Médico que emitió certificados: " . $medico['nombre'] . " " . $medico['apellido']);
            $spreadsheet->getActiveSheet()->mergeCells("A5:D5");
            $spreadsheet->getActiveSheet()->getStyle('A5')->getFont()->setSize(14);
            $spreadsheet->getActiveSheet()->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        }else {
            $spreadsheet->getActiveSheet()->setCellValue('A12',"No entro ningun medico".$matricula);
        }



        // ##############ENCABEZADO DE LA TABLA DE AUSENTISMOS##############//

        // COLORES DEL ENCABEZADO DE LA TABLA
        $tablaEncabezado = [
            'font' => [
                'color' => [
                    'rgb' => '000000'
                ],
                'size' => 15,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '9bb2bd'
                ]
            ]
        ];


        // BORDE DE LA TABLA
        $bordeEstilo = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '9bb2bd'],
                ],
            ],
        ];

        // COLUMNAS DE LA TABLA
        $sheet->setCellValue('A7', 'LEGAJO');
        $sheet->setCellValue('B7', 'SECTOR');
        $sheet->setCellValue('C7', 'NOMBRE');
        $sheet->setCellValue('D7', 'APELLIDO');
        $sheet->setCellValue('E7', 'CANTIDAD DE CERTIFICADOS');
        $spreadsheet->getActiveSheet()->getStyle('A7:E7')->applyFromArray($tablaEncabezado);

        // DATOS DE LA TABLA
        $row = 8;
        foreach ($datos as $unDato) {
            $sheet->setCellValue('A' . $row, $unDato->legajo);
            $sheet->setCellValue('B' . $row, $unDato->sector);
            $sheet->setCellValue('C' . $row, $unDato->nombre);
            $sheet->setCellValue('D' . $row, $unDato->apellido);
            $sheet->setCellValue('E' . $row, $unDato->ausencias);
            $row++;
        }
        $sheet->getStyle('A7:E' . ($row - 1))->applyFromArray($bordeEstilo);


        // ##############ENCABEZADO DE LA TABLA DE SECTORES##############//

        // COLORES DEL ENCABEZADO DE LA TABLA
        $tablaEncabezadoSectores = [
            'font' => [
                'color' => [
                    'rgb' => 'FFFFFF'
                ],
                'size' => 15,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '937aa2'
                ]
            ]
        ];

        // BORDE DE LA TABLA
        $bordeEstiloSectores = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '937aa2'],
                ],
            ],
        ];

        // COLUMNAS DE LA TABLA
        $sheet->setCellValue('G7', 'SECTOR');
        $sheet->setCellValue('H7', 'AUSENCIAS');

        // DATOS DE LA TABLA
        $row = 8;
        foreach ($datosSectores as $unDato) {
            $sheet->setCellValue('G' . $row, $unDato->sector);
            $sheet->setCellValue('H' . $row, $unDato->ausencias);
            $row++;
        }

        $sheet->getStyle('G7:H' . ($row - 1))->applyFromArray($bordeEstiloSectores);
        $spreadsheet->getActiveSheet()->getStyle('G7:H7')->applyFromArray($tablaEncabezadoSectores);



        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="reporte.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
