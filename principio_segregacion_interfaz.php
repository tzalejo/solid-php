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
 * Como podemos ver, estamos inyectando el modelo Subscriber únicamente
 * para utilizar uno de sus métodos(getNotifyEmail()).
 *
 *
 * Solucion:
 */

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
