<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Amigos
 *
 * @ORM\Table(name="amigos", indexes={@ORM\Index(name="ami_ami_fk", columns={"Amigo"}), @ORM\Index(name="usu_ami_fk", columns={"Usuario"})})
 * @ORM\Entity
 */
class Amigos
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Usuarios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario", referencedColumnName="Correo")
     * })
     */
    private $usuario;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Usuarios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="amigo", referencedColumnName="Correo")
     * })
     */
    private $amigo;

    public function getUsuario(): ?Usuarios
    {
        return $this->usuario;
    }

    public function getAmigo(): Usuarios
    {
        return $this->amigo;
    }

    public function setUsuario(?Usuarios $usuario): self
    {
        $this->usuario = $usuario;
        return $this;
    }

    public function setAmigo(?Usuarios $amigo): self
    {
        $this->amigo = $amigo;
        return $this;
    }
}
