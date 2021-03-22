<?php 

namespace App\Traits;

trait BatalhaTrait
{

    /**
     * Retorna primeiro atacante ('heroi' ou 'monstro')
     * 
     * @param Heroi $heroi, Monstro $monstro
     * @return string 
     */
    public function iniciativa($heroi, $monstro)
    {
        do {
            $iniHeroi = $this->rolar('1d10') + $heroi->agilidade;
            $iniMonstro = $this->rolar('1d10') + $monstro->agilidade;
        }
        while($iniHeroi === $iniMonstro);

        if($iniHeroi > $iniMonstro) {
            return 'heroi';
        }

        return 'monstro';
    }

    /**
     * Recebe atacante e defensor (heroi/monstro)
     * e retorna o resultado da tentativa de ataque
     * 
     * @param 
     * @return array
     */
    public function ataque($atacante, $defensor)
    {
        $ataque = $this->calcAtaque($atacante);
        $defesa = $this->calcDefesa($defensor);
        $dano = 0;

        if($ataque > $defesa) {
            $dano = $this->calcDano($atacante);
        }

        return [
            'ataque' => $ataque, 
            'defesa' => $defesa,
            'dano' => $dano
        ];
    }

    private function calcAtaque($atacante)
    {
        return $this->rolar('1d10') + $atacante->agilidade + $atacante->forca;
    }

    private function calcDefesa($defensor)
    {
        return $this->rolar('1d10') + $defensor->agilidade + $defensor->defesa;
    }

    private function calcDano($atacante)
    {
        return $this->rolar($atacante->fdd) + $atacante->forca;
    }

    /**
     * Diz quem é o defensor de acordo com o atacante
     * 
     * @param string $atacante
     * @return string 
     */
    public function defensor($atacante)
    {
        return ($atacante === 'heroi' ? 'monstro' : 'heroi');
    }

    /**
     * Rolar dados
     * 
     * Ex: '1d10'
     * 1 = número de dados
     * 10 = número de faces
     * 
     * Retorna a soma do resultado
     * 
     * @param string dado
     * @return int 
     */
    public function rolar($dado)
    {
        $dado = trim(strtolower($dado));
        $arr = explode('d', $dado);

	 	$qtd = (int) $arr[0];
	 	$faces = (int) $arr[1];

        $resultado = 0;

        for($x = 0; $x < $qtd; $x++) {
            $numDado = rand(1, $faces);			
            $resultado+= $numDado;
        }

        return $resultado;
    }

    /**
     * Retorna o atual status de uma batalha
     * 
     * @param Batalha $batalha
     * @return array
     */
    public function status($batalha)
    {
        $arrStatus = [
            'personagens' => [
                'heroi' => [
                    'nome' => $batalha->jogador->nickname,
                    'classe' => $batalha->heroi->nome,
                    'pdv' => $batalha->pdv_heroi
                ],
                'monstro' => [
                    'nome' => $batalha->monstro->nome,
                    'pdv' => $batalha->pdv_monstro
                ]
            ]
        ];

        if(!empty($batalha->vitoria)) {
            $arrStatus['resultado'] = [
                'vitoria' => $batalha->vitoria,
                'pontos' => $batalha->pontos
            ];
        }

        return $arrStatus;
    }

    public function tratarInteiro($valor)
    {
        return $valor >= 0 ? $valor : 0;
    }
}