<?php
interface Tarjeta{
	public function pagar($transporte, $hora, $fecha);
	public function recargar($monto);
	public function saldo();
	public function viajesRealizados();
}

class Viajes{
	protected $nombreultimotransporteusado;
	protected $horaultimoviajehecho;
	protected $fechaultimoviaje;
	protected $monto;
	
	public function darnombre(){
		return $this->nombreultimotransporteusado;
	}
	public function darhora(){
		return $this->horaultimoviajehecho;
	}
	public function darfecha(){
		return $this->fechaultimoviaje;
	}
	public function darmonto(){
		return $this->monto;
	}
	public function pedirdatosultimoviaje($nombre,$monto,$fecha,$hora){
		$this->nombreultimotransporteusado=$nombre;
		$this->monto=$monto;
		$this->fechaultimoviaje=$fecha;
		$this->horaultimoviajehecho=$hora;
	}
}

class Bicicletas{
	protected $costo;
	protected $nombre;
	public function __construct($nom){
		$this->nombre=$nom;
	}
	public function darnombre(){
		return $this->nombre;
	}
}

class Colectivos{
	protected $costo;
	protected $nombre;
	public function __construct($nom){
		$this->nombre=$nom;
	}
	
	public function darnombre(){
	return $this->nombre;
	}
}

class Tarjetas implements Tarjeta{ 
	protected $tipo;
	protected $saldo;
	protected $tipotransporte;
	protected $nombre;
	protected $viaje;
	protected $monto;
	
	public function __construct($tipopersona, $name){
		$this->tipo=$tipopersona;
		$this->saldo=0;
		$this->nombre=$name;
		$this->viaje=new Viajes();
	}
	
	public function pagar($transporte, $hora, $fecha){
		if($transporte instanceof Colectivos){
			$this->tipotransporte="Colectivo";
			if($this->tipo=='pase libre'){
				$this->saldo=$this->saldo;
				$this->monto=0;
			}
			if($this->viaje->darfecha()==$fecha&&$this->viaje->darnombre()!=$transporte->darnombre()&&($hora-$this->viaje->darhora())<1&&$this->tipo!='pase libre'){
				#caso del trasbordo
				if($this->tipo=='estudiante'){
					#ya sea terciario, secundario o primario
					$this->saldo=$this->saldo-1.32;
					$this->monto=1.32;
				}
				else{
					$this->saldo=$this->saldo-2.64;
					$this->monto=2.64;
				}
			}
			else{
				if($this->tipo!='pase libre'){
					if($this->tipo=='estudiante'){ 
						$this->saldo=$this->saldo-4;
						$this->monto=4;
					}
					else{
						$this->saldo=$this->saldo-8;
						$this->monto=8;
					}
				}
			}
		}
		else{
			$this->saldo=$this->saldo-12;
			$this->tipotransporte="Bicicleta";
			$this->monto=12;
		}
		$this->viaje->pedirdatosultimoviaje($transporte->darnombre(),$this->monto,$fecha,$hora);
	}
	
	
	
	public function recargar($monto){
        if($monto!=500&&$monto!=272){
			$this->saldo=$this->saldo+$monto;
		}
        if($monto==500){
			$this->saldo=$this->saldo+640;
		}
		if($monto==272){
			$this->saldo=$this->saldo+320;
		}
	}
	
	public function saldo(){
		echo "El saldo de la tarjeta ".$this->nombre." es: ".$this->saldo."\n";
	}
	
	public function viajesRealizados(){
        echo "El ultimo viaje realizado por ".$this->tipo." fue en ".$this->tipotransporte.": ".$this->viaje->darnombre()." el dia ".$this->viaje->darfecha()." a las: ".$this->viaje->darhora()." hs y pago un monto de: ".$this->viaje->darmonto()."\n";
	
    }
}

$moviestudiante=new Tarjetas("estudiante", "movi estudiante");
$movi=new Tarjetas("normal", "movi estandar");
$bondi=new Colectivos("115");
$bondi2=new Colectivos("116");
$bici=new Bicicletas(1234);
$moviconpase=new Tarjetas("pase libre", "movi con pase");
$moviestudiante->recargar(100);
$moviestudiante->saldo();
$moviestudiante->pagar($bondi,12,"14/06/2016");
$moviestudiante->viajesrealizados();
$moviestudiante->pagar($bondi2,12.3,"14/06/2016");
$moviestudiante->viajesrealizados();
$moviestudiante->pagar($bondi,15,"14/06/2016");
$moviestudiante->viajesrealizados();
$moviestudiante->pagar($bici,13.3,"14/06/2016");
$moviestudiante->viajesrealizados();
$moviestudiante->saldo();
$moviestudiante->recargar(272);
$moviestudiante->saldo();
$moviestudiante->recargar(500);
$moviestudiante->saldo();
echo "\n";
$moviconpase->recargar(20);
$moviconpase->saldo();
$moviconpase->pagar($bondi,19,"15/06/2016");
$moviconpase->viajesrealizados();
$moviconpase->pagar($bondi2,19.15,"15/06/2016");
$moviconpase->viajesrealizados();
$moviconpase->pagar($bondi,22,"15/06/2016");
$moviconpase->viajesrealizados();
$moviconpase->saldo();
echo "\n";
$movi->recargar(200);
$movi->saldo();
$movi->pagar($bondi,12,"14/06/2016");
$movi->viajesrealizados();
$movi->pagar($bondi2,12.3,"14/06/2016");
$movi->viajesrealizados();
$movi->pagar($bondi,15,"14/06/2016");
$movi->viajesrealizados();
$movi->pagar($bici,13.3,"14/06/2016");
$movi->viajesrealizados();
$movi->saldo();
?>
