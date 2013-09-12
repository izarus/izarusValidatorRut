<?php

/**
 * izarusValidatorRut
 *
 * Clase para validar un RUT chileno.
 * Basado en el trabajo de Francisco Salazar.
 * Adaptado por David Vega para Izarus.
 *
 */
class izarusValidatorRut extends sfValidatorBase
{
  /**
   * Configura el Validator
   * @param  array  $options  Opciones del validador
   * @param  array  $messages Mensajes de error
   * @return string           RUT limpio y validado
   */
  protected function configure($options = array(), $messages = array()) {
    $this->setMessage('invalid','El RUT "%value%" no es válido');
    $this->setMessage('required','Requerido.');
    $this->setOption('trim',true);
    $this->addOption('con_guion',true);
  }

  protected function doClean($value) {
    $rut = strtoupper(preg_replace('/[^0-9kK]/','',(string) $value));

    $r = substr($rut,0,strlen($rut)-1);
    $dv = substr($rut,-1);

    $clean = ($this->getOption('con_guion') == true)? $r.'-'.$dv:$r.$dv;

    $s=1;
    for($m=0;$r!=0;$r/=10)
    {
      $s=($s+$r%10*(9-$m++%6))%11;
    }

    if($dv != (string) chr($s?$s+47:75))
      throw new sfValidatorError($this, 'invalid', array('value' => $value));

    return $clean;
  }
}