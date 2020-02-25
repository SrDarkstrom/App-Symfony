<?php
namespace App\Controller;
// src/Controller/Login.php
use App\Entity\Usuarios;
use App\Service\Funciones;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
class Login extends AbstractController{
    /**
     * @Route("/", name="login")
     */
    public function login(){
        return $this->render('login.html.twig');
    }

    /**
     * @Route("olvidada/", name="olvidada")
     */
    public function olvidada(MailerInterface $mailer, Funciones $func){
        $entityManager = $this->getDoctrine()->getManager();
        $usuarios = $entityManager->createQuery("SELECT u.correo FROM App\Entity\Usuarios u")->getResult();
        $correo = $_POST['correo'];
        $sw = false;

        foreach ($usuarios as $usuario){
            if (in_array($correo, $usuario)){
                $sw = true;
            }
        }

        if ($sw) {
            $clave = $func->cambiarClave($correo, $entityManager);

            $message = (new Email())
                ->from('aplicacion1920@gmail.com')
                ->to($correo)
                ->html("<h1>Correo: $correo </h1><h2>Nueva contraseña: $clave </h2>");
            $mailer->send($message);

            return $this->render('login.html.twig', array('mensaje' => 'Se ha enviado un correo con una contraseña nueva'));
        }else{
            return $this->render('contraseñaOlvidada.html.twig', array('mensaje' => 'Revise email'));
        }
    }
}