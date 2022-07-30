<?php
/**
 * El principio de segregación dice que un cliente solo debe conocer los
 * métodos que van a utilizar y no aquellos que no utilizarán.
 *
 *
 */

class Subscriber
{
  public function subscribe()
  {
    // logica
  }

  public function unsubscribe()
  {
    // logica
  }

  public function getNotifyEmail()
  {
    // logica
  }
}

class Notifications
{
  public function send(Subscriber $subscriber, $message)
  {
    return $subscriber->getNotifyEmail();
  }
}
/**
 *
 * Como podemos ver, primero dividimos los metodos en interface
 * y luego solo inyectando el modelo Subscriber únicamente
 * el métodos getNotifyEmail() .
 *
 *
 * Solucion:
 */

interface UnsubscribeInterface
{
  public function unsubscribe();
}

interface SubscribeInterface
{
  public function subscribe();
}


interface NotificableInterface
{
  public function getNotifyEmail();
}

class NotificationsNew
{
  public function send(NotificableInterface $subscriber, $msg)
  {
    return $subscriber->getNotifyEmail();
  }
}
/**
 * De esta forma podemos hacer que cualquier clase implemente la interfaz
 * y el método getNotifyEmail(). Esto le da una independencia absoluta a
 * nuestro código ya que podríamos crear una clase para cada forma de obtener
 * el email
 */


/* Otro ejemplo */

class Duck {

  public function float() {
    echo 'The duck is floating\n';
  }

  public function quack() {
    echo 'The duck is quacking\n';
  }
}


class Pond {
  public function sendToPlay(Duck $duck){

    $duck->float();
    $duck->quack();

  }
}
/*
Aca como sabemos la clase estanque recibe la clase pato y ejecuta los metodos flotar y hacer quack, pero que pasa si
otro tipo de pato, como pato de madera, este pato no pudieramos usarlo en la clase estanque.
 */

interface CanPlay {
  public function play();
}

interface CanFloat {
  public function float();
}

interface CanQuack {
  public function quack();
}

class Duck implements CanQuack, CanFloat, CanPlay {

  public function float() {
    echo 'The duck is floating\n';
  }

  public function quack() {
    echo 'The duck is quacking\n';
  }

  public function play(){
    $this->float();
    $this->quack();
  }

}

class WoodenDuck implements CanQuack, CanPlay {

  public function float() {
    echo 'The duck is floating\n';
  }

  public function play(){
    $this->float();
  }
}


class Pond {

  public function sendToPlay(CanPlay $duck){
    duck->play();
  }

}

$pond = new Pond();
$pond->play(new Duck());
$pond->play(new WoodenDuck());

/*
 * Al separar en pequeñas interfaces estamos los roles y las responsabilidades, es decir, el rol de jugar, de flotar, de hacer quack etc esta separado
 * y las responsabilidades estan en cada clase(Duck , WoodenDuck). Tambien estamos favoreciendo la composicion sobre la herencia, es decir,
 * la clase estanque puede estar compuesta por distintos tipos de patos o mejor aun de distintos tipos de clases que pueden jugar en el estanque.
 */

class Frog implements CanPlay {

  public function play(){
    echo 'The frog is playing\n';
  }

}

$pond->play(new Frog());


