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
			'conditions' => $conditions,
			'group' => ['Exames.id']
		])->toList();

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
	        		'Positivo' => $positivo,
					'Negativo' => $negativo,
					'Inconclusivo' => $inconclusivo,
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
	        		'Positivo' => $positivo,
					'Negativo' => $negativo,
					'Inconclusivo' => $inconclusivo,
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
	        	'conditions' => $conditions20])->toArray();

        $inconclusivo = 0;
		$positivo = 0;
		$negativo = 0;

		if(!empty($amostras20)){
	        foreach ($amostras20 as $key => $amostra20) {

	        	if($amostra20->resultado == 'Em Análise'){
					$inconclusivo++;
				}elseif($amostra20->resultado == 'Positivo'){
					$positivo++;
				}elseif($amostra20->resultado == 'Negativo'){
					$negativo++;
				}
	        }

	        if($inconclusivo > 0 || $positivo > 0 || $negativo > 0 ){
	        		$result['0-20'] = [
		        		'Positivo' => $positivo,
						'Negativo' => $negativo,
						'Inconclusivo' => $inconclusivo,
		        	];
		     }
	 	}

	  
        //> 20 && <= 40

	    $inconclusivo = 0;
		$positivo = 0;
		$negativo = 0;

        $conditions40['Amostras.idade >'] = 20;
        $conditions40['Amostras.idade <='] = 40;
        $amostras40 = $this->Exames->find('all', [
	        	'contain' => ['Amostras'],
	        	'conditions' => $conditions40])->toArray();

        if(!empty($amostras40)){
	        foreach ($amostras40 as $key => $amostra40) {

	        	if($amostra40->resultado == 'Em Análise'){
					$inconclusivo++;
				}elseif($amostra40->resultado == 'Positivo'){
					$positivo++;
				}elseif($amostra40->resultado == 'Negativo'){
					$negativo++;
				}
	        }

	        if($inconclusivo > 0 || $positivo > 0 || $negativo > 0 ){
	        		$result['21-40'] = [
		        		'Positivo' => $positivo,
						'Negativo' => $negativo,
						'Inconclusivo' => $inconclusivo,
		        	];
		    }
	 	}

         //> 40 && <= 80

	    $inconclusivo = 0;
		$positivo = 0;
		$negativo = 0;

        $conditions80['Amostras.idade >'] = 40;
        $conditions80['Amostras.idade <='] = 80;
        $amostras80 = $this->Exames->find('all', [
	        	'contain' => ['Amostras'],
	        	'conditions' => $conditions80])->toArray();


        if(!empty($amostras80)){

	        foreach ($amostras80 as $key => $amostra80) {

	        	if($amostra80->resultado == 'Em Análise'){
					$inconclusivo++;
				}elseif($amostra80->resultado == 'Positivo'){
					$positivo++;
				}elseif($amostra80->resultado == 'Negativo'){
					$negativo++;
				}
	        }

	         if($inconclusivo > 0 || $positivo > 0 || $negativo > 0 ){
		      	$result['41-80'] = [
		        		'Positivo' => $positivo,
						'Negativo' => $negativo,
						'Inconclusivo' => $inconclusivo,
		        ];
	    	}

	    	
    	}


	    $inconclusivo = 0;
		$positivo = 0;
		$negativo = 0;

         // > 80
        $conditions81['Amostras.idade >'] = 80;
        $amostras81 = $this->Exames->find('all', [
	        	'contain' => ['Amostras'],
	        	'conditions' => $conditions81])->toArray();

        if(!empty($amostras81)){
	        foreach ($amostras81 as $key => $amostra81) {

	        	if($amostra81->resultado == 'Em Análise'){
					$inconclusivo++;
				}elseif($amostra81->resultado == 'Positivo'){
					$positivo++;
				}elseif($amostra81->resultado == 'Negativo'){
					$negativo++;
				}
	        }

	        if($inconclusivo > 0 || $positivo > 0 || $negativo > 0 ){
		        $result['> 81'] = [
		        		'Positivo' => $positivo,
						'Negativo' => $negativo,
						'Inconclusivo' => $inconclusivo,
		        ];
			}
        }
        echo json_encode($result);
        die();

	}


}