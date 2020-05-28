<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
/**
 * Dashboard Controller
 */

class DashboardController extends AppController
{


	public function initialize()
    {
        parent::initialize();
        $this->loadModel('Exames');
        $this->loadModel('Amostras');
    }

	public function index()
	{
		$user = $this->Auth->user();
		
		$this->set(compact('user'));
	}

	public function getExamesGlobal()
	{	

		$conditions = [];
		$result = [
			'Positivo' => 0,
			'Negativo' => 0,
			'Inconclusivo' => 0,
		];

		$exames = $this->Exames->find('all',[
			'contain' => ['Amostras'],
			'conditions' => $conditions
		]);

		foreach ($exames as $key => $exame) {
			if($exame->resultado == 'Em Análise'){
				$result['Inconclusivo']++;
			}elseif($exame->resultado == 'Positivo'){
				$result['Positivo']++;
			}elseif($exame->resultado == 'Negativo'){
				$result['Negativo']++;
			}
		}

		echo json_encode($result);
		die();

	}

	public function getExamesByUf()
	{
		
		$ufs_lista = $this->Amostras->find('all', ['fields' => ['DISTINCT Amostras.uf'], 'order' => ['Amostras.uf' => 'ASC']]);
        $ufs = [];
        $result = [];
        $conditions = [];

        foreach ($ufs_lista as $row)
            $ufs[$row['DISTINCT Amostras']['uf']] = $row['DISTINCT Amostras']['uf'];

        foreach ($ufs as $key => $uf) {
	        $conditions['Amostras.uf'] = $uf;

	        $amostras = $this->Exames->find('all', [
	        	'contain' => ['Amostras'],
	        	'conditions' => $conditions
	        ]);
	        $inconclusivo = 0;
	        $positivo = 0;
	        $negativo = 0;

	        foreach ($amostras as $key => $amostra) {

	        	if($amostra->resultado == 'Em Análise'){
					$inconclusivo++;
				}elseif($amostra->resultado == 'Positivo'){
					$positivo++;
				}elseif($amostra->resultado == 'Negativo'){
					$negativo++;
				}
	        }

	        $result[$uf] = [
	        		'Positivo' => $inconclusivo,
					'Negativo' => $positivo,
					'Inconclusivo' => $negativo,
	        	];

        }

			echo json_encode($result);
	        die();
        

	}

	public function getExamesByGener()
	{
		$sexo_lista = $this->Amostras->find('all', ['fields' => ['DISTINCT Amostras.sexo'], 'order' => ['Amostras.sexo' => 'ASC']]);
        $sexos = [];
        $result = [];
        $conditions = [];

        foreach ($sexo_lista as $row)
            $sexos[$row['DISTINCT Amostras']['sexo']] = $row['DISTINCT Amostras']['sexo'];

        foreach ($sexos as $key => $sexo) {
	        $conditions['Amostras.sexo'] = $sexo;

	        $amostras = $this->Exames->find('all', [
	        	'contain' => ['Amostras'],
	        	'conditions' => $conditions
	        ]);
	        $inconclusivo = 0;
	        $positivo = 0;
	        $negativo = 0;

	        foreach ($amostras as $key => $amostra) {

	        	if($amostra->resultado == 'Em Análise'){
					$inconclusivo++;
				}elseif($amostra->resultado == 'Positivo'){
					$positivo++;
				}elseif($amostra->resultado == 'Negativo'){
					$negativo++;
				}
	        }

	        $result[$sexo] = [
	        		'Positivo' => $inconclusivo,
					'Negativo' => $positivo,
					'Inconclusivo' => $negativo,
	        	];

        }

			echo json_encode($result);
	        die();
	}

	public function getExamesByAge()
	{
		

        $result = [];
        $conditions20 = [];
        $conditions40 = [];
        $conditions80 = [];
        $conditions81 = [];

        //<= 20
        $conditions20['Amostras.idade <='] = 20;
        $amostras20 = $this->Exames->find('all', [
	        	'contain' => ['Amostras'],
	        	'conditions' => $conditions20])->count();

        //> 20 && <= 40
        $conditions40['Amostras.idade >'] = 20;
        $conditions40['Amostras.idade <='] = 40;
        $amostras40 = $this->Exames->find('all', [
	        	'contain' => ['Amostras'],
	        	'conditions' => $conditions40])->count();

         //> 40 && <= 80
        $conditions80['Amostras.idade >'] = 40;
        $conditions80['Amostras.idade <='] = 80;
        $amostras80 = $this->Exames->find('all', [
	        	'contain' => ['Amostras'],
	        	'conditions' => $conditions80])->count();

         //> 80
        $conditions81['Amostras.idade >'] = 80;
        $amostras81 = $this->Exames->find('all', [
	        	'contain' => ['Amostras'],
	        	'conditions' => $conditions81])->count();

        if($amostras20 > 0){
        	$result['0-20'] = $amostras20;
        }
        if($amostras40 > 0){
        	$result['21-40'] = $amostras40;
        }
        if($amostras80 > 0){
        	$result['41-80'] = $amostras80;
        }
        if($amostras81 > 0){
        	$result['> 81'] = $amostras81;
        }

        echo json_encode($result);
        die();

	}


}