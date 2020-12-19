<?php
/** 
 * Principio de responsabilidad unica nos dice que una clase debe ser resposansable de una unica cosa.
 * Tenemos un controlador donde podemos ver un metodo store que tiene mas de una responsabilidad.
 * 
 */
public function store()
{
  // valida los datos
  $validators = $request->validate([
    'name' => 'required',
    'email' => 'required|unique:users|email',
    'password' => 'required',
  ]);

  $user->name = $request->input('name');
  $user->email = $request->input('email');
  $user->password = bcrypt($request- >input('password'));
  $user->save();

  return response()->json(['user' => $user], 201);
}
/**
 * Aplicando la S de SOLID a este metodo:
 * 
 * - Creamos una clase FromRequest que tendra la responsabilidad 
 * de todo lo que tenga que ver con la validacion de datos.
 * 
 * - Segundo, creamos una clase encargada de comunicarse con las 
 * clases del ORM.
 * 
 */


 public function store_refactory(StoreRequest $request, UserRespository $userRespository)
 {
   $user = $userRespository->create($request);
   return response()->json(['user' => $user], 201);
 }