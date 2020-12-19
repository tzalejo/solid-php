<?php

function pay($request)
{
  $payment = new Payment();

  switch ($request->type) {
    case 'credit':
      $payment->payWithCreditCard();
      break;
    case 'paypal':
      $payment->payWithPaypal();
      break;
  }
}

class Payment
{
  public function paywithCreditCard()
  {
    // logica
  }

  public function payWithPaypal()
  {
    // logica
  }
}

/**
 * Primero, que deberíamos agregar un case mas por cada nuevo pago que aceptemos o eliminar un case en el caso que no aceptemos mas pagos por PayPal.
 * Segundo, que todos los métodos que procesan los distintos tipos de pagos, se encuentran en una única clase, la clase Payment
 * 
 * 
 * Solucion:
 */
?>
<?php
interface PayableInterface
{
  public function pay();
}
class CreditCardPayment implements PayableInterface
{
  public function pay()
  {
    //logica
  }
}
class PaypalPayment implements PayableInterface
{
  public function pay()
  {
    //logica
  }
}
class PaymentFactory
{
  public function initialize($type)
  {
    switch ($type) {
      case 'credit':
        return new CreditCardPayment();
        break;
      case 'paypal':
        return new PaypalPayment();
        break;
      default:
        throw new Exception('Metodo no soportado');
        break;
    }
  }
}

function pay_refactory($request)
{
  $payMentFactory = new PaymentFactory();
  $payment = $payMentFactory->initialize($request->type);
  return $payment->pay();
}

