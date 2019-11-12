<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Email;

use App\Entity\User;
use App\Entity\Video;
use App\Services\JwtAuth;

use function GuzzleHttp\Psr7\hash;

class UserController extends AbstractController
{

    private function resjson($data){
        //SERIALIZAR DATOS CON SERVICIO SERIALIZER
        $json= $this->get('serializer')->serialize($data, 'json');

        //RESPONSE CON HTTPFOUNDATION
        $response= new Response();

        //ASIGNAR CONTENIDO A LA RESPUESTA
        $response->setContent($json);

        //INDICAR FORMATO DE RESPUESTA
        $response->headers->set('Content-Type', 'application/json');

        //DEVOLVER LA RESPUESTA
        return $response;
    }

    public function index()
    {

        $user_repo= $this->getDoctrine()->getRepository(User::class);
        $video_repo= $this->getDoctrine()->getRepository(Video::class);

        $users= $user_repo->findAll();
        $user = $user_repo->find(1);

        $videos= $video_repo->findAll();


        $data = [
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ];
        /*
        foreach ($users as $user) {
            echo "<h1>{$user->getName()}  {$user->getSurname()}</h1>";

            foreach ($user->getVideos() as $video) {
                echo "<p>{$video->getTitle()} - {$video->getUser()->getEmail()}</p>";
            }
        }
        die();*/
        return $this->resjson($data);
    }

    public function create(Request $request){
        
        //RECOGER LOS DATOS POR POST
        $json = $request->get('json',  null);

        //DECODICFICAR EL JSON
        $params = json_decode($json);

        //RESPUESTA POR DEFECTO
        $data=[
            'status' => 'error',
            'code' => 200,
            'message' => 'El usuario no se ha creado',
            
        ];
        //VALIDAR DATOS
        if($json!=null){

            $name= (!empty($params->name)) ? $params->name : null ;
            $surname= (!empty($params->surname)) ? $params->surname : null ;
            $email= (!empty($params->email)) ? $params->email : null ;
            $password= (!empty($params->password)) ? $params->password : null ;
            
            $validator= Validation::createValidator();
            $validate_email= $validator->validate($email, [
                new Email()
            ]);

            if(!empty($email) && count($validate_email) == 0 && !empty($name) && !empty($surname) && !empty($password)){
                //SI VALIDACION OK, CREAR OBJETO DEL USUARIO
                $user = new User();
                $user->setName($name);
                $user->setSurname($surname);
                $user->setEmail($email);
                $user->setRole('ROLE_USER');
                $user->setCreatedAt(new \Datetime('now'));
                
            //CIFRADO DE CONTRASEÑA
                //$pwd= hash('sha256', $password);
                $pwd=\hash('sha256', $password);
                
                $user->setPassword($pwd);

            //COMPROBAR SI EXISTE EL USUARIO
                $doctrine= $this->getDoctrine();
                $em = $doctrine->getManager();

                $user_repo= $doctrine->getRepository(User::class);

                $isset_user= $user_repo->findBy(array(
                    'email' => $email
                ));

                if(count($isset_user)==0){
                    //SI NO EXISTE GUARDARLO
                    $em->persist($user);
                    $em->flush();
                    $data=[
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'Usuario registrado',
                        'user' => $user
                    ];
                }else{
                    $data=[
                        'status' => 'error',
                        'code' => 400,
                        'message' => 'El usuario ya existe'
                        
                    ];
                }
            
            }else{
                $data=[
                    'status' => 'error',
                    'code' => 200,
                    'message' => 'Validacion incorrecta',
                    
                ];
            }
            
        }
           //HACER RESPUESTA EN JSON
            return $this->resjson($data);
    }

    public function login(Request $request, JwtAuth $jwt_auth)
    {
        //RECIBIR DATOS POR POST
        $json = $request->get('json',  null);
        $params = json_decode($json);
        //ARRRAY POR DEFECTO PARA DEVOLVER
        $data = [
            'status' => 'error',
            'code' => 200,
            'message' => 'El usurio no se ha podido identificar'
        ];
        //COMPROBAR Y VALIDAR DATOS
        if($json!=null){
            $email= (!empty($params->email)) ? $params->email : null ;
            $password= (!empty($params->password)) ? $params->password : null ;
            $gettoken= (!empty($params->gettoken)) ? $params->gettoken : null ;
        
            $validator= Validation::createValidator();
            $validate_email= $validator->validate($email, [
                new Email()
            ]);

            if(!empty($email) && !empty($password) && count($validate_email)==0){
                //CIFRAR LA CONTRASEÑA
                $pwd= \hash('sha256', $password);

                //SI TODO ES VALIDO, LLAMAREMOS A UN SERVICIO PARA IDENTIFICAR AL USUARIO (JWT, TOKEN O UN OBJETO)
                
                if($gettoken){
                    $signup = $jwt_auth->signup($email, $pwd, $gettoken);
                }else{
                    $signup = $jwt_auth->signup($email, $pwd);
                }
                return new JsonResponse($signup);
            }
            
        }
        //SI NOS DEVUELVE BIEN LOS DATOS, RESPUESTA
        return $this->resjson($data);
    }

    public function edit(Request $request, JwtAuth $jwt_auth){
        //RECOGER LA CABECERA DE AUTENTICACION
        $token = $request->headers->get('Authorization');
        //CREAR UN METODO PARA COMPROBAR SI EL TOKEN ES CORRECTO
        $checkToken=$jwt_auth->checkToken($token);
        
        $data = [
            'status' => 'error',
            'code' => 400,
            'message' => 'Usuario no actualizado'
        ];

        if($checkToken){
            //SI ES CORRECTO, HACER ACTUALIZACION DEL USUARIO
            $em= $this->getDoctrine()->getManager();

            $identity = $jwt_auth->checkToken($token, true);

            $user_repo=$this->getDoctrine()->getRepository(User::class);

            $user = $user_repo->findOneBy([
                'id' => $identity->sub,
            ]);

            $json=$request->get('json',null);
            $params=json_decode($json);

            if(!empty($json)){
                $name= (!empty($params->name)) ? $params->name : null ;
                $surname= (!empty($params->surname)) ? $params->surname : null ;
                $email= (!empty($params->email)) ? $params->email : null ;
        
                $validator= Validation::createValidator();
                $validate_email= $validator->validate($email, [
                    new Email()
                ]);

                if(!empty($email) && count($validate_email) == 0 && !empty($name) && !empty($surname)){
                    $user->setEmail($email);
                    $user->setName($name);
                    $user->setSurname($surname);

                    $isset_user= $user_repo->findBy([
                        'email' => $email
                    ]);

                    if(count($isset_user)== 0 || $identity->email == $email){
                        $em->persist($user);
                        $em->flush();

                        $data= [
                            'status' => 'success',
                            'code' => 200,
                            'message' => 'Usuario actualizado',
                            'user' =>$user
                        ];

                    }else{
                        $data= [
                            'status' => 'error',
                            'code' => 400,
                            'message' => 'No puedes usar ese email'
                        ];
                    }



                }
            }
        }

        return $this->resjson($data);
    }
}
