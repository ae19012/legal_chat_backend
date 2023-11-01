<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Service\GeneradorDeMensajes;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
<<<<<<< Updated upstream
=======
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
>>>>>>> Stashed changes

#[Route('/usuario', name: 'app_usuario_real')]
class UsuarioController extends AbstractController
{
    #[Route('/registrarme', name: 'app_usuario_real_create', methods: ['POST'])]
    public function create(EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $passwordHasher, GeneradorDeMensajes $generadorDeMensajes): JsonResponse
    {
        $usuario = new Usuario();
        $usuario->setEmail($request->request->get('email'));
        $plainPassword = $request->request->get('password');
        $usuario->setNombre($request->request->get('nombre'));
        $usuario->setApellido($request->request->get('apellido'));
        $usuario->setDui($request->request->get('dui'));
        $usuario->setRoles(["Cliente"]);
        $usuario->setActivo(true);
        $hashedPassword = $passwordHasher->hashPassword($usuario, $plainPassword);
        $usuario->setPassword($hashedPassword);
        // Se avisa a Doctrine que queremos guardar un nuevo registro pero no se ejecutan las consultas
        $entityManager->persist($usuario);
        // Se ejecutan las consultas SQL para guardar el nuevo registro
        $entityManager->flush();
        $data[] = [
            'id' => $usuario->getId(),
            'email' => $usuario->getEmail(),
            'nombre' => $usuario->getNombre(),
            'apellido' => $usuario->getApellido(),
            'dui' => $usuario->getDui()
        ];            
        return $this->json([
            $generadorDeMensajes->generarRespuesta("Se guardó el nuevo usuario.", $data)
        ]);
    }
<<<<<<< Updated upstream
=======

    #[Route('/actualizar-informacion', name: 'app_usuario_real_edit', methods: ['PUT'])]
  public function update(EntityManagerInterface $entityManager, Request $request, GeneradorDeMensajes $generadorDeMensajes, Security $security): JsonResponse
  {

    // obtiene el id del usuario mediante el token
    $usuarioLogueado = $security->getUser();
    if($usuarioLogueado !== null && $usuarioLogueado instanceof Usuario){
      $usuarioLogueadoObj = ['id' => $usuarioLogueado->getId()];
      $usuario = $entityManager->getRepository(Usuario::class)->find($usuarioLogueadoObj['id']);
    }
    
    // Obtiene los valores del body de la request
    $nombre = $request->request->get('nombre');
    $apellido = $request->request->get('apellido');
    $email = $request->request->get('email');
    $dui = $request->request->get('dui');

    // Si no envia uno responde con un error 422
    if ($nombre == null || $apellido == null || $email == null || $dui == null){
      return $this->json(['error'=>'Se debe enviar toda la informacion del usuario.'], 422);
    }

    // Se actualizan los datos a la entidad
    $usuario->setNombre($nombre);
    $usuario->setApellido($apellido);
    $usuario->setEmail($email);
    $usuario->setDui($dui);

    $data=['id' => $usuario->getId(), 'nombre' => $usuario->getNombre(), 'apellido' => $usuario->getApellido(), 'email' => $usuario->getEmail(), 'dui' => $usuario->getDui()];

    // Se aplican los cambios de la entidad en la bd
    $entityManager->flush();

    return $this->json([$generadorDeMensajes->generarRespuesta("Se actualizó la información del usuario.", $data)]);
  }

  #[Route('/usuario/{id}', name: 'app_usuario_delete', methods: ['DELETE'])]
  public function delete(EntityManagerInterface $entityManager, int $id, Request $request): JsonResponse
  {

    // Buscar usuario que se desea borrar ingresando su id
    $usuario = $entityManager->getRepository(Usuario::class)->find($id);

    // Si no se encuentra al usuario con el id ingresado, el programa devuelve error 404
    if (!$usuario) {
      return $this->json(['error'=>'No fue posible encontrar usuario con el siguiente id: '.$id], 404);
    }

    // En caso de encontrar al usuario se remueve de la base de registro al usuario seleccionado
    $entityManager->remove($usuario);

    $data=['id' => $usuario->getId(), 'nombre' => $usuario->getNombre(), 'edad' => $usuario->getEdad()];

    // Se aplican los cambios y se actualiza la BD 
    $entityManager->flush();

    return $this->json(['message'=>'Se ha eliminado al usuario correctamente.', 'data' => $data]);
  }



  
>>>>>>> Stashed changes
}
