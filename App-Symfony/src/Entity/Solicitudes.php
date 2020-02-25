<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Solicitudes
 *
 * @ORM\Table(name="solicitudes", indexes={@ORM\Index(name="ami_sol_fk", columns={"Amigo"}), @ORM\Index(name="sol_sol_fk", columns={"Solicitante"}), @ORM\Index(name="IDX_216D110EDD889C1", columns={"Usuario"})})
 * @ORM\Entity
 */
class Solicitudes
{
    /**
     * @var \Usuarios
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Usuarios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Amigo", referencedColumnName="Correo")
     * })
     */
    private $amigo;

    /**
     * @var \Usuarios
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Usuarios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Solicitante", referencedColumnName="Correo")
     * })
     */
    private $solicitante;

    /**
     * @var \Usuarios
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Usuarios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Usuario", referencedColumnName="Correo")
     * })
     */
    private $usuario;

    public function getAmigo(): ?Usuarios
    {
        return $this->amigo;
    }

    public function setAmigo(?Usuarios $amigo): self
    {
        $this->amigo = $amigo;

        return $this;
    }

    public function getSolicitante(): ?Usuarios
    {
        return $this->solicitante;
    }

    public function setSolicitante(?Usuarios $solicitante): self
    {
        $this->solicitante = $solicitante;

        return $this;
    }

    public function getUsuario(): ?Usuarios
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuarios $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }


}
