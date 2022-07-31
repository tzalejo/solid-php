<?php
/**
 * Si tenemos un código como el siguiente, estamos
 * generando un alto acoplamiento al instanciar directamente
 * el modelo User en el método.
 */

class User
{
}

function index()
{
  $users = new User();
  $users = $users->where('created_at', Carbon::yesterday())->get();

  return $users;
}

/**
 * Pero tiene varios problemas esta implementación:
 * - No podemos reutilizar el código ya que estamos atados a Eloquent.
 * - Se complica hacer test a los métodos que instancia uno o varios
 * objetos (alto acoplamiento), ya que es complicado verificar
 * que esta fallando.
 * - Se rompe el principio de responsabilidad única, porque, además
 * de que el método haga su trabajo, también tiene que crear los objetos
 * para poder hacer su labor.
 *
 */

interface UserRepositoryInterface
{
  public function getAfterDate($data): array;
}

// Ahora utilizamos la interface en vez del model:
function index(UserRepositoryInterface $user)
{
  // Utilizamos el metodo de la interface en vez del modelo
  $users = $user->getAfterDate(Carbon::yesterda());
  return $users;
}

// implementamos usando eloquent
class UserEloquentRepositoy implements UserRepositoryInterface
{
  public function getAfterDate($date): array
  {
    return User::where('created_at', Carbon::yesterday(), $date)
      ->get()
      ->toArray();
  }
}

// implementamos otra forma..usnado sql
class UsersSqlRepository implements UserRepositoryInterface
{
  public function getAfterDate($data): array
  {
    return \DB::table('users')
      ->where('created_at', '>', $data)
      ->get()
      ->toArray();
  }
}
:
 /*
  * Otro ejemplo de inversion de dependencia.
  *
  * Supongamos un clase tienda(Store) utiliza una Bank Api y se encuentra altamente acoplada a clase tienda. Cabe mensionar que la api nos porpociono el propio banco y no podemos modificarla.
  * |-----------------------------------|
  * |  Clase A  ===> Payment Processor |  <===   BankProcessor     <===    Banck API
  * |----–––––––––––––––––––––––––––––––|       (Adaptador del Banco)
  *  Modulo de Alto nivel      (interface)                               Modulo de Bajo nivel
  *
  * El modulo de alto nivel(Store) no depende del modulo de bajo nivel(Bank API). Ambos dependen de una abstraccion (PaymentProcess).
  * La abstraccion(PaymentProcess) no depende de los detalles (Bank API), los detalles (Banck API) dependen de una abstraccion(PaymentProcess).
  *
  *
  */


/* Clase altamente acoplada a una api, en este caso BankApi*/

class Store {
  protected $myBankApi;

  public function __construct(MyBankAPI $myBankApi)
  {
    $this->myBankApi = $myBankApi;
  }

  public function purchase()
  {
    $this->myBankApi->charge(); // metodo del api banco.
  }
}

/* clase api del banco, que no podeos modificar */

class MyBankAPI {

  public function charge()
  {
    // logica para cobrar
  }
}

/* Vamos aplicar el principio de inversion de dependencia
 *
 * Para aplicar nos dice q el modulo de lato nivel(clase Store) no debe depnder del modulo de bajo nivel
 */


interface PaymentProcess
{

  public function pay();

}

/* implementomos en la clase tienda: */

class Store {
  protected $paymentProcess;

  public function __construct(PaymentProcess $paymentProcess)
  {
    $this->paymentProcess= $paymentProcess;
  }

  public function purchase()
  {
    $this->paymentProcess->pay();
  }
}
/*
 * Al implementar la interfaz hemos desacopado la clase tienda de la clase api de q nos propiorciono el banco
 * Tambien los modulos de bajo nivel deben depender de abstraciones. Pero como no podemos modificar la api del banco vamos a utlizar un Adaptador.
 *
 */

class MyBankPaymentProcessor implements PaymentProcess
{
  protected $myBankApi;
  public function __construct(MyBankAPI $myBankApi)
  {
    $this->myBankApi = $myBankApi;
  }

  public function pay()
  {
    $this->myBankApi->charge(); // metodo del banco..
  }
}

/* Si queremos implementar otra api de otro pago, por ejemplo paypal, debemos solo agregar un adaptardor para esta api. */
