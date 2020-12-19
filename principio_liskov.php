<?php

/**
 * El Principio de Sustitución de Liskov dice que, cada clase
 * que hereda de otra puede usarse como su padre sin necesidad de
 * conocer las diferencias entre ellas.
 *
 * Hay 3 hitos que tenemos que tener en cuenta para no violar el principio de Liskov:
 * - No fortalecer las pre-condiciones y no debilitar las pos-condiciones.
 * - Las invariantes establecidas en la clase base deben mantenerse en las subclases.
 * - Y no puede existir un método en la subclase que vaya en contra de un comportamiento
 * de la clase base. Esto se lo llama Restricción Histórica.
 *
 *
 */

/**
 * Hito 1
 */
class shipping
{
  public function calculaShippingCost($pesoDelPaquete, $destino)
  {
    // Pres-condicion:
    if ($pesoDelPaquete <= 0) {
      throw new Exception('El peso no puese dser menor o igual a 0');
    }

    $shippingCost = '..'; // alguna logica

    // Pos-condicion:
    if ($shippingCost <= 0) {
      throw new Exception('El precio de envio no puede ser menor o igual 0');
    }

    return $shippingCost;
  }
}
/**
 * Hito 2:  Las invariantes son valores de la clase base que no pueden ser modificadas
 * por las clases hijas.
 */

class ShippingInterno
{
  protected $pesoMayorQue = 0;
  public function calcularCostoDeEnvio($pesoDelPaqueteKg, $destino)
  {
    // logica del codigo
  }
}

class WordWideShipping extends ShippingInterno
{
  public function calcularCostoDeEnvio($pesoDelPaqueteKg, $destino)
  {
    $this->pesoMayorQue = 5;

    // logica del codigo
  }
}
