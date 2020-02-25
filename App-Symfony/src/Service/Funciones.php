<?php
namespace App\Service;
use App\Entity\Amigos;
use App\Entity\Solicitudes;
use App\Entity\Usuarios;

class Funciones
{
    function avatar($file, $nombre,$ext){
        if($file["size"] > 500 *2048){
            return FALSE;
        }
        $res = move_uploaded_file($file["tmp_name"],"Ficheros/Avatares/" . $nombre.".".$ext);
        if($res){
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function ficheros($file){
        $res = move_uploaded_file($file["tmp_name"],"Ficheros/Adjuntos/" . $file["name"]);
        if($res){
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function clave($clave){
        $cadena1="/[A-Z]/";
        $cadena2="/[a-z]/";
        $cadena3="/[0-9]/";
        $sw=FALSE;
        if((strlen($clave)>=6 and strlen($clave)<=15) and (preg_match($cadena1,$clave)==TRUE) and (preg_match($cadena2,$clave)==TRUE) and (preg_match($cadena3,$clave)==TRUE) and (ctype_alnum($clave)==FALSE)){
            $sw=TRUE;
        }
        return $sw;
    }

    function aceptar_solicitud($correo, $amigo, $entityManager){
        $amigo1 = new Amigos();
        $amigo2 = new Amigos();
        $amigo1->setUsuario($correo);
        $amigo1->setAmigo($amigo);
        $amigo2->setUsuario($amigo);
        $amigo2->setAmigo($correo);

        $this->denegar_solicitud($correo, $amigo, $entityManager);

        $entityManager->persist($amigo1);
        $entityManager->persist($amigo2);

        $entityManager->flush();
    }

    function denegar_solicitud($correo, $amigo, $entityManager){
        $solicitud1 = $entityManager->getRepository(Solicitudes::class)->findOneBy(['usuario' => $correo, 'amigo' => $amigo]);
        $solicitud2 = $entityManager->getRepository(Solicitudes::class)->findOneBy(['usuario' => $amigo, 'amigo' => $correo]);

        $entityManager->remove($solicitud1);
        $entityManager->remove($solicitud2);

        $entityManager->flush();
    }

    function cambiarClave($correo, $entityManager){
        $user = $entityManager->getRepository(Usuarios::class)->findOneBy(['correo' => $correo]);
        $clave = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
        $hash = password_hash($clave, PASSWORD_DEFAULT);

        $user->setClave($hash);
        $entityManager->flush();

        return $clave;
    }
}