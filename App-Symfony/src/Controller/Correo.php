<?php
namespace App\Controller;
// src/Controller/Login.php
use App\Entity\Solicitudes;
use App\Entity\Usuarios;
use App\Entity\Mensajes;
use App\Service\Funciones;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Security("is_granted('ROLE_USER')")
 */
class Correo extends AbstractController
{
    /**
     * @Route("/recibidos", name="recibidos")
     */
    public function recibidos(){
        $entityManager = $this->getDoctrine()->getManager();
        $session = $this->getUser()->getUsername();

        $mensajes_consulta = $entityManager->createQuery("SELECT m FROM App\Entity\Mensajes m WHERE m.emisor != '$session'")->getResult();
        $recibidos = array_reverse($mensajes_consulta);

        return $this->render("recibidos.html.twig", array('mensajes'=>$recibidos));
    }

    /**
     * @Route("/redactar_mensaje", name="redactar_mensaje")
     */
    public function redactar_mensaje(){
        $session = $this->getUser()->getUsername();
        $entityManager = $this->getDoctrine()->getManager();
        $query = $entityManager->createQuery("SELECT u.idusuario FROM App\Entity\Usuarios u WHERE u.correo<>'$session'");
        $usuarios = $query->getResult();

        return $this->render("redactar.html.twig", array('id_usuarios'=>$usuarios));
    }

    /**
     * @Route("/enviar_mensaje", name="enviar_mensaje")
     */
    public function enviar_mensaje(Funciones $func){
        $entityManager = $this->getDoctrine()->getManager();
        $nuevo_mensaje = new Mensajes();
        $emisor = $this->getUser();
        $receptor = $entityManager->getRepository(Usuarios::class)->findOneBy(['idusuario' => $_POST["usuarios"]]);

        if ($func->ficheros($_FILES["fichero"])){
            $nuevo_mensaje->setEmisor($emisor);
            $nuevo_mensaje->setReceptor($receptor);
            $nuevo_mensaje->setAsunto($_POST['asunto']);
            $nuevo_mensaje->setMensaje($_POST['mensaje']);
            $nuevo_mensaje->setFichero($_FILES['fichero']["name"]);
            $nuevo_mensaje->setLeido(0);
        }else{
            $nuevo_mensaje->setEmisor($emisor);
            $nuevo_mensaje->setReceptor($receptor);
            $nuevo_mensaje->setAsunto($_POST['asunto']);
            $nuevo_mensaje->setMensaje($_POST['mensaje']);
            $nuevo_mensaje->setFichero("");
            $nuevo_mensaje->setLeido(0);
        }


        $entityManager->persist($nuevo_mensaje);
        $entityManager->flush();

        return $this->redirectToRoute('recibidos');
    }

    /**
     * @Route("/completo/{codigo}", name="mensaje_completo")
     */
    public function mensaje_completo($codigo){
        $entityManager = $this->getDoctrine()->getManager();
        $mensaje = $this->getDoctrine()->getRepository(Mensajes::class)->find($codigo);

        $mensaje->setLeido(1);
        $entityManager->persist($mensaje);
        $entityManager->flush();

        return $this->render("completo.html.twig", array('mensaje'=>$mensaje));
    }

    /**
     * @Route("/responder_mensaje/{id}", name="responder_mensaje")
     */
    public function responder_mensaje($id){
        $entityManager = $this->getDoctrine()->getManager();
        $msg = $entityManager->getRepository(Mensajes::class)->findOneBy(['codigo' => $id]);

        return $this->render('responder.html.twig', array('msg'=>$msg));
    }

    /**
     * @Route("/amigos", name="amigos")
     */
    public function amigos(){
        $correo=$this->getUser()->getUsername();
        $entityManager=$this->getDoctrine()->getManager();
        $amigos_consulta=$entityManager->createQuery("select u.idusuario from App\Entity\Usuarios u, App\Entity\Amigos a where u.correo=a.amigo and a.usuario='$correo'");
        $amigos=$amigos_consulta->getResult();
        return $this->render("amigos.html.twig",array("amigos"=>$amigos));
    }

    /**
     * @Route("/añadirAmigos", name="añadirAmigos")
     */
    public function añadirAmigos(){
        $correo = $this->getUser()->getUsername();
        $entityManager=$this->getDoctrine()->getManager();

        $usuarios=$entityManager->createQuery("SELECT u.idusuario from App\Entity\Usuarios u where not u.correo='$correo' 
                                 and u.correo not in (SELECT IDENTITY(a.amigo) from App\Entity\Amigos a where a.usuario='$correo')")->getResult();

        return $this->render("añadirAmigos.html.twig",array("usuarios"=>$usuarios));
    }

    /**
     * @Route("/perfilAmigo/{user}",name="perfilAmigo")
     */
    public function perfilAmigo($user){
        $entityManager=$this->getDoctrine()->getManager();
        $amigo=$entityManager->getRepository(Usuarios::class)->findOneBy(['idusuario' => $user]);
        $correo=$amigo->getCorreo();

        $query2=$entityManager->createQuery("select u from App\Entity\Usuarios u,App\Entity\Amigos a where u.correo=a.amigo and a.amigo='$correo'")->getResult();

        return $this->render("perfilAmigo.html.twig",array("perfil"=>$query2));
    }

    /**
     * @Route("/solicitudes", name="solicitudes")
     */
    public function solicitudes(){
        $correo = $this->getUser()->getUsername();
        $entityManager = $this->getDoctrine()->getManager();

        $solicitudes = $entityManager->createQuery("SELECT u.idusuario FROM App\Entity\Usuarios u, App\Entity\Solicitudes s WHERE u.correo=s.amigo and s.usuario='$correo'
 	and not s.solicitante='$correo'")->getResult();

        return $this->render("solicitudes.html.twig", array("solicitudes" => $solicitudes));
    }

    /**
     * @Route("/solicitudAmistad", name="solicitudAmistad")
     */
    function solicitudAmistad(){
        $correo=$this->getUser();
        $amigo = $_POST['usuarios'];
        $entityManager=$this->getDoctrine()->getManager();
        $receptor=$entityManager->getRepository(Usuarios::class)->findOneBy(['idusuario' => $amigo]);;

        $solicitud1 = new Solicitudes();
        $solicitud2 = new Solicitudes();

        $solicitud1->setUsuario($correo);
        $solicitud1->setAmigo($receptor);
        $solicitud1->setSolicitante($correo);

        $solicitud2->setUsuario($receptor);
        $solicitud2->setAmigo($correo);
        $solicitud2->setSolicitante($correo);

        $entityManager->persist($solicitud1);
        $entityManager->persist($solicitud2);
        $entityManager->flush();

        return $this->redirectToRoute('recibidos');
    }


    /**
     * @Route("/aceptarDenegar/{opcion}/{amigo}", name="aceptarDenegar")
     */
    public function aceptarDenegar($opcion, $amigo, Funciones $func){
        $correo=$this->getUser();
        $entityManager=$this->getDoctrine()->getManager();
        $amigo_user = $entityManager->getRepository(Usuarios::class)->findOneBy(['idusuario' => $amigo]);

        if ($opcion == '1'){
            $func->aceptar_solicitud($correo, $amigo_user, $entityManager);
        }else if ($opcion == '2'){
            $func->denegar_solicitud($correo, $amigo_user, $entityManager);
        }

        return $this->redirectToRoute('recibidos');
    }

    /**
     * @Route("/cambiarAvatar" , name="cambiarAvatar")
     */
    function cambiarAvatar(Funciones $func){
        $correo = $this->getUser()->getUsername();
        $avatar = $this->getUser()->getAvatar();
        $avatar_nuevo = $_FILES["nuevo"];
        $ext = pathinfo($avatar_nuevo["name"], PATHINFO_EXTENSION);
        $entityManager=$this->getDoctrine()->getManager();
        $usuario = $entityManager->getRepository(Usuarios::class)->findOneBy(['correo' => $correo]);
        $idusuario = $usuario->getIdusuario();

        if($avatar!="default.jpg"){
            unlink("Ficheros/Avatares/".$avatar);
        }
        if($func->avatar($avatar_nuevo, $idusuario, $ext)!=FALSE){
            $avatar = $idusuario.".".$ext;
            $usuario->setAvatar($avatar);
            $entityManager->flush();
            $this->addFlash('mensaje', "Se ha cambiado el avatar");
            return $this->redirectToRoute('recibidos');
        } else{
            $avatar="default.jpg";
            $usuario->setAvatar($avatar);
            $entityManager->flush();
            $this->addFlash('mensaje', "No se ha podido subir, se pondra la foto por defecto");
            return $this->redirectToRoute('recibidos');
        }
    }

    /**
     * @Route("/cambiarClave", name="cambiarClave")
     */
    function cambiarClave(Funciones $func){
        $clave=$this->getUser()->getPassword();
        $correo = $this->getUser()->getUsername();
        $entityManager=$this->getDoctrine()->getManager();
        $usuario = $entityManager->getRepository(Usuarios::class)->findOneBy(['correo' => $correo]);

        if(password_verify($_POST['actual'],$clave)){
            if($func->clave($_POST['nueva'])){
                if($_POST['repetida']===$_POST['nueva']){
                    $clave2=password_hash($_POST['nueva'] ,PASSWORD_DEFAULT);
                    $usuario->setClave($clave2);
                    $entityManager->flush();
                    $this->addFlash('mensaje', "Se ha cambiado la contraseña");
                    return $this->redirectToRoute('recibidos');
                }
                else{
                    $this->addFlash('mensaje', "No coinciden las contraseñas");
                    return $this->render("cambiarClave.html.twig");
                }
            }
            else{
                $this->addFlash('mensaje', "La contraseña es incorrecta");
                return $this->render("cambiarClave.html.twig");
            }
        }
        else{
            $this->addFlash('mensaje', "La clave antigua no coincide");
            return $this->render("cambiarClave.html.twig");
        }
    }
}