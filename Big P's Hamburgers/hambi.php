<?php

class hambi
{
    private $alapanyagok=[];
    private $kep_nev;
    private $nev;
    private $menyiseg;

    public function __construct($nev,$kep_nev,$alapanyagok){
        $this->nev=$nev;
        $this->kep_nev=$kep_nev;
        $this->alapanyagok=$alapanyagok;
        $this->menyiseg=1;
    }

    public function compare($hambi){
        if ($hambi->getNev()==$this->nev){
            return true;
        }
        return false;
    }

    public function ar(){
        $json=file_get_contents("json/alap.json");
        $alap=json_decode($json,true);
        $osszeg=0;
        foreach ($this->alapanyagok as $alapanyag){
            $osszeg+=$alap[$alapanyag];
        }
        return $osszeg*$this->menyiseg;
    }

    public function menny($mi){
        $db=0;
        foreach ($this->alapanyagok as $alap){
            if ($alap==$mi){
                $db++;
            }
        }
        return $db;
    }

    public function getKepNev(){
        return $this->kep_nev;
    }

    public function getNev()
    {
        return $this->nev;
    }

    public function getAlapanyagok(): array
    {
        return $this->alapanyagok;
    }

    public function getMenyiseg(){
        return $this->menyiseg;
    }

    public function setMenyiseg($menyiseg)
    {
        if ($menyiseg>10){
            $this->menyiseg = 10;
        }elseif ($menyiseg<0){
            $this->menyiseg = 0;
        }else{
            $this->menyiseg = $menyiseg;
        }
    }



}

?>