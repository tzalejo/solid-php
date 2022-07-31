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

## Principio de Sustitucion de Liskov (LSP)

Segun Wikipedia, dice que, cada clase que hereda de otra puede usarse como su padre sin necesidad de conocer la diferencia entre ellas.
Hay 3 hitos que tenemos que tener en cuenta para no violar el principio de Liskov:

- No fortalecer las pre-condiciones y no debilitar las pos-condiciones.
- Las invariantes establecidas en la clase base deben mantenerse en las subclases.
- Y nno puede existir un metodo en la subclases que vaya en contra de un comportamiento de la clase base. Esto se lo llama Restriccion Hostorica.

### Una forma facil dee no romper con el Principio LSP

Es utilizando Interfaces en lugar de extender nuestra clase hijasde una clase padre, podemos prescindir de la clase padre:

```
Interface CalculableShippingCost{
  public function calculateShippingCost($peso, $destino);
}

class WorldWideShipping implements CalculableShippingCost {
  public function calculateShippingCost($peso, $destino){
    // codigo
  }
}

```

## Principio de Segregacion de Interfaz(ISP)

El principio de segregacion dice que un cliente solo debe conocer los metodos que van a utilizar y no aquello que no utilizaran.
Basicamente, a lo que se refiere este principio es que no debemos crear clases con miles de metodos donde termina siendo un archivo enorme.

Ejemplo: supongamos que tenemos el modelo subcriber:

```
Class Subscriber extends Model {
   public function subscribe(){...codigo}
   public function unsubscribe(){...codigo}
   public function getNotifyEmail(){...codigo}
}
```

Luego tenemos una clase Notifications encargada de ejecutar la notificacion por mail.

```
Class Notifications {
  public function send(Subscriber $subscriber, $menssage){
    Mail::to($subscriber->getNotifyEmail())->queue();
  }
}
```

Como podemos ver, estamos inyectando el modelo Subscriber unicamente para utilizar uno de sus metods.
Para solucioanr esto, veamos:

```
Interface NotificableInterface{
  public function getNotifyEmail(): string;
}


Class Notifications {

  public function send(NotificableInterface $notificable, $menssage){
    Mail::to(notificable->getNotifyEmail())->queue();
  }

}
```

## Pricipio de Inversion de Dependencias.

Primero debo dejar claro que la inversion de dependencia NO es lo mismo que Inyeccion de dependencia. La inversion de dependencia es un prinicpio, mientras que la inyeccion de dependencia es un patron de diseño.
Aunque si tiene que ver ya que, el patron se basa en el principio de inversion.

Este principio establece lo siguiente:

Los módulos de alto nivel no deben depender de los módulos de bajo nivel, ambos deben depender de abstracciones.
Las abstracciones no deben depender de los detalles, los detalles deben depender de las abstracciones.

¿Que es una dependencia?
Esta se da cuando una clase A usa metodos de la clase B.

¿Que es un acoplamiento?
Es el grado de dependencia entre la clase A y la clase B.
Por ejemplo, si quisiera modificar la clase B cuanto tendria que modificar la clase A. Si tengo que redefinir la clase A se dice que tiene un alto acoplamiento, por lo contrario si tengo que modificar la clase B si tocar la clase A entonces digo que tengo un bajo acomplamiento.

¿Que es una abstraccion?
RAE: Separar por medio de una operacion intelectual un rasgo o una cualidad de algo para analizarlos aisladamente.
En programacion la abstracción la alcanzamos creando interfaces o clases abstractas. Porque ninguno de los dos son algo concreto, es decir, no podemos crear objetos a partir de ninguna de ellas.

