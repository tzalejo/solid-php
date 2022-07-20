# Principio SOLID - Explicado con Laravel

## Introduccion

Los principios SOLID son conceptos introducidos por Rober C Martin y otros gurues de la programacion.
Son muy importantes para crear software de calidad y estan muy relacionados con los patrones de diseño, en especial, con la `alta cohesion` y el `bajo acoplamiento`. Cabe decir, que los principios SOLID se pueden aplicar a cualquier lenguaje de programacion orientado a objetos.

## Principio de Responsabilidad Unica (Single Resposability Principle)

El Principio de Responsabilidad Unica(SRP) nos dice que una clase debe ser responsable de una unica cosa.

Ej: Tenemos un controladordonde podemos ver un metodo store() que tiene mas de una responsabilidad.

```
public function store(Request $request, User $user)
{
  // valida los datos
  $validarDatos = $request->validate([
  'name' => 'required',
  'email' => 'requerid|unique: users|email',
  'password' => 'requerid'
  ]);

  // encriptar contraseña y guradr datos.
  $user->name = $request->input('name');
  $user->email = $request->input('email');
  $user->password = bcrypt($request->input('email'));

  $user->save();
}

```

El metodo tiene responsabilidades que no le compten a la clase Controller, como validacion de datos, la encripcion de contraseña y el guardado en la bd.
Aplicando la S de SOLID a este metodo:

- Creamos una clase FormRequest que tendra la responsabilidad de todo lo que tenga que ver con la validacion de datos.
- Segundo, creamos una clase encargada de comunicarse con las clases del ORM.

Beneficios del SPP

- Cada clase se encarga de lo suyo.
- Hay un lugar para todo y todo esta en su lugar.
- Nombre mas especificos para las clases.
- Mayor facilidades para atacar el codigo con pruebas.

## Principios Abierto/Cerrado (Open/Close Principle)

El principio OCP establece que una clase(metodo o funcion) debe quedar abierta para extender su funcionalidad pero cerrada para modificar su codigo. Que una entidad sea abierta para extension, significa que es posible cambiar el comportamiento de dicha entidad.
Por otro ladao, que sea cerrada para modificacion, implica que debe ser posible cambiar el comportamiento sin modificar el codigo fuente original.
Ejemplo:

```
Class MercedezBenz {
  public $speed = 0;
  protected $model = '2019';
}

Class Pagani {
  public $speed = 0;
  protected $model = '2020';
}
```

Ahora suponiendo que queremos implementar la funcionalidad de acelerar dicha clase.

```
Class Driver {
  public function acelerar(Auto $auto){
    if($auto instanceof MercedezBenz) {
      $auto->speed += 10;
    } elseif($auto instanceof Pagani) {
      $auto->speed += 15;
    }
  }
}
```

Sin embargo, observa que cada vez que se agregue un auto, es decir, que se cree un clase nueva debe tambien modificarse la funcion acelerar del Driver para que soporte la funcionalidad de acelerar, osea, que la clase Driver esa abierta a modificacion por la cual estamos violando el principio OCP.
Podemos solucionar esto separando el comportamiento extensible detras de una interfaz y eliminando las dependencia.

```
Interface AutoInterface{
  public function speedUp();
}

Class MercedezBenz implements AutoInterface{
  public $speed = 0;
  protected $model = '2019';

  public function speed(){
    $this->speed += 10;
  }
}

Class Pagani implements AutoInterface{
  public $speed = 0;
  protected $model = '2020';

  public function speed(){
    $this->speed += 15;
  }
}

```

Por ultimo vamos a mover las dependencias de la clase Diver. De esta manera, la entidad conductor o driver solo debe ejecutar el mentodo que conoce de la interfaz, es decir, el metodo speedUp().

```
Class Driver {
  public function acelerar($auto){
    $auto->speedUp();
  }
}

```

Beneficios de Principio de Abierto/Cerrado:

- Extender las funcionalidades del sistema, sin tocar el nucleo del sistema.
- Prevenimos romper partes del sistema al añadir nuevas funcionalidades.
- Facilidad en el Testeo de clases.
- Separacion de las diferentes logicas.
