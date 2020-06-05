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
		$action = 'Geral';
        $title = 'Dashboard';

		$user = $this->Auth->user();

		$conditions_uf = [];

		if($this->Auth->user('user_type_id') == 3){
			$conditions_uf['Exames.created_by'] = $this->Auth->user('id');
		}

		if($this->Auth->user('user_type_id') == 2){
			$conditions_uf['Users.cliente_id'] = $this->Auth->user('cliente_id');
		}

		$ufs_lista = $this->Amostras->find('all', ['fields' => ['DISTINCT Amostras.uf'],
			'conditions' => $conditions_uf,
			'contain' => ['Exames.Users'],
		 'order' => ['Amostras.uf' => 'ASC']]);
        $ufs = [];

        foreach ($ufs_lista as $row)
            $ufs[$row['DISTINCT Amostras']['uf']] = $row['DISTINCT Amostras']['uf'];

		
		$this->set(compact('user','ufs','action','title'));
	}

	public function getExamesGlobal()
	{	

		$query = $this->request->getQuery();
		$conditions_query = [];
		
		if(!empty($query['date_init'])){
			$data1 = implode('-', array_reverse(explode('/', $query['date_init'])));
			$conditions_query['cast(Amostras.created as date) >='] =  $data1;
		}
		if(!empty($query['date_end'])){
			$data2 = implode('-', array_reverse(explode('/', $query['date_end'])));
			$conditions_query['cast(Amostras.created as date) <='] = $data2;
		}
		if(!empty($query['estado'])){
			$conditions_query['Amostras.uf'] = $query['estado'];
		}


        $conditions = [];

        $conditions = array_merge($conditions,$conditions_query);

		$result = [
			'Positivo' => 0,
			'Negativo' => 0,
			'Indeterminado' => 0,
		];
		

		if($this->Auth->user('user_type_id') == 3){
			$conditions['Exames.created_by'] = $this->Auth->user('id');
		}

		if($this->Auth->user('user_type_id') == 2){
			$conditions['Users.cliente_id'] = $this->Auth->user('cliente_id');
		}

		$exames = $this->Exames->find('all',[
			'contain' => ['Amostras','Users'],
			'conditions' => $conditions,
			'group' => ['Exames.id']
		])->toList();

		foreach ($exames as $key => $exame) {
			if($exame->resultado == 'Indeterminado'){
				$result['Indeterminado']++;
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
		

		$query = $this->request->getQuery();
		$conditions_query = [];
		
		if(!empty($query['date_init'])){
			$data1 = implode('-', array_reverse(explode('/', $query['date_init'])));
			$conditions_query['cast(Amostras.created as date) >='] =  $data1;
		}
		if(!empty($query['date_end'])){
			$data2 = implode('-', array_reverse(explode('/', $query['date_end'])));
			$conditions_query['cast(Amostras.created as date) <='] = $data2;
		}
		if(!empty($query['estado'])){
			$conditions_query['Amostras.uf'] = $query['estado'];
		}


       
		$conditions_uf = [];

        $conditions_uf = array_merge($conditions_uf,$conditions_query);

		$ufs_lista = $this->Amostras->find('all', ['fields' => ['DISTINCT Amostras.uf'],
			'conditions' => $conditions_uf,
		 'order' => ['Amostras.uf' => 'ASC']]);
        $ufs = [];

        $result = [];

      
        $conditions = [];
       

        if($this->Auth->user('user_type_id') == 3){
			$conditions['Exames.created_by'] = $this->Auth->user('id');
		}

		if($this->Auth->user('user_type_id') == 2){
			$conditions['Users.cliente_id'] = $this->Auth->user('cliente_id');
		}


        foreach ($ufs_lista as $row)
            $ufs[$row['DISTINCT Amostras']['uf']] = $row['DISTINCT Amostras']['uf'];

        foreach ($ufs as $key => $uf) {

	        $conditions['Amostras.uf'] = $uf;

	        $amostras = $this->Exames->find('all', [
	        	'contain' => ['Amostras','Users'],
	        	'conditions' => $conditions
	        ])->toArray();

	        $Indeterminado = 0;
	        $positivo = 0;
	        $negativo = 0;

	        foreach ($amostras as $key => $amostra) {

	        	if($amostra->resultado == 'Indeterminado'){
					$Indeterminado++;
				}elseif($amostra->resultado == 'Positivo'){
					$positivo++;
				}elseif($amostra->resultado == 'Negativo'){
					$negativo++;
				}
	        }

	        $result[$uf] = [
	        		'Positivo' => $positivo,
					'Negativo' => $negativo,
					'Indeterminado' => $Indeterminado,
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

        $query = $this->request->getQuery();
		$conditions_query = [];
		
		if(!empty($query['date_init'])){
			$data1 = implode('-', array_reverse(explode('/', $query['date_init'])));
			$conditions_query['cast(Amostras.created as date) >='] =  $data1;
		}
		if(!empty($query['date_end'])){
			$data2 = implode('-', array_reverse(explode('/', $query['date_end'])));
			$conditions_query['cast(Amostras.created as date) <='] = $data2;
		}
		if(!empty($query['estado'])){
			$conditions_query['Amostras.uf'] = $query['estado'];
		}


        $conditions = [];

        $conditions = array_merge($conditions,$conditions_query);

        foreach ($sexo_lista as $row)
            $sexos[$row['DISTINCT Amostras']['sexo']] = $row['DISTINCT Amostras']['sexo'];

        if($this->Auth->user('user_type_id') == 3){
			$conditions['Exames.created_by'] = $this->Auth->user('id');
		}

		if($this->Auth->user('user_type_id') == 2){
			$conditions['Users.cliente_id'] = $this->Auth->user('cliente_id');
		}

        foreach ($sexos as $key => $sexo) {
	        $conditions['Amostras.sexo'] = $sexo;

	        $amostras = $this->Exames->find('all', [
	        	'contain' => ['Amostras','Users'],
	        	'conditions' => $conditions
	        ]);
	        $Indeterminado = 0;
	        $positivo = 0;
	        $negativo = 0;

	        foreach ($amostras as $key => $amostra) {

	        	if($amostra->resultado == 'Indeterminado'){
					$Indeterminado++;
				}elseif($amostra->resultado == 'Positivo'){
					$positivo++;
				}elseif($amostra->resultado == 'Negativo'){
					$negativo++;
				}
	        }

	        $result[$sexo] = [
	        		'Positivo' => $positivo,
					'Negativo' => $negativo,
					'Indeterminado' => $Indeterminado,
	        	];

        }

			echo json_encode($result);
	        die();
	}

	public function getExamesByAge()
	{
		
		$query = $this->request->getQuery();
		$conditions_query = [];
		
		if(!empty($query['date_init'])){
			$data1 = implode('-', array_reverse(explode('/', $query['date_init'])));
			$conditions_query['cast(Amostras.created as date) >='] =  $data1;
		}
		if(!empty($query['date_end'])){
			$data2 = implode('-', array_reverse(explode('/', $query['date_end'])));
			$conditions_query['cast(Amostras.created as date) <='] = $data2;
		}
		if(!empty($query['estado'])){
			$conditions_query['Amostras.uf'] = $query['estado'];
		}

        $result = [];
        $result2 = [];
        $conditions20 = [];
        $conditions40 = [];
        $conditions60 = [];
        $conditions80 = [];
        $conditions81 = [];

        $conditions20 = array_merge($conditions20,$conditions_query);
        $conditions40 = array_merge($conditions40,$conditions_query);
        $conditions60 = array_merge($conditions60,$conditions_query);
        $conditions80 = array_merge($conditions80,$conditions_query);
        $conditions81 = array_merge($conditions81,$conditions_query);

        if($this->Auth->user('user_type_id') == 3){
			$conditions20['Exames.created_by'] = $this->Auth->user('id');
			$conditions40['Exames.created_by'] = $this->Auth->user('id');
			$conditions60['Exames.created_by'] = $this->Auth->user('id');
			$conditions80['Exames.created_by'] = $this->Auth->user('id');
			$conditions81['Exames.created_by'] = $this->Auth->user('id');
		}

		if($this->Auth->user('user_type_id') == 2){
			$conditions20['Users.cliente_id'] = $this->Auth->user('cliente_id');
			$conditions40['Users.cliente_id'] = $this->Auth->user('cliente_id');
			$conditions60['Users.cliente_id'] = $this->Auth->user('cliente_id');
			$conditions80['Users.cliente_id'] = $this->Auth->user('cliente_id');
			$conditions81['Users.cliente_id'] = $this->Auth->user('cliente_id');
		}

        //<= 20
        $conditions20['Amostras.idade <='] = 20;
        $amostras20 = $this->Exames->find('all', [
	        	'contain' => ['Amostras','Users'],
	        	'conditions' => $conditions20])->toArray();

        $Indeterminado = 0;
        $IndeterminadoM = 0;
        $IndeterminadoF = 0;
		$positivo = 0;
		$positivoM = 0;
		$positivoF = 0;
		$negativo = 0;
		$negativoM = 0;
		$negativoF = 0;


		if(!empty($amostras20)){
	        foreach ($amostras20 as $key => $amostra20) {

	        	if($amostra20->resultado == 'Indeterminado'){
					$Indeterminado++;
					if($amostra20->amostra->sexo == 'M'){
						$IndeterminadoM++;
					}else{
						$IndeterminadoF++;
					}
				}elseif($amostra20->resultado == 'Positivo'){
					$positivo++;
					if($amostra20->amostra->sexo == 'M'){
						$positivoM++;
					}else{
						$positivoF++;
					}
				}elseif($amostra20->resultado == 'Negativo'){
					$negativo++;
					if($amostra20->amostra->sexo == 'M'){
						$negativoM++;
					}else{
						$negativoF++;
					}
				}
	        }

	        if($Indeterminado > 0 || $positivo > 0 || $negativo > 0 ){
	        		$result['0-20'] = [
		        		'Positivo' => $positivo,
						'Negativo' => $negativo,
						'Indeterminado' => $Indeterminado,
		        	];
		     }
	 	}
	 	$result2['0-20'] = [
		        		'Positivo' => ['M' => $positivoM, 'F' => $positivoF],
						'Negativo' => ['M' => $negativoM, 'F' => $negativoF],
						'Indeterminado' => ['M' => $IndeterminadoM, 'F' => $IndeterminadoF],
		        	];

        //> 20 && <= 40

	    $Indeterminado = 0;
        $IndeterminadoM = 0;
        $IndeterminadoF = 0;
		$positivo = 0;
		$positivoM = 0;
		$positivoF = 0;
		$negativo = 0;
		$negativoM = 0;
		$negativoF = 0;

        $conditions40['Amostras.idade >'] = 20;
        $conditions40['Amostras.idade <='] = 40;
        $amostras40 = $this->Exames->find('all', [
	        	'contain' => ['Amostras','Users'],
	        	'conditions' => $conditions40])->toArray();

        if(!empty($amostras40)){
	        foreach ($amostras40 as $key => $amostra40) {

	        	if($amostra40->resultado == 'Indeterminado'){
					$Indeterminado++;
					if($amostra40->amostra->sexo == 'M'){
						$IndeterminadoM++;
					}else{
						$IndeterminadoF++;
					}
				}elseif($amostra40->resultado == 'Positivo'){
					$positivo++;
					if($amostra40->amostra->sexo == 'M'){
						$positivoM++;
					}else{
						$positivoF++;
					}
				}elseif($amostra40->resultado == 'Negativo'){
					$negativo++;
					if($amostra40->amostra->sexo == 'M'){
						$negativoM++;
					}else{
						$negativoF++;
					}
				}
	        }

	        if($Indeterminado > 0 || $positivo > 0 || $negativo > 0 ){
	        		$result['21-40'] = [
		        		'Positivo' => $positivo,
						'Negativo' => $negativo,
						'Indeterminado' => $Indeterminado,
		        	];
		    }
	 	}
	 	$result2['21-40'] = [
		        		'Positivo' => ['M' => $positivoM, 'F' => $positivoF],
						'Negativo' => ['M' => $positivoM, 'F' => $positivoF],
						'Indeterminado' => ['M' => $positivoM, 'F' => $positivoF],
		        	];

		  //> 41 && <= 60

	   	$Indeterminado = 0;
        $IndeterminadoM = 0;
        $IndeterminadoF = 0;
		$positivo = 0;
		$positivoM = 0;
		$positivoF = 0;
		$negativo = 0;
		$negativoM = 0;
		$negativoF = 0;

        $conditions60['Amostras.idade >'] = 41;
        $conditions60['Amostras.idade <='] = 60;
        $amostras60 = $this->Exames->find('all', [
	        	'contain' => ['Amostras','Users'],
	        	'conditions' => $conditions40])->toArray();

        if(!empty($amostras60)){
	        foreach ($amostras60 as $key => $amostra60) {

	        	if($amostra60->resultado == 'Indeterminado'){
					$Indeterminado++;
					if($amostra60->amostra->sexo == 'M'){
						$IndeterminadoM++;
					}else{
						$IndeterminadoF++;
					}
				}elseif($amostra60->resultado == 'Positivo'){
					$positivo++;
					if($amostra60->amostra->sexo == 'M'){
						$positivoM++;
					}else{
						$positivoF++;
					}
				}elseif($amostra60->resultado == 'Negativo'){
					$negativo++;
					if($amostra60->amostra->sexo == 'M'){
						$negativoM++;
					}else{
						$negativoF++;
					}
				}
	        }

	        if($Indeterminado > 0 || $positivo > 0 || $negativo > 0 ){
	        		$result['41-60'] = [
		        		'Positivo' => $positivo,
						'Negativo' => $negativo,
						'Indeterminado' => $Indeterminado,
		        	];
		    }

	 	}

	 	$result2['41-60'] = [
		        		'Positivo' => ['M' => $positivoM, 'F' => $positivoF],
						'Negativo' => ['M' => $negativoM, 'F' => $negativoF],
						'Indeterminado' => ['M' => $IndeterminadoM, 'F' => $IndeterminadoF],
		        	];

         //> 40 && <= 80

	    $Indeterminado = 0;
        $IndeterminadoM = 0;
        $IndeterminadoF = 0;
		$positivo = 0;
		$positivoM = 0;
		$positivoF = 0;
		$negativo = 0;
		$negativoM = 0;
		$negativoF = 0;

        $conditions80['Amostras.idade >'] = 61;
        $conditions80['Amostras.idade <='] = 80;
        $amostras80 = $this->Exames->find('all', [
	        	'contain' => ['Amostras','Users'],
	        	'conditions' => $conditions80])->toArray();


        if(!empty($amostras80)){

	        foreach ($amostras80 as $key => $amostra80) {

	        	if($amostra80->resultado == 'Indeterminado'){
					$Indeterminado++;
					if($amostra80->amostra->sexo == 'M'){
						$IndeterminadoM++;
					}else{
						$IndeterminadoF++;
					}
				}elseif($amostra80->resultado == 'Positivo'){
					$positivo++;
					if($amostra80->amostra->sexo == 'M'){
						$positivoM++;
					}else{
						$positivoF++;
					}
				}elseif($amostra80->resultado == 'Negativo'){
					$negativo++;
					if($amostra80->amostra->sexo == 'M'){
						$negativoM++;
					}else{
						$negativoF++;
					}
				}
	        }

	         if($Indeterminado > 0 || $positivo > 0 || $negativo > 0 ){
		      	$result['61-80'] = [
		        		'Positivo' => $positivo,
						'Negativo' => $negativo,
						'Indeterminado' => $Indeterminado,
		        ];
	    	}
	    	
    	}
    	$result2['61-80'] = [
		        		'Positivo' => ['M' => $positivoM, 'F' => $positivoF],
						'Negativo' => ['M' => $positivoM, 'F' => $positivoF],
						'Indeterminado' => ['M' => $positivoM, 'F' => $positivoF],
		        ];

	    $Indeterminado = 0;
        $IndeterminadoM = 0;
        $IndeterminadoF = 0;
		$positivo = 0;
		$positivoM = 0;
		$positivoF = 0;
		$negativo = 0;
		$negativoM = 0;
		$negativoF = 0;

         // > 80
        $conditions81['Amostras.idade >'] = 80;
        $amostras81 = $this->Exames->find('all', [
	        	'contain' => ['Amostras','Users'],
	        	'conditions' => $conditions81])->toArray();

        if(!empty($amostras81)){
	        foreach ($amostras81 as $key => $amostra81) {

	        	if($amostra81->resultado == 'Indeterminado'){
					$Indeterminado++;
					if($amostra81->amostra->sexo == 'M'){
						$IndeterminadoM++;
					}else{
						$IndeterminadoF++;
					}
				}elseif($amostra81->resultado == 'Positivo'){
					$positivo++;
					if($amostra81->amostra->sexo == 'M'){
						$positivoM++;
					}else{
						$positivoF++;
					}
				}elseif($amostra81->resultado == 'Negativo'){
					$negativo++;
					if($amostra81->amostra->sexo == 'M'){
						$negativoM++;
					}else{
						$negativoF++;
					}
				}
	        }

	        if($Indeterminado > 0 || $positivo > 0 || $negativo > 0 ){
		        $result['> 80'] = [
		        		'Positivo' => $positivo,
						'Negativo' => $negativo,
						'Indeterminado' => $Indeterminado,
		        ];
			}
        }
        $result2['> 80'] = [
		        		'Positivo' => ['M' => $positivoM, 'F' => $positivoF],
						'Negativo' => ['M' => $positivoM, 'F' => $positivoF],
						'Indeterminado' => ['M' => $positivoM, 'F' => $positivoF],
		        ];

		 $resulfinal = [
		 	'result' => $result,
		 	'result_table' => $result2
		 ];       
        echo json_encode($resulfinal);
        die();

	}


}