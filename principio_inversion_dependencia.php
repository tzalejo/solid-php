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
